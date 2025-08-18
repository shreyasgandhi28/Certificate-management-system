<div x-data="{ show: false, message: '' }"
     x-show="show"
     x-on:open-whatsapp.window="show = true; message = $event.detail.message || ''"
     x-on:close.window.esc="show = false"
     x-on:keydown.escape.prevent.window="show = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="show" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             x-on:click="show = false"
             aria-hidden="true"></div>

        <!-- Modal panel -->
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle bg-white dark:bg-gray-800 rounded-xl shadow-xl transform transition-all">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                    Send WhatsApp Message
                </h3>
                <button type="button" 
                        @click="show = false"
                        class="text-gray-400 hover:text-gray-500 focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form x-bind:action="'{{ route('admin.applicants.send-whatsapp', $applicant) }}'" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Message
                        </label>
                        <textarea x-model="message"
                                id="message"
                                name="message"
                                rows="6"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm"
                                placeholder="Type your message here..."></textarea>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            The message will be sent to {{ $applicant->country_code ?? '+91' }}{{ $applicant->phone }}
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button"
                            @click="show = false"
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit"
                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.498 14.382v-.001c-.301-.15-1.767-.867-2.143-.967-.273-.073-.473-.107-.673.107-.2.214-.77.834-.95.992-.172.15-.344.167-.624.05-.3-.1-1.263-.465-2.406-1.485-.888-.795-1.484-1.77-1.66-2.07-.174-.3-.019-.463.13-.612.136-.136.3-.357.456-.582.147-.21.194-.362.29-.6.1-.24.05-.45-.05-.63-.1-.18-.673-1.62-.922-2.22-.24-.58-.487-.51-.672-.52-.172-.01-.37-.01-.57-.01-.2 0-.52.08-.8.37-.3.31-1.15 1.12-1.15 2.73s1.17 3.17 1.33 3.39c.17.23 2.29 3.5 5.56 4.87.79.33 1.41.53 1.89.68.79.24 1.5.21 2.07.13.64-.1 1.97-.8 2.25-1.57.26-.7.26-1.3.18-1.43-.07-.13-.27-.2-.57-.32"/>
                        </svg>
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
