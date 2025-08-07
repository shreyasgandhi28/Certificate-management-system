<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
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
}


