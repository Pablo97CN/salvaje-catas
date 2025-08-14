<?php

namespace App\Http\Controllers\Api\Usuarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateMeRequest; // << importa tu FormRequest

class PerfilController extends Controller
{
    // GET /api/usuarios/me
    public function show(Request $request)
    {
        return $request->user();
    }

    // PUT /api/usuarios/me  (campos: nombre, email, telefono, password)
    public function update(UpdateMeRequest $request) // << usa el FormRequest
    {
        $user = $request->user();
        $data = $request->validated(); // << ya viene validado por UpdateMeRequest

        // Si viene password y no es cadena vacía, la hasheamos. Si no, no se toca.
        if (array_key_exists('password', $data) && $data['password'] !== '') {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->fill($data)->save();

        return $user->fresh();
    }

    // DELETE /api/usuarios/me
    public function destroy(Request $request)
    {
        $request->user()->delete();
        return response()->noContent();
    }
}
