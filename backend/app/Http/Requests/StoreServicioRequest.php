<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServicioRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Solo admin puede crear servicios
        return $this->user()?->rol === 'admin';
    }

    public function rules(): array
    {
        return [
            'nombre'           => 'required|string|max:150',
            'descripcion'      => 'nullable|string',
            'duracion_minutos' => 'nullable|integer|min:1',
            'precio'           => 'nullable|numeric|min:0|max:99999999.99',
            'activo'           => 'sometimes|boolean',
            'image_path'       => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'           => 'nombre del servicio',
            'descripcion'      => 'descripción',
            'duracion_minutos' => 'duración (minutos)',
            'precio'           => 'precio',
            'activo'           => 'estado activo',
            'image_path'       => 'ruta de imagen',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'           => 'El :attribute es obligatorio.',
            'nombre.string'             => 'El :attribute debe ser texto.',
            'nombre.max'                => 'El :attribute no puede superar :max caracteres.',

            'descripcion.string'        => 'La :attribute debe ser texto.',

            'duracion_minutos.integer'  => 'La :attribute debe ser un número entero.',
            'duracion_minutos.min'      => 'La :attribute debe ser al menos :min.',

            'precio.numeric'            => 'El :attribute debe ser un número.',
            'precio.min'                => 'El :attribute no puede ser negativo.',
            'precio.max'                => 'El :attribute es demasiado alto.',

            'activo.boolean'            => 'El :attribute debe ser verdadero o falso.',

            'image_path.string'         => 'La :attribute debe ser texto.',
        ];
    }
}
