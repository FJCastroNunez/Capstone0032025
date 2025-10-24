<?php

namespace App\Http\Controllers;

use App\Models\DetalleCotizacion;
use Illuminate\Http\Request;

class DetalleCotizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detalles = DetalleCotizacion::all();
        return view('detalle.index', $detalles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DetalleCotizacion $detalleCotizacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DetalleCotizacion $detalleCotizacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DetalleCotizacion $detalleCotizacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetalleCotizacion $detalleCotizacion)
    {
        //
    }
}
