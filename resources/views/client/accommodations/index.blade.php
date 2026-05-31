<x-client-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista de propiedades
        </h2>
    </x-slot>

    <x-container class="mt-12">        
        <div class="md:flex md:justify-end">
            <a href="{{ route('client.accommodations.create') }}" class="btn btn-blue block w-full md:w-auto text-center">
                Agregar Propiedad
            </a>
        </div>
    </x-container>
    
</x-client-layout>