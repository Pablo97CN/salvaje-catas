<?php

namespace App\Http\Controllers\Api\Publico;

use App\Http\Controllers\Controller;
use App\Models\Servicio;

class ServicioPublicController extends Controller
{
    // GET /api/public/servicios (solo activos)
    public function index()
    {
        return Servicio::where('activo', true)
            ->orderByDesc('id')
            ->get();
    }

    // GET /api/public/servicios/{id} (solo si activo)
    public function show($id)
    {
        $srv = Servicio::where('id', $id)
            ->where('activo', true)
            ->first();

        if (! $srv) return response()->json(['message' => 'No encontrado o inactivo'], 404);
        return $srv;
    }
}
