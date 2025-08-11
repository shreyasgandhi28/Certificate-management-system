@extends('layouts.admin')
@section('title', 'Invite User')
@section('page-title', 'Invite User')
@section('page-description', 'Send an email invitation to a new user')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.users.send-invite') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Roles</label>
            <select name="roles[]" multiple class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
                @foreach($roles as $role)
                    <option value="{{ $role }}" @selected(collect(old('roles', []))->contains($role))>{{ $role }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-700">Cancel</a>
            <button type="submit" class="px-4 py-2 text-sm rounded-lg btn-primary">Send Invite</button>
        </div>
    </form>
</div>
@endsection


