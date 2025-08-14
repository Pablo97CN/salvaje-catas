<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servicio;

class ServicioAdminController extends Controller
{
    public function index()
    {
        return Servicio::orderByDesc('id')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => ['required','string','max:255'],
            'descripcion' => ['nullable','string'],
            'duracion_minutos' => ['sometimes','integer','min:1'],
            'precio'      => ['sometimes','numeric','min:0'],
            'activo'      => ['sometimes','boolean'],
            'image_path'  => ['sometimes','nullable','string'],
        ]);

        $srv = new Servicio($data);
        $srv->save();

        return response()->json($srv, 201);
    }

    public function show($id)
    {
        $srv = Servicio::find($id);
        if (! $srv) return response()->json(['message'=>'No encontrado'], 404);
        return $srv;
    }

    public function update(Request $request, $id)
    {
        $srv = Servicio::find($id);
        if (! $srv) return response()->json(['message'=>'No encontrado'], 404);

        $data = $request->validate([
            'nombre'      => ['sometimes','string','max:255'],
            'descripcion' => ['sometimes','nullable','string'],
            'duracion_minutos' => ['sometimes','integer','min:1'],
            'precio'      => ['sometimes','numeric','min:0'],
            'activo'      => ['sometimes','boolean'],
            'image_path'  => ['sometimes','nullable','string'],
        ]);

        $srv->fill($data)->save();
        return $srv->fresh();
    }

    public function destroy($id)
    {
        $srv = Servicio::find($id);
        if (! $srv) return response()->json(['message'=>'No encontrado'], 404);
        $srv->delete();
        return response()->noContent();
    }

    public function activar($id)
    {
        $srv = Servicio::find($id);
        if (! $srv) return response()->json(['message'=>'No encontrado'], 404);
        $srv->activo = true;
        $srv->save();
        return $srv->fresh();
    }

    public function desactivar($id)
    {
        $srv = Servicio::find($id);
        if (! $srv) return response()->json(['message'=>'No encontrado'], 404);
        $srv->activo = false;
        $srv->save();
        return $srv->fresh();
    }
}
