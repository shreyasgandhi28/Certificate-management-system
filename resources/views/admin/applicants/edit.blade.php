@extends('layouts.admin')
@section('title', 'Edit Application')
@section('page-title', 'Edit Application')
@section('page-description', 'Update applicant details')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.applicants.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to List
        </a>
    </div>
    
    <form method="POST" action="{{ route('admin.applicants.update', $applicant) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name', $applicant->name) }}" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $applicant->email) }}" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $applicant->phone) }}" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
            <select name="status" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
                @foreach(['pending'=>'Pending','in_verification'=>'In Verification','verified'=>'Verified','rejected'=>'Rejected','certificate_generated'=>'Certificate Generated'] as $val=>$label)
                    <option value="{{ $val }}" @selected(old('status',$applicant->status)===$val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="pt-4 mt-6 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-md font-semibold card-text-primary mb-3">Attachments & Verification</h3>
            @if($applicant->uploads->isEmpty())
                <p class="text-sm table-text-muted">No attachments uploaded.</p>
            @else
                <div class="space-y-4">
                    @foreach($applicant->uploads as $upload)
                        <div class="p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium table-text">{{ $upload->getTypeLabel() }} — {{ $upload->original_filename }}</div>
                                    <div class="text-xs table-text-muted">#U{{ str_pad($upload->id, 4, '0', STR_PAD_LEFT) }} · {{ $upload->mime_type }} · {{ $upload->getFileSizeFormatted() }}</div>
                                </div>
                                <a href="{{ route('admin.uploads.view', $upload) }}" target="_blank" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 font-medium">View</a>
                            </div>


                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('admin.applicants.show', $applicant) }}" class="px-4 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-700">Cancel</a>
            <button type="submit" class="px-4 py-2 text-sm rounded-lg btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection


