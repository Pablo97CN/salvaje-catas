<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Login público
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'email'    => 'email',
            'password' => 'contraseña',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'El :attribute es obligatorio.',
            'email.email'       => 'El :attribute debe tener un formato válido.',
            'password.required' => 'La :attribute es obligatoria.',
        ];
    }
}
