@extends('layouts.admin')
@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 px-2 sm:px-4" x-data="{ showFilter: false }" @keydown.escape.window="showFilter = false">
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-lg font-semibold card-text-primary">Applicants</h1>
                
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
                         class="fixed mt-2 w-[380px] rounded-xl shadow-xl ring-1 ring-black/10 dark:ring-white/5 z-50 bg-white dark:bg-gray-800"
                         style="display: none; right: 1rem; top: 5rem;">
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
                                           class="block w-full px-4 py-3 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-800 dark:text-gray-300">
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
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                    <div class="space-y-3">
                                        <label class="relative flex items-center px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="{ 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/10': $refs.pendingStatus.checked }">
                                            <input x-ref="pendingStatus" type="radio" name="status" value="pending" {{ request('status') === 'pending' ? 'checked' : '' }} class="h-4 w-4 text-yellow-600 border-gray-300 focus:ring-yellow-500">
                                            <div class="flex items-center gap-2 ml-2" :class="{ 'text-yellow-600 font-medium': $refs.pendingStatus.checked }">
                                                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                                <span class="text-sm">Pending</span>
                                            </div>
                                        </label>
                                        <label class="relative flex items-center px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="{ 'border-green-500 bg-green-50 dark:bg-green-900/10': $refs.verifiedStatus.checked }">
                                            <input x-ref="verifiedStatus" type="radio" name="status" value="verified" {{ request('status') === 'verified' ? 'checked' : '' }} class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                                            <div class="flex items-center gap-2 ml-2" :class="{ 'text-green-600 font-medium': $refs.verifiedStatus.checked }">
                                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                                <span class="text-sm">Verified</span>
                                            </div>
                                        </label>
                                        <label class="relative flex items-center px-3 py-2 border border-gray-200 dark:border-gray-700 rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="{ 'border-red-500 bg-red-50 dark:bg-red-900/10': $refs.rejectedStatus.checked }">
                                            <input x-ref="rejectedStatus" type="radio" name="status" value="rejected" {{ request('status') === 'rejected' ? 'checked' : '' }} class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500">
                                            <div class="flex items-center gap-2 ml-2" :class="{ 'text-red-600 font-medium': $refs.rejectedStatus.checked }">
                                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                                <span class="text-sm">Rejected</span>
                                            </div>
                                        </label>
                                    </div>
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
            </div>

            <!-- Active Filters Display -->
            @if(request()->hasAny(['name','email','phone','status','submitted_at']))
            <div class="flex flex-wrap items-center gap-2 mb-4">
                @foreach(['name'=>'Name','email'=>'Email','phone'=>'Phone','status'=>'Status','submitted_at'=>'Date'] as $key=>$label)
                    @if(request($key))
                        <span class="inline-flex items-center px-3 py-1.5 text-sm bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 text-blue-700 dark:text-blue-400 rounded-lg">
                            <span class="font-medium">{{ $label }}:</span>
                            <span class="ml-1.5">{{ $key === 'submitted_at' ? \Carbon\Carbon::parse(request($key))->format('M j, Y') : request($key) }}</span>
                            <a href="{{ request()->fullUrlWithQuery([$key=>null]) }}" 
                               class="ml-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
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
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="text-left py-3 px-4 font-medium table-text-muted text-sm">Applicant</th>
                            <th class="text-left py-3 px-4 font-medium table-text-muted text-sm">Email</th>
                            <th class="text-left py-3 px-4 font-medium table-text-muted text-sm">Documents</th>
                            <th class="text-left py-3 px-4 font-medium table-text-muted text-sm">Status</th>
                            <th class="text-left py-3 px-4 font-medium table-text-muted text-sm">Date</th>
                            <th class="text-left py-3 px-4 font-medium table-text-muted text-sm">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($applicants as $applicant)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
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
                                @if($applicant->status === 'pending')
                                    <div class="flex items-center">
                                        <div class="legend-dot-pending"></div>
                                        <span class="text-sm font-medium card-text-primary">Pending</span>
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
                            </td>
                            <td class="py-4 px-4">
                                <div class="table-text">{{ $applicant->submitted_at ? $applicant->submitted_at->format('M j, Y') : '-' }}</div>
                                <div class="table-text-muted text-sm">{{ $applicant->submitted_at ? $applicant->submitted_at->diffForHumans() : '' }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <a href="{{ route('admin.applicants.show', $applicant) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 font-medium">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center table-text-muted">
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
            </div>
            <div class="mt-4">
                {{ $applicants->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
