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
                                <a href="{{ route('admin.uploads.view', $upload) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 dark:hover:bg-blue-800/50 text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
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
               class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
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
                <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                Review all documents before verifying or rejecting the application.
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.applicants.complete-verification', $applicant) }}" method="POST" class="w-full">
                    @csrf
                    <div class="space-y-4">
                        <div class="w-full">
                            <label for="verification_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Verification Notes (Optional)</label>
                            <input type="text" 
                                   id="verification_notes" 
                                   name="verification_notes" 
                                   placeholder="Add any notes about this verification" 
                                   class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 flex-1 sm:flex-none">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Approve Application
                            </button>
                            
                            <button type="button" 
                                    x-data="" 
                                    x-on:click="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'reject-modal' }))"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 flex-1 sm:flex-none">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Reject Application
                            </button>
                        </div>
                    </div>
                </form>
            @endif

            @if($applicant->status === 'verified')
                <form action="{{ route('admin.applicants.generate-certificate', $applicant) }}" method="POST" class="inline flex items-center gap-2">
                    @csrf
                    <select name="template_id" class="px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        @foreach(\App\Models\CertificateTemplate::where('active', true)->get() as $template)
                            <option value="{{ $template->id }}">{{ $template->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="inline-flex items-center px-4 py-2 btn-purple text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Generate Certificate
                    </button>
                </form>
            @endif

            @if(auth()->user()->hasAnyRole(['Super Admin','Certificate Issuer']))
                <a href="{{ route('admin.applicants.edit', $applicant) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Edit</a>
                <form action="{{ route('admin.applicants.destroy', $applicant) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg">Delete</button>
                </form>
            @endif
        </div>

        <!-- Verification Notes -->
            @if($applicant->verification_notes)
            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-300 mb-2">Verification Notes</h4>
                <p class="text-sm text-blue-700 dark:text-blue-400">{{ $applicant->verification_notes }}</p>
            </div>
        @endif

        <!-- Rejection Reason (if rejected) -->
        @if($applicant->status === 'rejected' && $applicant->rejection_reason)
            <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <h4 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-2">Rejection Reason</h4>
                <p class="text-sm text-red-700 dark:text-red-400">{{ $applicant->rejection_reason }}</p>
            </div>
        @endif
    </div>

    <!-- Reject Application Modal -->
    <div x-data="{ show: false, rejectionReason: '' }" 
         x-show="show" 
         x-on:open-modal.window="if ($event.detail === 'reject-modal') { show = true; $nextTick(() => $refs.rejectionReason.focus()) }"
         x-on:keydown.escape.window="show = false"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <!-- Overlay -->
        <div x-show="show" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"
             aria-hidden="true"></div>

        <!-- Modal Container -->
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <!-- Modal Panel -->
            <div x-show="show" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-xl transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-8 text-left shadow-xl transition-all">
                
                <!-- Header -->
                <div class="flex items-start">
                    <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100 dark:bg-red-900/30">
                        <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">Reject Application</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Please provide a reason for rejection that will be shared with the applicant.
                        </p>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.applicants.reject', $applicant) }}" method="POST" class="mt-8">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Reason for Rejection <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2 relative">
                                <textarea x-ref="rejectionReason"
                                          id="rejection_reason"
                                          name="rejection_reason"
                                          rows="5"
                                          required
                                          x-model="rejectionReason"
                                          class="block w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-red-500 focus:ring-red-500 text-base dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 p-3 border"
                                          placeholder="Please provide specific details about why this application is being rejected..."></textarea>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Be specific and constructive. This feedback will help the applicant understand what needs to be improved.
                            </p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                        <button type="button"
                                @click="show = false"
                                class="inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Cancel
                        </button>
                        <button type="submit"
                                :disabled="!rejectionReason.trim()"
                                :class="{'opacity-75 cursor-not-allowed': !rejectionReason.trim()}"
                                class="inline-flex justify-center rounded-lg border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-75 disabled:cursor-not-allowed">
                            Reject Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

