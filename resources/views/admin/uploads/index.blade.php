@extends('layouts.admin')
@section('title', 'Documents')
@section('page-title', 'Documents')
@section('page-description', 'Browse and preview uploaded documents')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold card-text-primary">All Uploads</h1>
        <form method="GET" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by applicant/email/type" class="px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
            <button class="px-3 py-2 text-sm rounded-lg btn-primary">Search</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="py-3 px-4 text-left text-sm table-text-muted">Applicant</th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">Type</th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">Filename</th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">Status</th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($uploads as $upload)
                <tr>
                    <td class="py-3 px-4 table-text">
                        <div class="font-medium">{{ $upload->applicant->name }}</div>
                        <div class="text-xs table-text-muted">{{ $upload->applicant->email }}</div>
                    </td>
                    <td class="py-3 px-4 table-text">{{ $upload->getTypeLabel() }}</td>
                    <td class="py-3 px-4 table-text">{{ $upload->original_filename }}</td>
                    <td class="py-3 px-4">
                        @if($upload->verification_status === 'pending')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400">Pending</span>
                        @elseif($upload->verification_status === 'verified')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400">Verified</span>
                        @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">Rejected</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.uploads.view', $upload) }}" target="_blank" class="px-2 py-1 text-xs rounded btn-primary">View</a>
                            <form action="{{ route('admin.uploads.verify', $upload) }}" method="POST">
                                @csrf
                                <button class="px-2 py-1 text-xs rounded btn-success">Verify</button>
                            </form>
                            <form action="{{ route('admin.uploads.reject', $upload) }}" method="POST">
                                @csrf
                                <input type="hidden" name="verification_comments" value="Rejected from documents list">
                                <button class="px-2 py-1 text-xs rounded btn-danger">Reject</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-10 text-center table-text-muted">No uploads found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $uploads->links() }}
    </div>
</div>
@endsection


