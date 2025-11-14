<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedor::orderBy('nombre')->get();
        return view('proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'rut' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:255'
        ]);

        Proveedor::create($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:150',
            'rut' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:150',
            'direccion' => 'nullable|string|max:255'
        ]);

        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Proveedor::findOrFail($id)->delete();
        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado correctamente');
    }
}
