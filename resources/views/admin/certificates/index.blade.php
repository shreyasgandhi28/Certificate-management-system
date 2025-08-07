@extends('layouts.admin')
@section('title', 'Certificates')
@section('page-title', 'Certificates')
@section('page-description', 'Generated certificates and delivery status')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-semibold card-text-primary">Certificates</h1>
        <form method="GET" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by applicant/email/serial" class="px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
            <button class="px-3 py-2 text-sm rounded-lg btn-primary">Search</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
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
                    <td class="py-3 px-4 table-text">
                        <div class="font-medium">{{ $certificate->serial_number }}</div>
                        <div class="text-xs table-text-muted">ID: {{ $certificate->id }}</div>
                    </td>
                    <td class="py-3 px-4 table-text">
                        <div class="font-medium">{{ $certificate->applicant->name }}</div>
                        <div class="text-xs table-text-muted">{{ $certificate->applicant->email }}</div>
                        <div class="text-xs table-text-muted">Phone: {{ $certificate->applicant->phone }}</div>
                        <div class="text-xs table-text-muted">Submitted: {{ $certificate->applicant->submitted_at?->format('M d, Y') }}</div>
                    </td>
                    <td class="py-3 px-4 table-text">{{ optional($certificate->template)->name ?? '—' }}</td>
                    <td class="py-3 px-4 table-text">{{ $certificate->generated_at?->format('M d, Y g:i A') }}</td>
                    <td class="py-3 px-4">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">{{ $certificate->status }}</span>
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2">
                            <form action="{{ route('admin.certificates.send-email', $certificate) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs rounded btn-success">Send Email</button>
                            </form>
                            <form action="{{ route('admin.certificates.send-whatsapp', $certificate) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs rounded btn-primary">Send WhatsApp</button>
                            </form>
                            <form action="{{ route('admin.certificates.reset-status', $certificate) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs rounded btn-warning">Reset</button>
                            </form>
                        </div>
                    </td>
                    <td class="py-3 px-4 table-text">
                        <div class="text-xs mb-2">Email: {{ $certificate->email_sent_at ? $certificate->email_sent_at->diffForHumans() : '-' }}</div>
                        <div class="text-xs mb-2">WhatsApp: {{ $certificate->whatsapp_sent_at ? $certificate->whatsapp_sent_at->diffForHumans() : '-' }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">Attempts: {{ $certificate->send_attempts }}</div>
                        <a class="px-2 py-1 text-xs rounded btn-primary" target="_blank" href="{{ Storage::disk('public')->url($certificate->pdf_path) }}">View PDF</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center table-text-muted">No certificates generated yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $certificates->links() }}
    </div>
</div>
@endsection


