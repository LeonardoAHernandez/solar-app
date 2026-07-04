<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Nueva Temporada
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-xl p-6 border border-gray-100">
                <form action="{{ route('admin.seasons.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Nombre -->
                        <div>
                            <x-label for="name" value="Nombre de la Temporada" class="font-medium text-gray-700" />
                            <x-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="Ej: Vacaciones de Verano 2026" :value="old('name')" required />
                            <x-input-error for="name" class="mt-2" />
                        </div>

                        <!-- Tipo de Temporada -->
                        <div>
                            <x-label for="type" value="Tipo de Calendario / Tarifa" class="font-medium text-gray-700" />
                            <x-select id="type" name="type" class="mt-1 block w-full" required>
                                <option value="" disabled @selected(!old('type'))>Selecciona el tipo de temporada...</option>
                                <option value="mid" @selected(old('type') == 'mid')>Temporada Media</option>
                                <option value="high" @selected(old('type') == 'high')>Temporada Alta</option>
                            </x-select>
                            <x-input-error for="type" class="mt-2" />
                        </div>

                        <!-- Fechas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-label for="start_date" value="Fecha de Inicio" class="font-medium text-gray-700" />
                                <x-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date')" required />
                                <x-input-error for="start_date" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="end_date" value="Fecha de Fin" class="font-medium text-gray-700" />
                                <x-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="old('end_date')" required />
                                <x-input-error for="end_date" class="mt-2" />
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                            <a href="{{ route('admin.seasons.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                                Cancelar
                            </a>
                            <x-button class="bg-indigo-600 hover:bg-indigo-700">
                                Guardar Temporada
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>