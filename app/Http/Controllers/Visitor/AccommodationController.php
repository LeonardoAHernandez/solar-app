<?php

namespace App\Http\Controllers\Visitor;

use App\Enums\AccommodationStatus;
use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Season;
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
        try {
            $statusFilter = AccommodationStatus::from(2);
        } catch (\Throwable $e) {
            $statusFilter = 2;
        }

        $query = Accommodation::where('status', $statusFilter)->with(['services', 'tags']);

        // 1. Obtener dinámicamente la capacidad máxima registrada en tu base de datos
        $maxCapacityAllowed = (int) Accommodation::where('status', $statusFilter)->max('capacityMax') ?: 15;
        // El tope del slider será el valor máximo + 1 (para representar "Cualquier cantidad")
        $sliderMaxCapacity = $maxCapacityAllowed + 1;

        $season = config('app.season');
        $priceColumn = ($season === 'high') ? 'price_highSeason' : (($season === 'mid') ? 'price_midSeason' : 'price_lowSeason');

        $hasPriceFilter = ($request->filled('min_price') && (int)$request->input('min_price') > 0) 
                       || ($request->filled('max_price') && (int)$request->input('max_price') < 10000);
                       
        // Modificado: Solo filtra huéspedes si el usuario bajó el slider del tope máximo
        $hasCapacityFilter = $request->filled('capacity') && (int)$request->input('capacity') < $sliderMaxCapacity;

        $isSearching = $request->filled('search') 
                    || $hasCapacityFilter
                    || $request->filled('services') 
                    || $request->filled('tags') 
                    || $hasPriceFilter;

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Modificado: Aplicar el filtro numérico
        if ($hasCapacityFilter) {
            $query->where('capacityMax', '>=', (int) $request->input('capacity'));
        }

        if ($request->filled('min_price') && (int)$request->input('min_price') > 0) {
            $query->where($priceColumn, '>=', (int) $request->input('min_price'));
        }
        if ($request->filled('max_price') && (int)$request->input('max_price') < 10000) {
            $query->where($priceColumn, '<=', (int) $request->input('max_price'));
        }

        if ($request->filled('services')) {
            $serviceIds = $request->input('services');
            $query->whereHas('services', function ($q) use ($serviceIds) {
                $q->whereIn('services.id', $serviceIds);
            });
        }

        if ($request->filled('tags')) {
            $tagIds = $request->input('tags');
            $query->whereHas('tags', function ($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            });
        }

        $accommodations = $query->latest()->get();

        $groupedAccommodations = [];
        if (!$isSearching && $accommodations->isNotEmpty()) {
            $groupedAccommodations = $accommodations->groupBy('category');
        }

        $allServices = Service::orderBy('name')->get();
        $allTags = Tag::orderBy('category')->orderBy('name')->get()->groupBy('category');

        // Pasamos las variables de capacidad a la vista
        return view('visitor.accommodations.index', compact(
            'accommodations', 
            'groupedAccommodations', 
            'allServices', 
            'allTags', 
            'isSearching',
            'sliderMaxCapacity'
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
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // 1. Buscamos el registro
            $accommodation = Accommodation::where('id', $id)
                ->orWhere('slug', $id)
                ->with(['images', 'services', 'details', 'tags'])
                ->firstOrFail();

            // 2. Obtener temporadas
            $seasons = Season::all();

            // 3. Intentar renderizar la vista
            return view('visitor.accommodations.show', compact('accommodation', 'seasons'));

        } catch (\Throwable $e) {
            // Si truena en el controlador o dentro de las directivas Blade de la vista, se detiene aquí
            dd([
                'Mensaje de Error' => $e->getMessage(),
                'Archivo donde falló' => $e->getFile(),
                'Línea del error' => $e->getLine(),
                'Traza abreviada' => array_slice($e->getTrace(), 0, 5)
            ]);
        }
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
