@extends('layouts.admin')
@section('title', 'Documents')
@section('page-title', 'Documents')
@section('page-description', 'Browse and preview uploaded documents')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold card-text-primary">All Uploads</h1>

        <div class="flex items-center gap-3">
        <!-- Filter Button (Applicants-style) -->
        <div class="relative inline-block" x-data="{ showFilter: false }">
            <button @click="showFilter = !showFilter" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-all">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filter
                @if(request()->hasAny(['q','applicant','filename','type','status','from','to']))
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
                 class="fixed mt-2 w-[380px] rounded-xl shadow-xl ring-1 ring-black/10 dark:ring-white/10 z-50 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm"
                 style="display: none; right: 1rem; top: 5rem; max-height: 80vh; overflow-y: auto;">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-medium text-gray-900 dark:text-white">Filter Documents</h3>
                        <button type="button" @click="showFilter = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form method="GET" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Applicant (name or email)</label>
                            <input type="text" name="applicant" value="{{ request('applicant') }}" placeholder="e.g. John or john@email.com"
                                   class="block w-full px-4 py-3 text-base border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filename</label>
                                <input type="text" name="filename" value="{{ request('filename') }}" placeholder="original filename"
                                       class="block w-full px-4 py-3 text-base border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                                <select name="type" class="block w-full px-4 py-3 text-base border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                                    <option value="">All</option>
                                    @foreach(['tenth'=>'10th','twelfth'=>'12th','graduation'=>'Graduation','masters'=>"Master's"] as $val=>$label)
                                        <option value="{{ $val }}" @selected(request('type')===$val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Application ID</label>
                            <input type="number" name="applicant_id" value="{{ request('applicant_id') }}" placeholder="e.g. 5"
                                   class="block w-full px-4 py-3 text-base border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Verification Status</label>
                            <select name="status" class="block w-full px-4 py-3 text-base border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                                <option value="">All Statuses</option>
                                <option value="pending" @selected(request('status') === 'pending')>
                                    Pending
                                </option>
                                <option value="verified" @selected(request('status') === 'verified')>
                                    Verified
                                </option>
                                <option value="rejected" @selected(request('status') === 'rejected')>
                                    Rejected
                                </option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From</label>
                                <input type="date" name="from" value="{{ request('from') }}"
                                       class="block w-full px-4 py-3 text-base border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To</label>
                                <input type="date" name="to" value="{{ request('to') }}"
                                       class="block w-full px-4 py-3 text-base border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-4 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.uploads.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">Reset all filters</a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900">Apply filters</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Export CSV Button -->
        <form method="GET" action="{{ route('admin.uploads.export') }}">
            <input type="hidden" name="q" value="{{ request('q') }}">
            <input type="hidden" name="applicant" value="{{ request('applicant') }}">
            <input type="hidden" name="filename" value="{{ request('filename') }}">
            <input type="hidden" name="type" value="{{ request('type') }}">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="from" value="{{ request('from') }}">
            <input type="hidden" name="to" value="{{ request('to') }}">
            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Export CSV
            </button>
        </form>
        </div>
    </div>

    @if(request()->hasAny(['q','applicant','filename','type','status','from','to','applicant_id']))
    <div class="flex flex-wrap items-center gap-2 mb-4">
        @foreach(['q'=>'Search','applicant'=>'Applicant','filename'=>'Filename','type'=>'Type','status'=>'Status','from'=>'From','to'=>'To','applicant_id'=>'Application ID'] as $key=>$label)
            @if(request($key))
                <span class="inline-flex items-center px-3 py-1.5 text-sm bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 text-blue-700 dark:text-blue-400 rounded-lg">
                    <span class="font-medium">{{ $label }}:</span>
                    <span class="ml-1.5">{{ request($key) }}</span>
                    <a href="{{ request()->fullUrlWithQuery([$key=>null]) }}" class="ml-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                </span>
            @endif
        @endforeach
    </div>
    @endif

    <div class="overflow-x-auto">
        <form method="GET" action="{{ route('admin.uploads.export') }}" id="bulkExportUploads">
            <input type="hidden" name="ids" id="bulkUploadIds">
        </form>
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="py-3 px-4 w-10 text-center">
                        <input type="checkbox" id="selectAllUploads" class="align-middle">
                    </th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">
                        <x-table.sortable-header 
                            field="applicant_id" 
                            :sortField="$sort['field'] ?? null" 
                            :sortDirection="$sort['direction'] ?? 'asc'"
                            :nextDirection="$sort['nextDirection'] ?? 'desc'"
                            class="justify-start"
                        >
                            Applicant
                        </x-table.sortable-header>
                    </th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">
                        <x-table.sortable-header 
                            field="type" 
                            :sortField="$sort['field'] ?? null" 
                            :sortDirection="$sort['direction'] ?? 'asc'"
                            :nextDirection="$sort['nextDirection'] ?? 'desc'"
                            class="justify-start"
                        >
                            Type
                        </x-table.sortable-header>
                    </th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">
                        <x-table.sortable-header 
                            field="original_filename" 
                            :sortField="$sort['field'] ?? null" 
                            :sortDirection="$sort['direction'] ?? 'asc'"
                            :nextDirection="$sort['nextDirection'] ?? 'desc'"
                            class="justify-start"
                        >
                            Filename
                        </x-table.sortable-header>
                    </th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">
                        <x-table.sortable-header 
                            field="verification_status" 
                            :sortField="$sort['field'] ?? null" 
                            :sortDirection="$sort['direction'] ?? 'asc'"
                            :nextDirection="$sort['nextDirection'] ?? 'desc'"
                            class="justify-start"
                        >
                            Status
                        </x-table.sortable-header>
                    </th>
                    <th class="py-3 px-4 text-left text-sm table-text-muted">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($uploads as $upload)
                <tr>
                    <td class="py-3 px-4 w-10 text-center">
                        <input type="checkbox" class="rowChkUpload align-middle" value="{{ $upload->id }}">
                    </td>
                    <td class="py-3 px-4 table-text">
                        @if($upload->applicant)
                            <div class="font-medium">{{ $upload->applicant->name }}</div>
                            <div class="text-xs table-text-muted">#{{ str_pad($upload->applicant->id, 4, '0', STR_PAD_LEFT) }} · {{ $upload->applicant->email }}</div>
                        @else
                            <div class="text-gray-400 italic">No applicant</div>
                        @endif
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
                        <a href="{{ route('admin.uploads.view', $upload) }}" target="_blank" 
                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-md shadow-sm transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-10 text-center table-text-muted">No uploads found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3 flex justify-end">
            <button type="button" onclick="submitBulkUploads()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Export Selected</button>
        </div>
        <script>
        function submitBulkUploads(){
            const ids = Array.from(document.querySelectorAll('.rowChkUpload:checked')).map(el => el.value).join(',');
            document.getElementById('bulkUploadIds').value = ids;
            document.getElementById('bulkExportUploads').submit();
        }
        document.getElementById('selectAllUploads')?.addEventListener('change', e => {
            document.querySelectorAll('.rowChkUpload').forEach(cb => cb.checked = e.target.checked);
        });
        </script>
    </div>

    <div class="mt-4">
        {{ $uploads->links() }}
    </div>
</div>
@endsection


