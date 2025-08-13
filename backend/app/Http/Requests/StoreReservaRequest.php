<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
        return [
            'servicio_id'        => ['required', 'integer', 'exists:servicios,id'],
            'fecha'              => ['required', 'date', 'after_or_equal:today', 'unique:reservas,fecha'],
            'personas'           => ['required', 'integer', 'min:1'],
            'alergias'           => ['nullable', 'string', 'max:255'],
            'ubicacion'          => ['nullable', 'string', 'max:120'],
            // Solo el admin podrá tocar estado en el controller; aquí lo permitimos opcionalmente
            'estado_reserva_id'  => ['sometimes', 'nullable', 'integer', 'exists:estado_reservas,id'],
            // NOTA: usuario_id no se acepta desde cliente; lo setea el controller
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
        return [
            'servicio_id.required' => 'El :attribute es obligatorio.',
            'servicio_id.exists'   => 'El :attribute seleccionado no existe.',

            'fecha.required'       => 'La :attribute es obligatoria.',
            'fecha.date'           => 'La :attribute debe ser una fecha válida.',
            'fecha.after_or_equal' => 'La :attribute no puede ser pasada.',
            'fecha.unique'         => 'Ya existe una reserva para esa :attribute.',

            'personas.required'    => 'El :attribute es obligatorio.',
            'personas.integer'     => 'El :attribute debe ser un número entero.',
            'personas.min'         => 'El :attribute debe ser al menos :min.',

            'alergias.string'      => 'Las :attribute deben ser texto.',
            'alergias.max'         => 'Las :attribute no pueden superar :max caracteres.',

            'ubicacion.string'     => 'La :attribute debe ser texto.',
            'ubicacion.max'        => 'La :attribute no puede superar :max caracteres.',

            'estado_reserva_id.integer' => 'El :attribute debe ser un número.',
            'estado_reserva_id.exists'  => 'El :attribute seleccionado no existe.',
        ];
    }

    public function withValidator($validator): void
    {
        // Regla de negocio: no permitir reservar en días cerrados (disponibilidades.abierta = false)
        $validator->after(function ($v) {
            $fecha = $this->input('fecha');
            if (!$fecha) return;

            $cerrado = DB::table('disponibilidades')
                ->where('fecha', $fecha)
                ->where('abierta', false)
                ->exists();

            if ($cerrado) {
                $v->errors()->add('fecha', 'El día seleccionado no está disponible.');
            }
        });
    }
}

