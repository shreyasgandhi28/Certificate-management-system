@extends('layouts.admin')
@section('title', 'Certificates')
@section('page-title', 'Certificates')
@section('page-description', 'Generated certificates and delivery status')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold card-text-primary">Certificates</h1>

        <div class="flex items-center gap-3">
        <!-- Filter Button (Applicants-style) -->
        <div class="relative inline-block" x-data="{ showFilter: false }">
            <button @click="showFilter = !showFilter" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-all">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filter
                @if(request()->hasAny(['q','applicant','status','template_id','from','to']))
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
                        <h3 class="text-base font-medium text-gray-900 dark:text-white">Filter Certificates</h3>
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
                                   class="block w-full px-4 py-3 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-800 dark:text-gray-300">
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                <select name="status" class="block w-full px-4 py-3 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-800 dark:text-gray-300">
                                    <option value="">All</option>
                                    @foreach(['generated'=>'Generated','sent_email'=>'Sent Email','sent_whatsapp'=>'Sent WhatsApp','failed'=>'Failed'] as $val=>$label)
                                        <option value="{{ $val }}" @selected(request('status')===$val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Template</label>
                                <select name="template_id" class="block w-full px-4 py-3 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-800 dark:text-gray-300">
                                    <option value="">All</option>
                                    @foreach(\App\Models\CertificateTemplate::query()->where('active', true)->orderBy('name')->get() as $tpl)
                                        <option value="{{ $tpl->id }}" @selected((string)request('template_id')===(string)$tpl->id)>{{ $tpl->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Application ID</label>
                            <input type="number" name="applicant_id" value="{{ request('applicant_id') }}" placeholder="e.g. 5"
                                   class="block w-full px-4 py-3 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-800 dark:text-gray-300">
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From</label>
                                <input type="date" name="from" value="{{ request('from') }}"
                                       class="block w-full px-4 py-3 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-800 dark:text-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To</label>
                                <input type="date" name="to" value="{{ request('to') }}"
                                       class="block w-full px-4 py-3 text-sm border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent dark:bg-gray-800 dark:text-gray-300">
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-4 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin.certificates.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">Reset all filters</a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900">Apply filters</button>
                        </div>
        </form>
                </div>
            </div>
        </div>

        <!-- Export CSV Button -->
        <form method="GET" action="{{ route('admin.certificates.export') }}">
            <input type="hidden" name="q" value="{{ request('q') }}">
            <input type="hidden" name="applicant" value="{{ request('applicant') }}">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="template_id" value="{{ request('template_id') }}">
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

    @if(request()->hasAny(['q','applicant','status','template_id','from','to','applicant_id']))
    <div class="flex flex-wrap items-center gap-2 mb-4">
        @foreach(['q'=>'Search','applicant'=>'Applicant','status'=>'Status','template_id'=>'Template','from'=>'From','to'=>'To','applicant_id'=>'Application ID'] as $key=>$label)
            @if(request($key))
                <span class="inline-flex items-center px-3 py-1.5 text-sm bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 text-blue-700 dark:text-blue-400 rounded-lg">
                    <span class="font-medium">{{ $label }}:</span>
                    <span class="ml-1.5">
                        @if($key==='template_id')
                            {{ optional(\App\Models\CertificateTemplate::find(request('template_id')))->name ?? request('template_id') }}
                        @else
                            {{ request($key) }}
                        @endif
                    </span>
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
        <form method="GET" action="{{ route('admin.certificates.export') }}" id="bulkExportCertificates">
            <input type="hidden" name="ids" id="bulkCertIds">
        </form>
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="text-left py-3 px-4"><input type="checkbox" id="selectAllCerts"></th>
                    <th class="text-left py-3 px-4 font-medium table-text-muted text-sm">Serial</th>
                    <th class="text-left py-3 px-4 font-medium table-text-muted text-sm">Applicant Details</th>
                    <th class="text-left py-3 px-4 font-medium table-text-muted text-sm">Template</th>
                    <th class="text-left py-3 px-4 font-medium table-text-muted text-sm">Generated</th>
                    <th class="text-left py-3 px-4 font-medium table-text-muted text-sm">Status</th>
                    <th class="text-left py-3 px-4 font-medium table-text-muted text-sm">Delivery</th>
                    <th class="text-left py-3 px-4 font-medium table-text-muted text-sm">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($certificates as $certificate)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="py-3 px-4"><input type="checkbox" class="rowChkCert" value="{{ $certificate->id }}"></td>
                    <td class="py-3 px-4 table-text">
                        <div class="font-medium">{{ $certificate->serial_number }}</div>
                        <div class="text-xs table-text-muted">ID: {{ $certificate->id }}</div>
                    </td>
                    <td class="py-3 px-4 table-text">
                        <div class="font-medium">{{ $certificate->applicant->name }}</div>
                        <div class="text-xs table-text-muted">#{{ str_pad($certificate->applicant->id, 4, '0', STR_PAD_LEFT) }} · {{ $certificate->applicant->email }}</div>
                        <div class="text-xs table-text-muted">Phone: {{ $certificate->applicant->phone }}</div>
                        <div class="text-xs table-text-muted">Submitted: {{ $certificate->applicant->submitted_at?->format('M d, Y') }}</div>
                    </td>
                    <td class="py-3 px-4 table-text">{{ optional($certificate->template)->name ?? '—' }}</td>
                    <td class="py-3 px-4 table-text">{{ $certificate->generated_at?->format('M d, Y g:i A') }}</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">{{ $certificate->status }}</span>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2 whitespace-nowrap">
                            <form action="{{ route('admin.certificates.send-email', $certificate) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs rounded-md btn-success">Send Email</button>
                            </form>
                            <form action="{{ route('admin.certificates.send-whatsapp', $certificate) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs rounded-md btn-primary">Send WhatsApp</button>
                            </form>
                            <form action="{{ route('admin.certificates.reset-status', $certificate) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs rounded-md btn-warning">Reset</button>
                            </form>
                        </div>
                    </td>
                    <td class="py-3 px-4 table-text">
                        <div class="text-xs mb-2">Email: {{ $certificate->email_sent_at ? $certificate->email_sent_at->diffForHumans() : '-' }}</div>
                        <div class="text-xs mb-2">WhatsApp: {{ $certificate->whatsapp_sent_at ? $certificate->whatsapp_sent_at->diffForHumans() : '-' }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">Attempts: {{ $certificate->send_attempts }}</div>
                        <a class="inline-flex items-center px-3 py-1.5 text-xs rounded-md border border-blue-300 text-blue-600 bg-blue-50 hover:bg-blue-100 dark:text-blue-300 dark:border-blue-600 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 whitespace-nowrap" target="_blank" href="{{ route('admin.certificates.view', $certificate) }}">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11V3m0 8l3.5-3.5M12 11L8.5 7.5M6 16h12M6 20h12"/>
                            </svg>
                            View PDF
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center table-text-muted">No certificates generated yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3 flex justify-end">
            <button type="button" onclick="submitBulkCerts()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">Export Selected</button>
        </div>
        <script>
        function submitBulkCerts(){
            const ids = Array.from(document.querySelectorAll('.rowChkCert:checked')).map(el => el.value).join(',');
            document.getElementById('bulkCertIds').value = ids;
            document.getElementById('bulkExportCertificates').submit();
        }
        document.getElementById('selectAllCerts')?.addEventListener('change', e => {
            document.querySelectorAll('.rowChkCert').forEach(cb => cb.checked = e.target.checked);
        });
        </script>
    </div>

    <div class="mt-4">
        {{ $certificates->links() }}
    </div>
</div>
@endsection


