<x-admin-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista de propiedades
        </h2>
    </x-slot>

    <x-container class="mt-12">        
        {{-- Encabezado Principal y Botón de Creación --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Catálogo General</h1>
            <a href="{{ route('admin.accommodations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-6 py-3 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 text-center sm:w-auto w-full">
                + Agregar Propiedad
            </a>
        </div>

        {{-- ========================================== --}}
        {{-- FORMULARIO DE BÚSQUEDA Y FILTROS AVANZADOS --}}
        {{-- ========================================== --}}
        <form action="{{ route('admin.accommodations.index') }}" method="GET" class="bg-white border border-gray-200 rounded-2xl p-5 shadow-xs mb-8">
            
            {{-- Fila 1: Barra Principal (Texto y Capacidad) --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                <div class="md:col-span-7 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">🔍</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o descripción..." 
                           class="w-full h-11 border border-gray-300 rounded-xl pl-9 pr-3 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div class="md:col-span-3">
                    <select name="capacity" class="w-full h-11 border border-gray-300 rounded-xl px-3 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">¿Cuántos huéspedes?</option>
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ request('capacity') == $i ? 'selected' : '' }}>{{ $i }}+ Personas</option>
                        @endfor
                    </select>
                </div>

                <div class="md:col-span-2 flex gap-2 h-11">
                    <button type="submit" class="flex-1 bg-gray-900 hover:bg-gray-800 text-white font-bold text-sm rounded-xl transition">
                        Buscar
                    </button>
                    <button type="button" id="btn-toggle-filters" class="px-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition text-sm" title="Filtros Avanzados">
                        🎛️
                    </button>
                </div>
            </div>

            {{-- Fila 2: Contenedor Desplegable (Servicios y Etiquetas) --}}
            <div id="advanced-filters" class="mt-5 pt-5 border-t border-gray-100 hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    {{-- Subgrupo: Servicios --}}
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Filtrar por Servicios</h4>
                        <div class="grid grid-cols-2 gap-2 max-h-36 overflow-y-auto pr-2">
                            @foreach ($allServices as $service)
                                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer p-1.5 hover:bg-gray-50 rounded-md">
                                    <input type="checkbox" name="services[]" value="{{ $service->id }}" 
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-4 w-4"
                                           {{ is_array(request('services')) && in_array($service->id, request('services')) ? 'checked' : '' }}>
                                    <span>{{ $service->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Subgrupo: Etiquetas por Categoría --}}
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Filtrar por Etiquetas</h4>
                        <div class="space-y-3 max-h-36 overflow-y-auto pr-2">
                            @foreach ($allTags as $category => $tags)
                                <div>
                                    <h5 class="text-[10px] font-bold text-gray-400 uppercase mb-1 border-b border-gray-50 pb-0.5">{{ $category }}</h5>
                                    <div class="grid grid-cols-2 gap-1">
                                        @foreach ($tags as $tag)
                                            <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer p-1">
                                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" 
                                                       class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 h-3.5 w-3.5"
                                                       {{ is_array(request('tags')) && in_array($tag->id, request('tags')) ? 'checked' : '' }}>
                                                <span>{{ $tag->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- Botón para Limpiar Búsqueda --}}
                @if(request()->hasAny(['search', 'capacity', 'services', 'tags']))
                    <div class="flex justify-end mt-4 pt-4 border-t border-gray-50">
                        <a href="{{ route('admin.accommodations.index') }}" class="text-xs text-red-500 font-semibold hover:underline">
                            ❌ Limpiar todos los filtros
                        </a>
                    </div>
                @endif
            </div>

        </form>

        {{-- ========================================== --}}
        {{--      RENDERIZADO DEL LISTADO DE CARDS      --}}
        {{-- ========================================== --}}
        <div class="space-y-6">
            @forelse ($accommodations as $accommodation)
                <article class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-blue-100 shadow-xs hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                    <a href="{{ route('admin.accommodations.edit', $accommodation) }}" class="flex flex-col md:flex-row h-full">
                        
                        {{-- Imagen de Portada --}}
                        <div class="relative w-full md:w-64 lg:w-72 flex-shrink-0 aspect-video md:aspect-square overflow-hidden bg-gray-900">
                            <img src="{{ $accommodation->image }}" class="w-full h-full object-cover object-center" alt="{{ $accommodation->name }}">
                            
                            {{-- Badge de Estado --}}
                            <div class="absolute top-4 left-4">
                                <span class="bg-gray-900/80 backdrop-blur-xs text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-xs uppercase tracking-wider">
                                    {{ $accommodation->status->name }}
                                </span>
                            </div>
                        </div>

                        {{-- Datos de la tarjeta --}}
                        <div class="flex-1 flex flex-col justify-between p-6 sm:p-8">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-full">
                                
                                <div class="lg:col-span-2 flex flex-col justify-between space-y-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $accommodation->name }}</h3>
                                        <p class="text-sm text-gray-500 line-clamp-3 leading-relaxed mb-4">{{ $accommodation->summary }}</p>

                                        {{-- Etiquetas --}}
                                        @if($accommodation->tags->isNotEmpty())
                                            <div class="flex flex-wrap gap-1.5 mb-2">
                                                @foreach($accommodation->tags as $tag)
                                                    <span class="inline-flex items-center text-[11px] font-bold uppercase tracking-wider text-purple-700 bg-purple-50 border border-purple-100 px-2.5 py-0.5 rounded-md shadow-2xs">
                                                        {{ $tag->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Capacidad y Servicios --}}
                                    <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-gray-50">
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 bg-gray-50 border border-gray-100 px-3 py-1.5 rounded-lg">
                                            👥 {{ $accommodation->capacity }} Huéspedes
                                        </span>
                                        @if($accommodation->services->isNotEmpty())
                                            <span class="inline-flex items-center text-xs font-semibold text-blue-600 bg-blue-50/50 border border-blue-100/50 px-3 py-1.5 rounded-lg">
                                                🛠️ {{ $accommodation->services->count() }} Servicios
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Precio --}}
                                <div class="flex flex-row lg:flex-col justify-between lg:justify-between items-center lg:items-end border-t lg:border-t-0 lg:border-l border-gray-100 pt-4 lg:pt-0 lg:pl-6 h-full">
                                    <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-100 px-2.5 py-1 rounded-lg">
                                        <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                                        <span class="text-xs font-bold text-amber-800">5.0</span>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-0.5">Precio por noche</p>
                                        <p class="text-2xl font-black text-gray-900 tracking-tight">
                                            ${{ number_format($accommodation->price, 2) }} <span class="text-xs font-semibold text-gray-400">MXN</span>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </a>
                </article>
            @empty
                <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-12 text-center shadow-xs">
                    <p class="text-sm text-gray-400 mb-3">No se encontraron propiedades que coincidan con los filtros aplicados.</p>
                    <a href="{{ route('admin.accommodations.index') }}" class="text-xs bg-gray-100 px-3 py-1.5 rounded-md font-bold text-gray-700 hover:bg-gray-200">Reestablecer Búsqueda</a>
                </div>
            @endforelse
        </div>
    </x-container>

    {{-- Script JavaScript para Alternar el Acordeón de Filtros --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnToggle = document.getElementById('btn-toggle-filters');
            const advancedFilters = document.getElementById('advanced-filters');

            // Si hay filtros activos en la URL al recargar, dejamos el panel visible automáticamente
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('services[]') || urlParams.has('tags[]')) {
                advancedFilters.classList.remove('hidden');
            }

            btnToggle.addEventListener('click', function () {
                advancedFilters.classList.toggle('hidden');
            });
        });
    </script>
    
</x-admin-layout>