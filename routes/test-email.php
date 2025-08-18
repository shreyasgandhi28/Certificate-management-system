<?php

use App\Models\Certificate;
use App\Mail\CertificateEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/test-email', function () {
    return view('test-email');
});

Route::post('/test-email', function (Request $request) {
    $request->validate([
        'email' => 'required|email'
    ]);

    try {
        // Test sending a simple email first
        Mail::raw('This is a test email from the Certificate Management System', function($message) use ($request) {
            $message->to($request->email)
                   ->subject('Test Email from Certificate System');
        });
        
        return back()->with('success', 'Test email sent to ' . $request->email . '. Check your Mailtrap inbox.');
        
    } catch (\Exception $e) {
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
})->name('test-email.send');
