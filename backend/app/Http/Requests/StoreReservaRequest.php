<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreReservaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Debe estar autenticado (cliente o admin)
        return $this->user() !== null;
    }

    public function rules(): array
    {
        // Reserva con ≥ 3 días de antelación
        $minDate = now()->startOfDay()->addDays(3)->toDateString();

        return [
            'servicio_id'       => ['required', 'integer', 'exists:servicios,id'],
            // Ojo: no usamos unique(fecha) para no bloquear por canceladas (opción B)
            'fecha'             => ['required', 'date', "after_or_equal:$minDate"],
            'personas'          => ['required', 'integer', 'min:1'],
            'alergias'          => ['nullable', 'string', 'max:255'],
            'ubicacion'         => ['nullable', 'string', 'max:120'],
            'estado_reserva_id' => ['prohibited'], // el cliente no fija estado
        ];
    }

    public function attributes(): array
    {
        return [
            'servicio_id'       => 'servicio',
            'fecha'             => 'fecha',
            'personas'          => 'número de personas',
            'alergias'          => 'alergias',
            'ubicacion'         => 'ubicación',
            'estado_reserva_id' => 'estado de la reserva',
        ];
    }

    public function messages(): array
    {
        // Recalculo aquí para mostrar la fecha mínima en el mensaje
        $minDate = now()->startOfDay()->addDays(3)->toDateString();

        return [
            'servicio_id.required' => 'El :attribute es obligatorio.',
            'servicio_id.exists'   => 'El :attribute seleccionado no existe.',

            'fecha.required'       => 'La :attribute es obligatoria.',
            'fecha.date'           => 'La :attribute debe ser una fecha válida.',
            'fecha.after_or_equal' => "La :attribute debe ser igual o posterior a $minDate.",

            'personas.required'    => 'El :attribute es obligatorio.',
            'personas.integer'     => 'El :attribute debe ser un número entero.',
            'personas.min'         => 'El :attribute debe ser al menos :min.',

            'alergias.string'      => 'Las :attribute deben ser texto.',
            'alergias.max'         => 'Las :attribute no pueden superar :max caracteres.',

            'ubicacion.string'     => 'La :attribute debe ser texto.',
            'ubicacion.max'        => 'La :attribute no puede superar :max caracteres.',

            'estado_reserva_id.prohibited' => 'No puedes establecer el :attribute.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $fecha = $this->input('fecha');
            $servicioId = $this->input('servicio_id');
            if (!$fecha || !$servicioId) return;

            // 1) Servicio activo
            $servicioActivo = DB::table('servicios')
                ->where('id', $servicioId)
                ->where('activo', true)
                ->exists();

            if (! $servicioActivo) {
                $v->errors()->add('servicio_id', 'El servicio está inactivo o no existe.');
            }

            // 2) Día no bloqueado por admin (abierta = false)
            $cerrado = DB::table('disponibilidades')
                ->whereDate('fecha', $fecha)
                ->where('abierta', false)
                ->exists();

            if ($cerrado) {
                $v->errors()->add('fecha', 'El día seleccionado está bloqueado por el administrador.');
            }

            // 3) Sin reservas NO canceladas en esa fecha (opción B)
            $hayReservaActiva = DB::table('reservas as r')
                ->join('estado_reservas as e', 'e.id', '=', 'r.estado_reserva_id')
                ->whereDate('r.fecha', $fecha)
                ->where('e.codigo', '!=', 'cancelada')
                ->exists();

            if ($hayReservaActiva) {
                $v->errors()->add('fecha', 'Ya existe una reserva activa para esa fecha.');
            }
        });
    }
}
