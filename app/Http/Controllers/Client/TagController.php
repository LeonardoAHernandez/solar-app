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
        // 1. Traemos todos los tags ordenados para las tarjetas principales
        $groupedTags = Tag::orderBy('category')->orderBy('name')->get()->groupBy('category');

        // 2. NUEVO: Obtenemos una lista única de todas las categorías existentes para el select del modal
        $categories = Tag::select('category')->distinct()->orderBy('category')->pluck('category');

        $selectedTagIds = $accommodation->tags()->pluck('tags.id')->toArray();

        // Enviamos $categories a la vista
        return view('client.accommodations.tags', compact('accommodation', 'groupedTags', 'categories', 'selectedTagIds'));
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

    /**
     * NUEVO: Crea una etiqueta desde cero o actualiza una existente en el catálogo maestro.
     */
    public function createOrUpdateTag(Request $request)
    {
        $request->validate([
            'tag_id'   => 'nullable|exists:tags,id', // Si viene, edita; si no, crea
            'name'     => 'required|string|max:255|unique:tags,name,' . $request->tag_id,
            'category' => 'required|string|max:255', // Campo obligatorio para organizar los tags
        ], [
            'name.unique' => 'Esta etiqueta ya existe en el catálogo.',
        ]);

        try {
            DB::beginTransaction();

            // Sincroniza o crea el registro maestro guardando nombre y categoría
            Tag::updateOrCreate(
                ['id' => $request->tag_id],
                [
                    'name'     => $request->name,
                    'category' => $request->category
                ]
            );

            DB::commit();

            $message = $request->tag_id ? '¡Etiqueta modificada con éxito!' : '¡Nueva etiqueta agregada al catálogo!';
            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al gestionar el catálogo: ' . $e->getMessage());
        }
    }
}
