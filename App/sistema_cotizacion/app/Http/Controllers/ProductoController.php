<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
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
        $productos = Producto::all();
        return view('app.producto.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('app.producto.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los datos del formulario
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required|min:10|string',
            'precio' => 'required',
            'unidad' => 'required',
            'stock' => 'required'
        ]);

        // Crear el nuevo cliente y guardar en $cliente
        $producto = \App\Models\Producto::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'unidad' => $request->unidad,
            'stock' => $request->stock, // Activo por defecto
            'created_at' => now(),
            'activo' => 1
        ]);

        // Redireccionar con mensaje de éxito incluyendo el ID
        return redirect()->route('productos.index')->with('success', 'Producto registrado exitosamente con el ID: ' . $producto->id_producto);
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $producto = Producto::where('id_producto', $id)->get()->shift();
        return view('app.producto.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validación de los datos del formulario
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required|min:10|string',
            'precio' => 'required',
            'unidad' => 'required',
            'stock' => 'required'
        ]);

        $producto = Producto::where('id_producto', $id)->get()->shift();

        // Crear el nuevo cliente y guardar en $cliente
        $datos = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'unidad' => $request->unidad,
            'stock' => $request->stock, // Activo por defecto
            'updated_at' => now(),
            'activo' => $request->activo
        ];

        $producto->update($datos);


        // Redireccionar con mensaje de éxito incluyendo el ID
        return redirect()->route('producto.index')->with('success', 'Producto modificado exitosamente con el ID: ' . $producto->id_producto);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
