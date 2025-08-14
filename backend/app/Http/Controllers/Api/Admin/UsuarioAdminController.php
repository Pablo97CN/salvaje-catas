<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;

class UsuarioAdminController extends Controller
{
    public function index()
    {
        return Usuario::orderByDesc('id')->paginate(20);
    }

    public function show($id)
    {
        $u = Usuario::find($id);
        if (! $u) return response()->json(['message'=>'No encontrado'], 404);
        return $u;
    }
}
