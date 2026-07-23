<x-visitor-layout>
    <!-- Estilos de GLightbox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6 lg:space-y-8">

        {{-- Título de la propiedad --}}
        <div>
            <h1 class="text-3xl md:text-4xl text-solar-blue font-tenor tracking-wide">
                {{ $accommodation->name }}
            </h1>
        </div>

        @php
            // Ordenamos todas las imágenes por su posición asignada
            $allSortedImages = $accommodation->images->sortBy('position');

            // 1. Imagen Principal Superior (Posición 1 o la primera que exista)
            $cover = $allSortedImages->first();

            // 2. Imágenes para la cuadrícula superior (Posiciones de la 2 a la 5)
            $gridImages = $allSortedImages->filter(fn($img) => $img->position >= 2 && $img->position <= 5)->take(4);

            // 3. Imágenes para el carrusel de descanso (Posición 6 en adelante)
            $restImages = $allSortedImages->filter(fn($img) => $img->position >= 6);
        @endphp

        {{-- CONTENEDOR DE GALERÍA INFALIBLE (Posiciones 1 al 5) --}}
        <div class="flex flex-col md:flex-row gap-3 md:gap-4 w-full h-auto md:h-[450px] lg:h-[550px]">

            {{-- Imagen Principal Izquierda (Posición 1) --}}
            @if ($cover)
                <a href="{{ Storage::url($cover->image_path) }}"
                    class="glightbox w-full md:w-1/2 aspect-[4/3] md:aspect-auto md:h-full bg-gray-100 rounded-2xl md:rounded-3xl overflow-hidden shadow-xs group cursor-zoom-in"
                    data-gallery="accommodation-gallery">
                    <img src="{{ Storage::url($cover->image_path) }}"
                        class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                        alt="{{ $accommodation->name }}">
                </a>
            @else
                <div
                    class="w-full md:w-1/2 aspect-[4/3] md:aspect-auto md:h-full bg-gray-100 rounded-2xl md:rounded-3xl overflow-hidden shadow-xs flex items-center justify-center">
                    <img src="/page-resources/img/NoImage.webp" class="w-full h-full object-cover opacity-40"
                        alt="No Image">
                </div>
            @endif

            {{-- Cuadrícula Derecha (Posiciones 2 a 5) --}}
            <div
                class="w-full md:w-1/2 aspect-[4/3] md:aspect-auto md:h-full grid grid-cols-2 grid-rows-2 gap-3 md:gap-4">
                @foreach ($gridImages as $img)
                    <a href="{{ Storage::url($img->image_path) }}"
                        class="glightbox w-full h-full bg-gray-100 rounded-xl md:rounded-2xl overflow-hidden shadow-xs group cursor-zoom-in"
                        data-gallery="accommodation-gallery">
                        <img src="{{ Storage::url($img->image_path) }}"
                            class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                            alt="Galería {{ $accommodation->name }} - {{ $img->position }}">
                    </a>
                @endforeach

                {{-- Espacios vacíos de respaldo si no se llenan las 5 imágenes --}}
                @for ($i = $gridImages->count(); $i < 4; $i++)
                    <div
                        class="w-full h-full bg-amber-50/30 rounded-xl md:rounded-2xl border border-dashed border-amber-200/60 flex items-center justify-center text-gray-400 font-raleway text-xs p-2 text-center">
                        Próximamente
                    </div>
                @endfor
            </div>

        </div>

        {{-- DETALLES DEL ALOJAMIENTO --}}
        <div class="pt-2 md:pt-4 space-y-4">

            @php
                $subtitulo = $accommodation->tags->where('category', 'Tipo de alojamiento')->first();
                $zona = $accommodation->tags->where('category', 'Zona')->first();
            @endphp

            <p
                class="text-sm md:text-base lg:text-lg text-gray-500 font-raleway flex flex-wrap items-center gap-x-2 gap-y-1">
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

            <div class="border-b border-gray-100 pb-4">
                <div
                    class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-sm md:text-base lg:text-lg text-gray-600 font-raleway justify-start">
                    <span>
                        👥 Capacidad:
                        @if ($accommodation->capacityMin === $accommodation->capacityMax)
                            {{ $accommodation->capacityMin }} Persona(s)
                        @else
                            {{ $accommodation->capacityMin }} - {{ $accommodation->capacityMax }} Personas
                        @endif
                    </span>
                    @if ($accommodation->details->count() > 0)
                        <span class="text-gray-300">|</span>
                    @endif
                    @foreach ($accommodation->details as $detail)
                        <span>
                            @if ($detail->pivot->quantity > 1)
                                {{ $detail->pivot->quantity }}
                            @endif
                            {{ $detail->name }}
                        </span>
                        @if (!$loop->last)
                            <span class="text-gray-300">|</span>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="py-2">
                <div
                    class="text-gray-900 text-base md:text-lg font-basker leading-relaxed whitespace-pre-line text-justify">
                    {{ $accommodation->description }}
                </div>
            </div>

        </div>

        {{-- SECCIÓN: ¿DÓNDE VOY A DESCANSAR? (Posición 6+) --}}
        @if ($restImages->isNotEmpty())
            <div class="w-full space-y-6 pt-6">
                {{-- Barra naranja de título --}}
                <div class="w-full bg-[#e0691d] text-white text-center py-3 rounded-xl shadow-xs">
                    <h2 class="text-xl md:text-2xl font-raleway tracking-wide">¿Dónde voy a descansar?</h2>
                </div>

                {{-- Slider/Galería con flechas laterales --}}
                <div class="flex items-center justify-between gap-2 md:gap-4 px-2">

                    {{-- Botón Izquierdo --}}
                    <button onclick="document.getElementById('rest-slider').scrollBy({left: -300, behavior: 'smooth'})"
                        class="text-3xl md:text-5xl font-light text-[#0f2d3a] hover:opacity-70 transition select-none hidden sm:block">
                        &lt;
                    </button>

                    {{-- Contenedor deslizable de imágenes --}}
                    <div id="rest-slider"
                        class="flex-1 flex gap-4 overflow-x-auto snap-x snap-mandatory scrollbar-none pb-2">
                        @foreach ($restImages as $img)
                            <a href="{{ Storage::url($img->image_path) }}"
                                class="glightbox w-full sm:w-[calc(50%-8px)] flex-shrink-0 snap-start aspect-[4/3] bg-gray-100 rounded-2xl overflow-hidden shadow-sm cursor-zoom-in group"
                                data-gallery="accommodation-gallery">
                                <img src="{{ Storage::url($img->image_path) }}"
                                    class="w-full h-full object-cover object-center group-hover:scale-102 transition-transform duration-300"
                                    alt="Descanso {{ $accommodation->name }}">
                            </a>
                        @endforeach
                    </div>

                    {{-- Botón Derecho --}}
                    <button onclick="document.getElementById('rest-slider').scrollBy({left: 300, behavior: 'smooth'})"
                        class="text-3xl md:text-5xl font-light text-[#0f2d3a] hover:opacity-70 transition select-none hidden sm:block">
                        &gt;
                    </button>
                </div>
            </div>
        @endif

        {{-- 1. PRIMERO: SECCIÓN DE SERVICIOS QUE INCLUYE --}}
        <div id="services-section" class="border-t border-gray-100 pt-8 mt-6 space-y-8 pb-4">
            <h3 class="text-2xl md:text-4xl text-solar-blue font-tenor tracking-wide text-center">
                Servicios que incluye
            </h3>

            <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-y-5 gap-x-12 max-w-7xl mx-auto px-4 md:px-8">
                @foreach ($accommodation->services as $service)
                    <div class="flex items-center gap-4 text-gray-800 font-raleway text-base md:text-lg italic group">
                        <div
                            class="text-xl md:text-2xl text-gray-700 w-8 flex justify-center items-center flex-shrink-0">
                            @if ($service->icon)
                                <i class="{{ $service->icon->class_name }}"></i>
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

    </div> {{-- CERRAMOS MAX-W-7XL TEMPORALMENTE PARA EL FONDO ANCHO TOTAL DEL CALENDARIO --}}

    {{-- 2. SEGUNDO: SECCIÓN CALENDARIO ANCHO TOTAL (W-SCREEN) --}}
    @php
        \Carbon\Carbon::setLocale('es');

        $mesActual = \Carbon\Carbon::now();
        $mesSiguiente = \Carbon\Carbon::now()->addMonth();

        $diasTemporadaMedia = [];
        $diasTemporadaAlta = [];

        if (isset($seasons)) {
            foreach ($seasons as $season) {
                $inicio = \Carbon\Carbon::parse($season->start_date);
                $fin = \App\Models\Season::find($season->id)->end_date;

                while ($inicio->lte($fin)) {
                    $fechaString = $inicio->toDateString();
                    if ($season->type === 'mid') {
                        $diasTemporadaMedia[$fechaString] = true;
                    } elseif ($season->type === 'high') {
                        $diasTemporadaAlta[$fechaString] = true;
                    }
                    $inicio->addDay();
                }
            }
        }
    @endphp

    <div id="calendar-section" class="w-full bg-cover bg-center shadow-inner py-12 my-8 px-4"
        style="background-image: url('{{ asset('page-resources/img/peFondoCalendarios.webp') }}')">

        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">

                @foreach ([$mesActual, $mesSiguiente] as $mesObjeto)
                    @php
                        $primerDiaDelMes = $mesObjeto->copy()->startOfMonth();
                        $totalDiasMes = $mesObjeto->daysInMonth;
                        $diaSemanaInicio = $primerDiaDelMes->dayOfWeekIso - 1;
                        $nombreMes = strtoupper($mesObjeto->isoFormat('MMMM YYYY'));
                    @endphp

                    {{-- Contenedor de Tarjeta de Mes --}}
                    <div
                        class="bg-[#ebdccb]/95 backdrop-blur-xs rounded-[2rem] p-6 shadow-xl flex flex-col items-center">
                        <h3 class="text-lg font-bold text-[#0f2d3a] tracking-widest font-tenor mb-4">
                            {{ $nombreMes }}</h3>

                        <div class="w-full grid grid-cols-7 gap-y-3 text-center text-xs font-bold text-[#0f2d3a]/80">
                            {{-- Días de la semana en Español --}}
                            <span>L</span><span>M</span><span>X</span><span>J</span><span>V</span><span>S</span><span>D</span>

                            <div class="col-span-7 border-b border-[#0f2d3a]/20 my-1"></div>

                            {{-- Espacios vacíos de alineación --}}
                            @for ($i = 0; $i < $diaSemanaInicio; $i++)
                                <div></div>
                            @endfor

                            {{-- Días del mes --}}
                            @for ($dia = 1; $dia <= $totalDiasMes; $dia++)
                                @php
                                    $fechaEvaluar = $mesObjeto->copy()->day($dia)->toDateString();

                                    $esMedia = isset($diasTemporadaMedia[$fechaEvaluar]);
                                    $esAlta = isset($diasTemporadaAlta[$fechaEvaluar]);

                                    $claseDia =
                                        'w-8 h-8 flex items-center justify-center rounded-full mx-auto transition-all text-sm ';

                                    if ($esAlta) {
                                        $claseDia .=
                                            'bg-solar-blue text-white shadow-md font-black ring-2 ring-white/20';
                                    } elseif ($esMedia) {
                                        $claseDia .=
                                            'bg-solar-yellow text-solar-brown shadow-md font-black ring-2 ring-white/20';
                                    } else {
                                        $claseDia .= 'text-solar-brown font-medium hover:bg-[#0f2d3a]/5';
                                    }
                                @endphp

                                <div class="flex items-center justify-center">
                                    <span class="{{ $claseDia }}">
                                        {{ $dia }}
                                    </span>
                                </div>
                            @endfor
                        </div>
                    </div>
                @endforeach

            </div>

            {{-- Leyenda inferior --}}
            <div
                class="flex justify-center flex-wrap gap-6 pt-2 text-xs font-bold font-raleway text-white bg-black/20 backdrop-blur-xs rounded-xl p-3 max-w-md mx-auto mt-8">
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded-full bg-solar-yellow inline-block ring-1 ring-white"></span>
                    <span>Temporada Media</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded-full bg-solar-blue inline-block ring-1 ring-white"></span>
                    <span>Temporada Alta</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded-full bg-[#e4d4c3] border border-white inline-block"></span>
                    <span>Tarifa Base</span>
                </div>
            </div>
        </div>
    </div>

    {{-- REABRIMOS EL MAX-W-7XL PARA EL CONTENEDOR ANCLA FINAL --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ========================================== --}}
        {{-- SECCIÓN: UBICACIÓN Y MAPA DE GOOGLE MAPS --}}
        {{-- ========================================== --}}
        @php
            $locationUrl = $accommodation->locationURL ?? $accommodation->location_url;
        @endphp

        @if (!empty($locationUrl))
            <div id="location-section" class="border-t border-gray-100 pt-8 mt-6 space-y-6 pb-12">
                <h3 class="text-2xl md:text-4xl text-solar-blue font-tenor tracking-wide text-center">
                    Ubicación
                </h3>

                {{-- Marco contenedor del Mapa Embebido --}}
                <div
                    class="w-full h-[350px] md:h-[450px] rounded-3xl overflow-hidden shadow-md border border-gray-100 bg-gray-50">
                    <iframe src="{{ $locationUrl }}" class="w-full h-full border-0" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        @endif

        {{-- CONTENEDOR ANCLA COLOCADO AL FINAL --}}
        <div id="sticky-anchor" class="w-full h-auto"></div>

    </div>

    {{-- CONTENEDOR ANCLA COLOCADO AL FINAL --}}
    <div id="sticky-anchor" class="w-full h-auto"></div>
    </div>

    {{-- BARRA / BOTÓN FLOTANTE "ME INTERESA" --}}
    <div id="interest-sticky-bar"
        class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-gray-100 p-4 shadow-2xl transition-all duration-300 z-40">
        <div
            class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4 bg-amber-50 border border-amber-100 p-4 rounded-2xl">
            <div class="hidden sm:block">
                <p class="text-xs uppercase font-bold text-gray-400 tracking-wider">¿Te gustaría agendar o cotizar?</p>
                <p class="text-sm font-semibold text-solar-brown">Guarda esta propiedad en tu lista de interés para
                    enviarla por WhatsApp.</p>
            </div>
            <button
                onclick="addToInterestList({{ $accommodation->id }}, '{{ addslashes($accommodation->name) }}', '{{ $accommodation->image }}', {{ $accommodation->price }})"
                class="w-full sm:w-auto bg-[#e0691d] hover:bg-[#c95916] text-white font-bold px-8 py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-sm md:text-base">
                ❤️ Me interesa esta propiedad
            </button>
        </div>
    </div>

    {{-- ALERTA FLOTANTE PERSONALIZADA (TOAST) --}}
    <div id="custom-toast"
        class="fixed top-5 right-5 z-50 transform translate-y-[-20px] opacity-0 pointer-events-none transition-all duration-300 ease-out max-w-sm w-full bg-white border border-gray-100 shadow-2xl rounded-2xl p-4 flex items-center gap-3">
        <div id="toast-icon-bg" class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0">
        </div>
        <div class="flex-grow">
            <p id="toast-message" class="text-sm font-semibold text-gray-900 font-raleway"></p>
        </div>
        <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600 text-xs p-1">✕</button>
    </div>

    <!-- Script de GLightbox y Gestión de Scroll/Toast -->
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Inicializar GLightbox
            const lightbox = GLightbox({
                selector: '.glightbox',
                touchNavigation: true,
                loop: true,
                zoomable: true,
                draggable: true
            });

            // 2. LÓGICA DE ACOPLE INTELIGENTE DEBAJO DEL CALENDARIO
            const stickyBar = document.getElementById('interest-sticky-bar');
            const lastSection = document.getElementById('calendar-section');
            const anchor = document.getElementById('sticky-anchor');

            if (stickyBar && lastSection && anchor) {
                window.addEventListener('scroll', () => {
                    const rect = lastSection.getBoundingClientRect();
                    if (rect.bottom <= window.innerHeight) {
                        stickyBar.className =
                            "w-full relative bg-transparent border-none p-0 shadow-none transition-all duration-300 z-10 mt-6 mb-4";
                        anchor.appendChild(stickyBar);
                    } else {
                        stickyBar.className =
                            "fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-gray-100 p-4 shadow-2xl transition-all duration-300 z-40";
                        if (stickyBar.parentElement !== document.body) {
                            document.body.appendChild(stickyBar);
                        }
                    }
                });
            }
        });

        // 3. Funciones del Toast y LocalStorage
        function showToast(message, type = 'success') {
            const toast = document.getElementById('custom-toast');
            const toastMsg = document.getElementById('toast-message');
            const iconBg = document.getElementById('toast-icon-bg');

            toastMsg.innerText = message;

            if (type === 'success') {
                iconBg.className =
                    "w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0 bg-green-50 text-green-600";
                iconBg.innerHTML = "✓";
            } else {
                iconBg.className =
                    "w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0 bg-amber-50 text-amber-600";
                iconBg.innerHTML = "ℹ";
            }

            toast.classList.remove('translate-y-[-20px]', 'opacity-0', 'pointer-events-none');
            setTimeout(hideToast, 3000);
        }

        function hideToast() {
            const toast = document.getElementById('custom-toast');
            if (toast) {
                toast.classList.add('translate-y-[-20px]', 'opacity-0', 'pointer-events-none');
            }
        }

        function addToInterestList(id, name, image, price) {
            let list = JSON.parse(localStorage.getItem('solar_interest_list')) || [];
            const exists = list.some(item => item.id === id);

            if (!exists) {
                list.push({
                    id,
                    name,
                    image,
                    price
                });
                localStorage.setItem('solar_interest_list', JSON.stringify(list));
                showToast(`"${name}" se agregó a tu lista de interés.`, 'success');
            } else {
                showToast("Esta propiedad ya está en tu lista.", 'info');
            }
        }
    </script>
</x-visitor-layout>
