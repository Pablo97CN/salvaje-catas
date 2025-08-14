<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; 

// Auth stateful (grupo web) — cookies + CSRF (Sanctum)
use App\Http\Controllers\Api\Auth\SessionController;

// Público
use App\Http\Controllers\Api\Publico\ServicioPublicController;

// Cliente autenticado (perfil, reservas, disponibilidad)
use App\Http\Controllers\Api\Usuarios\PerfilController;
use App\Http\Controllers\Api\Reservas\ReservaController;
use App\Http\Controllers\Api\Disponibilidad\DisponibilidadController;

// Admin
use App\Http\Controllers\Api\Admin\UsuarioAdminController;
use App\Http\Controllers\Api\Admin\ReservaAdminController;
use App\Http\Controllers\Api\Admin\ServicioAdminController;
use App\Http\Controllers\Api\Admin\DisponibilidadAdminController;




//ping rápido (liveness) diagnóstico y monitorización.
Route::get('/health', function () {
    return response()->json([
        'ok' => true,
        'ts' => now()->toISOString(),
    ]);
});

Route::middleware('web')->group(function () {
    // Login/logout con sesión (Sanctum stateful)
    Route::post('/login',  [SessionController::class, 'login']); //autentica credenciales y crea la sesión. El servidor emite la cookie laravel_session
    Route::post('/logout', [SessionController::class, 'logout'])->middleware('auth');//invalida la sesión (servidor) y el cliente deja de estar autenticado
    Route::get('/me',      [SessionController::class, 'me'])->middleware('auth');//Rehidratar la sesión al cargar/recargar la SPA sin depender de localStorage
});



/*
|--------------------------------------------------------------------------
| Público (/api/public)
|--------------------------------------------------------------------------
| Mostrar servicios activos sin autenticación
*/
Route::prefix('public')->group(function () {
    Route::get('/servicios',        [ServicioPublicController::class, 'index']); // solo activos
    Route::get('/servicios/{id}',   [ServicioPublicController::class, 'show']);  // solo activos
});

/*
|--------------------------------------------------------------------------
| Cliente autenticado (/api)
|--------------------------------------------------------------------------
| Perfil propio, reservas propias y consulta de días disponibles
*/
Route::middleware('auth:sanctum')->group(function () {
    // Perfil
    Route::get('/usuarios/me',    [PerfilController::class, 'show']);
    Route::put('/usuarios/me',    [PerfilController::class, 'update']);
    Route::delete('/usuarios/me', [PerfilController::class, 'destroy']);

    // Mis reservas
    Route::get('/reservas',              [ReservaController::class, 'index']);  // solo mías
    Route::post('/reservas',             [ReservaController::class, 'store']);
    Route::get('/reservas/{id}',         [ReservaController::class, 'show']);   // owner
    
    Route::delete('/reservas/{id}',      [ReservaController::class, 'destroy']); // owner (cancelar->estado 'cancelada')

    // Días disponibles
    Route::get('/disponibilidad',        [DisponibilidadController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| Admin (/api/admin)
|--------------------------------------------------------------------------
| Requiere login + rol admin. El middleware 'role:admin' 
*/
Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Usuarios (consulta)
    Route::get('/usuarios',          [UsuarioAdminController::class, 'index']);
    Route::get('/usuarios/{id}',     [UsuarioAdminController::class, 'show']);

    // Reservas (todas)
    Route::get('/reservas',          [ReservaAdminController::class, 'index']);
    Route::get('/reservas/{id}',     [ReservaAdminController::class, 'show']);
    Route::put('/reservas/{id}',     [ReservaAdminController::class, 'update']);
    Route::delete('/reservas/{id}',  [ReservaAdminController::class, 'destroy']); // cancelar/forzar estado

    // Disponibilidades (bloqueos de día)
    Route::get('/disponibilidades',          [DisponibilidadAdminController::class, 'index']);
    Route::post('/disponibilidades',         [DisponibilidadAdminController::class, 'store']);   // bloquear día
    Route::delete('/disponibilidades/{id}',  [DisponibilidadAdminController::class, 'destroy']); // desbloquear

    // Servicios (gestión completa)
    Route::get('/servicios',                 [ServicioAdminController::class, 'index']); // todos (activos/inactivos)
    Route::post('/servicios',                [ServicioAdminController::class, 'store']);
    Route::get('/servicios/{id}',            [ServicioAdminController::class, 'show']);
    Route::put('/servicios/{id}',            [ServicioAdminController::class, 'update']);
    Route::delete('/servicios/{id}',         [ServicioAdminController::class, 'destroy']);
    Route::patch('/servicios/{id}/activar',   [ServicioAdminController::class, 'activar']);
    Route::patch('/servicios/{id}/desactivar',[ServicioAdminController::class, 'desactivar']);
});