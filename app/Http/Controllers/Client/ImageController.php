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
        // 1. Validar que se reciba un array de imágenes y el ID de la acomodación
        $request->validate([
            'images'           => 'required|array',
            'images.*'         => 'image|mimes:jpeg,png,jpg,webp|max:2048', // Regla para cada imagen del array
            'accommodation_id' => 'required|exists:accommodations,id',
        ]);

        // Buscamos la acomodación para usar su relación (o puedes guardar directo con el modelo Image)
        $accommodation = Accommodation::findOrFail($request->accommodation_id);

        // Carpeta destino dentro de storage/app/public/
        $folderPath = 'accommodations/properties';

        // 2. Verificar si el request trae archivos en el array 'images'
        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $index => $file) {

                // 3. Renombrar el archivo de forma única
                $extension = $file->getClientOriginalExtension();
                $fileName = 'accommodation_' . $accommodation->id . '_' . uniqid() . '.' . $extension;

                // 4. Guardar físicamente el archivo en storage/app/public/accommodations/properties
                $file->storePubliclyAs($folderPath, $fileName, 'public');

                // 5. Determinar la posición de manera dinámica
                // Si la vista no manda una posición específica, usamos el índice del ciclo ($index) 
                // sumado a las imágenes que ya tenga la propiedad para no duplicar posiciones.
                $currentImagesCount = $accommodation->images()->count();
                $position = $request->input('position', $currentImagesCount + $index);

                // 6. Guardar en la base de datos usando la relación de Eloquent
                $accommodation->images()->create([
                    'image_path'       => $folderPath . '/' . $fileName,
                    'type'             => $file->getClientMimeType(), // Guarda ej: 'image/jpeg' o puedes usar $extension
                    'position'         => $position,
                    'accommodation_id' => $accommodation->id,
                ]);
            }
        }

        return redirect()->back()->with('success', '¡Imágenes subidas y registradas con éxito!');
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        //
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
