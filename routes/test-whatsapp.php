<?php

use App\Models\Applicant;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/test-whatsapp', function () {
    return view('test-whatsapp');
});

Route::post('/test-whatsapp', function (Request $request) {
    $request->validate([
        'phone' => 'required|string',
        'message' => 'required|string',
    ]);

    try {
        $whatsappService = app(WhatsAppService::class);
        $whatsappService->sendMessage($request->phone, $request->message);
        
        return back()->with('success', 'WhatsApp message sent successfully!');
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to send WhatsApp: ' . $e->getMessage());
    }
})->name('test-whatsapp.send');
