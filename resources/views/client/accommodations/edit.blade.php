<x-client-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Propiedad: {{ $accommodation->name }}
        </h2>
    </x-slot>

    <x-client.accommodation-sidebar :accommodation="$accommodation">

        <form action="{{ route('client.accommodations.update', $accommodation) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <p class="text-2xl font-semibold">
                Información General
            </p>

            <hr class="mt-2 mb-6">

            <x-validation-errors class="mb-4" />

            <div class="mb-4">
                <x-label for="name" class="mb-1" value="Nombre de la Propiedad" />
                <x-input name="name" class="w-full" value="{{ old('name', $accommodation->name) }}" />
            </div>

            @empty($accommodation->published_at)
                <div class="mb-4">
                    <x-label for="slug" class="mb-1" value="Slug de la propiedad" />
                    <x-input name="slug" class="w-full" value="{{ old('slug', $accommodation->slug) }}" />
                </div>
            @endempty

            <div class="mb-4">
                <x-label for="summary" class="mb-1" value="Resumen de la propiedad" />
                <x-textarea name="summary" class="w-full">
                    {{ old('summary', $accommodation->summary) }}
                </x-textarea>
            </div>

            <div class="mb-4 ckeditor">
                <x-label for="description" class="mb-1" value="Descripción de la propiedad" />
                <x-textarea name="description" class="w-full" id="editor">
                    {{ old('description', $accommodation->description) }}
                </x-textarea>
            </div>

            <div class="mb-4">
                <x-label value="Estatus de la propiedad" class="mb-1" for="status" />
                <x-input placeholder="Estatus" class="w-full" name="status"
                    value="{{ old('status', $accommodation->status) }}" />
            </div>

            <div class="mb-4">
                <x-label value="Capacidad de la propiedad" class="mb-1" for="capacity" />
                <x-input placeholder="Capacidad" class="w-full" name="capacity"
                    value="{{ old('capacity', $accommodation->capacity) }}" />
            </div>

            <div class="mb-4">
                <x-label value="Precio por noche" class="mb-1" for="price" />
                <x-input placeholder="Precio por noche" class="w-full" name="price"
                    value="{{ old('price', $accommodation->price) }}" />
            </div>

            <div class="mb-4">
                <x-label value="Ubicación (URL)" class="mb-1" for="locationURL" />
                <x-input placeholder="URL de la ubicacion" class="w-full" name="locationURL"
                    value="{{ old('locationURL', $accommodation->locationURL) }}" />
            </div>

            <div class="flex md:justify-end mt-4">
                <x-button>
                    Guardar cambios
                </x-button>
            </div>

            {{-- <div>
                <p class="text-2xl font-semibold mb-2">
                    Imagen de la propiedad
                </p>

                <div class="grid md:grid-cols-2 gap-4">

                    <figure>
                        <img src="{{ $accommodation->image }}" alt="{{ $accommodation->name }}" id="imgPreview"
                            class="w-full aspect-video object-cover object-center">
                    </figure>

                    <div>
                        <p class="mb-2">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ullam
                            similique est tempora repellendus natus maxime quam placeat eius dolore enim
                            odio suscipit.</p>

                        <label>

                            <span class="btn btn-blue md:hidden cursor-pointer">
                                Selecciona una imagen
                            </span>

                            <input class="hidden md:block" type="file" accept="image/*" name="image"
                                onchange="preview_image(event, '#imgPreview')">
                        </label>

                        <div class="flex md:justify-end mt-4">
                            <x-button>
                                Guardar cambios
                            </x-button>
                        </div>
                    </div>

                </div>

            </div> --}}


        </form>

    </x-client.accommodation-sidebar>

    @push('js')
        <script src="{{ asset('vendor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>

        <script>
            ClassicEditor
                .create(document.querySelector('#editor'))
                .catch(error => {
                    console.error(error);
                });
        </script>
    @endpush

</x-client-layout>
