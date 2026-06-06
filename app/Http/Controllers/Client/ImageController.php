<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Accommodation $accommodation)
    {
        $images = $accommodation->images()->orderBy('position')->get();

        return view('client.accommodations.images', compact('accommodation', 'images'));
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
        $request->validate([
            'images'           => 'required|array',
            'images.*'         => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'accommodation_id' => 'required|exists:accommodations,id',
        ]);

        $accommodation = Accommodation::findOrFail($request->accommodation_id);
        $folderPath = 'accommodations/properties';

        if ($request->hasFile('images')) {
            // Evaluamos si ya cuenta con una imagen principal
            $hasPrincipal = $accommodation->images()->where('type', 'principal')->exists();
            $currentImagesCount = $accommodation->images()->count();

            foreach ($request->file('images') as $index => $file) {
                $extension = $file->getClientOriginalExtension();
                $fileName = 'accommodation_' . $accommodation->id . '_' . uniqid() . '.' . $extension;
                
                $file->storePubliclyAs($folderPath, $fileName, 'public');

                // Si no tiene principal, la primera de la tanda será la principal. Las demás serán galería.
                $type = (!$hasPrincipal && $index === 0) ? 'principal' : 'galeria';
                
                // La posición solo importa para las de galería, la principal puede ser 0
                $position = $type === 'principal' ? 0 : ($currentImagesCount + $index);

                $accommodation->images()->create([
                    'image_path'       => $folderPath . '/' . $fileName,
                    'type'             => $type,
                    'position'         => $position,
                    'accommodation_id' => $accommodation->id,
                ]);
            }
        }

        return redirect()->back()->with('success', '¡Imágenes subidas con éxito!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Actualiza el tipo y la posición de una imagen individual de forma segura.
     */
    public function update(Request $request, Image $image)
    {
        $request->validate([
            'type'     => 'required|in:principal,galeria',
            'position' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // REGLA DE NEGOCIO: Si esta imagen pasa a ser "principal",
            // quitamos la principal anterior de este alojamiento para que no haya duplicados.
            if ($request->type === 'principal') {
                Image::where('accommodation_id', $image->accommodation_id)
                    ->where('type', 'principal')
                    ->update(['type' => 'galeria', 'position' => 1]); // La mandamos a la galería

                // La nueva principal no necesita posición de orden de catálogo
                $image->update([
                    'type' => 'principal',
                    'position' => 0
                ]);
            } else {
                // Si solo cambia posición en la galería
                $image->update([
                    'type' => 'galeria',
                    'position' => $request->position
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', '¡Ajustes de la imagen actualizados!');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al actualizar la imagen: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        // Guardamos la ruta del archivo en una variable local antes de borrar el registro
        $filePath = $image->image_path;

        try {
            // Iniciamos el "todo o nada" en la Base de Datos
            DB::beginTransaction();

            // 1. Eliminamos el registro de la BD primero
            $image->delete();

            // Si todo va bien hasta aquí, guardamos los cambios definitivamente
            DB::commit();

            // 2. PASO DE SEGURIDAD FÍSICA: Solo borramos del disco si la BD se procesó con éxito
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->back()->with('success', '¡Imagen eliminada de la base de datos y del servidor con éxito!');
        } catch (Exception $e) {
            // Si algo falla dentro del bloque 'try', deshacemos cualquier cambio en la BD
            DB::rollBack();

            // Redireccionamos reportando el error sin haber tocado el archivo físico
            return redirect()->back()->with('error', 'No se pudo eliminar la imagen: ' . $e->getMessage());
        }
    }
}
