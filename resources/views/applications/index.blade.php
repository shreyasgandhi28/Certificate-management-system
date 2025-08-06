<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                {{ __('Applications') }}
            </h2>
        </div>
    </x-slot>

    {{-- content --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-4 lg:px-8">

            {{-- Filters card --}}
            <form method="GET"
                  class="mb-6 bg-white dark:bg-gray-800 shadow rounded-lg px-6 py-4 grid gap-4
                         md:grid-cols-5">

                <input name="q"
                       value="{{ request('q') }}"
                       placeholder="Search name, email, phone…"
                       class="md:col-span-2 border-gray-300 dark:border-gray-700 dark:bg-gray-900
                              rounded-md focus:ring-indigo-500 focus:border-indigo-500 w-full"/>

                <select name="status"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md w-full">
                    <option value="">{{ __('All status') }}</option>
                    <option value="pending"    @selected(request('status')==='pending')>Pending</option>
                    <option value="verified"   @selected(request('status')==='verified')>Verified</option>
                    <option value="rejected"   @selected(request('status')==='rejected')>Rejected</option>
                </select>

                <input type="date" name="from"
                       value="{{ request('from') }}"
                       class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md w-full"/>

                <input type="date" name="to"
                       value="{{ request('to') }}"
                       class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md w-full"/>

                <div class="flex gap-2 md:col-span-5 justify-end">
                    <x-primary-button>{{ __('Filter') }}</x-primary-button>
                    <a href="{{ route('admin.applications.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-500 dark:bg-gray-600
                              border border-transparent rounded-md font-semibold text-xs text-white
                              hover:bg-gray-600 dark:hover:bg-gray-500 focus:outline-none
                              transition ease-in-out duration-150">
                        {{ __('Clear') }}
                    </a>
                </div>
            </form>

            {{-- Table --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                {{ __('Applicant') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                {{ __('Contact') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                {{ __('Status') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                {{ __('Documents') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                {{ __('Date') }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                {{ __('Action') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($applicants as $applicant)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $applicant->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        #{{ str_pad($applicant->id, 4, '0', STR_PAD_LEFT) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $applicant->email }}<br>{{ $applicant->phone }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php($color = [
                                        'pending'  => 'yellow',
                                        'verified' => 'green',
                                        'rejected' => 'red',
                                    ][$applicant->status] ?? 'gray')
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold
                                                 bg-{{ $color }}-100 text-{{ $color }}-800">
                                        {{ ucfirst($applicant->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $applicant->uploads()->count() }} {{ __('file(s)') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $applicant->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.applicants.show', $applicant) }}"
                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                                        {{ __('View') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    {{ __('No applications found.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $applicants->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
