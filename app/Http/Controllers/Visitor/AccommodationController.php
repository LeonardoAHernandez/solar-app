<?php

namespace App\Http\Controllers\Visitor;

use App\Enums\AccommodationStatus;
use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Service;
use App\Models\Tag;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // 1. Resolvemos el estado usando directamente el caso del Enum si es posible,
    // o en su defecto el valor entero '2' de forma nativa.
    // Intentamos obtener el enum de forma segura; si falla, usamos el 2 directamente.
    try {
        $statusFilter = AccommodationStatus::from(2);
    } catch (\Throwable $e) {
        $statusFilter = 2;
    }

    // Iniciamos la consulta base con relaciones precargadas
    $query = Accommodation::where('status', $statusFilter)->with(['services', 'tags']);

    // Variable para saber si el usuario usó el buscador
    $isSearching = $request->hasAny(['search', 'capacity', 'services', 'tags']);

    // 2. Filtro: Barra de Búsqueda de Texto
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('description', 'LIKE', "%{$search}%");
        });
    }

    // 3. Filtro: Capacidad de Huéspedes
    if ($request->filled('capacity')) {
        $capacity = $request->input('capacity');
        $query->where(function ($q) use ($capacity) {
            $q->where('capacityMin', '<=', $capacity)
              ->where('capacityMax', '>=', $capacity);
        });
    }

    // 4. Filtro Avanzado: Servicios
    if ($request->filled('services')) {
        $serviceIds = $request->input('services');
        $query->whereHas('services', function ($q) use ($serviceIds) {
            $q->whereIn('services.id', $serviceIds);
        });
    }

    // 5. Filtro Avanzado: Etiquetas
    if ($request->filled('tags')) {
        $tagIds = $request->input('tags');
        $query->whereHas('tags', function ($q) use ($tagIds) {
            $q->whereIn('tags.id', $tagIds);
        });
    }

    // 6. Ejecutamos la consulta
    $accommodations = $query->latest()->get();

    // 7. Agrupamos por categoría SOLO si el usuario NO está buscando
    $groupedAccommodations = [];
    if (!$isSearching) {
        $groupedAccommodations = $accommodations->groupBy('category');
    }

    // 8. Catálogos globales para los selectores del formulario
    $allServices = Service::orderBy('name')->get();
    $allTags = Tag::orderBy('category')->orderBy('name')->get()->groupBy('category');

    return view('visitor.accommodations.index', compact(
        'accommodations', 
        'groupedAccommodations', 
        'allServices', 
        'allTags', 
        'isSearching'
    ));
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
    public function show($id)
    {
        // Buscamos el registro de forma manual por ID o por Slug
        $accommodation = \App\Models\Accommodation::where('id', $id)
            ->orWhere('slug', $id)
            ->with(['images', 'services', 'details', 'tags'])
            ->firstOrFail(); // Lanza un error 404 si no existe, ideal para producción

        return view('visitor.accommodations.show', compact('accommodation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Accommodation $accommodation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Accommodation $accommodation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accommodation $accommodation)
    {
        //
    }
}
