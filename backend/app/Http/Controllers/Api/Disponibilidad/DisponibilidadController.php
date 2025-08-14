<?php

namespace App\Http\Controllers\Api\Disponibilidad;

use App\Http\Controllers\Controller;
use App\Models\Disponibilidad;

class DisponibilidadController extends Controller
{
    // GET /api/disponibilidad
    public function index()
    {
        // abierta = true y sin reservas NO canceladas (opción B)
        return Disponibilidad::query()
            ->where('abierta', true)
            ->whereDoesntHave('reserva', function ($r) {
                $r->whereHas('estado', fn($e) => $e->where('codigo','!=','cancelada'));
            })
            ->orderBy('fecha')
            ->get();
    }
}
