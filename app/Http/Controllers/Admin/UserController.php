<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\HasSortableColumns;

class UserController extends Controller
{
    use HasSortableColumns;

    public function index(Request $request)
    {
        $query = User::query();

        // Include trashed users if requested
        if ($request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        // Search by name or email
        if ($q = $request->input('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        // Filter by role
        if ($role = $request->input('role')) {
            $query->role($role);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Date range filter
        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        
        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        // Apply sorting
        $validSortFields = ['id', 'name', 'email', 'is_active', 'created_at', 'updated_at'];
        $sort = $this->applySorting($query, $request, $validSortFields, 'created_at', 'desc');

        $users = $query->with('roles')
                      ->paginate(15)
                      ->appends($request->except('page'));

        $roles = Role::orderBy('name')->pluck('name');
        
        return view('admin.users.index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => $request->only(['q', 'role', 'status', 'from', 'to']),
            'sort' => $sort
        ]);
    }

    public function deactivate(User $user, Request $request)
    {
        abort_if(!auth()->user()->hasRole('Super Admin'), 403);
        
        // Use direct DB query to update the status
        $result = \DB::table('users')
            ->where('id', $user->id)
            ->update(['is_active' => false]);
            
        // Refresh the user model to get updated data
        $user->refresh();
        
        \Log::info('Direct DB update result', [
            'rows_affected' => $result,
            'user_id' => $user->id,
            'new_status' => $user->is_active
        ]);

        // Log the action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_deactivated',
            'description' => 'Deactivated user ' . $user->email,
            'model_type' => get_class($user),
            'model_id' => $user->id,
            'properties' => $user->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'User deactivated successfully');
    }

    public function activate(User $user, Request $request)
    {
        abort_if(!auth()->user()->hasRole('Super Admin'), 403);
        
        // Use direct DB query to update the status
        $result = \DB::table('users')
            ->where('id', $user->id)
            ->update(['is_active' => true]);
            
        // Refresh the user model to get updated data
        $user->refresh();

        // Log the action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_activated',
            'description' => 'Activated user ' . $user->email,
            'model_type' => get_class($user),
            'model_id' => $user->id,
            'properties' => $user->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'User activated successfully');
    }

    public function destroy(User $user, Request $request)
    {
        abort_if(!auth()->user()->hasRole('Super Admin'), 403);
        
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        
        // Log the action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_deleted',
            'description' => 'Moved user ' . $user->email . ' to trash',
            'model_type' => get_class($user),
            'model_id' => $user->id,
            'properties' => $user->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return back()->with('success', 'User moved to trash.');
    }

    public function restore($id, Request $request)
    {
        abort_if(!auth()->user()->hasRole('Super Admin'), 403);
        
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        
        // Log the action
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user_restored',
            'description' => 'Restored user ' . $user->email . ' from trash',
            'model_type' => get_class($user),
            'model_id' => $user->id,
            'properties' => $user->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return back()->with('success', 'User restored successfully.');
    }

    public function updateRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => ['array'],
            'roles.*' => ['string'],
        ]);

        $newRoles = collect($request->input('roles', []))->values()->all();
        $user->syncRoles($newRoles);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'user_roles_updated',
            'target_type' => User::class,
            'target_id' => $user->id,
            'metadata' => ['roles' => $newRoles],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'User roles updated.');
    }

    public function create()
    {
        abort_if(!auth()->user()->hasRole('Super Admin'), 403);
        $roles = Role::orderBy('name')->pluck('name');
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->hasRole('Super Admin'), 403);
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
            'roles' => ['array'],
            'roles.*' => ['string']
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->syncRoles(collect($validated['roles'] ?? [])->values()->all());

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'user_created',
            'target_type' => User::class,
            'target_id' => $user->id,
            'metadata' => ['roles' => $user->getRoleNames()],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.users.index')->with('success','User created.');
    }
}
