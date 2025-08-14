<?php

namespace App\Http\Controllers\Api\Reservas;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReservaRequest; // tu Request existente
use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\EstadoReserva;
use App\Models\Servicio;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservaController extends Controller
{
    // GET /api/reservas  (solo mías)
    public function index(Request $request)
    {
        return Reserva::where('usuario_id', $request->user()->id)
            ->with(['servicio','estado'])
            ->orderByDesc('id')
            ->get();
    }

    // POST /api/reservas  (crea en estado 'pendiente')
    public function store(StoreReservaRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        $estadoPendienteId = EstadoReserva::where('codigo', 'pendiente')->value('id');
        if (! $estadoPendienteId) {
            return response()->json(['message' => 'Falta estado "pendiente" en estado_reservas'], 500);
        }

        return DB::transaction(function () use ($user, $data, $estadoPendienteId) {
            $r = new Reserva();
            $r->usuario_id = $user->id;
            $r->servicio_id = $data['servicio_id'];
            $r->fecha       = $data['fecha'];
            $r->personas    = $data['personas'] ?? 1;
            $r->alergias    = $data['alergias'] ?? null;
            $r->ubicacion   = $data['ubicacion'] ?? null;
            $r->estado_reserva_id = $estadoPendienteId;
            $r->save();

            return response()->json($r->load(['servicio','estado']), 201);
        });
    }

    // GET /api/reservas/{id} (owner)
    public function show(Request $request, $id)
    {
        $reserva = Reserva::with(['servicio','estado'])->find($id);
        if (! $reserva) return response()->json(['message'=>'No encontrada'], 404);
        if ($reserva->usuario_id !== $request->user()->id) return response()->json(['message'=>'Prohibido'], 403);
        return $reserva;
    }

    // DELETE /api/reservas/{id} (owner) → cancelar si faltan ≥ 3 días
    public function destroy(Request $request, $id)
    {
        $reserva = Reserva::with('estado')->find($id);
        if (! $reserva) return response()->json(['message'=>'No encontrada'], 404);
        if ($reserva->usuario_id !== $request->user()->id) return response()->json(['message'=>'Prohibido'], 403);

        $limite = now()->startOfDay()->addDays(3);
        $fecha  = Carbon::parse($reserva->fecha)->startOfDay();
        if ($fecha->lt($limite)) {
            return response()->json(['message'=>'No puedes cancelar con menos de 3 días de antelación.'], 403);
        }

        $estadoCanceladaId = EstadoReserva::where('codigo','cancelada')->value('id');
        if (! $estadoCanceladaId) return response()->json(['message'=>'Falta estado "cancelada"'], 500);

        $reserva->estado_reserva_id = $estadoCanceladaId;
        $reserva->save();

        return response()->noContent();
    }
}
