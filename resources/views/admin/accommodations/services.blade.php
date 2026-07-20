<x-admin-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Propiedad: {{ $accommodation->name }}
        </h2>
    </x-slot>

    <x-admin.accommodation-sidebar :accommodation="$accommodation">

        {{-- Encabezado con Botón de Administración --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-2">
            <div>
                <p class="text-2xl font-semibold">Servicios de la Propiedad</p>
                <p class="text-sm text-gray-500">Selecciona los servicios disponibles en el alojamiento.</p>
            </div>
            <div>
                <button type="button" id="btn-open-modal"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
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
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg shadow-sm">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORMULARIO PRINCIPAL: Checkboxes de servicios --}}
        <form action="{{ route('admin.accommodations.services.store', $accommodation) }}" method="POST">
            @csrf

            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($services as $service)
                        <label
                            class="flex items-center gap-3 cursor-pointer p-3 border border-gray-100 hover:border-blue-200 hover:bg-blue-50/30 rounded-xl transition duration-150">
                            <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                {{ in_array($service->id, $selectedServiceIds) ? 'checked' : '' }}>

                            <span class="text-sm font-medium text-gray-700 select-none flex items-center gap-2">
                                @if ($service->icon)
                                    <i class="{{ $service->icon->class_name }} text-gray-500 w-5 text-center"></i>
                                @else
                                    <i class="fa-solid fa-circle-check text-gray-400 w-5 text-center"></i>
                                @endif
                                {{ $service->name }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <x-button type="submit">
                    Guardar Servicios
                </x-button>
            </div>
        </form>


        {{-- ========================================== --}}
        {{-- MODAL FLOTANTE DE ADMINISTRACIÓN DE CATÁLOGO --}}
        {{-- ========================================== --}}
        <div id="catalog-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <div id="modal-overlay" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:min-h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                    <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center border-b pb-3 mb-4">
                            <h3 class="text-lg font-bold text-gray-900" id="modal-title">
                                Administrar Catálogo de Servicios
                            </h3>
                            <button type="button" id="btn-close-modal-x"
                                class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6">
                            <h4 id="form-action-title"
                                class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Agregar Nuevo Servicio</h4>

                            <form action="{{ route('admin.services.manage') }}" method="POST" id="manage-service-form">
                                @csrf
                                <input type="hidden" name="service_id" id="service_id" value="">

                                <div class="flex flex-col gap-3">
                                    <div>
                                        <label class="text-xs font-medium text-gray-600">Nombre del Servicio</label>
                                        <input type="text" name="name" id="service_name" required
                                            placeholder="Ej. Piscina, Wifi, Estacionamiento"
                                            class="w-full h-10 border border-gray-300 rounded-lg px-3 focus:ring-blue-500 text-sm mt-1">
                                    </div>

                                    <div>
                                        <label class="text-xs font-medium text-gray-600">Icono Asociado</label>
                                        <div class="flex items-center gap-2 mt-1">
                                            
                                            {{-- CONTENEDOR DE VISTA PREVIA REPARADO --}}
                                            <div id="preview-wrapper" class="w-10 h-10 bg-gray-100 border border-gray-300 rounded-lg flex items-center justify-center text-gray-600 shadow-sm">
                                                <i class="fa-solid fa-circle-check fa-lg"></i>
                                            </div>

                                            {{-- SELECT LIMPIO Y CORREGIDO --}}
                                            <select name="icon_id" id="icon_id"
                                                class="flex-1 h-10 border border-gray-300 rounded-lg px-3 focus:ring-blue-500 text-sm bg-white">
                                                <option value="" data-class="fa-solid fa-circle-check">-- Sin Icono (Por defecto) --</option>
                                                @foreach ($icons as $icon)
                                                    <option value="{{ $icon->id }}" data-class="{{ $icon->class_name }}">
                                                        {{ $icon->name }}
                                                    </option>
                                                @endforeach
                                                <option value="new" data-class="fa-solid fa-plus" class="text-blue-600 font-semibold">+ Agregar nuevo ícono personalizado</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="new-icon-fields"
                                        class="hidden border-l-4 border-blue-500 pl-3 py-1 bg-blue-50/50 rounded-r-lg flex flex-col gap-2">
                                        <p class="text-xs font-bold text-blue-700 uppercase tracking-wider">Nuevo Icono Font Awesome</p>
                                        <div>
                                            <input type="text" name="new_icon_name" id="new_icon_name"
                                                placeholder="Nombre descriptivo (Ej: Wifi premium)"
                                                class="w-full h-9 border border-gray-300 rounded-lg px-3 text-sm">
                                        </div>
                                        <div>
                                            <input type="text" name="new_icon_class" id="new_icon_class"
                                                placeholder="Clase CSS (Ej: fa-solid fa-wifi)"
                                                class="w-full h-9 border border-gray-300 rounded-lg px-3 text-sm">
                                        </div>

                                        <label for="new_icon_class" class="text-xs text-gray-500">
                                            Para consultar los iconos disponibles, visita <a href="https://fontawesome.com/search?ic=free-collection" target="_blank" class="text-blue-500 hover:underline">Font Awesome</a>
                                        </label>
                                    </div>

                                    <div class="flex items-center justify-between mt-2">
                                        <button type="button" id="btn-cancel-edit"
                                            class="text-xs text-red-500 hover:underline hidden">
                                            ❌ Cancelar edición
                                        </button>
                                        <x-button type="submit" id="btn-submit" class="ml-auto">
                                            <span id="btn-submit-text">Agregar</span>
                                        </x-button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Servicios Disponibles Actuales</h4>
                        <div class="max-h-52 overflow-y-auto border border-gray-100 rounded-lg divide-y divide-gray-100 px-3 bg-white">
                            @foreach ($services as $service)
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-700 font-medium flex items-center gap-2">
                                        @if ($service->icon)
                                            <i class="{{ $service->icon->class_name }} text-gray-400 w-5 text-center"></i>
                                        @else
                                            <i class="fa-solid fa-circle-check text-gray-300 w-5 text-center"></i>
                                        @endif
                                        {{ $service->name }}
                                    </span>
                                    <button type="button"
                                        onclick="setEditMode({{ $service->id }}, '{{ addslashes($service->name) }}', '{{ $service->icon_id ?? '' }}', '{{ $service->icon->class_name ?? '' }}')"
                                        class="text-xs bg-gray-100 hover:bg-blue-100 text-gray-600 hover:text-blue-700 px-2.5 py-1 rounded-md font-medium transition">
                                        Editar
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                        <button type="button" id="btn-close-modal-footer"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cerrar
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </x-admin.accommodation-sidebar>

    <script>
        const modal = document.getElementById('catalog-modal');
        const btnOpenModal = document.getElementById('btn-open-modal');
        const btnCloseX = document.getElementById('btn-close-modal-x');
        const btnCloseFooter = document.getElementById('btn-close-modal-footer');
        const overlay = document.getElementById('modal-overlay');

        const formActionTitle = document.getElementById('form-action-title');
        const serviceIdInput = document.getElementById('service_id');
        const serviceNameInput = document.getElementById('service_name');
        const iconIdSelect = document.getElementById('icon_id');
        
        // Apuntamos al contenedor wrapper en lugar de a la etiqueta <i> directamente
        const previewWrapper = document.getElementById('preview-wrapper');
        
        const newIconFields = document.getElementById('new-icon-fields');
        const newIconName = document.getElementById('new_icon_name');
        const newIconClass = document.getElementById('new_icon_class');
        const btnSubmitText = document.getElementById('btn-submit-text');
        const btnCancelEdit = document.getElementById('btn-cancel-edit');

        function openModal() {
            modal.classList.remove('hidden');
            updateIconPreview();
        }

        function closeModal() {
            modal.classList.add('hidden');
            resetForm();
        }

        btnOpenModal.addEventListener('click', openModal);
        btnCloseX.addEventListener('click', closeModal);
        btnCloseFooter.addEventListener('click', closeModal);
        overlay.addEventListener('click', closeModal);

        // --- FUNCIÓN DE VISTA PREVIA CORREGIDA PARA SVG/JS ---
        function updateIconPreview() {
            toggleNewIconFields();

            let iconClass = 'fa-solid fa-circle-check';

            if (iconIdSelect.value === 'new') {
                let inputClass = newIconClass.value.trim();
                if (inputClass) iconClass = inputClass;
            } else {
                const selectedOption = iconIdSelect.options[iconIdSelect.selectedIndex];
                if (selectedOption && selectedOption.hasAttribute('data-class')) {
                    const customClass = selectedOption.getAttribute('data-class').trim();
                    if (customClass !== '') iconClass = customClass;
                }
            }

            // REPARACIÓN CLAVE: Reemplazamos el HTML interno por completo. 
            // Esto obliga a Font Awesome a procesar el nuevo icono sin importar si usa SVG o Fuentes Web.
            previewWrapper.innerHTML = `<i class="${iconClass} fa-lg"></i>`;
            
            // Si tu proyecto usa la versión JS de Font Awesome, le pedimos que vuelva a buscar cambios en el DOM
            if (window.FontAwesome) {
                window.FontAwesome.dom.i2svg({ node: previewWrapper });
            }
        }

        function toggleNewIconFields() {
            if (iconIdSelect.value === 'new') {
                newIconFields.classList.remove('hidden');
                newIconName.required = true;
                newIconClass.required = true;
            } else {
                newIconFields.classList.add('hidden');
                newIconName.required = false;
                newIconClass.required = false;
            }
        }

        function setEditMode(id, name, iconId, className) {
            formActionTitle.innerText = "✏️ Modificar Nombre de Servicio";
            serviceIdInput.value = id;
            serviceNameInput.value = name;
            iconIdSelect.value = iconId || "";

            // Renderizar inmediatamente en modo edición
            let finalClass = className.trim() || 'fa-solid fa-circle-check';
            previewWrapper.innerHTML = `<i class="${finalClass} fa-lg"></i>`;
            if (window.FontAwesome) {
                window.FontAwesome.dom.i2svg({ node: previewWrapper });
            }
            
            toggleNewIconFields();

            btnSubmitText.innerText = "Actualizar";
            btnCancelEdit.classList.remove('hidden');
            serviceNameInput.focus();
        }

        function resetForm() {
            formActionTitle.innerText = "Agregar Nuevo Servicio";
            serviceIdInput.value = "";
            serviceNameInput.value = "";
            iconIdSelect.value = "";
            newIconName.value = "";
            newIconClass.value = "";

            previewWrapper.innerHTML = `<i class="fa-solid fa-circle-check fa-lg"></i>`;
            if (window.FontAwesome) {
                window.FontAwesome.dom.i2svg({ node: previewWrapper });
            }
            
            toggleNewIconFields();

            btnSubmitText.innerText = "Agregar";
            btnCancelEdit.classList.add('hidden');
        }

        // Listeners activos para cambios en tiempo real
        iconIdSelect.addEventListener('change', updateIconPreview);
        newIconClass.addEventListener('input', updateIconPreview);
        btnCancelEdit.addEventListener('click', resetForm);
    </script>

</x-admin-layout>