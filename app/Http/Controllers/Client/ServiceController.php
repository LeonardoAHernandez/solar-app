<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Muestra la lista de todos los servicios disponibles.
     */
    public function index(Accommodation $accommodation)
    {
        // 1. Obtener todos los servicios de la base de datos
        $services = Service::all();

        // 2. Obtener los IDs de los servicios que este alojamiento ya tiene asociados
        $selectedServiceIds = $accommodation->services()->pluck('services.id')->toArray();

        return view('client.accommodations.services', compact('accommodation', 'services', 'selectedServiceIds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Guarda o actualiza los servicios seleccionados (Selección Múltiple).
     */
    public function store(Request $request, Accommodation $accommodation)
    {
        $request->validate([
            'services'   => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        try {
            DB::beginTransaction();

            // Sincroniza los servicios: añade los nuevos y quita los desmarcados de golpe
            $accommodation->services()->sync($request->input('services', []));

            DB::commit();

            return redirect()->back()->with('success', '¡Servicios actualizados con éxito!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocurrió un error al guardar los servicios: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        //
    }
}
