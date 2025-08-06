@extends('layouts.admin')

@section('title', 'View Applicant')
@section('page-title', 'Applicant Details')
@section('page-description', 'View applicant information and uploaded documents')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $applicant->name }}</h2>
        <p class="text-gray-600 dark:text-gray-400">Application ID: {{ str_pad($applicant->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Personal Information</h3>
            <dl class="space-y-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ $applicant->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ $applicant->phone }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ ucfirst($applicant->gender) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date of Birth</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ $applicant->date_of_birth ? $applicant->date_of_birth->format('M d, Y') : 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                    <dd class="text-sm">
                        @if($applicant->status === 'pending')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400">
                                Pending
                            </span>
                        @elseif($applicant->status === 'verified')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400">
                                Verified
                            </span>
                        @elseif($applicant->status === 'rejected')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">
                                Rejected
                            </span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Submitted</dt>
                    <dd class="text-sm text-gray-900 dark:text-white">{{ $applicant->submitted_at ? $applicant->submitted_at->format('M d, Y g:i A') : 'Not submitted' }}</dd>
                </div>
            </dl>
        </div>

        <!-- Uploaded Documents -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Uploaded Documents</h3>
            @if($applicant->uploads->count() > 0)
                <div class="space-y-3">
                    @foreach($applicant->uploads as $upload)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $upload->getTypeLabel() }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $upload->original_filename }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($upload->verification_status === 'pending')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400">
                                        Pending
                                    </span>
                                @elseif($upload->verification_status === 'verified')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400">
                                        Verified
                                    </span>
                                @elseif($upload->verification_status === 'rejected')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">
                                        Rejected
                                    </span>
                                @endif
                                <a href="{{ route('admin.uploads.view', $upload) }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm">
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-sm">No documents uploaded yet.</p>
            @endif
        </div>
    </div>

    <!-- Educational Details -->
    @if($applicant->educational_details)
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Educational Details</h3>
            <p class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                {{ $applicant->educational_details }}
            </p>
        </div>
    @endif

    <!-- Action Buttons and Status Section -->
    <div class="mt-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 space-y-6">
        <!-- Current Status -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Application Status</h3>
            <div class="flex items-center">
                @if($applicant->status === 'pending')
                    <div class="flex items-center">
                        <div class="legend-dot-pending"></div>
                        <span class="text-sm font-medium card-text-primary">Pending Review</span>
                    </div>
                @elseif($applicant->status === 'in_verification')
                    <div class="flex items-center">
                        <div class="legend-dot-pending"></div>
                        <span class="text-sm font-medium card-text-primary">Verification in Progress</span>
                    </div>
                @elseif($applicant->status === 'verified')
                    <div class="flex items-center">
                        <div class="legend-dot-verified"></div>
                        <span class="text-sm font-medium card-text-primary">Verified</span>
                    </div>
                @else
                    <div class="flex items-center">
                        <div class="legend-dot-rejected"></div>
                        <span class="text-sm font-medium card-text-primary">Rejected</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.applicants.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Applications
            </a>

            @if($applicant->status === 'pending')
                <form action="{{ route('admin.applicants.start-verification', $applicant) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Start Verification
                    </button>
                </form>
            @endif

            @if($applicant->status === 'in_verification')
                <form action="{{ route('admin.applicants.complete-verification', $applicant) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Complete Verification
                    </button>
                </form>

                <button type="button" 
                        x-data="" 
                        x-on:click="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'reject-modal' }))"
                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reject Application
                </button>
            @endif

            @if($applicant->status === 'verified')
                <form action="{{ route('admin.applicants.generate-certificate', $applicant) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Generate Certificate
                    </button>
                </form>
            @endif
        </div>

        <!-- Verification Notes -->
        @if($applicant->status === 'in_verification')
            <div class="mt-4">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Verification Notes</h4>
                <textarea name="verification_notes" rows="3" 
                          class="block w-full px-4 py-3 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-800 dark:text-gray-300"
                          placeholder="Add notes about the verification process..."></textarea>
            </div>
        @endif

        <!-- Rejection Reason (if rejected) -->
        @if($applicant->status === 'rejected')
            <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <h4 class="text-sm font-medium text-red-800 dark:text-red-300 mb-2">Rejection Reason</h4>
                <p class="text-sm text-red-700 dark:text-red-400">{{ $applicant->rejection_reason }}</p>
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div x-data="{ show: false }" 
         x-show="show" 
         x-on:open-modal.window="if ($event.detail === 'reject-modal') show = true"
         x-on:keydown.escape.window="show = false"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="show" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.applicants.reject', $applicant) }}" method="POST">
                    @csrf
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                    Reject Application
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Please provide a reason for rejecting this application. This will be visible to the applicant.
                                    </p>
                                    <div class="mt-4">
                                        <textarea name="rejection_reason" rows="4" required
                                                  class="block w-full px-4 py-3 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-transparent dark:bg-gray-800 dark:text-gray-300"
                                                  placeholder="Enter rejection reason..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Reject Application
                        </button>
                        <button type="button"
                                x-on:click="show = false"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
