<x-client-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Propiedad: {{ $accommodation->name }}
        </h2>
    </x-slot>



    <x-client.accommodation-sidebar :accommodation="$accommodation">

    </x-client.accommodation-sidebar>


</x-client-layout>
