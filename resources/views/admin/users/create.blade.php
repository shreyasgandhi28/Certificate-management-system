@extends('layouts.admin')
@section('title', 'Create User')
@section('page-title', 'Create User')
@section('page-description', 'Add a new user and assign roles')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800" required>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password</label>
                <input type="password" name="password" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800" required>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Roles</label>
            <select name="roles[]" multiple class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
                @foreach($roles as $role)
                    <option value="{{ $role }}" @selected(collect(old('roles', []))->contains($role))>{{ $role }}</option>
                @endforeach
            </select>
            <p class="text-xs mt-1 text-gray-500">Hold Ctrl/Cmd to select multiple roles.</p>
        </div>
        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-700">Cancel</a>
            <button type="submit" class="px-4 py-2 text-sm rounded-lg btn-primary">Create User</button>
        </div>
    </form>
</div>
@endsection


