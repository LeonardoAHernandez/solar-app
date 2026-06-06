<x-client-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista de propiedades
        </h2>
    </x-slot>

    <x-container class="mt-12">        
        {{-- Botón de Acción Principal --}}
        <div class="md:flex md:justify-end mb-8">
            <a href="{{ route('client.accommodations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm px-6 py-3 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 block w-full md:w-auto text-center">
                + Agregar Propiedad
            </a>
        </div>

        {{-- Contenedor de la Lista --}}
        <div class="space-y-6">
            @forelse ($accommodations as $accommodation)
                <article class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-blue-100 shadow-xs hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                    <a href="{{ route('client.accommodations.edit', $accommodation) }}" class="flex flex-col md:flex-row h-full">
                        
                        {{-- Panel Izquierdo: Imagen de Portada --}}
                        <div class="relative w-full md:w-64 lg:w-72 flex-shrink-0 aspect-video md:aspect-square overflow-hidden bg-gray-900">
                            <img src="{{ $accommodation->image }}"
                                 class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500" 
                                 alt="{{ $accommodation->name }}">
                            
                            {{-- Badge de Estado flotante sobre la imagen --}}
                            <div class="absolute top-4 left-4">
                                @switch($accommodation->status->name)
                                    @case('BORRADOR')
                                        <span class="bg-amber-500/90 backdrop-blur-xs text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-xs uppercase tracking-wider">
                                            {{ $accommodation->status->name }}
                                        </span>
                                        @break
                                    @case('ACTIVO')
                                        <span class="bg-emerald-500/90 backdrop-blur-xs text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-xs uppercase tracking-wider">
                                            {{ $accommodation->status->name }}
                                        </span>
                                        @break
                                    @case('INACTIVO')
                                        <span class="bg-rose-500/90 backdrop-blur-xs text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-xs uppercase tracking-wider">
                                            {{ $accommodation->status->name }}
                                        </span>
                                        @break
                                    @default
                                        <span class="bg-gray-500/90 backdrop-blur-xs text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-xs uppercase tracking-wider">
                                            {{ $accommodation->status->name }}
                                        </span>
                                @endswitch
                            </div>
                        </div>

                        {{-- Panel Central y Derecho --}}
                        <div class="flex-1 flex flex-col justify-between p-6 sm:p-8">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-full">
                                
                                {{-- Información Descriptiva Principal (2 Columnas) --}}
                                <div class="lg:col-span-2 flex flex-col justify-between space-y-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 tracking-tight hover:text-blue-600 transition duration-150 mb-2">
                                            {{ $accommodation->name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 line-clamp-3 leading-relaxed mb-4">
                                            {{ $accommodation->summary }}
                                        </p>

                                        {{-- NUEVO: Lista Desplegada de Etiquetas (Tags) Propias --}}
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

                                    {{-- Características Básicas y de Servicios --}}
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
                                
                                {{-- Panel de Tarifas y Puntuación Lateral --}}
                                <div class="flex flex-row lg:flex-col justify-between lg:justify-between items-center lg:items-end border-t lg:border-t-0 lg:border-l border-gray-100 pt-4 lg:pt-0 lg:pl-6 h-full">
                                    
                                    {{-- Calificación simulada --}}
                                    <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-100 px-2.5 py-1 rounded-lg">
                                        <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                                        <span class="text-xs font-bold text-amber-800">5.0</span>
                                    </div>

                                    {{-- Bloque de Precio Enfatizado --}}
                                    <div class="text-right">
                                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-0.5">Precio por noche</p>
                                        <p class="text-2xl font-black text-gray-900 tracking-tight">
                                            ${{ number_format($accommodation->price, 2) }}
                                            <span class="text-xs font-semibold text-gray-400 block lg:inline lg:ml-0.5">MXN</span>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </a>
                </article>
            @empty
                {{-- Estado de catálogo vacío --}}
                <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-12 text-center shadow-xs">
                    <div class="max-w-md mx-auto py-4">
                        <div class="text-4xl mb-4">🏢</div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">No hay propiedades registradas</h3>
                        <p class="text-sm text-gray-400 mb-6">Comienza a construir tu catálogo agregando tu primer alojamiento al sistema.</p>
                        <a href="{{ route('client.accommodations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-xs transition">
                            Agrega una propiedad
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </x-container>
    
</x-client-layout>