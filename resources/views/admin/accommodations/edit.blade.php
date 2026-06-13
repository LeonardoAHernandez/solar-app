@php
    $optionsCategory = ['Para vacacionar', 'Para desconectarse', 'Vida urbana', 'CoWorking'];
@endphp

<x-admin-layout>

    <x-slot name="header">
        <x-admin.header-edit-accommodation :accommodation="$accommodation" />
    </x-slot>

    <x-admin.accommodation-sidebar :accommodation="$accommodation">

        <form action="{{ route('admin.accommodations.update', $accommodation) }}" method="POST"
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
                <x-label value="Categoría de la propiedad" class="mb-1" for="category" />
                <x-select name="category" class="w-full">
                    @foreach ($optionsCategory as $category)
                        <option value="{{ $category }}" @selected(old('category', $accommodation->category) == $category)>
                            {{ $category }}
                        </option>
                    @endforeach
                </x-select>
            </div>

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

            {{-- <div class="mb-4">
                <x-label value="Estatus de la propiedad" class="mb-1" for="status" />
                <x-input placeholder="Estatus" class="w-full" name="status"
                    value="{{ old('status', $accommodation->status) }}" />
            </div> --}}

            <div class="mb-4">
                <x-label value="Capacidad de la propiedad" class="mb-1" for="capacity" />
                <x-input placeholder="1, 2, 3, ..." class="w-full" name="capacity"
                    value="{{ old('capacity', $accommodation->capacity) }}" />
            </div>

            {{-- <div class="mb-4">
                <x-label value="Precio por noche" class="mb-1" for="price" />
                <x-input placeholder="2000, 2500, ..." class="w-full" name="price"
                    value="{{ old('price', $accommodation->price) }}" />
            </div> --}}

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <x-label value="Precio por noche" class="mb-1" for="price" />
                    <x-input placeholder="2000, 2500, ..." class="w-full" name="price"
                        value="{{ old('price', $accommodation->price) }}" />
                </div>

                <div>
                    <x-label value="Precio en temporada alta" class="mb-1" for="price_highseason" />
                    <x-input placeholder="2500, 3000, ..." class="w-full" name="price_highseason"
                        value="{{ old('price_highseason', $accommodation->price_highseason) }}" />
                </div>
            </div>

            <div class="mb-4">
                <x-label value="Ubicación (URL)" class="mb-1" for="locationURL" />
                <x-input placeholder="https://www.google.com/maps..." class="w-full" name="locationURL"
                    value="{{ old('locationURL', $accommodation->locationURL) }}" />
            </div>

            <div class="flex md:justify-end mt-4">
                <x-button>
                    Guardar cambios
                </x-button>
            </div>

        </form>

    </x-admin.accommodation-sidebar>

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

</x-admin-layout>
