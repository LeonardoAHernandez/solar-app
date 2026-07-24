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

        // 2. Filtro: Barra de Búsqueda de Texto (Nombre o Descripción)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                // Se cambió 'summary' por 'description' ya que 'summary' no existe en tu tabla
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // 3. AJUSTE: Filtro Inteligente de Capacidad de Huéspedes
        if ($request->filled('capacity')) {
            $capacity = $request->input('capacity');

            // Buscamos que el número de huéspedes solicitado quepa dentro del rango permitido de la propiedad
            $query->where(function ($q) use ($capacity) {
                $q->where('capacityMin', '<=', $capacity)
                    ->where('capacityMax', '>=', $capacity);
            });
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
        // Si no marcaron el checkbox, igualamos el máximo al mínimo de forma interna
        if (!$request->has('has_range')) {
            $request->merge(['capacityMax' => $request->input('capacityMin')]);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:accommodations',
            'category' => 'required|string|max:255',
            // 'summary' => 'string',
            'description' => 'required|string',
            'capacityMin' => 'required|integer',
            'capacityMax' => 'required|integer',
            'price_lowSeason' => 'required|numeric',
            'price_midSeason' => 'required|numeric',
            'price_highSeason' => 'required|numeric',
            'locationURL' => 'required',
        ]);

        // Transformación automática de la URL de Google Maps a Embed
        if (!empty($data['locationURL'])) {
            $data['locationURL'] = $this->formatGoogleMapsUrl($data['locationURL']);
        }

        $data['status'] = AccommodationStatus::BORRADOR->value;

        $accommodation = Accommodation::create($data);

        return redirect()->route('admin.accommodations.edit', $accommodation);
    }

    /**
     * Convierte cualquier formato de URL de Google Maps (incluyendo pines de coordenadas) a Embed.
     */
    private function formatGoogleMapsUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        // 1. Si pegan el tag HTML <iframe> completo, extraemos solo la URL de src=""
        if (str_contains($url, '<iframe')) {
            preg_match('/src="([^"]+)"/', $url, $matches);
            return $matches[1] ?? $url;
        }

        // 2. Si es un enlace acortado (maps.app.goo.gl o goo.gl), resolvemos la redirección real
        if (str_contains($url, 'goo.gl')) {
            try {
                // Forzamos seguir redirecciones de la cabecera HTTP
                $headers = get_headers($url, 1);
                if (isset($headers['Location'])) {
                    $url = is_array($headers['Location']) ? end($headers['Location']) : $headers['Location'];
                }
            } catch (\Throwable $e) {
                // Si la solicitud falla, continua con la cadena original
            }
        }

        // Decodificamos por si la URL trae caracteres escapados (%2F, %2C, etc)
        $decodedUrl = urldecode($url);

        // 3. Caso A: Si la URL contiene coordenadas explícitas en search/coordenadas o /@coordenadas
        // Coincide con patrones como: /search/19.391008,+-99.538903 o @19.391008,-99.538903
        if (preg_match('/(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/', $decodedUrl, $matches)) {
            $lat = $matches[1];
            $lng = $matches[2];
            return "https://maps.google.com/maps?q={$lat},{$lng}&z=16&output=embed";
        }

        // 4. Si ya es una URL embed válida
        if (str_contains($url, 'google.com/maps/embed')) {
            return $url;
        }

        // 5. Caso B: Para nombres de negocios, escuelas, o restaurantes sin coordenadas visibles
        return "https://maps.google.com/maps?q=" . urlencode($url) . "&z=15&output=embed";
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
        if (!$request->has('has_range')) {
            $request->merge(['capacityMax' => $request->input('capacityMin')]);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:accommodations,slug,' . $accommodation->id,
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'capacityMin' => 'required|integer|min:1',
            'capacityMax' => 'required|integer|gte:capacityMin',
            'price_lowSeason' => 'required|numeric',
            'price_midSeason' => 'required|numeric',
            'price_highSeason' => 'required|numeric',
            'locationURL' => 'required',
        ]);

        // Transformación automática de la URL de Google Maps a Embed al actualizar
        if (!empty($data['locationURL'])) {
            $data['locationURL'] = $this->formatGoogleMapsUrl($data['locationURL']);
        }

        $accommodation->update($data);

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

    /**
     * Muestra la lista de propiedades seleccionadas por un cliente desde un enlace recibido.
     */
    public function sharedInterests(Request $request)
    {
        // Obtenemos el parámetro 'ids' (ej: "2,3" o "2%2C3")
        $rawIds = $request->input('ids', '');

        // Decodificamos la cadena por si incluye %2C
        $decoded = urldecode($rawIds);

        // Convertimos en array
        $ids = array_filter(explode(',', $decoded), 'is_numeric');

        $accommodations = Accommodation::whereIn('id', $ids)
            ->with(['images', 'tags'])
            ->get();

        return view('admin.accommodations.shared-interests', compact('accommodations', 'rawIds'));
    }
}
