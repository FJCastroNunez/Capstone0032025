<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
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
        $clientes = Cliente::where('id_cliente', '!=', 23)->get();
        return view('app.cliente.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('app.cliente.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $request->validate([
            'nombre' => 'required|string|min:5',
            'empresa' => 'required|string',
            'correo' => 'nullable|email',
            'telefono' => 'required',
            'direccion' => 'nullable|string|max:255',
        ]);

        // Crear el nuevo cliente y guardar en $cliente
        $cliente = \App\Models\Cliente::create([
            'nombre' => $request->nombre,
            'empresa' => $request->empresa,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion, // Activo por defecto
            'created_at' => now()
        ]);

        // Redireccionar con mensaje de éxito incluyendo el ID
        return redirect()->route('clientes.index')->with('success', 'Cliente registrado exitosamente con el ID: ' . $cliente->id_cliente);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cliente = Cliente::where('id_cliente', $id)->get()->shift();
        return view('app.cliente.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validación de los datos del formulario
        $request->validate([
            'nombre' => 'required|string|min:5',
            'empresa' => 'required|string',
            'correo' => 'nullable|email',
            'telefono' => 'required',
            'direccion' => 'nullable|string|max:255',
        ]);

        $cliente = Cliente::where('id', $id)->get()->shift();

        // Crear el nuevo cliente y guardar en $cliente
        $datos = [
            'nombre' => $request->nombre,
            'empresa' => $request->empresa,
            'correo' => $request->correo,
            'telefono' => $request->telefono, // Activo por defecto
            'direccion' => $request->direccion,
            'updated_at' => now()
        ];

        $cliente->update($datos);


        // Redireccionar con mensaje de éxito incluyendo el ID
        return redirect()->route('clientes.index')->with('success', 'Cliente modificado exitosamente con el ID: ' . $cliente->id_cliente);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        //
    }
}
