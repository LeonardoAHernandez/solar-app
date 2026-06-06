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

        {{-- <div class="grid md:grid-cols-2 gap-4">

            @foreach ($images as $image)
                <figure>
                    <img src="{{ $image->image_for_src }}" alt="Imagen {{ $image->id }}" id="imgPreview"
                        class="w-full aspect-video object-cover object-center">
                </figure>
            @endforeach

        </div> --}}

        <div class="grid md:grid-cols-2 gap-4">

            @foreach ($images as $image)
                {{-- Añadimos 'relative group' para controlar la posición del botón --}}
                <div class="relative group">

                    <figure>
                        <img src="{{ $image->image_for_src }}" alt="Imagen {{ $image->id }}"
                            class="w-full aspect-video object-cover object-center rounded-lg shadow-sm">
                    </figure>

                    {{-- Formulario para eliminar la imagen --}}
                    <form action="{{ route('client.images.destroy', $image) }}" method="POST"
                        class="absolute top-2 right-2"
                        onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta imagen? Esta acción no se puede deshacer.');">
                        @csrf
                        @method('DELETE') {{-- Directiva obligatoria para indicarle a Laravel que es un borrado --}}

                        <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-full shadow-md transition duration-200 focus:outline-none"
                            title="Eliminar Imagen">
                            {{-- Icono de bote de basura (SVG integrado) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>

                </div>
            @endforeach

        </div>

    </x-client.accommodation-sidebar>


</x-client-layout>
