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
    {{-- CONTENEDOR MAESTRO --}}
    {{-- ========================================== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ========================================== --}}
        {{-- FORMULARIO DE BÚSQUEDA Y FILTROS PARA VISITOR --}}
        {{-- ========================================== --}}
        <div class="mb-8">
            <form action="{{ route('visitor.accommodations.index') }}" method="GET" class="bg-white border border-gray-100 rounded-2xl p-5 shadow-xs">
                
                {{-- Fila 1: Barra Principal --}}
                <div class="flex gap-3 items-center flex-wrap sm:flex-nowrap">
                    <div class="flex-1 min-w-[200px] relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">🔍</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por destino, nombre..." 
                               class="w-full h-11 border border-gray-200 rounded-xl pl-9 pr-3 focus:ring-solar-brown focus:border-solar-brown text-sm">
                    </div>

                    <div class="flex gap-2 h-11 flex-shrink-0">
                        <button type="submit" class="px-6 bg-solar-brown text-white font-bold text-sm rounded-xl transition hover:opacity-90">
                            Buscar
                        </button>
                        
                        <button type="button" id="btn-toggle-filters" class="px-4 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-xl transition text-sm flex items-center gap-2 font-medium" title="Filtros Avanzados">
                            <span>🎛️</span> <span class="hidden sm:inline">Filtros</span>
                        </button>

                        {{-- NUEVO BOTÓN: Colocado estratégicamente al lado del botón de Filtros --}}
                        @if(isset($isSearching) && $isSearching)
                            <a href="{{ route('visitor.accommodations.index') }}" class="px-4 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl transition text-sm flex items-center gap-2 font-bold shadow-xs border border-red-100/50" title="Limpiar todos los filtros">
                                <span>❌</span> <span class="hidden sm:inline">Limpiar</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Fila 2: Contenedor Desplegable --}}
                <div id="advanced-filters" class="mt-5 pt-5 border-t border-gray-100 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-4">
                        
                        {{-- BLOQUE DE RANGOS INTERACTIVOS (Precio y Capacidad) --}}
                        <div class="md:col-span-4 space-y-6 border-b md:border-b-0 md:border-r border-gray-100 pb-6 md:pb-0 md:pr-6">
                            
                            {{-- Selector de Rango de Precio --}}
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Precio</h4>
                                <div class="text-sm font-semibold text-gray-800 mb-3 font-raleway">
                                    <span id="price-min-lbl">${{ number_format(request('min_price', 0)) }}</span> – 
                                    <span id="price-max-lbl">${{ number_format(request('max_price', 10000)) }}</span><span id="price-plus-lbl">{{ request('max_price', 10000) >= 10000 ? ' y más' : '' }}</span>
                                </div>
                                <div class="relative w-full h-2 bg-gray-200 rounded-lg interaction-range-container">
                                    <div id="price-track" class="absolute h-full bg-solar-brown rounded-lg" style="left: 0%; right: 0%;"></div>
                                    <input type="range" id="min_price" name="min_price" min="0" max="10000" step="100" value="{{ request('min_price', 0) }}" 
                                           class="absolute w-full appearance-none bg-transparent pointer-events-none top-0 h-2 accent-solar-brown custom-slider-thumb">
                                    <input type="range" id="max_price" name="max_price" min="0" max="10000" step="100" value="{{ request('max_price', 10000) }}" 
                                           class="absolute w-full appearance-none bg-transparent pointer-events-none top-0 h-2 accent-solar-brown custom-slider-thumb">
                                </div>
                            </div>

                            {{-- Selector de Rango de Huéspedes --}}
                            <div>
                                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Capacidad</h4>
                                <div class="text-sm font-semibold text-gray-800 mb-2 font-raleway h-5">
                                    <span id="capacity-lbl-prefix">Hasta</span><span id="capacity-lbl"> {{ request('capacity', $sliderMaxCapacity) }} huéspedes</span>
                                </div>
                                <input type="range" id="capacity" name="capacity" min="1" max="{{ $sliderMaxCapacity }}" step="1" value="{{ request('capacity', $sliderMaxCapacity) }}"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-solar-brown">
                            </div>

                        </div>

                        {{-- Servicios --}}
                        @if(isset($allServices) && $allServices->isNotEmpty())
                        <div class="md:col-span-4 border-b md:border-b-0 md:border-r border-gray-100 pb-6 md:pb-0 md:px-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Servicios</h4>
                            <div class="grid grid-cols-1 gap-1.5 max-h-44 overflow-y-auto pr-2">
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
                        <div class="md:col-span-4 md:pl-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Etiquetas</h4>
                            <div class="space-y-4 max-h-44 overflow-y-auto pr-2">
                                @foreach ($allTags as $category => $tags)
                                    <div>
                                        <h5 class="text-[10px] font-bold text-gray-400 uppercase mb-1.5 border-b border-gray-50 pb-0.5">{{ $category }}</h5>
                                        <div class="grid grid-cols-1 gap-1">
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
                </div>
            </form>
        </div>

        {{-- Estilos para superponer los selectores nativos del rango de precio --}}
        <style>
            .interaction-range-container input[type="range"]::-webkit-slider-thumb { pointer-events: auto; width: 18px; height: 18px; border-radius: 50%; border: 3px solid #fff; background: #8B5A2B; cursor: pointer; box-shadow: 0 0 4px rgba(0,0,0,0.3); }
            .interaction-range-container input[type="range"]::-moz-range-thumb { pointer-events: auto; width: 18px; height: 18px; border-radius: 50%; border: 3px solid #fff; background: #8B5A2B; cursor: pointer; box-shadow: 0 0 4px rgba(0,0,0,0.3); }
        </style>

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
                @endforeach
            @endif
        @endif

    </div> {{-- FIN DEL CONTENEDOR MAESTRO --}}

    {{-- Script JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Control de Visibilidad del Desplegable
            const btnToggle = document.getElementById('btn-toggle-filters');
            const advancedFilters = document.getElementById('advanced-filters');

            if (btnToggle && advancedFilters) {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('services[]') || urlParams.has('tags[]') || urlParams.has('min_price') || urlParams.has('max_price') || urlParams.has('capacity')) {
                    advancedFilters.classList.remove('hidden');
                }

                btnToggle.addEventListener('click', function () {
                    advancedFilters.classList.toggle('hidden');
                });
            }

            // 2. Lógica Dinámica de Rango de Precio (Doble Slider Nátivo)
            const minPriceInput = document.getElementById('min_price');
            const maxPriceInput = document.getElementById('max_price');
            const priceMinLbl = document.getElementById('price-min-lbl');
            const priceMaxLbl = document.getElementById('price-max-lbl');
            const pricePlusLbl = document.getElementById('price-plus-lbl');
            const priceTrack = document.getElementById('price-track');

            function updatePriceSlider() {
                let minVal = parseInt(minPriceInput.value);
                let maxVal = parseInt(maxPriceInput.value);

                if (minVal > maxVal) {
                    minPriceInput.value = maxVal;
                    minVal = maxVal;
                }

                priceMinLbl.innerText = '$' + minVal.toLocaleString();
                priceMaxLbl.innerText = '$' + maxVal.toLocaleString();
                
                if (maxVal >= 10000) {
                    pricePlusLbl.innerText = ' y más';
                } else {
                    pricePlusLbl.innerText = '';
                }

                const minPercent = (minVal / minPriceInput.max) * 100;
                const maxPercent = 100 - ((maxVal / maxPriceInput.max) * 100);
                
                priceTrack.style.left = minPercent + '%';
                priceTrack.style.right = maxPercent + '%';
            }

            if (minPriceInput && maxPriceInput) {
                minPriceInput.addEventListener('input', updatePriceSlider);
                maxPriceInput.addEventListener('input', updatePriceSlider);
                updatePriceSlider();
            }

            // 3. Lógica Dinámica del Rango de Huéspedes (Slider Simple)
            const capacityInput = document.getElementById('capacity');
            const capacityLbl = document.getElementById('capacity-lbl');
            const capacityPrefix = document.getElementById('capacity-lbl-prefix');

            if (capacityInput && capacityLbl) {
                const maxValLimit = parseInt(capacityInput.max);

                function updateCapacityLabel() {
                    let val = parseInt(capacityInput.value);
                    
                    if (val >= maxValLimit) {
                        capacityPrefix.innerText = "Cualquier cantidad de huéspedes";
                        capacityLbl.innerText = "";
                    } else {
                        capacityPrefix.innerText = "Hasta ";
                        capacityLbl.innerText = val + " huéspedes";
                    }
                }
                capacityInput.addEventListener('input', updateCapacityLabel);
                updateCapacityLabel();
            }
        });
    </script>
    
</x-visitor-layout>