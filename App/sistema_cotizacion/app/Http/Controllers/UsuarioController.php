<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UsuarioController extends Controller
{
    public function __construct()
    {
        // 🔒 Requiere usuario autenticado
        $this->middleware('auth');

        // 🔹 Solo el VENDEDOR (rol = 2) puede acceder
        if (Auth::check() && Auth::user()->rol != 1) {
            abort(403, 'Acceso denegado');
        }
    }

    public function index()
    {
        $usuarios = Usuario::where('id_usuario', '!=', 8)->get();
        return view('app.usuario.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('app.usuario.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $request->validate([
            'nombre' => 'required|string',
            'correo' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required'
        ]);

        // Crear el nuevo cliente y guardar en $cliente
        $usuario = \App\Models\Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->correo,
            'password' => Hash::make($request->password),
            'clave' => $request->password,
            'rol' => $request->rol,
            'activo' => 1, // Activo por defecto
            'created_at' => now()
        ]);

        // Redireccionar con mensaje de éxito incluyendo el ID
        return redirect()->route('usuarios.index')->with('success', 'Usuario registrado exitosamente con el ID: ' . $usuario->id_usuario);
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $usuario = Usuario::where('id_usuario', $id)->get()->shift();
        return view('app.usuario.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validación de los datos del formulario
        $request->validate([
            'nombre' => 'required|string',
            'correo' => 'required|string',
            'password' => 'required|string|max:100',
            'rol' => 'required'
        ]);

        $usuario = Usuario::where('id', $id)->get()->shift();

        // Crear el nuevo cliente y guardar en $cliente
        $datos = [
            'nombre' => $request->nombre,
            'email' => $request->correo,
            'password' => bcrypt($request->contraseña),
            'rol' => $request->rol,
            'activo' => 1, // Activo por defecto
            'updated_at' => now()
        ];

        $usuario->update($datos);


        // Redireccionar con mensaje de éxito incluyendo el ID
        return redirect()->route('usuario.index')->with('success', 'Usuario modificado exitosamente con el ID: ' . $usuario->id_usuario);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        //
    }
}
