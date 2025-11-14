<?php

namespace App\Http\Controllers;

use App\Models\material;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function __construct()
    {
        // 🔒 Requiere usuario autenticado
        $this->middleware('auth');

        // 🔹 Solo el VENDEDOR (rol = 2) puede acceder
        if (Auth::check() && Auth::user()->rol != 2) {
            abort(403, 'Acceso denegado');
        }
    }
    public function index()
    {
        $materiales = Material::all();
        return view('app.material.index', compact('materiales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('app.material.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required|min:10|string',
            'espesor' => 'required',
            'valor_m2' => 'required'
        ]);

        $material = Material::create([
            'nombre' => $request->nombre,
            'espesor' => $request->espesor,
            'valor_m2' => $request->valor_m2,
            'descripcion' => $request->descripcion
        ]);

        return redirect()->route('materiales.index')->with('success', 'Material registrado');
    }

    /**
     * Display the specified resource.
     */
    public function show(material $material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $material = Material::where('id', $id)->get()->shift();
        return view('app.material.edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $material = Material::where('id', $id)->get()->shift();

        $datos = [
            'nombre' => $request->nombre,
            'espesor' => $request->espesor,
            'valor_m2' => $request->valor_m2,
            'descripcion' => $request->descripcion
        ];

        $material->update($datos);

        return redirect()->route('materiales.index')->with('success', 'Material modificado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(material $material)
    {
        //
    }
}
