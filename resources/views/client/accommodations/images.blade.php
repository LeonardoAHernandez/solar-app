<x-client-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Propiedad: {{ $accommodation->name }}
        </h2>
    </x-slot>



    <x-client.accommodation-sidebar :accommodation="$accommodation">

        <p class="text-2xl font-semibold">
            Imágenes de la Propiedad
        </p>

        <hr class="mt-2 mb-6">

        <x-validation-errors class="mb-4" />

        <form action="{{ route('client.images.store') }}" method="POST" enctype="multipart/form-data" class="mb-6">
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

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            @foreach ($images as $image)
                <div
                    class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm relative group flex flex-col justify-between">

                    {{-- Área del archivo visual --}}
                    <div class="relative">
                        <figure>
                            <img src="{{ $image->image_for_src }}" alt="Imagen {{ $image->id }}"
                                class="w-full aspect-video object-cover object-center">
                        </figure>

                        {{-- BADGE: Indicador de Tipo de imagen en la esquina superior izquierda --}}
                        <div class="absolute top-2 left-2">
                            @if ($image->type === 'principal')
                                <span
                                    class="px-2.5 py-1 text-xs font-bold bg-blue-600 text-white rounded-full shadow-sm uppercase tracking-wider">
                                    📌 Principal
                                </span>
                            @else
                                <span
                                    class="px-2.5 py-1 text-xs font-bold bg-gray-700/80 text-white rounded-full shadow-sm uppercase tracking-wider backdrop-blur-xs">
                                    🖼️ Galería
                                </span>
                            @endif
                        </div>

                        {{-- Botón de eliminar (Esquina superior derecha) --}}
                        <form action="{{ route('client.images.destroy', $image) }}" method="POST"
                            class="absolute top-2 right-2 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                            onsubmit="return confirm('¿Eliminar esta imagen por completo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white p-1.5 rounded-full shadow-md transition"
                                title="Eliminar Imagen">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>

                    {{-- PANEL INFERIOR: Gestión de posición y cambio de tipo --}}
                    <div class="p-3 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-2">
                        @if ($image->type === 'principal')
                            <p class="text-xs text-gray-400 italic">Esta imagen se muestra en las búsquedas principales.
                            </p>
                        @else
                            {{-- Formulario rápido para actualizar Posición --}}
                            <form action="{{ route('client.images.update', $image) }}" method="POST"
                                class="flex items-center gap-1.5">
                                @csrf
                                @method('PUT')
                                {{-- Mantenemos el tipo actual --}}
                                <input type="hidden" name="type" value="galeria">

                                <label class="text-xs font-semibold text-gray-500">Posición:</label>
                                <input type="number" name="position" value="{{ $image->position }}" min="1"
                                    class="w-14 h-7 text-center border border-gray-300 rounded-md text-xs font-bold focus:ring-blue-500">

                                <button type="submit" class="p-1 text-blue-600 hover:bg-blue-50 rounded transition"
                                    title="Guardar Posición">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </form>

                            {{-- Formulario rápido para cambiar a Principal --}}
                            <form action="{{ route('client.images.update', $image) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="type" value="principal">
                                <input type="hidden" name="position" value="0">

                                <button type="submit"
                                    class="inline-flex items-center px-2 py-1 bg-white border border-gray-300 rounded-md text-[11px] font-bold text-gray-700 uppercase tracking-wider hover:bg-gray-50 shadow-xs transition">
                                    ⭐ Portada
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            @endforeach

        </div>

    </x-client.accommodation-sidebar>


</x-client-layout>
