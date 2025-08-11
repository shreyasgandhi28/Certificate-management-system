<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->boolean('with_trashed')) {
            $query->withTrashed();
        }
        if ($q = $request->input('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }
        $users = $query->with('roles')->paginate(15)->appends($request->except('page'));
        $roles = Role::orderBy('name')->pluck('name');
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function destroy(User $user)
    {
        abort_if(!auth()->user()->hasRole('Super Admin'), 403);
        $user->delete();
        return back()->with('success', 'User moved to trash.');
    }

    public function restore($id)
    {
        abort_if(!auth()->user()->hasRole('Super Admin'), 403);
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return back()->with('success', 'User restored.');
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

    public function inviteForm()
    {
        abort_if(!auth()->user()->hasRole('Super Admin'), 403);
        $roles = Role::orderBy('name')->pluck('name');
        return view('admin.users.invite', compact('roles'));
    }

    public function sendInvite(Request $request)
    {
        abort_if(!auth()->user()->hasRole('Super Admin'), 403);
        $validated = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
            'roles' => ['array'],
            'roles.*' => ['string']
        ]);

        $invitation = \App\Models\UserInvitation::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'roles' => array_values($validated['roles'] ?? []),
            'token' => \Str::uuid()->toString(),
            'invited_by' => $request->user()->id,
            'expires_at' => now()->addDays(7),
        ]);

        $invitation->notify(new \App\Notifications\UserInvitationNotification($invitation));

        return redirect()->route('admin.users.index')->with('success','Invitation sent.');
    }
}


