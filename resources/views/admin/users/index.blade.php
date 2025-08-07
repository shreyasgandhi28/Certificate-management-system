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
                        <span class="text-xs table-text-muted">ID: {{ $user->id }}</span>
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


