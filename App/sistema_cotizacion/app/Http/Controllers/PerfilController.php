<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    // Mostrar el formulario de edición del perfil
    public function edit()
    {
        $usuario = Auth::user();
        return view('app.usuario.profile', compact('usuario'));
    }

    // Actualizar la información del usuario autenticado
    public function update(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'password' => 'nullable|min:6',
        ]);

        $usuario->nombre = $request->nombre;

        if ($request->filled('password')) {
            $usuario->contrasena = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('perfil.edit')->with('success', 'Perfil actualizado correctamente.');
    }
}
