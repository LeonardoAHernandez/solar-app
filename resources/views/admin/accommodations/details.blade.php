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
                <p class="text-2xl font-semibold">Detalles de la Propiedad</p>
                <p class="text-sm text-gray-500">Especifica los números de lo que incluye este alojamiento.</p>
            </div>
            <div>
                {{-- Botón para abrir el modal --}}
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

        {{-- FORMULARIO PRINCIPAL: Cantidades de la propiedad --}}
        <form action="{{ route('admin.accommodations.details.store', $accommodation) }}" method="POST"
            id="main-details-form">
            @csrf

            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($allDetails as $detail)
                        @php
                            $currentQuantity = $currentDetails[$detail->id] ?? 0;
                        @endphp

                        <div
                            class="flex items-center justify-between gap-4 p-3 border border-gray-100 rounded-xl bg-gray-50/50 hover:bg-gray-50 transition">
                            <span class="text-sm font-medium text-gray-700">
                                {{ $detail->name }}
                            </span>
                            <input type="number" name="quantities[{{ $detail->id }}]" value="{{ $currentQuantity }}"
                                min="0"
                                class="w-20 text-center h-9 border border-gray-300 rounded-lg focus:ring-blue-500 text-sm font-semibold">
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end mt-4">
                <x-button type="submit">Guardar Cantidades</x-button>
            </div>
        </form>


        {{-- ========================================== --}}
        {{-- MODAL FLOTANTE DE ADMINISTRACIÓN DE CATÁLOGO --}}
        {{-- ========================================== --}}
        <div id="catalog-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                {{-- Fondo oscuro difuminado --}}
                <div id="modal-overlay" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:min-h-screen" aria-hidden="true">&#8203;</span>

                {{-- Cuerpo del Modal --}}
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">

                    <div class="bg-white px-6 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center border-b pb-3 mb-4">
                            <h3 class="text-lg font-bold text-gray-900" id="modal-title">
                                Administrar Catálogo de Detalles
                            </h3>
                            <button type="button" id="btn-close-modal-x"
                                class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
                        </div>

                        {{-- Formulario de Creación / Edición integrado --}}
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6">
                            <h4 id="form-action-title"
                                class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Agregar Nuevo
                                Detalle</h4>

                            <form action="{{ route('admin.details.manage') }}" method="POST" id="manage-detail-form">
                                @csrf
                                <input type="hidden" name="detail_id" id="detail_id" value="">

                                <div class="flex gap-2">
                                    <input type="text" name="name" id="detail_name" required
                                        placeholder="Ej. Jacuzzi, Terraza, Sofás"
                                        class="flex-1 h-10 border border-gray-300 rounded-lg px-3 focus:ring-blue-500 text-sm">

                                    <x-button type="submit" id="btn-submit">
                                        <span id="btn-submit-text">Agregar</span>
                                    </x-button>
                                </div>
                                <button type="button" id="btn-cancel-edit"
                                    class="text-xs text-red-500 hover:underline mt-2 hidden">
                                    ❌ Cancelar edición (volver a crear nuevo)
                                </button>
                            </form>
                        </div>

                        {{-- Listado de opciones actuales dentro del modal --}}
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Opciones Disponibles
                            Actuales</h4>
                        <div
                            class="max-h-52 overflow-y-auto border border-gray-100 rounded-lg divide-y divide-gray-100 px-3 bg-white">
                            @foreach ($allDetails as $detail)
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-700 font-medium">{{ $detail->name }}</span>
                                    <button type="button"
                                        onclick="setEditMode({{ $detail->id }}, '{{ addslashes($detail->name) }}')"
                                        class="text-xs bg-gray-100 hover:bg-blue-100 text-gray-600 hover:text-blue-700 px-2.5 py-1 rounded-md font-medium transition">
                                        Editar
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Botón de cerrar inferior --}}
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t">
                        <button type="button" id="btn-close-modal-footer"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cerrar
                        </button>
                    </div>

                </div>
            </div>
        </div>


        {{-- ALERTA FLOTANTE PERSONALIZADA (TOAST) --}}
        <div id="custom-toast"
            class="fixed top-5 right-5 z-50 transform translate-y-[-20px] opacity-0 pointer-events-none transition-all duration-300 ease-out max-w-sm w-full bg-white border border-gray-100 shadow-2xl rounded-2xl p-4 flex items-center gap-3">
            <div id="toast-icon-bg" class="w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0">
            </div>
            <div class="flex-grow">
                <p id="toast-message" class="text-sm font-semibold text-gray-900"></p>
            </div>
            <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600 text-xs p-1">✕</button>
        </div>

    </x-admin.accommodation-sidebar>

    {{-- Lógica JavaScript para control del Modal y del Formulario --}}
    <script>
        // Elementos del Modal
        const modal = document.getElementById('catalog-modal');
        const btnOpenModal = document.getElementById('btn-open-modal');
        const btnCloseX = document.getElementById('btn-close-modal-x');
        const btnCloseFooter = document.getElementById('btn-close-modal-footer');
        const overlay = document.getElementById('modal-overlay');

        // Elementos del Formulario Dinámico
        const formActionTitle = document.getElementById('form-action-title');
        const detailIdInput = document.getElementById('detail_id');
        const detailNameInput = document.getElementById('detail_name');
        const btnSubmitText = document.getElementById('btn-submit-text');
        const btnCancelEdit = document.getElementById('btn-cancel-edit');

        // --- FUNCIONES PARA ABRIR / CERRAR MODAL ---
        function openModal() {
            modal.classList.remove('hidden');
        }
        function closeModal() {
            modal.classList.add('hidden');
            resetForm(); // Limpiamos estados de edición si cierran el modal
        }

        btnOpenModal.addEventListener('click', openModal);
        btnCloseX.addEventListener('click', closeModal);
        btnCloseFooter.addEventListener('click', closeModal);
        overlay.addEventListener('click', closeModal);

        // --- LÓGICA DE EDICIÓN DEL FORMULARIO ---
        function setEditMode(id, name) {
            formActionTitle.innerText = "✏️ Modificar Nombre de Detalle";
            detailIdInput.value = id;
            detailNameInput.value = name;
            btnSubmitText.innerText = "Actualizar";
            btnCancelEdit.classList.remove('hidden');
            detailNameInput.focus();
        }

        function resetForm() {
            formActionTitle.innerText = "Agregar Nuevo Detalle";
            detailIdInput.value = "";
            detailNameInput.value = "";
            btnSubmitText.innerText = "Agregar";
            btnCancelEdit.classList.add('hidden');
        }

        btnCancelEdit.addEventListener('click', resetForm);

        // --- LÓGICA PARA GESTIÓN DE TOASTS ---
        let toastTimeout;
        function showToast(message, type = 'success') {
            const toast = document.getElementById('custom-toast');
            const toastMsg = document.getElementById('toast-message');
            const iconBg = document.getElementById('toast-icon-bg');
            
            if (!toast || !toastMsg || !iconBg) return;

            toastMsg.innerText = message;
            
            // Limpiar timeout previo si el usuario hace acciones muy rápido
            clearTimeout(toastTimeout);

            // Configuramos estilos y símbolos basados en el tipo de alerta
            if (type === 'success') {
                iconBg.className = "w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0 bg-green-50 text-green-600";
                iconBg.innerHTML = "✓";
            } else if (type === 'error') {
                iconBg.className = "w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0 bg-red-50 text-red-600";
                iconBg.innerHTML = "✕";
            } else {
                iconBg.className = "w-10 h-10 rounded-xl flex items-center justify-center text-lg flex-shrink-0 bg-amber-50 text-amber-600";
                iconBg.innerHTML = "ℹ";
            }
            
            toast.classList.remove('translate-y-[-20px]', 'opacity-0', 'pointer-events-none');
            toastTimeout = setTimeout(hideToast, 4000); // 4 segundos visible
        }

        function hideToast() {
            const toast = document.getElementById('custom-toast');
            if (toast) {
                toast.classList.add('translate-y-[-20px]', 'opacity-0', 'pointer-events-none');
            }
        }

        // --- CAPTURA DE ALERTAS DE SESIÓN DESDE EL SERVIDOR ---
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif
            @if (session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif
        });

        // --- VALIDACIÓN DE MÁXIMO 6 DETALLES AL GUARDAR FORMULARIO PRINCIPAL ---
        const mainForm = document.getElementById('main-details-form');
        if (mainForm) {
            mainForm.addEventListener('submit', function(event) {
                const inputs = mainForm.querySelectorAll('input[type="number"]');
                let activeDetailsCount = 0;

                inputs.forEach(input => {
                    const value = parseInt(input.value, 10);
                    if (!isNaN(value) && value > 0) {
                        activeDetailsCount++;
                    }
                });

                if (activeDetailsCount > 6) {
                    event.preventDefault(); // Detiene el envío
                    showToast(`Límite excedido: Has asignado ${activeDetailsCount} detalles. El máximo permitido son 6.`, 'error');
                }
            });
        }
    </script>

</x-admin-layout>
