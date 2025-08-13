<?php
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SessionController;

Route::get('/health', function () {
    return response()->json([
        'ok' => true,
        'ts' => now()->toISOString(),
    ]);
});

Route::middleware(['web','auth'])->get('/me', function (Request $request) {
    return $request->user();
});

// Login/logout con sesión (Sanctum stateful)
Route::post('/login',  [SessionController::class, 'store'])->middleware('web');
Route::post('/logout', [SessionController::class, 'destroy'])->middleware(['web','auth']);

