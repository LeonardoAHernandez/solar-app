<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Tag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    /**
     * Muestra la vista con los tags agrupados y los ya seleccionados.
     */
    public function index(Accommodation $accommodation)
    {
        // 1. Obtenemos todos los tags y los agrupamos en colecciones según su 'category'
        $groupedTags = Tag::all()->groupBy('category');

        // 2. Obtenemos los IDs de los tags que ya tiene el alojamiento actualmente
        $selectedTagIds = $accommodation->tags()->pluck('tags.id')->toArray();

        return view('client.accommodations.tags', compact('accommodation', 'groupedTags', 'selectedTagIds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Sincroniza las etiquetas seleccionadas en la base de datos.
     */
    public function store(Request $request, Accommodation $accommodation)
    {
        $request->validate([
            'tags'   => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        try {
            DB::beginTransaction();

            // sync() quita los desmarcados y añade los nuevos automáticamente en un solo paso
            $accommodation->tags()->sync($request->input('tags', []));

            DB::commit();

            return redirect()->back()->with('success', '¡Etiquetas actualizadas con éxito!');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Ocurrió un error al guardar las etiquetas: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
