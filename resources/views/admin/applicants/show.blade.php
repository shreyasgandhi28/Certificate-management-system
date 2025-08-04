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
                                <button class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm">
                                    View
                                </button>
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

    <!-- Action Buttons -->
    <div class="mt-6 flex space-x-4">
        <a href="{{ route('admin.applicants.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
            Back to Applications
        </a>
        <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
            Approve Application
        </button>
        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
            Reject Application
        </button>
    </div>
</div>
@endsection
