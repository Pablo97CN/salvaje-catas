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
            // el usuario puede actualizar su email
            'email' => [
                'sometimes', 'required', 'email', 'max:255',
                Rule::unique('usuarios', 'email')->ignore($id),
            ],

            // Si llega password, debe ser válida (no vacía, min 8)
            // Si no llega, NO se toca la contraseña actual (no se pone a null).
            'password' => ['sometimes','required','string','min:8'],
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

            'email.required'     => 'El :attribute es obligatorio.',
            'email.email'        => 'El :attribute debe tener un formato válido.',
            'email.max'          => 'El :attribute no puede superar :max caracteres.',
            'email.unique'       => 'Este :attribute ya está registrado.',

            'password.required'  => 'La :attribute es obligatoria cuando se envía.',
            'password.string'    => 'La :attribute debe ser texto.',
            'password.min'       => 'La :attribute debe tener al menos :min caracteres.',
        ];
    }
}

