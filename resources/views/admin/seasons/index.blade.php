<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Temporadas Dinámicas
            </h2>
            <a href="{{ route('admin.seasons.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs uppercase tracking-widest rounded-md shadow-xs transition">
                + Nueva Temporada
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl p-6 border border-gray-100">
                @if ($seasons->isEmpty())
                    <p class="text-gray-500 text-center py-4">No hay temporadas configuradas. El sistema usará los precios estándar (Temporada Baja).</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Fecha Inicio</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Fecha Fin</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Estado Actual</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                @foreach ($seasons as $season)
                                    @php
                                        $todayStr = now()->toDateString();
                                        $isCurrent = $todayStr >= $season->start_date->toDateString() && $todayStr <= $season->end_date->toDateString();
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $season->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($season->type === 'high')
                                                <span class="px-2.5 py-0.5 text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 rounded-md uppercase">Alta</span>
                                            @else
                                                <span class="px-2.5 py-0.5 text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 rounded-md uppercase">Media</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $season->start_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $season->end_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($isCurrent)
                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 animate-pulse">Activa</span>
                                            @else
                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactiva</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium flex justify-end gap-4">
                                            <a href="{{ route('admin.seasons.edit', $season) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                            <form action="{{ route('admin.seasons.destroy', $season) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta temporada?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>