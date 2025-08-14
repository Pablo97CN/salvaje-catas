<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    // POST /api/login
    public function login(Request $request)
    {
        // Valida credenciales básicas
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
        ]);

        // Intenta autenticar (guard web, sesión)
        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales inválidas.'],
            ])->status(422);
        }

        // Regenera la sesión para prevenir fixation
        $request->session()->regenerate();

        return response()->json(['message' => 'ok'], 200);
    }

    // GET /api/me  (web + auth)
    public function me(Request $request)
        {
            return $request->user();
        }

    // POST /api/logout
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'bye'], 200);
    }
}

