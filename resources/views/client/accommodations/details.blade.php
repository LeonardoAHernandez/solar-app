<x-client-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Propiedad: {{ $accommodation->name }}
        </h2>
    </x-slot>

    <x-client.accommodation-sidebar :accommodation="$accommodation">

        <p class="text-2xl font-semibold">
            Detalles de la Propiedad
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

        <form action="{{ route('client.accommodations.details.store', $accommodation) }}" method="POST">
            @csrf

            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm mb-6">
                <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">
                    Especifica las cantidades para cada detalle (ej. Habitaciones, Camas, Baños)
                </h3>

                {{-- Grid de controles numéricos --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($allDetails as $detail)
                        {{-- Buscamos la cantidad actual en el array, si no existe inicializa en 0 --}}
                        @php
                            $currentQuantity = $currentDetails[$detail->id] ?? 0;
                        @endphp

                        <div class="flex items-center justify-between gap-4 p-3 border border-gray-100 rounded-xl bg-gray-50/50 hover:bg-gray-50 transition">
                            
                            <span class="text-sm font-medium text-gray-700 select-none">
                                {{ $detail->name }}
                            </span>

                            {{-- Input numérico asociado al ID del detalle mediante la clave del array --}}
                            <input type="number" 
                                   name="quantities[{{ $detail->id }}]" 
                                   value="{{ $currentQuantity }}"
                                   min="0"
                                   placeholder="0"
                                   class="w-20 text-center h-9 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm font-semibold">
                        </div>
                    @endforeach
                </div>
                
                <p class="text-xs text-gray-400 mt-4">
                    * Los campos que permanezcan o se configuren en 0 no se guardarán ni se mostrarán en la propiedad.
                </p>
            </div>

            {{-- Botón de envío --}}
            <div class="flex justify-end">
                <x-button type="submit">
                    Guardar Detalles
                </x-button>
            </div>
        </form>

    </x-client.accommodation-sidebar>

</x-client-layout>