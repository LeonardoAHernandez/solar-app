<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Detail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailController extends Controller
{
    /**
     * Muestra el listado de todos los detalles con sus cantidades actuales.
     */
    public function index(Accommodation $accommodation)
    {
        // 1. Obtener todos los detalles maestros disponibles
        $allDetails = Detail::all();

        // 2. Obtener los detalles asociados a este alojamiento con su cantidad en el pivote
        // Formato devuelto: [id_del_detalle => cantidad]
        $currentDetails = $accommodation->details->pluck('pivot.quantity', 'id')->toArray();

        return view('admin.accommodations.details', compact('accommodation', 'allDetails', 'currentDetails'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Sincroniza las cantidades, guardando únicamente los que sean mayores a 0.
     * Máximo 6 detalles por propiedad.
     */
    public function store(Request $request, Accommodation $accommodation)
    {
        $request->validate([
            'quantities'   => 'nullable|array',
            'quantities.*' => 'nullable|integer|min:0',
        ]);

        $syncData = [];

        if ($request->has('quantities')) {
            foreach ($request->input('quantities') as $detailId => $quantity) {
                if ($quantity > 0) {
                    $syncData[$detailId] = [
                        'quantity' => $quantity
                    ];
                }
            }
        }

        // VALIDACIÓN DE NEGOCIO: Máximo 6 detalles con cantidad > 0
        if (count($syncData) > 6) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'No puedes asignar más de 6 detalles a este alojamiento. Por favor, reduce la selección.');
        }

        try {
            DB::beginTransaction();

            $accommodation->details()->sync($syncData);

            DB::commit();

            return redirect()->back()->with('success', '¡Detalles y cantidades actualizados con éxito!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocurrió un error al guardar los detalles: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Detail $detail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Detail $detail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Detail $detail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Detail $detail)
    {
        //
    }

    /**
     * NUEVO: Crea un detalle desde cero o actualiza el nombre de uno existente.
     */
    public function createOrUpdateDetail(Request $request)
    {
        $request->validate([
            'detail_id' => 'nullable|exists:details,id', // Si viene, es edición; si no, es creación
            'name'      => 'required|string|max:255|unique:details,name,' . $request->detail_id,
        ], [
            'name.unique' => 'Ese detalle ya existe en el catálogo.',
        ]);

        try {
            DB::beginTransaction();

            // updateOrCreate busca por ID; si lo encuentra actualiza el nombre, si no, lo crea.
            Detail::updateOrCreate(
                ['id' => $request->detail_id],
                ['name' => $request->name]
            );

            DB::commit();

            $message = $request->detail_id ? '¡Detalle modificado con éxito!' : '¡Nuevo detalle agregado al catálogo!';
            return redirect()->back()->with('success', $message);

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al gestionar el catálogo: ' . $e->getMessage());
        }
    }

}
