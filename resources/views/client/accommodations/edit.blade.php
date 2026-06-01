<x-client-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Propiedad: {{ $accommodation->name }}
        </h2>
    </x-slot>

    <x-container class="py-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            <aside class="col-span-1">

                <h1 class="text-xl font-semibold mb-4">Edición de Propiedad</h1>

                <nav>
                    <ul>
                        <li class="border-l-4 border-indigo-500 pl-3">
                            <a
                                href="
                            {{ route('client.accommodations.edit', $accommodation) }}">
                                Información General
                            </a>
                        </li>
                    </ul>
                </nav>

            </aside>

            <div class="col-span-1 lg:col-span-4">

                <div class="card">

                    <form action="{{ route('client.accommodations.update', $accommodation) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <p class="text-2xl font-semibold">
                            Información General
                        </p>

                        <hr class="mt-2 mb-6">

                        <x-validation-errors />

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

                        <div>
                            <p class="text-2xl font-semibold mb-2">
                                Imagen de la propiedad
                            </p>

                            <div class="grid md:grid-cols-2 gap-4">

                                <figure>
                                    <img src="{{ $accommodation->image }}" alt="{{ $accommodation->name }}" class="w-full aspect-video object-cover object-center">
                                </figure>

                                <div>
                                    <p class="mb-2">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ullam similique est tempora repellendus natus maxime quam placeat eius dolore enim odio suscipit.</p>
                                    
                                    <label>

                                        <span class="btn btn-blue md:hidden cursor-pointer">
                                            Selecciona una imagen
                                        </span>

                                        <input class="hidden md:block" 
                                            type="file" accept="image/*"
                                            name="image">
                                    </label>
                                    
                                    <div class="flex md:justify-end mt-4">
                                        <x-button>
                                            Guardar cambios
                                        </x-button>
                                    </div>
                                </div>

                            </div>

                        </div>


                    </form>

                </div>

            </div>

        </div>
    </x-container>


</x-client-layout>
