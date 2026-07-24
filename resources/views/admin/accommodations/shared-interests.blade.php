<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lista de Selección de Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Encabezado con conteo --}}
            <div
                class="bg-white p-6 rounded-2xl shadow-xs border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Propiedades Consultadas por el Cliente</h3>
                    <p class="text-sm text-gray-500">Se encontraron {{ $accommodations->count() }} propiedad(es)
                        asociadas a esta solicitud.</p>
                </div>
                <a href="{{ route('admin.accommodations.index') }}"
                    class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-4 py-2.5 rounded-xl transition">
                    ← Volver a todas las propiedades
                </a>
            </div>

            {{-- Contenido --}}
            @if ($accommodations->isEmpty())
                <div class="bg-white p-12 text-center rounded-2xl border border-dashed border-gray-200">
                    <p class="text-gray-500">No se encontraron propiedades válidas para los IDs especificados.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($accommodations as $accommodation)
                        <div
                            class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-xs hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                {{-- Imagen (Aprovechamos el Accessor $accommodation->image del Modelo) --}}
                                <div class="aspect-video w-full bg-gray-100 overflow-hidden relative">
                                    <img src="{{ $accommodation->image }}" class="w-full h-full object-cover"
                                        alt="{{ $accommodation->name }}">

                                    <span
                                        class="absolute top-3 left-3 bg-black/60 backdrop-blur-xs text-white text-[10px] font-bold px-2.5 py-1 rounded-md">
                                        ID: {{ $accommodation->id }}
                                    </span>
                                </div>

                                {{-- Detalle de la propiedad --}}
                                <div class="p-5 space-y-2">
                                    <h4 class="font-bold text-gray-900 text-lg">{{ $accommodation->name }}</h4>
                                    <p class="text-xs text-gray-500 line-clamp-2">{{ $accommodation->description }}</p>
                                </div>
                            </div>

                            {{-- Footer Tarjeta --}}
                            <div class="p-5 border-t border-gray-50 flex items-center justify-between bg-gray-50/50">
                                {{-- Manejo correcto de Enum casteado en el Modelo --}}
                                <span class="text-xs font-bold text-gray-400 uppercase">
                                    Estado: {{ $accommodation->status->label() }}
                                </span>

                                <a href="{{ route('admin.accommodations.edit', $accommodation) }}"
                                    class="text-xs bg-solar-brown hover:opacity-90 text-white font-bold px-3.5 py-2 rounded-lg transition">
                                    Editar
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-admin-layout>
