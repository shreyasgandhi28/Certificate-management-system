@extends('layouts.admin')
@section('title', 'Users')
@section('page-title', 'Users')
@section('page-description', 'Manage user roles and access')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6" x-data="{ showFilter: false }">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold card-text-primary">Users</h1>
        
        <div class="flex items-center gap-3">
            <!-- Create User Button -->
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create User
            </a>
            
            <!-- Filter Button -->
            <div class="relative inline-block">
                <button @click="showFilter = !showFilter" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-all">
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                    @if(request()->hasAny(['q','role','status','from','to']))
                    <span class="ml-2 flex h-2 w-2">
                        <span class="animate-bounce inline-flex h-2 w-2 rounded-full bg-blue-500"></span>
                    </span>
                    @endif
                </button>

                <!-- Filter Dropdown -->
                <div x-show="showFilter"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    @click.outside="showFilter = false"
                    class="fixed mt-2 w-[380px] rounded-xl shadow-xl ring-1 ring-black/10 dark:ring-white/5 z-50 bg-white dark:bg-gray-800"
                    style="display: none; right: 1rem; top: 5rem;">
                    
                    <form method="GET" class="space-y-4 p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-medium text-gray-900 dark:text-white">Filter Users</h3>
                            <button type="button" @click="showFilter = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Name or email"
                                class="block w-full px-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-800 dark:text-gray-300">
                        </div>

                        <!-- Role Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role</label>
                            <select name="role" class="block w-full px-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-800 dark:text-gray-300">
                                <option value="">All Roles</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select name="status" class="block w-full px-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-800 dark:text-gray-300">
                                <option value="">All Statuses</option>
                                <option value="active" @selected(request('status') === 'active')>Active</option>
                                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From</label>
                                <input type="date" name="from" value="{{ request('from') }}"
                                    class="block w-full px-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-800 dark:text-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To</label>
                                <input type="date" name="to" value="{{ request('to') }}"
                                    class="block w-full px-4 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-800 dark:text-gray-300">
                            </div>
                        </div>

                        <!-- Filter Actions -->
                        <div class="flex items-center justify-between gap-4 pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">Reset all filters</a>
                            <button type="submit" class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900">Apply filters</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="py-3 px-4 text-left text-sm table-text-muted w-20">
                        <x-table.sortable-header 
                            field="id" 
                            :sortField="$sort['field'] ?? null" 
                            :sortDirection="$sort['direction'] ?? 'asc'"
                            :nextDirection="$sort['nextDirection'] ?? 'desc'"
                            class="justify-start"
                        >
                            ID
                        </x-table.sortable-header>
                    </th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">
                        <x-table.sortable-header 
                            field="name" 
                            :sortField="$sort['field'] ?? null" 
                            :sortDirection="$sort['direction'] ?? 'asc'"
                            :nextDirection="$sort['nextDirection'] ?? 'desc'"
                            class="justify-start"
                        >
                            Name
                        </x-table.sortable-header>
                    </th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">
                        <x-table.sortable-header 
                            field="email" 
                            :sortField="$sort['field'] ?? null" 
                            :sortDirection="$sort['direction'] ?? 'asc'"
                            :nextDirection="$sort['nextDirection'] ?? 'desc'"
                            class="justify-start"
                        >
                            Email
                        </x-table.sortable-header>
                    </th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">
                        <x-table.sortable-header 
                            field="is_active" 
                            :sortField="$sort['field'] ?? null" 
                            :sortDirection="$sort['direction'] ?? 'asc'"
                            :nextDirection="$sort['nextDirection'] ?? 'desc'"
                            class="justify-start"
                        >
                            Status
                        </x-table.sortable-header>
                    </th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">Roles</th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($users as $user)
                <tr>
                    <td class="py-3 px-4 table-text text-center">{{ $user->id }}</td>
                    <td class="py-3 px-4 table-text">{{ $user->name }}</td>
                    <td class="py-3 px-4 table-text">{{ $user->email }}</td>
                    <td class="py-3 px-4">
                        @if($user->is_active)
                            <div class="flex items-center">
                                <div class="w-2 h-2 rounded-full bg-green-500 mr-2"></div>
                                <span class="text-sm font-medium text-green-700 dark:text-green-400">Active</span>
                            </div>
                        @else
                            <div class="flex items-center">
                                <div class="w-2 h-2 rounded-full bg-red-500 mr-2"></div>
                                <span class="text-sm font-medium text-red-700 dark:text-red-400">Inactive</span>
                            </div>
                        @endif
                    </td>
                    <td class="py-3 px-4 table-text">

                            <!-- Dropdown toggle button -->
                        <div x-data="{ open: false }" class="relative z-50" @click.away="open = false" style="position: static;">
                            <!-- Dropdown toggle button -->
                            <button 
                                @click="open = !open" 
                                class="flex items-center justify-between w-48 px-3 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900"
                                type="button"
                            >
                                <span class="truncate">
                                    {{ $user->roles->pluck('name')->first() ?? 'No Role' }}
                                    @if($user->roles->count() > 1)+{{ $user->roles->count() - 1 }} more @endif
                                </span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown menu -->
                            <div 
                                x-show="open" 
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="fixed z-50 mt-1 w-56 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 py-1"
                                style="display: none;"
                            >
                                <form method="POST" action="{{ route('admin.users.update-roles', $user) }}" class="space-y-2 p-2">
                                    @csrf
                                    @foreach($roles as $role)
                                        <div class="flex items-center px-3 py-1.5">
                                            <input 
                                                id="role-{{ $user->id }}-{{ $role }}"
                                                type="checkbox" 
                                                name="roles[]" 
                                                value="{{ $role }}" 
                                                @checked($user->hasRole($role))
                                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:border-gray-600"
                                            >
                                            <label for="role-{{ $user->id }}-{{ $role }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $role }}
                                            </label>
                                        </div>
                                    @endforeach
                                    <div class="border-t border-gray-200 dark:border-gray-700 mt-2 pt-2">
                                        <button type="submit" class="w-full px-3 py-1.5 text-xs text-center bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            @if(!$user->trashed())
                                @if($user->is_active)
                                    <form method="POST" action="{{ route('admin.users.deactivate', $user) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs rounded-md btn-warning hover:bg-yellow-600" onclick="return confirm('Are you sure you want to deactivate this user?')">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            Deactivate
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.activate', $user) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs rounded-md btn-success hover:bg-green-600">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Activate
                                        </button>
                                    </form>
                                @endif
                                
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs rounded-md btn-danger hover:bg-red-600" 
                                        onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.users.restore', $user->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs rounded-md btn-success">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Restore
                                    </button>
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


