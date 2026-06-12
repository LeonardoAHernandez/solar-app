<x-admin-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Agregar Propiedad
        </h2>
    </x-slot>

    <x-container class="mt-12" width="4xl">

        <div class="bg-white rounded-lg shadow-lg p-6">

            <form action="{{ route('admin.accommodations.store') }}" method="POST">

                @csrf

                <h2 class="text-xl uppercase text-center mb-4">
                    Completa esta información para agregar una propiedad
                </h2>

                <x-validation-errors class="mb-4"></x-validation-errors>

                <div class="mb-4">
                    <x-label value="Nombre de la propiedad" class="mb-1" 
                        for="name" />
                    <x-input placeholder="Nombre de la propiedad" class="w-full" 
                        name="name" value="{{ old('name') }}"
                        oninput="string_to_slug(this.value, '#slug')"/>
                </div>
                
                <div class="mb-4">
                    <x-label value="Slug de la propiedad" class="mb-1" 
                        for="slug" />
                    <x-input placeholder="Slug" class="w-full" id="slug"
                        name="slug" value="{{ old('slug') }}"/>
                </div>
                
                <div class="mb-4">
                    <x-label value="Resumen de la propiedad" class="mb-1" 
                        for="summary" />
                    <x-input placeholder="Resumen de la propiedad" class="w-full" 
                        name="summary" value="{{ old('summary') }}"/>
                </div>
                
                <div class="mb-4">
                    <x-label value="Descripción de la propiedad" class="mb-1" 
                        for="description" />
                    <x-input placeholder="Descripción de la propiedad" class="w-full" 
                        name="description" value="{{ old('description') }}"/>
                </div>
                
                <div class="mb-4">
                    <x-label value="Estatus de la propiedad" class="mb-1" 
                        for="status" />
                    <x-input placeholder="Estatus" class="w-full" 
                        name="status" value="{{ old('status') }}"/>
                </div>
                
                <div class="mb-4">
                    <x-label value="Capacidad de la propiedad" class="mb-1" 
                        for="capacity" />
                    <x-input placeholder="Capacidad" class="w-full" 
                        name="capacity" value="{{ old('capacity') }}"/>
                </div>
                
                <div class="mb-4">
                    <x-label value="Precio por noche" class="mb-1" 
                        for="price" />
                    <x-input placeholder="Precio por noche" class="w-full" 
                        name="price" value="{{ old('price') }}"/>
                </div>
                
                <div class="mb-4">
                    <x-label value="Ubicación (URL)" class="mb-1" 
                        for="locationURL" />
                    <x-input placeholder="URL de la ubicacion" class="w-full" 
                        name="locationURL" value="{{ old('locationURL') }}"/>
                </div>

                {{-- <div class="grid grid-cols-3 gap-4 mb-4">

                    <div>
                        <x-label value="Detalles de la propiedad" class="mb-1" 
                            for="detail_id" />

                        <x-select name="service_id" class="w-full">
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}"
                                    @selected(old('service_id') == $service->id)>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>

                    <div>
                        <x-label value="Tags de la propiedad" class="mb-1" 
                            for="tag_id" />

                        <x-select name="tag_id" class="w-full">
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}"
                                    @selected(old('tag_id') == $tag->id)>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                    
                    <div>
                        <x-label value="Servicios de la propiedad" class="mb-1" 
                            for="service_id" />

                        <x-select name="service_id" class="w-full">
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}"
                                    @selected(old('service_id') == $service->id)>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>

                </div> --}}

                <div class="flex justify-end">
                    <x-button>
                        Agregar Propiedad
                    </x-button>
                </div>

            </form>

        </div>

    </x-container>

</x-admin-layout>
