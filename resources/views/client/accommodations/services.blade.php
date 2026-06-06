<x-client-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Propiedad: {{ $accommodation->name }}
        </h2>
    </x-slot>

    <x-client.accommodation-sidebar :accommodation="$accommodation">

        <p class="text-2xl font-semibold">
            Servicios de la Propiedad
        </p>

        <hr class="mt-2 mb-6">

        {{-- Alertas del Sistema --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('client.accommodations.services.store', $accommodation) }}" method="POST">
            @csrf

            {{-- Caja contenedora de la lista de servicios --}}
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm mb-6">
                <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">
                    Selecciona los servicios disponibles en el alojamiento
                </h3>

                {{-- Grid adaptativo para mostrar los checkboxes organizados en columnas --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($services as $service)
                        <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-100 hover:border-blue-200 hover:bg-blue-50/30 rounded-xl transition duration-150">
                            <input type="checkbox" 
                                   name="services[]" 
                                   value="{{ $service->id }}"
                                   class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                   {{ in_array($service->id, $selectedServiceIds) ? 'checked' : '' }}>
                            
                            <span class="text-sm font-medium text-gray-700 select-none">
                                {{ $service->name }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Botón de envío --}}
            <div class="flex justify-end">
                <x-button type="submit">
                    Guardar Servicios
                </x-button>
            </div>
        </form>

    </x-client.accommodation-sidebar>

</x-client-layout>