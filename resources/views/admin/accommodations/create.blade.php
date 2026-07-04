@php
    $optionsCategory = ['Para vacacionar', 'Para desconectarse', 'Vida urbana', 'CoWorking'];
@endphp

<x-admin-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Agregar Propiedad
        </h2>
    </x-slot>

    <x-container class="mt-12" width="4xl">

        <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">

            <!-- Encabezado del Formulario -->
            <div class="bg-gradient-to-r from-slate-800 to-indigo-900 p-6 text-white text-center">
                <h2 class="text-xl font-bold uppercase tracking-wider">
                    Nueva Propiedad
                </h2>
                <p class="text-xs text-indigo-200 mt-1">
                    Completa los campos a continuación para dar de alta el alojamiento en el sistema.
                </p>
            </div>

            <form action="{{ route('admin.accommodations.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <x-validation-errors class="mb-4"></x-validation-errors>

                <!-- ========================================== -->
                <!-- BLOQUE 1: INFORMACIÓN BÁSICA               -->
                <!-- ========================================== -->
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-7h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Datos Generales
                    </h3>

                    <!-- Grid Nombre y Slug -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-label value="Nombre de la propiedad" class="mb-1 text-gray-600 font-medium" for="name" />
                            <x-input placeholder="Ej: Villa Escondida - Vista al Mar" class="w-full focus:ring-indigo-500" name="name"
                                value="{{ old('name') }}" oninput="string_to_slug(this.value, '#slug')" />
                        </div>

                        <div>
                            <x-label value="Slug de la propiedad" class="mb-1 text-gray-500 font-medium" for="slug" />
                            <x-input placeholder="villa-escondida-vista-al-mar" class="w-full bg-gray-50 text-gray-500 cursor-not-allowed" id="slug" name="slug"
                                value="{{ old('slug') }}" readonly />
                            <p class="text-[10px] text-gray-400 mt-1">Se genera automáticamente para la URL de la web.</p>
                        </div>
                    </div>

                    <!-- Categoría -->
                    <div>
                        <x-label value="Categoría de la propiedad" class="mb-1 text-gray-600 font-medium" for="category" />
                        <x-select name="category" class="w-full focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="" disabled @selected(!old('category'))>Selecciona una opción...</option>
                            @foreach ($optionsCategory as $category)
                                <option value="{{ $category }}" @selected(old('category') == $category)>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>

                    <!-- Descripción -->
                    <div>
                        <x-label value="Descripción detallada de la propiedad" class="mb-1 text-gray-600 font-medium" for="description" />
                        <!-- Si tu componente x-input no es un textarea nativo, puedes usar esto para mejor UI de textos largos -->
                        <textarea placeholder="Describe los atractivos, el espacio, las reglas o amenidades únicas..." 
                                  class="w-full rounded-md border-gray-350 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-gray-700 p-2.5 text-sm" 
                                  name="description" rows="4">{{ old('description') }}</textarea>
                    </div>
                </div>


                <!-- ========================================== -->
                <!-- BLOQUE 2: CAPACIDADES (Alpine.js)          -->
                <!-- ========================================== -->
                <div x-data="{ 
                    hasRange: {{ old('has_range') ?? 0 }} ? true : false,
                    min: '{{ old('capacityMin') }}',
                    max: '{{ old('capacityMax') }}'
                }" 
                x-init="$watch('min', value => { if(!hasRange) max = value })"
                class="bg-gray-50 p-5 rounded-xl border border-gray-200 space-y-4">
                    
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider pb-2 border-b border-gray-200 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Capacidad de Huéspedes
                    </h3>

                    <!-- Checkbox de rango estilizado -->
                    <div class="flex items-center">
                        <input type="checkbox" id="has_range" name="has_range" value="1" 
                               x-model="hasRange" 
                               @change="if(!hasRange) max = min"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer w-4 h-4">
                        <label for="has_range" class="ml-2.5 text-sm text-gray-600 select-none cursor-pointer font-medium">
                            Esta propiedad maneja un rango de capacidad (mínimo y máximo)
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                        <!-- Capacidad Mínima / Única -->
                        <div>
                            <x-label x-text="hasRange ? 'Capacidad mínima de personas' : 'Capacidad total de personas'" class="mb-1 text-gray-600 font-medium" for="capacityMin" />
                            <x-input type="number" min="1" placeholder="Ej: 2" class="w-full" name="capacityMin" x-model="min" />
                        </div>
        
                        <!-- Capacidad Máxima (Aparece con animación) -->
                        <div x-show="hasRange" 
                             x-transition:enter="transition ease-out duration-200" 
                             x-transition:enter-start="opacity-0 -translate-y-2" 
                             x-transition:enter-end="opacity-100 translate-y-0">
                            <x-label value="Capacidad máxima de personas" class="mb-1 text-gray-600 font-medium" for="capacityMax" />
                            <x-input type="number" min="1" placeholder="Ej: 4" class="w-full" name="capacityMax" x-model="max" />
                        </div>
                    </div>
                </div>


                <!-- ========================================== -->
                <!-- BLOQUE 3: ESQUEMA DE PRECIOS               -->
                <!-- ========================================== -->
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Esquema tarifario por temporadas
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Temporada Baja -->
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <x-label value="Temporada Baja" class="mb-1 font-semibold text-emerald-700 text-xs uppercase" for="price_lowSeason" />
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-400 sm:text-sm">$</span>
                                </div>
                                <x-input type="number" step="0.01" min="0" placeholder="0.00" class="w-full pl-7 pr-12 text-gray-700 font-semibold" name="price_lowSeason" value="{{ old('price_lowSeason') }}" />
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-400 text-xs">MXN</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Precio base estándar.</p>
                        </div>
                        
                        <!-- Temporada Media -->
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <x-label value="Temporada Media" class="mb-1 font-semibold text-amber-700 text-xs uppercase" for="price_midSeason" />
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-400 sm:text-sm">$</span>
                                </div>
                                <x-input type="number" step="0.01" min="0" placeholder="0.00" class="w-full pl-7 pr-12 text-gray-700 font-semibold" name="price_midSeason" value="{{ old('price_midSeason') }}" />
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-400 text-xs">MXN</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Fines de semana o festivos.</p>
                        </div>

                        <!-- Temporada Alta -->
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <x-label value="Temporada Alta" class="mb-1 font-semibold text-rose-700 text-xs uppercase" for="price_highSeason" />
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-400 sm:text-sm">$</span>
                                </div>
                                <x-input type="number" step="0.01" min="0" placeholder="0.00" class="w-full pl-7 pr-12 text-gray-700 font-semibold" name="price_highSeason" value="{{ old('price_highSeason') }}" />
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-400 text-xs">MXN</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Navidad, Verano o asuetos.</p>
                        </div>
                    </div>
                </div>


                <!-- ========================================== -->
                <!-- BLOQUE 4: UBICACIÓN Y ENLACES              -->
                <!-- ========================================== -->
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider pb-2 border-b border-gray-100 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Ubicación Geográfica
                    </h3>

                    <div>
                        <x-label value="Ubicación (URL de Google Maps)" class="mb-1 text-gray-600 font-medium" for="locationURL" />
                        <x-input placeholder="https://www.google.com/maps/place/..." class="w-full text-sm" name="locationURL"
                            value="{{ old('locationURL') }}" />
                        <p class="text-[11px] text-gray-400 mt-1">Pega el enlace compartido directamente de Google Maps para ligar el mapa.</p>
                    </div>
                </div>


                <!-- Botonera de Acciones -->
                <div class="flex items-center justify-end pt-4 border-t border-gray-100 space-x-3">
                    <a href="{{ route('admin.accommodations.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 px-4 py-2 transition">
                        Cancelar
                    </a>
                    <x-button class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 transition">
                        Crear y Continuar
                    </x-button>
                </div>

            </form>

        </div>
    </x-container>
</x-admin-layout>