<x-visitor-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <div class="space-y-1">
            <h1 class="text-3xl md:text-4xl text-solar-blue font-tenor tracking-wide">
                {{ $accommodation->name }}
            </h1>
        </div>

        @php
            // 1. Buscamos explícitamente la imagen definida como 'principal'
            $cover = $accommodation->images->where('type', 'principal')->first();

            // 2. Si por error de captura no hubiera una principal, tomamos la primera de respaldo
            $cover = $cover ?? $accommodation->images->first();

            // 3. Obtenemos las imágenes de tipo 'galeria' excluyendo la portada, ordenadas por su posición en la BD
            $gridImages = $accommodation->images
                ->reject(fn($img) => $img->id === $cover?->id)
                ->sortBy('position')
                ->take(4);
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">

            <div class="w-full aspect-[4/3] md:aspect-auto md:h-full bg-gray-100 rounded-2xl md:rounded-3xl overflow-hidden shadow-xs">
                @if ($cover)
                    <img src="{{ Storage::url($cover->image_path) }}"
                        class="w-full h-full object-cover object-center hover:scale-102 transition-transform duration-500"
                        alt="{{ $accommodation->name }}">
                @else
                    <img src="/page-resources/img/NoImage.webp" class="w-full h-full object-cover opacity-40"
                        alt="No Image">
                @endif
            </div>

            <div class="grid grid-cols-2 grid-rows-2 gap-3 md:gap-4 aspect-[4/3] md:aspect-auto">
                @foreach ($gridImages as $img)
                    <div class="w-full h-full bg-gray-100 rounded-xl md:rounded-2xl overflow-hidden shadow-xs">
                        <img src="{{ Storage::url($img->image_path) }}"
                            class="w-full h-full object-cover object-center hover:scale-103 transition-transform duration-500"
                            alt="Galería {{ $accommodation->name }} - {{ $img->position }}">
                    </div>
                @endforeach

                @for ($i = $gridImages->count(); $i < 4; $i++)
                    <div class="w-full h-full bg-amber-50/30 rounded-xl md:rounded-2xl border border-dashed border-amber-200/60 flex items-center justify-center text-gray-400 font-raleway text-xs p-2 text-center">
                        Próximamente
                    </div>
                @endfor
            </div>

        </div>

        <div class="grid grid-cols-1 gap-6 md:gap-8 lg:gap-12">

            <div class="lg:col-span-2 space-y-4">

                @php
                    $subtitulo = $accommodation->tags->where('category', 'Tipo de alojamiento')->first();
                    $zona = $accommodation->tags->where('category', 'Zona')->first();
                @endphp
                
                <p class="text-sm md:text-base lg:text-lg text-gray-500 font-raleway flex flex-wrap items-center gap-x-2 gap-y-1">
                    @if ($subtitulo)
                        <span class="font-bold">{{ $subtitulo->name }}</span>
                    @endif
                    @if ($subtitulo && $zona)
                        <span class="text-gray-300 hidden sm:inline">|</span>
                    @endif
                    @if ($zona)
                        <span class="text-solar-brown flex items-center">
                            <i class="fa-solid fa-location-dot text-xs mr-1.5"></i>{{ $zona->name }}
                        </span>
                    @endif
                </p>

                <div class="border-b border-gray-100 pb-3">
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-sm md:text-base lg:text-lg text-gray-600 font-raleway justify-end">
                        <span>Capacidad de {{ $accommodation->capacity }} persona(s)</span>
                        @if($accommodation->details->count() > 0)
                            <span class="text-gray-300">|</span>
                        @endif
                        @foreach ($accommodation->details as $detail)
                            <span>{{ $detail->pivot->quantity }} {{ $detail->name }}</span>
                            @if (!$loop->last)
                                <span class="text-gray-300">|</span>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="py-2">
                    <div class="text-gray-900 text-base md:text-lg font-basker leading-relaxed whitespace-pre-line text-justify">
                        {{ $accommodation->summary }}
                    </div>
                </div>

            </div>
            
        </div>

        <div class="border-t border-gray-100 pt-8 mt-12 space-y-8">
            <h3 class="text-2xl md:text-4xl text-solar-blue font-tenor tracking-wide text-center">
                Servicios que incluye
            </h3>

            <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-y-5 gap-x-12 max-w-7xl mx-auto px-4 md:px-8">
                @foreach ($accommodation->services as $service)
                    @php
                        $serviceNameLower = strtolower($service->name);
                    @endphp
                    <div class="flex items-center gap-4 text-gray-800 font-raleway text-base md:text-lg italic group">
                        <div class="text-xl md:text-2xl text-gray-700 w-8 flex justify-center items-center flex-shrink-0">
                            @if (str_contains($serviceNameLower, 'wifi'))
                                <i class="fa-solid fa-wifi"></i>
                            @elseif (str_contains($serviceNameLower, 'tv') || str_contains($serviceNameLower, 'cable'))
                                <i class="fa-solid fa-desktop"></i>
                            @elseif (str_contains($serviceNameLower, 'aire') || str_contains($serviceNameLower, 'acondicionado'))
                                <i class="fa-solid fa-snowflake"></i>
                            @elseif (str_contains($serviceNameLower, 'cocina'))
                                <i class="fa-solid fa-kitchen-set"></i>
                            @elseif (str_contains($serviceNameLower, 'lavadora'))
                                <i class="fa-solid fa-circle-check text-base text-gray-400"></i>
                            @elseif (str_contains($serviceNameLower, 'comedor'))
                                <i class="fa-solid fa-circle-check text-base text-gray-400"></i>
                            @elseif (str_contains($serviceNameLower, 'jardín') || str_contains($serviceNameLower, 'jardin'))
                                <i class="fa-solid fa-circle-check text-base text-gray-400"></i>
                            @elseif (str_contains($serviceNameLower, 'alberca') || str_contains($serviceNameLower, 'piscina'))
                                <i class="fa-solid fa-person-swimming"></i>
                            @elseif (str_contains($serviceNameLower, 'vista') || str_contains($serviceNameLower, 'mar'))
                                <i class="fa-solid fa-umbrella-beach"></i>
                            @else
                                <i class="fa-solid fa-circle-check text-base text-gray-400"></i>
                            @endif
                        </div>
                        
                        <span class="tracking-wide text-left text-sm md:text-base lg:text-lg">
                            {{ $service->name }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</x-visitor-layout>