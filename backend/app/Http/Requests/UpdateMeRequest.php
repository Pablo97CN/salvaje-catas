<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Debe estar autenticado
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'nombre'   => 'sometimes|required|string|max:120',
            'telefono' => [
                'sometimes', 'required', 'string', 'max:20',
                Rule::unique('usuarios', 'telefono')->ignore($this->user()->id),
            ],
            // Añadiremos email/password si decides habilitar su edición por el usuario
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'   => 'nombre',
            'telefono' => 'teléfono',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'    => 'El :attribute es obligatorio.',
            'nombre.string'      => 'El :attribute debe ser texto.',
            'nombre.max'         => 'El :attribute no puede superar :max caracteres.',

            'telefono.required'  => 'El :attribute es obligatorio.',
            'telefono.string'    => 'El :attribute debe ser texto.',
            'telefono.max'       => 'El :attribute no puede superar :max caracteres.',
            'telefono.unique'    => 'Este :attribute ya está registrado.',
        ];
    }
}

