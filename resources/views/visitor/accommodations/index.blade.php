<x-visitor-layout>

    {{-- Banner de Bienvenida --}}
    <div class="w-full [mask-image:linear-gradient(to_bottom,black_85%,transparent_100%)]"
        style="background-image: url('{{ asset('page-resources/img/peFondoInicio.webp') }}')">
        <img src="page-resources/img/LogoSOLAR.webp" class="pt-12 p-24">
    </div>

    <div class="w-full text-center mt-9 mb-6">
        <h1 class="text-3xl text-solar-brown font-tenor">Tu Próxima Historia comienza aquí</h1>
    </div>

    {{-- ========================================== --}}
    {{-- CONTENEDOR MAESTRO (CENTRA Y LIMITA EL ANCHO COMO EN ADMIN) --}}
    {{-- ========================================== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ========================================== --}}
        {{-- FORMULARIO DE BÚSQUEDA Y FILTROS PARA VISITOR --}}
        {{-- ========================================== --}}
        <div class="mb-8">
            <form action="{{ route('visitor.accommodations.index') }}" method="GET" class="bg-white border border-gray-100 rounded-2xl p-5 shadow-xs">
                
                {{-- Fila 1: Barra Principal --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                    <div class="md:col-span-7 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">🔍</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por destino, nombre..." 
                               class="w-full h-11 border border-gray-200 rounded-xl pl-9 pr-3 focus:ring-solar-brown focus:border-solar-brown text-sm">
                    </div>

                    <div class="md:col-span-3">
                        <select name="capacity" class="w-full h-11 border border-gray-200 rounded-xl px-3 focus:ring-solar-brown focus:border-solar-brown text-sm">
                            <option value="">¿Cuántos huéspedes?</option>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ request('capacity') == $i ? 'selected' : '' }}>{{ $i }}+ Personas</option>
                            @endfor
                        </select>
                    </div>

                    <div class="md:col-span-2 flex gap-2 h-11">
                        <button type="submit" class="flex-1 bg-solar-brown text-white font-bold text-sm rounded-xl transition hover:opacity-90">
                            Buscar
                        </button>
                        <button type="button" id="btn-toggle-filters" class="px-3 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-xl transition text-sm" title="Filtros Avanzados">
                            🎛️
                        </button>
                    </div>
                </div>

                {{-- Fila 2: Contenedor Desplegable --}}
                <div id="advanced-filters" class="mt-5 pt-5 border-t border-gray-100 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Servicios --}}
                        @if(isset($allServices) && $allServices->isNotEmpty())
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Servicios</h4>
                            <div class="grid grid-cols-2 gap-2 max-h-36 overflow-y-auto pr-2">
                                @foreach ($allServices as $service)
                                    <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer p-1.5 hover:bg-gray-50 rounded-md">
                                        <input type="checkbox" name="services[]" value="{{ $service->id }}" 
                                               class="rounded border-gray-300 text-solar-brown focus:ring-solar-brown h-4 w-4"
                                               {{ is_array(request('services')) && in_array($service->id, request('services')) ? 'checked' : '' }}>
                                        <span>{{ $service->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Etiquetas --}}
                        @if(isset($allTags) && $allTags->isNotEmpty())
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Etiquetas</h4>
                            <div class="space-y-3 max-h-36 overflow-y-auto pr-2">
                                @foreach ($allTags as $category => $tags)
                                    <div>
                                        <h5 class="text-[10px] font-bold text-gray-400 uppercase mb-1 border-b border-gray-50 pb-0.5">{{ $category }}</h5>
                                        <div class="grid grid-cols-2 gap-1">
                                            @foreach ($tags as $tag)
                                                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer p-1">
                                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                                           class="rounded border-gray-300 text-solar-brown focus:ring-solar-brown h-3.5 w-3.5"
                                                           {{ is_array(request('tags')) && in_array($tag->id, request('tags')) ? 'checked' : '' }}>
                                                    <span>{{ $tag->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Limpiar filtros --}}
                    @if(isset($isSearching) && $isSearching)
                        <div class="flex justify-end mt-4 pt-4 border-t border-gray-50">
                            <a href="{{ route('visitor.accommodations.index') }}" class="text-xs text-red-500 font-semibold hover:underline">
                                ❌ Limpiar todos los filtros
                            </a>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        {{-- ========================================== --}}
        {{-- RENDERIZADO CONDICIONAL DE RESULTADOS --}}
        {{-- ========================================== --}}

        @if(isset($isSearching) && $isSearching)
            {{-- MODO BÚSQUEDA: Lista de tarjetas horizontales (Estilo Admin limpia) --}}
            <div class="space-y-6 mb-12">
                <h2 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-2">Resultados de búsqueda ({{ $accommodations->count() }})</h2>
                
                @forelse ($accommodations as $accommodation)
                    <article class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-solar-brown/30 shadow-xs hover:shadow-lg transition-all duration-300">
                        <a href="{{ route('visitor.accommodations.show', $accommodation->id) }}" class="flex flex-col md:flex-row h-full">
                            
                            {{-- Imagen --}}
                            <div class="relative w-full md:w-64 lg:w-72 flex-shrink-0 aspect-video md:aspect-square overflow-hidden bg-gray-900">
                                <img src="{{ $accommodation->image }}" class="w-full h-full object-cover object-center" alt="{{ $accommodation->name }}">
                            </div>

                            {{-- Datos del Contenido --}}
                            <div class="flex-1 flex flex-col justify-between p-6 sm:p-6">
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-full">
                                    
                                    <div class="lg:col-span-2 flex flex-col justify-between space-y-4">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-950 mb-2 font-raleway">{{ $accommodation->name }}</h3>
                                            <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed mb-4">
                                                {{ $accommodation->description }}
                                            </p>

                                            {{-- Etiquetas en los resultados --}}
                                            @if($accommodation->tags && $accommodation->tags->isNotEmpty())
                                                <div class="flex flex-wrap gap-1.5 mb-2">
                                                    @foreach($accommodation->tags as $tag)
                                                        <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider text-solar-brown bg-amber-50 border border-amber-100 px-2 rounded-md">
                                                            {{ $tag->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Capacidad y contador de servicios --}}
                                        <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-gray-50 text-xs text-gray-600 font-medium">
                                            <span class="bg-gray-50 px-2.5 py-1 rounded-lg">
                                                👥 {{ $accommodation->capacityMin == $accommodation->capacityMax ? $accommodation->capacityMin : $accommodation->capacityMin . ' - ' . $accommodation->capacityMax }} Huéspedes
                                            </span>
                                            @if($accommodation->services && $accommodation->services->isNotEmpty())
                                                <span class="bg-amber-50/50 text-solar-brown px-2.5 py-1 rounded-lg">
                                                    🛠️ {{ $accommodation->services->count() }} Servicios
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    {{-- Precio y estrellas --}}
                                    <div class="flex flex-row lg:flex-col justify-between lg:justify-between items-center lg:items-end border-t lg:border-t-0 lg:border-l border-gray-100 pt-4 lg:pt-0 lg:pl-6 h-full">
                                        <div class="flex items-center gap-1">
                                            <span class="text-sm font-bold text-gray-900">⭐ 5.0</span>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-0.5 font-raleway">
                                                @if(config('app.season') === 'high')
                                                    Temporada Alta
                                                @elseif(config('app.season') === 'mid')
                                                    Temporada Media
                                                @else
                                                    Tarifa Base
                                                @endif
                                            </p>
                                            <p class="text-2xl font-black text-gray-950 tracking-tight">
                                                ${{ number_format($accommodation->price, 2) }} <span class="text-xs font-semibold text-gray-400 font-raleway">MXN</span>
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </a>
                    </article>
                @empty
                    <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-12 text-center">
                        <p class="text-sm text-gray-400 mb-3">No encontramos hospedajes que coincidan con tu búsqueda.</p>
                        <a href="{{ route('visitor.accommodations.index') }}" class="text-xs bg-gray-100 px-3 py-1.5 rounded-md font-bold text-gray-700 hover:bg-gray-200">Ver todas las propiedades</a>
                    </div>
                @endforelse
            </div>

        @else
            {{-- MODO NORMAL: Agrupado por Categorías (Tarjetas Súper Redondeadas Verticales) --}}
            @if(isset($groupedAccommodations) && count($groupedAccommodations) > 0)
                @foreach ($groupedAccommodations as $category => $accommodationsList)
                    <div class="mt-4 mb-2">
                        <div class="flex items-center gap-2">
                            <img src="page-resources/img/peFlechaS1.webp" class="w-8 aspect-square">
                            <span class="font-raleway text-lg font-bold text-solar-brown">{{ $category }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-4 mb-10">
                        @foreach ($accommodationsList as $accommodation)
                            <article class="bg-solar-yellow rounded-[2.5rem] overflow-hidden shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                                <a href="{{ route('visitor.accommodations.show', $accommodation->id) }}" class="flex flex-col h-full">

                                    <div class="w-full aspect-[4/3] overflow-hidden">
                                        <img src="{{ $accommodation->image }}" class="w-full h-full object-cover object-center" alt="{{ $accommodation->name }}">
                                    </div>

                                    <div class="p-6 flex flex-col justify-between flex-grow gap-2 text-solar-brown">
                                        <div>
                                            <h3 class="text-xl font-semibold leading-snug mb-1 font-tenor">{{ $accommodation->name }}</h3>
                                            <p class="text-sm font-medium italic">
                                                ${{ number_format($accommodation->price, 2) }} MXN por noche
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-1 mt-1 text-sm font-bold">
                                            <i class="fa-solid fa-star text-xs"></i>
                                            <span>5.0</span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>

                    <div class="text-center mt-3 mb-12">
                        <a href="#" class="btn btn-link text-decoration-none text-warning fw-bold">
                            &lt; Ver más &gt;
                        </a>
                    </div>
                @endforeach
            @endif
        @endif

    </div> {{-- FIN DEL CONTENEDOR MAESTRO --}}

    {{-- Script JavaScript para Alternar el Acordeón de Filtros --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnToggle = document.getElementById('btn-toggle-filters');
            const advancedFilters = document.getElementById('advanced-filters');

            if (btnToggle && advancedFilters) {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('services[]') || urlParams.has('tags[]')) {
                    advancedFilters.classList.remove('hidden');
                }

                btnToggle.addEventListener('click', function () {
                    advancedFilters.classList.toggle('hidden');
                });
            }
        });
    </script>
    
</x-visitor-layout>