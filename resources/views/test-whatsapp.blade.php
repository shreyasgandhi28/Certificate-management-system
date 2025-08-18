<!DOCTYPE html>
<html>
<head>
    <title>Test WhatsApp Sending</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Test WhatsApp Sending</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ route('test-whatsapp.send') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number (with country code, e.g., +1234567890)</label>
                <input type="text" name="phone" id="phone" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                       placeholder="e.g., +1234567890"
                       value="{{ old('phone') }}">
            </div>
            
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                <textarea name="message" id="message" rows="4" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                          placeholder="Type your message here...">{{ old('message') ?? 'Hello! This is a test message from the Certificate Management System.' }}</textarea>
            </div>
            
            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.5 2.5L23 12l-5.5 9.5h-11L1 12l5.5-9.5h11zm-1.153 2H7.653L3.311 12l4.342 7.5h8.694l4.342-7.5-4.342-7.5zM11 15.5V18h2v-2.5h-2zm0-9V13h2V6.5h-2z"/>
                    </svg>
                    Send WhatsApp Message
                </button>
            </div>
        </form>
        
        <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400">
            <p class="text-sm text-yellow-700">
                <strong>Note:</strong> To test WhatsApp messages, you need to:
                <ol class="list-decimal pl-5 mt-2 space-y-1">
                    <li>Sign up for a <a href="https://www.twilio.com" target="_blank" class="text-blue-600 hover:underline">Twilio account</a></li>
                    <li>Set up WhatsApp Sandbox in Twilio Console</li>
                    <li>Add your phone number to the Sandbox recipients</li>
                    <li>Update your <code>.env</code> with Twilio credentials</li>
                </ol>
            </p>
        </div>
    </div>
</body>
</html>
