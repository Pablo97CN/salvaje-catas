<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\EstadoReserva;

class ReservaAdminController extends Controller
{
    public function index()
    {
        return Reserva::with(['usuario','servicio','estado'])
            ->orderByDesc('id')
            ->paginate(20);
    }

    public function show($id)
    {
        $r = Reserva::with(['usuario','servicio','estado'])->find($id);
        if (! $r) return response()->json(['message'=>'No encontrada'], 404);
        return $r;
    }

    // (Dejamos simple; más adelante añadimos validación de fecha y disponibilidad)
    public function update(Request $request, $id)
    {
        $r = Reserva::find($id);
        if (! $r) return response()->json(['message'=>'No encontrada'], 404);

        $data = $request->validate([
            'estado' => ['sometimes','string','in:pendiente,confirmada,cancelada'],
        ]);

        if (isset($data['estado'])) {
            $estadoId = EstadoReserva::where('codigo', $data['estado'])->value('id');
            if (! $estadoId) return response()->json(['message'=>'Estado no configurado'], 422);
            $r->estado_reserva_id = $estadoId;
        }

        $r->save();
        return $r->fresh()->load(['usuario','servicio','estado']);
    }

    public function destroy($id)
    {
        $r = Reserva::find($id);
        if (! $r) return response()->json(['message'=>'No encontrada'], 404);

        $estadoCanceladaId = EstadoReserva::where('codigo','cancelada')->value('id');
        if (! $estadoCanceladaId) return response()->json(['message'=>'Falta estado "cancelada"'], 500);

        $r->estado_reserva_id = $estadoCanceladaId;
        $r->save();
        return response()->noContent();
    }
}
