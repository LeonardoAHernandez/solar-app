<?php

namespace App\Http\Controllers\Admin;

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
        // 1. Obtener todos los servicios de la base de datos precargando su icono
        $services = Service::with('icon')->get();

        // 2. Obtener los IDs de los servicios que este alojamiento ya tiene asociados
        $selectedServiceIds = $accommodation->services()->pluck('services.id')->toArray();

        // NUEVO: Obtener todos los iconos disponibles para el selector del catálogo
        $icons = \App\Models\Icon::orderBy('name')->get();

        return view('admin.accommodations.services', compact('accommodation', 'services', 'selectedServiceIds', 'icons'));
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

    /**
     * NUEVO: Crea un servicio desde cero o actualiza el nombre/icono de uno existente en el catálogo maestro.
     */
    public function createOrUpdateService(Request $request)
    {
        $request->validate([
            'service_id'      => 'nullable|exists:services,id',
            'name'            => 'required|string|max:255|unique:services,name,' . $request->service_id,
            'icon_id'         => 'nullable|string', // Puede ser ID numérico o el texto "new"
            'new_icon_name'   => 'required_if:icon_id,new|nullable|string|max:255',
            'new_icon_class'  => 'required_if:icon_id,new|nullable|string|max:255',
        ], [
            'name.unique' => 'Ese servicio ya existe en el catálogo.',
            'new_icon_name.required_if' => 'El nombre del nuevo icono es obligatorio.',
            'new_icon_class.required_if' => 'La clase de Font Awesome es obligatoria.',
        ]);

        try {
            DB::beginTransaction();

            $iconId = $request->icon_id;

            // Si el usuario seleccionó "Agregar nuevo icono"
            if ($request->icon_id === 'new') {
                $newIcon = \App\Models\Icon::create([
                    'name' => $request->new_icon_name,
                    'class_name' => $request->new_icon_class,
                ]);
                $iconId = $newIcon->id;
            }

            // Busca por ID para actualizar, o crea uno nuevo si el ID está vacío
            Service::updateOrCreate(
                ['id' => $request->service_id],
                [
                    'name' => $request->name,
                    'icon_id' => $iconId ?: null // Si está vacío se guarda como NULL
                ]
            );

            DB::commit();

            $message = $request->service_id ? '¡Servicio modificado con éxito!' : '¡Nuevo servicio agregado al catálogo!';
            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al gestionar el catálogo: ' . $e->getMessage());
        }
    }

}
