@extends('layouts.admin')

@section('title', 'User Profile - ' . config('app.name'))
@section('page-title', 'User Profile')
@section('page-description', 'Profile Settings')

@push('header')
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">User Profile</h2>
        <p class="text-gray-600 dark:text-gray-300">Profile Settings</p>
    </div>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Flash Messages -->
    @if (session('status') === 'profile-updated')
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700 dark:text-green-300">
                        Profile updated successfully.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6">
        <!-- Profile Information -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile Information
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Update your account's profile information and email address.</p>
            </div>
            <div class="p-6">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v4h8z" />
                    </svg>
                    Update Password
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Ensure your account is using a long, random password to stay secure.</p>
            </div>
            <div class="p-6">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Delete Account -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-900/10">
                <h2 class="text-lg font-semibold text-red-700 dark:text-red-400 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Account
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-red-300/80">Once your account is deleted, all of its resources and data will be permanently removed.</p>
            </div>
            <div class="p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
