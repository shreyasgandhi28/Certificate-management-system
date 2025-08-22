@extends('layouts.admin')

@push('styles')
<style>
    /* Ensure edit button gradient is applied */
    a[href*="edit"].edit-btn,
    a[href*="edit"].edit-btn:hover {
        background: linear-gradient(135deg, #0d9488, #0f766e) !important;
        color: white !important;
        border: none !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }
    
    a[href*="edit"].edit-btn:hover {
        background: linear-gradient(135deg, #0f766e, #0d5e56) !important;
    }
</style>
@endpush

@section('title', 'Applicants')
@section('page-title', 'Applicant Management')
@section('page-description', 'View and manage certificate applications')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6" x-data="{ showFilter: false }" @keydown.escape.window="showFilter = false">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-lg font-semibold card-text-primary">Applicants</h1>

                <div class="flex items-center gap-3">
                <!-- Filter Button -->
                <div class="relative inline-block" x-data="{ showFilter: false }">
                    <button @click="showFilter = !showFilter" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-all">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Filter
                        @if(request()->hasAny(['name','email','phone','status','submitted_at']))
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
                                <h3 class="text-base font-medium text-gray-900 dark:text-white">Filter Applicants</h3>
                                <button type="button" @click="showFilter = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <form method="GET" x-ref="filterForm" class="space-y-4" @reset.prevent="$event.target.submit()">
                                <!-- Name Search -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                                    <input type="text" 
                                           name="name" 
                                           value="{{ request('name') }}" 
                                           placeholder="Filter by name"
                                           class="block w-full px-4 py-3 text-base border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                                </div>

                                <!-- Advanced Filters -->
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                        <input type="email" name="email" value="{{ request('email') }}" placeholder="Filter by email" 
                                               class="block w-full px-4 py-3 text-base border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                                        <input type="text" name="phone" value="{{ request('phone') }}" placeholder="Filter by phone"
                                               class="block w-full px-4 py-3 text-base border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                                    </div>
                                </div>

                                <!-- Status Filter -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Verification Status</label>
                                    <select name="status" class="block w-full px-4 py-3 text-base border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                                        <option value="">All Statuses</option>
                                        <option value="pending" @selected(request('status') === 'pending') class="flex items-center">
                                            <span class="w-2 h-2 rounded-full bg-yellow-500 mr-2"></span>
                                            Pending
                                        </option>
                                        <option value="verified" @selected(request('status') === 'verified') class="flex items-center">
                                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                            Verified
                                        </option>
                                        <option value="rejected" @selected(request('status') === 'rejected') class="flex items-center">
                                            <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                                            Rejected
                                        </option>
                                    </select>
                                </div>

                                <!-- Certificate Status Filter -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Certificate Status</label>
                                    <select name="certificate_status" class="block w-full px-4 py-3 text-base border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                                        <option value="">All Statuses</option>
                                        <option value="pending" @selected(request('certificate_status') === 'pending')>
                                            Pending
                                        </option>
                                        <option value="generated" @selected(request('certificate_status') === 'generated')>
                                            Generated
                                        </option>
                                    </select>
                                </div>

                                <!-- Application ID -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Application ID</label>
                                    <input type="number" name="id" value="{{ request('id') }}" placeholder="e.g. 5"
                                           class="block w-full px-4 py-3 text-base border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                                </div>

                                <!-- Date Filter -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Submitted Date</label>
                                    <input type="date" name="submitted_at" value="{{ request('submitted_at') }}"
                                           class="block w-full px-4 py-3 text-base border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-700 dark:text-gray-300">
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-end gap-4 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ route('admin.applicants.index') }}"
                                       class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                        Reset all filters
                                    </a>
                                    <button type="submit"
                                            class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900">
                                        Apply filters
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Export CSV Button (visible in both themes) -->
                <form method="GET" action="{{ route('admin.applicants.export') }}">
                    <input type="hidden" name="name" value="{{ request('name') }}">
                    <input type="hidden" name="email" value="{{ request('email') }}">
                    <input type="hidden" name="phone" value="{{ request('phone') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="submitted_at" value="{{ request('submitted_at') }}">
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                        </svg>
                        Export CSV
                    </button>
                </form>
                </div>
            </div>

            <!-- Active Filters Display -->
            @if(request()->hasAny(['name','email','phone','status','certificate_status','submitted_at','id']))
            <div class="flex flex-wrap items-center gap-2 mb-4">
                @if(request('status'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        Verification: {{ ucfirst(request('status')) }}
                        <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full bg-blue-200 dark:bg-blue-800 hover:bg-blue-300 dark:hover:bg-blue-700">
                            <span class="sr-only">Remove status filter</span>
                            <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 8 8">
                                <path fill-rule="evenodd" d="M4 3.293l2.146-2.147a.5.5 0 01.708.708L4.707 4l2.147 2.146a.5.5 0 01-.708.708L4 4.707l-2.146 2.147a.5.5 0 01-.708-.708L3.293 4 1.146 1.854a.5.5 0 01.708-.708L4 3.293z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </span>
                @endif
                
                @if(request('certificate_status'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                        Certificate: {{ ucfirst(request('certificate_status')) }}
                        <a href="{{ request()->fullUrlWithQuery(['certificate_status' => null]) }}" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full bg-indigo-200 dark:bg-indigo-800 hover:bg-indigo-300 dark:hover:bg-indigo-700">
                            <span class="sr-only">Remove certificate status filter</span>
                            <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 8 8">
                                <path fill-rule="evenodd" d="M4 3.293l2.146-2.147a.5.5 0 01.708.708L4.707 4l2.147 2.146a.5.5 0 01-.708.708L4 4.707l-2.146 2.147a.5.5 0 01-.708-.708L3.293 4 1.146 1.854a.5.5 0 01.708-.708L4 3.293z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </span>
                @endif
                
                @foreach(['name'=>'Name','email'=>'Email','phone'=>'Phone','submitted_at'=>'Date','id'=>'Application ID'] as $key=>$label)
                    @if(request($key))
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                            {{ $label }}: {{ request($key) }}
                            <a href="{{ request()->fullUrlWithQuery([$key => null]) }}" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500">
                                <span class="sr-only">Remove {{ strtolower($label) }} filter</span>
                                <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 8 8">
                                    <path fill-rule="evenodd" d="M4 3.293l2.146-2.147a.5.5 0 01.708.708L4.707 4l2.147 2.146a.5.5 0 01-.708.708L4 4.707l-2.146 2.147a.5.5 0 01-.708-.708L3.293 4 1.146 1.854a.5.5 0 01.708-.708L4 3.293z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </span>
                    @endif
                @endforeach
            </div>
            @endif

            <div class="overflow-x-auto">

                <form method="GET" action="{{ route('admin.applicants.export') }}" id="bulkExportForm">
                    <input type="hidden" name="ids" id="bulkIds">
                </form>
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-3 px-4"><input type="checkbox" id="selectAll"></th>
                            <th class="text-left py-3 px-4 text-sm table-text-muted">
                                <x-table.sortable-header 
                                    field="id" 
                                    :sortField="$sort['field'] ?? null" 
                                    :sortDirection="$sort['direction'] ?? 'asc'"
                                    :nextDirection="$sort['nextDirection'] ?? 'desc'"
                                    class="justify-start"
                                >
                                    Applicant
                                </x-table.sortable-header>
                            </th>
                            <th class="text-left py-3 px-4 text-sm table-text-muted">
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
                            <th class="text-left py-3 px-4 text-sm table-text-muted">Documents</th>
                            <th class="text-left py-3 px-4 text-sm table-text-muted">
                                <x-table.sortable-header 
                                    field="status" 
                                    :sortField="$sort['field'] ?? null" 
                                    :sortDirection="$sort['direction'] ?? 'asc'"
                                    :nextDirection="$sort['nextDirection'] ?? 'desc'"
                                    class="justify-start"
                                >
                                    Status
                                </x-table.sortable-header>
                            </th>
                            <th class="text-left py-3 px-4 text-sm table-text-muted">
                                <x-table.sortable-header 
                                    field="submitted_at" 
                                    :sortField="$sort['field'] ?? null" 
                                    :sortDirection="$sort['direction'] ?? 'asc'"
                                    :nextDirection="$sort['nextDirection'] ?? 'desc'"
                                    class="justify-start"
                                >
                                    Date
                                </x-table.sortable-header>
                            </th>
                            <th class="text-left py-3 px-4 text-sm table-text-muted">
                                <x-table.sortable-header 
                                    field="certificate_status" 
                                    :sortField="$sort['field'] ?? null" 
                                    :sortDirection="$sort['direction'] ?? 'asc'"
                                    :nextDirection="$sort['nextDirection'] ?? 'desc'"
                                    class="justify-start"
                                >
                                    Certificate
                                </x-table.sortable-header>
                            </th>
                            <th class="text-left py-3 px-4 text-sm table-text-muted">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($applicants as $applicant)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-4 px-4"><input type="checkbox" class="rowChk" value="{{ $applicant->id }}"></td>
                            <td class="py-4 px-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-bold text-white">{{ strtoupper(substr($applicant->name, 0, 2)) }}</span>
                                    </div>
                                    <div>
                                        <div class="font-medium table-text">{{ $applicant->name }}</div>
                                        <div class="table-text-muted text-sm">#{{ str_pad($applicant->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 table-text">{{ Str::limit($applicant->email, 30) }}</td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $applicant->uploads->count() }} files
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                @if($applicant->status === 'pending' || $applicant->status === 'in_verification')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400">Pending</span>
                                @elseif($applicant->status === 'verified')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400">Verified</span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">Rejected</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="table-text">{{ $applicant->submitted_at ? $applicant->submitted_at->format('M j, Y') : '-' }}</div>
                                <div class="table-text-muted text-sm">{{ $applicant->submitted_at ? $applicant->submitted_at->diffForHumans() : '' }}</div>
                            </td>
                            <td class="py-4 px-4">
                                @if($applicant->hasCertificate())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Generated
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.applicants.show', $applicant) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-md shadow-sm transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </a>
                                    @if(auth()->user()->hasAnyRole(['Super Admin','Certificate Issuer']))
                                    <a href="{{ route('admin.applicants.edit', $applicant) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white rounded-md shadow-sm transition-all duration-200" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 50%, #f59e0b 100%) !important; background-size: 200% auto !important;" onmouseover="this.style.backgroundPosition='right center'" onmouseout="this.style.backgroundPosition='left center'">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center table-text-muted">
                                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-medium">No applicants found</p>
                                <p class="text-sm">Applicants will appear here once submitted</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3 flex justify-end">
                    <button type="button" onclick="submitBulkExport()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Export Selected</button>
                </div>
                <script>
                function submitBulkExport(){
                    const ids = Array.from(document.querySelectorAll('.rowChk:checked')).map(el => el.value).join(',');
                    document.getElementById('bulkIds').value = ids;
                    document.getElementById('bulkExportForm').submit();
                }
                document.getElementById('selectAll')?.addEventListener('change', e => {
                    document.querySelectorAll('.rowChk').forEach(cb => cb.checked = e.target.checked);
                });
                </script>
            </div>
            <div class="mt-4 flex justify-end">
                <div class="w-full">
                    {{ $applicants->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
