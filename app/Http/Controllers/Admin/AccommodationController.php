<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AccommodationStatus;
use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Detail;
use App\Models\Service;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AccommodationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Iniciamos la consulta base con relaciones precargadas para óptimo rendimiento
        $query = Accommodation::with(['services', 'tags', 'images']);

        // 2. Filtro: Barra de Búsqueda de Texto (Nombre o Resumen)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('summary', 'LIKE', "%{$search}%");
            });
        }

        // 3. Filtro: Capacidad Mínima de Huéspedes
        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->input('capacity'));
        }

        // 4. Filtro Avanzado: Por Servicios (Muchos a Muchos)
        if ($request->filled('services')) {
            $serviceIds = $request->input('services'); // Array de IDs
            $query->whereHas('services', function ($q) use ($serviceIds) {
                $q->whereIn('services.id', $serviceIds);
            });
        }

        // 5. Filtro Avanzado: Por Etiquetas (Muchos a Muchos)
        if ($request->filled('tags')) {
            $tagIds = $request->input('tags'); // Array de IDs
            $query->whereHas('tags', function ($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            });
        }

        // 6. Ejecutamos la consulta filtrada
        $accommodations = $query->latest()->get();

        // 7. Obtenemos catálogos globales para llenar los checkboxes del buscador
        $allServices = Service::orderBy('name')->get();
        $allTags = Tag::orderBy('category')->orderBy('name')->get()->groupBy('category');

        return view('admin.accommodations.index', compact('accommodations', 'allServices', 'allTags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $details = Detail::all();
        $tags = Tag::all();
        $services = Service::all();

        return view('admin.accommodations.create', compact('details', 'tags', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:accommodations',
            'summary' => 'string',
            'description' => 'required|string',
            'capacity' => 'required|integer',
            'price' => 'required|numeric',
            'price_highseason' => 'required|numeric',
            'locationURL' => 'required',
        ]);

        $data['status'] = AccommodationStatus::BORRADOR->value;

        $accommodation = Accommodation::create($data);

        return redirect()->route('admin.accommodations.edit', $accommodation);
    }

    /**
     * Display the specified resource.
     */
    public function show(Accommodation $accommodation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Accommodation $accommodation)
    {
        $details = Detail::all();
        $tags = Tag::all();
        $services = Service::all();

        return view('admin.accommodations.edit', compact('accommodation', 'details', 'tags', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Accommodation $accommodation)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:accommodations,slug,' . $accommodation->id,
            'summary' => 'required|string',
            'description' => 'required|string',
            'capacity' => 'required|integer',
            'price' => 'required|numeric',
            'price_highseason' => 'required|numeric',
            'locationURL' => 'required',
        ]);

        // if ($request->hasFile('image')) {
        //     if ($accommodation->image) {
        //         Storage::delete($accommodation->image);
        //     }

        //     $data['image_path'] = Storage::put('/images', $request->file('image'));
        // }

        $accommodation->update($data);

        // session()->flash('flash.bannerStyle', 'danger');
        session()->flash('flash.banner', 'Propiedad actualizada correctamente.');

        return redirect()->route('admin.accommodations.edit', $accommodation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accommodation $accommodation)
    {
        //
    }

    public function images(Accommodation $accommodation)
    {
        return view('admin.accommodations.images', compact('accommodation'));
    }

    public function updateStatus(Request $request, Accommodation $accommodation)
    {
        $request->validate([
            'status' => ['required', Rule::enum(AccommodationStatus::class)],
        ]);

        $accommodation->update([
            'status' => $request->status,
        ]);

        return back()->with('flash.banner', 'El estatus de la propiedad ha sido actualizado.');
    }
}
