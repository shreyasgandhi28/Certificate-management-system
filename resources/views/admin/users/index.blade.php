@extends('layouts.admin')
@section('title', 'Users')
@section('page-title', 'Users')
@section('page-description', 'Manage user roles and access')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold card-text-primary">Users</h1>
        <form method="GET" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name/email" class="px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
            <button class="px-3 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700">Search</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <div class="flex items-center justify-end mb-3 gap-2">
            <a href="{{ route('admin.users.invite') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Invite User</a>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Create User</a>
        </div>
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="py-3 px-4 text-left text-sm table-text-muted">Name</th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">Email</th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">Roles</th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($users as $user)
                <tr>
                    <td class="py-3 px-4 table-text">{{ $user->name }}</td>
                    <td class="py-3 px-4 table-text">{{ $user->email }}</td>
                    <td class="py-3 px-4 table-text">
                        <form method="POST" action="{{ route('admin.users.update-roles', $user) }}" class="flex items-center gap-2">
                            @csrf
                            <select name="roles[]" multiple size="1" class="px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" @selected($user->hasRole($role))>{{ $role }}</option>
                                @endforeach
                            </select>
                            <button class="px-3 py-1.5 text-xs rounded bg-emerald-600 text-white hover:bg-emerald-700">Save</button>
                        </form>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs table-text-muted">ID: {{ $user->id }}</span>
                            @if(!$user->trashed())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                @csrf
                                @method('DELETE')
                                <button class="px-2 py-1 text-xs rounded btn-danger">Delete</button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('admin.users.restore', $user->id) }}">
                                @csrf
                                <button class="px-2 py-1 text-xs rounded btn-success">Restore</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-10 text-center table-text-muted">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection


