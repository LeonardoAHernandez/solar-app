<x-admin-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Propiedad: {{ $accommodation->name }}
        </h2>
    </x-slot>

    <x-admin.accommodation-sidebar :accommodation="$accommodation">

        <p class="text-2xl font-semibold">
            Imágenes de la Propiedad
        </p>

        <hr class="mt-2 mb-6">

        <x-validation-errors class="mb-4" />

        {{-- FORMULARIO PARA SUBIR NUEVAS IMÁGENES (Vuelto a agregar) --}}
        <form action="{{ route('admin.images.store') }}" method="POST" enctype="multipart/form-data" class="mb-6">
            @csrf

            <input type="hidden" name="accommodation_id" value="{{ $accommodation->id }}">

            <div class="flex justify-around items-center gap-4 mb-4">
                <label>
                    <span class="btn btn-blue md:hidden cursor-pointer">
                        Selecciona una imagen
                    </span>

                    <input class="hidden md:block" type="file" name="images[]" id="images" multiple
                        accept="image/*" required>
                </label>

                <div class="flex md:justify-end">
                    <x-button type="submit">
                        Subir imágenes
                    </x-button>
                </div>
            </div>
        </form>

        {{-- Formulario para subir nuevas imágenes --}}
        <form id="form-orden-global" action="{{ route('admin.images.updateOrder') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">

                @foreach ($images as $image)
                    <div
                        class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm relative group flex flex-col justify-between">

                        {{-- Área del archivo visual --}}
                        <div class="relative">
                            <figure>
                                <img src="{{ $image->image_for_src }}" alt="Imagen {{ $image->id }}"
                                    class="w-full aspect-video object-cover object-center">
                            </figure>

                            {{-- BADGE: Indicador de Tipo de imagen --}}
                            <div class="absolute top-2 left-2">
                                @if ($image->type === 'principal')
                                    <span
                                        class="px-2.5 py-1 text-xs font-bold bg-blue-600 text-white rounded-full shadow-sm uppercase tracking-wider">
                                        📌 Principal (Posición {{ $image->position }})
                                    </span>
                                @else
                                    <span
                                        class="px-2.5 py-1 text-xs font-bold bg-gray-700/80 text-white rounded-full shadow-sm uppercase tracking-wider backdrop-blur-xs">
                                        🖼️ Galería (Posición {{ $image->position }})
                                    </span>
                                @endif
                            </div>

                            {{-- NUEVO BOTÓN VISUAL DE ELIMINAR --}}
                            {{-- Cambiado a tipo 'button' y con un disparador 'onclick' para el formulario oculto --}}
                            <div
                                class="absolute top-2 right-2 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <button type="button"
                                    onclick="if(confirm('¿Eliminar esta imagen por completo?')) { document.getElementById('form-eliminar-{{ $image->id }}').submit(); }"
                                    class="bg-red-600 hover:bg-red-700 text-white p-1.5 rounded-full shadow-md transition cursor-pointer"
                                    title="Eliminar Imagen">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- PANEL INFERIOR --}}
                        <div class="p-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-1.5">
                                <label class="text-xs font-semibold text-gray-500">Posición:</label>
                                <input type="number" name="positions[{{ $image->id }}]"
                                    value="{{ $image->position ?? $loop->iteration }}" min="1"
                                    class="js-input-posicion w-14 h-7 text-center border border-gray-300 rounded-md text-xs font-bold focus:ring-blue-500">
                            </div>

                            @if ($image->position !== 1)
                                <button type="button"
                                    onclick="document.getElementById('form-portada-{{ $image->id }}').submit();"
                                    class="inline-flex items-center px-2 py-1 bg-white border border-gray-300 rounded-md text-[11px] font-bold text-gray-700 uppercase tracking-wider hover:bg-gray-50 shadow-xs transition">
                                    ⭐ Portada
                                </button>
                            @endif
                        </div>

                    </div>
                @endforeach

            </div>

            @if ($images->isNotEmpty())
                <div class="flex justify-end bg-white p-4 border border-gray-200 rounded-xl shadow-xs">
                    <x-button type="submit" class="bg-green-600 hover:bg-green-700 focus:ring-green-500">
                        💾 Guardar Orden Completo
                    </x-button>
                </div>
            @endif
        </form>


        {{-- ========================================== --}}
        {{-- FORMULARIOS OCULTOS (FUERA DEL FORM PRINCIPAL) --}}
        {{-- ========================================== --}}

        @foreach ($images as $image)
            {{-- Formularios de portada rápida --}}
            <form id="form-portada-{{ $image->id }}" action="{{ route('admin.images.update', $image) }}"
                method="POST" class="hidden">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="principal">
                <input type="hidden" name="position" value="1">
            </form>

            {{-- NUEVO FORMULARIO OCULTO DE ELIMINACIÓN --}}
            <form id="form-eliminar-{{ $image->id }}" action="{{ route('admin.images.destroy', $image) }}"
                method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach


    </x-admin.accommodation-sidebar>

</x-admin-layout>

{{-- SCRIPT DE VALIDACIÓN ANTES DE ENVIAR --}}
<script>
    document.getElementById('form-orden-global').addEventListener('submit', function(event) {
        // Seleccionamos todos los inputs numéricos de posición
        const inputs = document.querySelectorAll('.js-input-posicion');
        const posiciones = [];
        let tieneDuplicados = false;
        let numeroDuplicado = null;

        inputs.forEach(input => {
            const valor = parseInt(input.value);

            // Si el número ya existe en nuestro array, encontramos un duplicado
            if (posiciones.includes(valor)) {
                tieneDuplicados = true;
                numeroDuplicado = valor;
            }
            posiciones.push(valor);
        });

        // Si se detectó algún repetido, frenamos el envío del formulario
        if (tieneDuplicados) {
            event.preventDefault(); // Cancela el envío a Laravel
            alert(
                `⚠️ No puedes guardar el orden: Has asignado la posición "${numeroDuplicado}" a más de una imagen. Por favor, asegúrate de que cada imagen tenga un número único.`);
        }
    });
</script>
