<x-client-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista de propiedades
        </h2>
    </x-slot>

    <x-container class="mt-12">        
        <div class="md:flex md:justify-end mb-6">
            <a href="{{ route('client.accommodations.create') }}" class="btn btn-blue block w-full md:w-auto text-center">
                Agregar Propiedad
            </a>
        </div>

        <ul>
            @forelse ($accommodations as $accommodation)

                <li class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <a href="{{ route('client.accommodations.edit', $accommodation) }}" class="md:flex">
                        <figure class="flex-shrink-0">
                            <img src="{{ $accommodation->image }}"
                                class="w-full md:w-36 aspect-video md:aspect-square object-cover object-center" alt="{{ $accommodation->name }}">
                        </figure>

                        <div class="flex-1">
                            <div class="py-4 px-8">

                                <div class="grid md:grid-cols-9 gap-4">
                                    <div class="md:col-span-2">
                                        <h1>{{ $accommodation->name }}</h1>

                                        @switch($accommodation->status->name)
                                            @case('BORRADOR')
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm">
                                                    {{ $accommodation->status->name }}
                                                </span>
                                                @break
                                            @case('ACTIVO')
                                                <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm">
                                                    {{ $accommodation->status->name }}
                                                </span>
                                                @break
                                            @case('INACTIVO')
                                                <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm">
                                                    {{ $accommodation->status->name }}
                                                </span>
                                                @break
                                            @default
                                                
                                        @endswitch
                                    </div>
                                    
                                    <div class="hidden md:block col-span-3">
                                        <p class="text-sm">
                                            {{ $accommodation->summary }}
                                        </p>
                                    </div>
    
                                    <div class="hidden md:block col-span-2">
                                        <p class="text-sm font-bold">
                                            Precio:
                                        </p>
                                        
                                        <p class="mb-1 text-sm">
                                            {{ $accommodation->price }} MXN/Noche
                                        </p>
                                        
                                        <p class="text-sm font-bold">
                                            Capacidad:
                                        </p>

                                        <p class="text-sm">
                                            {{ $accommodation->capacity }} personas
                                        </p>

                                    </div>
    
                                    
                                    <div class="hidden md:block col-span-2">
    
                                        <div class="flex justify-end">
                                            <p class="mr-3">5</p>

                                            <ul class="text-xs space-x-1 flex items-center">
                                                <i class="fa-solid fa-star text-yellow-400"></i>
                                                <i class="fa-solid fa-star text-yellow-400"></i>
                                                <i class="fa-solid fa-star text-yellow-400"></i>
                                                <i class="fa-solid fa-star text-yellow-400"></i>
                                                <i class="fa-solid fa-star text-yellow-400"></i>
                                            </ul>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </a>
                </li>

            @empty
                <li class="bg-white rounded-lg shadow-lg p-6">

                    <div class="flex justify-between items-center">
                        <p>
                            Salta a la creación de una propiedad
                        </p>

                        <a href="{{ route('client.accommodations.create') }}" class="btn btn-blue">
                            Agrega una propiedad
                        </a>
                    </div>

                </li>

            @endforelse
        </ul>

    </x-container>
    
</x-client-layout>