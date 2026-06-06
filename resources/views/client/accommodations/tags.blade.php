<x-client-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Propiedad: {{ $accommodation->name }}
        </h2>
    </x-slot>

    <x-client.accommodation-sidebar :accommodation="$accommodation">

        {{-- Encabezado con Botón de Administración --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-2">
            <div>
                <p class="text-2xl font-semibold">Etiquetas de la Propiedad</p>
                <p class="text-sm text-gray-500">Selecciona las etiquetas correspondientes. Máximo una por categoría.</p>
            </div>
            <div>
                <button type="button" id="btn-open-modal" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    ⚙️ Administrar Catálogo
                </button>
            </div>
        </div>

        <hr class="mb-6">

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

        {{-- FORMULARIO PRINCIPAL: Cards de Categorías --}}
        <form action="{{ route('client.accommodations.tags.store', $accommodation) }}" method="POST">
            @csrf

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                @forelse ($groupedTags as $category => $tags)
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
                @empty
                    <div class="col-span-2 text-center p-8 bg-gray-50 border border-dashed rounded-xl text-gray-400">
                        No hay etiquetas en el catálogo. Haz clic en "Administrar Catálogo" para agregar la primera.
                    </div>
                @endforelse
            </div>

            @if($groupedTags->isNotEmpty())
                <div class="flex justify-end">
                    <x-button type="submit">Guardar Etiquetas</x-button>
                </div>
            @endif
        </form>


        {{-- ========================================== --}}
        {{-- MODAL FLOTANTE DE ADMINISTRACIÓN DE CATÁLOGO --}}
        {{-- ========================================== --}}
        <div id="catalog-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div id="modal-overlay" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:min-h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center border-b pb-3 mb-4">
                            <h3 class="text-lg font-bold text-gray-900" id="modal-title">
                                Administrar Catálogo de Etiquetas
                            </h3>
                            <button type="button" id="btn-close-modal-x" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
                        </div>

                        {{-- Formulario Integrado (Nombre + Categoría Dinámica) --}}
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6">
                            <h4 id="form-action-title" class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Agregar Nueva Etiqueta</h4>
                            
                            <form action="{{ route('client.tags.manage') }}" method="POST" id="manage-tag-form" class="space-y-3">
                                @csrf
                                <input type="hidden" name="tag_id" id="tag_id" value="">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    {{-- Campo Nombre --}}
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Nombre de Etiqueta</label>
                                        <input type="text" name="name" id="tag_name" required placeholder="Ej. Norte, Rural, Premium"
                                               class="w-full h-10 border border-gray-300 rounded-lg px-3 focus:ring-blue-500 text-sm">
                                    </div>

                                    {{-- Campo Categoría (Doble Control) --}}
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Categoría</label>
                                        
                                        <select id="tag_category_select" class="w-full h-10 border border-gray-300 rounded-lg px-3 focus:ring-blue-500 text-sm capitalize">
                                            <option value="" disabled selected>-- Selecciona una --</option>
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat }}">{{ $cat }}</option>
                                            @endforeach
                                            <option value="NEW_CATEGORY" class="text-blue-600 font-semibold">+ Crear nueva categoría...</option>
                                        </select>

                                        <input type="text" 
                                               name="category" 
                                               id="tag_category_input" 
                                               placeholder="Escribe la nueva categoría"
                                               class="w-full h-10 border border-gray-300 rounded-lg px-3 focus:ring-blue-500 text-sm mt-2 hidden capitalize">
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center gap-2 pt-1">
                                    <button type="button" id="btn-cancel-edit" class="text-xs text-red-500 hover:underline hidden">
                                        ❌ Cancelar edición
                                    </button>
                                    <x-button type="submit" id="btn-submit" class="ml-auto">
                                        <span id="btn-submit-text">Agregar</span>
                                    </x-button>
                                </div>
                            </form>
                        </div>

                        {{-- Lista de Etiquetas Actuales del Catálogo Maestro --}}
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Etiquetas Disponibles Actuales</h4>
                        <div class="max-h-44 overflow-y-auto border border-gray-100 rounded-lg divide-y divide-gray-100 px-3 bg-white">
                            @forelse ($groupedTags as $category => $tags)
                                @foreach ($tags as $tag)
                                    <div class="flex items-center justify-between py-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-gray-700 font-medium">{{ $tag->name }}</span>
                                            <span class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded font-mono uppercase">{{ $tag->category }}</span>
                                        </div>
                                        <button type="button" 
                                                onclick="setEditMode({{ $tag->id }}, '{{ addslashes($tag->name) }}', '{{ addslashes($tag->category) }}')"
                                                class="text-xs bg-gray-100 hover:bg-blue-100 text-gray-600 hover:text-blue-700 px-2.5 py-1 rounded-md font-medium transition">
                                            Editar
                                        </button>
                                    </div>
                                @endforeach
                            @empty
                                <div class="text-center py-4 text-sm text-gray-400">No hay etiquetas registradas.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                        <button type="button" id="btn-close-modal-footer" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cerrar
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </x-client.accommodation-sidebar>

    {{-- Lógica JavaScript para control del Modal, Formulario y Checkboxes Únicos --}}
    <script>
        // Elementos del Modal
        const modal = document.getElementById('catalog-modal');
        const btnOpenModal = document.getElementById('btn-open-modal');
        const btnCloseX = document.getElementById('btn-close-modal-x');
        const btnCloseFooter = document.getElementById('btn-close-modal-footer');
        const overlay = document.getElementById('modal-overlay');

        // Elementos del Formulario Dinámico
        const formActionTitle = document.getElementById('form-action-title');
        const tagIdInput = document.getElementById('tag_id');
        const tagNameInput = document.getElementById('tag_name');
        const tagCategorySelect = document.getElementById('tag_category_select');
        const tagCategoryInput = document.getElementById('tag_category_input');
        const btnSubmitText = document.getElementById('btn-submit-text');
        const btnCancelEdit = document.getElementById('btn-cancel-edit');

        // --- MANEJO DEL MODAL ---
        function openModal() { modal.classList.remove('hidden'); }
        function closeModal() { modal.classList.add('hidden'); resetForm(); }

        btnOpenModal.addEventListener('click', openModal);
        btnCloseX.addEventListener('click', closeModal);
        btnCloseFooter.addEventListener('click', closeModal);
        overlay.addEventListener('click', closeModal);

        // --- ESCUCHAR CAMBIOS EN EL SELECT DE CATEGORÍAS ---
        tagCategorySelect.addEventListener('change', function() {
            if (this.value === 'NEW_CATEGORY') {
                tagCategoryInput.classList.remove('hidden');
                tagCategoryInput.required = true;
                tagCategoryInput.value = '';
                tagCategoryInput.focus();
            } else {
                tagCategoryInput.classList.add('hidden');
                tagCategoryInput.required = false;
                tagCategoryInput.value = this.value;
            }
        });

        // --- MANEJO DEL FORMULARIO DINÁMICO (CREAR / EDITAR) ---
        function setEditMode(id, name, category) {
            formActionTitle.innerText = "✏️ Modificar Etiqueta";
            tagIdInput.value = id;
            tagNameInput.value = name;
            
            const optionExists = Array.from(tagCategorySelect.options).some(option => option.value === category);
            
            if (optionExists) {
                tagCategorySelect.value = category;
                tagCategoryInput.value = category;
                tagCategoryInput.classList.add('hidden');
                tagCategoryInput.required = false;
            } else {
                tagCategorySelect.value = 'NEW_CATEGORY';
                tagCategoryInput.value = category;
                tagCategoryInput.classList.remove('hidden');
                tagCategoryInput.required = true;
            }
            
            btnSubmitText.innerText = "Actualizar";
            btnCancelEdit.classList.remove('hidden');
            tagNameInput.focus();
        }

        function resetForm() {
            formActionTitle.innerText = "Agregar Nueva Etiqueta";
            tagIdInput.value = "";
            tagNameInput.value = "";
            tagCategorySelect.value = "";
            tagCategoryInput.value = "";
            tagCategoryInput.classList.add('hidden');
            tagCategoryInput.required = false;
            btnSubmitText.innerText = "Agregar";
            btnCancelEdit.classList.add('hidden');
        }

        btnCancelEdit.addEventListener('click', resetForm);

        // --- LÓGICA DE CONTROL: Checkbox único por categoría ---
        document.querySelectorAll('.category-card').forEach(card => {
            const checkboxes = card.querySelectorAll('.tag-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    if (this.checked) {
                        checkboxes.forEach(cb => { if (cb !== this) cb.checked = false; });
                    }
                });
            });
        });
    </script>

</x-client-layout>