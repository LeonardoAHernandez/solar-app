<x-client-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Propiedad: {{ $accommodation->name }}
        </h2>
    </x-slot>

    <x-client.accommodation-sidebar :accommodation="$accommodation">

        <p class="text-2xl font-semibold">
            Etiquetas de la Propiedad
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

        <form action="{{ route('client.accommodations.tags.store', $accommodation) }}" method="POST">
            @csrf

            {{-- Grid donde se renderizan las Categorías (Cards) --}}
            <div class="grid md:grid-cols-2 gap-6 mb-6">

                @foreach ($groupedTags as $category => $tags)
                    {{-- Tarjeta contenedora con la clase 'category-card' para control de JS --}}
                    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm category-card">
                        
                        <h3 class="text-lg font-bold text-gray-700 mb-3 border-b pb-2 capitalize">
                            {{ $category }}
                        </h3>

                        <div class="space-y-2">
                            @foreach ($tags as $tag)
                                <label class="flex items-center gap-3 cursor-pointer p-2 hover:bg-gray-50 rounded-md transition">
                                    <input type="checkbox" 
                                           name="tags[]" 
                                           value="{{ $tag->id }}"
                                           class="tag-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                           {{ in_array($tag->id, $selectedTagIds) ? 'checked' : '' }}>
                                    
                                    <span class="text-sm text-gray-600">
                                        {{ $tag->name }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                    </div>
                @endforeach

            </div>

            {{-- Botón de envío --}}
            <div class="flex justify-end">
                <x-button type="submit">
                    Guardar Etiquetas
                </x-button>
            </div>
        </form>

    </x-client.accommodation-sidebar>

    {{-- Lógica Front-End: Máximo un checkbox por tarjeta de categoría --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Buscamos todas las tarjetas de categoría
            const cards = document.querySelectorAll('.category-card');

            cards.forEach(card => {
                // Obtenemos los checkboxes que pertenecen únicamente a esta tarjeta
                const checkboxes = card.querySelectorAll('.tag-checkbox');

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        if (this.checked) {
                            // Al marcar uno, desmarcamos los demás hermanos de la misma tarjeta
                            checkboxes.forEach(cb => {
                                if (cb !== this) cb.checked = false;
                            });
                        }
                    });
                });
            });
        });
    </script>

</x-client-layout>