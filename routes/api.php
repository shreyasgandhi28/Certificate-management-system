<?php

use Illuminate\Support\Facades\Route;

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/certificate/verify/{serial}', function (string $serial) {
        $certificate = \App\Models\Certificate::where('serial_number', $serial)->first();
        if (!$certificate) {
            return response()->json(['valid' => false], 404);
        }
        return response()->json([
            'valid' => true,
            'serial_number' => $certificate->serial_number,
            'applicant_name' => $certificate->applicant->name,
            'generated_at' => optional($certificate->generated_at)?->toDateTimeString(),
            'status' => $certificate->status,
        ]);
    });
});


