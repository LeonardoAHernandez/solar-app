<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Nueva Temporada Alta
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('admin.seasons.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <x-label for="name" value="Nombre de la Temporada" />
                            <x-input id="name" name="name" type="text" class="mt-1 block w-full"
                                placeholder="Ej: Vacaciones de Verano 2026" :value="old('name')" required />
                            <x-input-error for="name" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-label for="start_date" value="Fecha de Inicio" />
                                <x-input id="start_date" name="start_date" type="date" class="mt-1 block w-full"
                                    :value="old('start_date')" required />
                                <x-input-error for="start_date" class="mt-2" />
                            </div>

                            <div>
                                <x-label for="end_date" value="Fecha de Fin" />
                                <x-input id="end_date" name="end_date" type="date" class="mt-1 block w-full"
                                    :value="old('end_date')" required />
                                <x-input-error for="end_date" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 border-t pt-4">
                            <a href="{{ route('admin.seasons.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <x-button>
                                Guardar Temporada
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
