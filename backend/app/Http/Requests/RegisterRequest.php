<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Registro público
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'                 => 'required|string|max:120',
            'email'                  => 'required|email|max:255|unique:usuarios,email',
            'telefono'               => 'required|string|max:20|unique:usuarios,telefono',
            'password'               => 'required|string|min:8|confirmed',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'                 => 'nombre',
            'email'                  => 'email',
            'telefono'               => 'teléfono',
            'password'               => 'contraseña',
            'password_confirmation'  => 'confirmación de contraseña',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'      => 'El :attribute es obligatorio.',
            'nombre.string'        => 'El :attribute debe ser texto.',
            'nombre.max'           => 'El :attribute no puede superar :max caracteres.',

            'email.required'       => 'El :attribute es obligatorio.',
            'email.email'          => 'El :attribute debe tener un formato válido.',
            'email.max'            => 'El :attribute no puede superar :max caracteres.',
            'email.unique'         => 'Este :attribute ya está registrado.',

            'telefono.required'    => 'El :attribute es obligatorio.',
            'telefono.string'      => 'El :attribute debe ser texto.',
            'telefono.max'         => 'El :attribute no puede superar :max caracteres.',
            'telefono.unique'      => 'Este :attribute ya está registrado.',

            'password.required'    => 'La :attribute es obligatoria.',
            'password.string'      => 'La :attribute debe ser texto.',
            'password.min'         => 'La :attribute debe tener al menos :min caracteres.',
            'password.confirmed'   => 'La :attribute no coincide con la confirmación.',
        ];
    }
}

