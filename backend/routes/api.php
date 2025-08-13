<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SessionController;

Route::get('/health', function () {
    return response()->json([
        'ok' => true,
        'ts' => now()->toISOString(),
    ]);
});

// Login/logout con sesión (Sanctum stateful)
Route::post('/login',  [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth');
