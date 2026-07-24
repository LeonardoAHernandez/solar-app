<x-visitor-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8 min-h-[60vh]">

        <div>
            <h1 class="text-3xl md:text-4xl text-solar-blue font-tenor tracking-wide">
                Mi Lista de Interés
            </h1>
            <p class="text-sm text-gray-500 font-raleway mt-1">Aquí puedes revisar las propiedades seleccionadas antes de
                contactar con un asesor.</p>
        </div>

        {{-- CONTENEDOR PRINCIPAL DINÁMICO --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- LISTADO DE PROPIEDADES (IZQUIERDA) --}}
            <div id="interests-container" class="lg:col-span-2 space-y-4">
                <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200">
                    <p class="text-gray-400 text-sm">Cargando tus propiedades seleccionadas...</p>
                </div>
            </div>

            {{-- RESUMEN Y BOTÓN WHATSAPP (DERECHA) --}}
            <div id="summary-card" class="bg-white border border-gray-100 p-6 rounded-2xl shadow-xs space-y-4 hidden">
                <h3 class="text-lg font-bold text-gray-900 font-raleway border-b border-gray-50 pb-2">Hablar con un
                    asesor</h3>

                <div class="flex justify-between text-sm text-gray-600 font-medium">
                    <span>Propiedades seleccionadas:</span>
                    <span id="items-count">0</span>
                </div>

                {{-- BOTÓN DINÁMICO DE WHATSAPP --}}
                <a id="whatsapp-btn" href="#" target="_blank"
                    class="w-full bg-[#25D366] hover:bg-[#20ba5a] text-white font-bold py-3.5 rounded-xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2 text-center text-sm md:text-base">
                    <i class="fa-brands fa-whatsapp text-lg"></i> Enviar lista por WhatsApp
                </a>
            </div>

        </div>
    </div>

    {{-- LÓGICA DE CONTROL DEL CARRITO POR JAVASCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            renderInterests();
        });

        function renderInterests() {
            const container = document.getElementById('interests-container');
            const summaryCard = document.getElementById('summary-card');
            const list = JSON.parse(localStorage.getItem('solar_interest_list')) || [];

            // Si el carrito está vacío
            if (list.length === 0) {
                summaryCard.classList.add('hidden');
                container.innerHTML = `
                    <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-12 text-center shadow-xs">
                        <p class="text-sm text-gray-400 mb-3">Aún no has agregado ninguna propiedad a tu lista de interés.</p>
                        <a href="{{ route('visitor.accommodations.index') }}" class="text-xs bg-gray-100 px-4 py-2 rounded-xl font-bold text-gray-700 hover:bg-gray-200 transition">Explorar catálogo</a>
                    </div>
                `;
                return;
            }

            // Mostrar tarjeta de resumen si hay elementos
            summaryCard.classList.remove('hidden');

            let html = '';
            let whatsappPropertiesText = [];

            list.forEach((item) => {
                // Estructuramos el formato: (id - nombre) requerido para Whatsapp
                whatsappPropertiesText.push(`(${item.id} - ${item.name})`);

                // Formateador de moneda MXN
                const formattedPrice = new Intl.NumberFormat('es-MX', {
                    style: 'currency',
                    currency: 'MXN'
                }).format(item.price);

                // Construimos la URL dinámica hacia la vista show.blade.php
                // Si guardaste el slug en localStorage usará item.slug, de lo contrario usará el ID
                const targetSlugOrId = item.slug || item.id;
                const detailUrl = `/accommodations/${targetSlugOrId}`;

                html += `
                    <article class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-xs hover:shadow-md transition-all duration-200 group">
                        <div class="flex flex-col sm:flex-row h-full">
                            
                            {{-- Imagen clickeable para abrir la propiedad --}}
                            <a href="${detailUrl}" class="relative w-full sm:w-48 flex-shrink-0 aspect-video sm:aspect-square overflow-hidden bg-gray-900 block">
                                <img src="${item.image}" class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300" alt="${item.name}">
                            </a>

                            <div class="flex-1 flex flex-col justify-between p-5">
                                <div class="flex justify-between items-start gap-4">
                                    <div>
                                        {{-- Título clickeable hacia show.blade --}}
                                        <a href="${detailUrl}" class="text-lg font-bold text-gray-950 font-raleway hover:text-solar-brown transition-colors">
                                            ${item.name}
                                        </a>
                                        <p class="text-xs text-gray-400 mt-0.5">ID Propiedad: ${item.id}</p>
                                    </div>
                                    <button onclick="removeFromList(${item.id})" class="text-gray-400 hover:text-red-500 text-sm transition" title="Eliminar de la lista">
                                        ❌ Quitar
                                    </button>
                                </div>

                                <div class="flex justify-between items-end pt-4 border-t border-gray-50 mt-4">
                                    <div>
                                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider font-raleway block">Tarifa Base</span>
                                        <p class="text-lg font-black text-gray-950">${formattedPrice} <span class="text-xs font-semibold text-gray-400 font-raleway">MXN</span></p>
                                    </div>
                                    
                                    {{-- Botón adicional explícito "Ver detalles" --}}
                                    <a href="${detailUrl}" class="text-xs bg-amber-50 text-solar-brown font-bold px-3 py-2 rounded-lg border border-amber-200/60 hover:bg-amber-100 transition">
                                        Ver detalles →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                `;
            });

            container.innerHTML = html;

            // Actualizar contador en el DOM
            document.getElementById('items-count').innerText = list.length;

            // GENERAR ENLACE DE WHATSAPP DINÁMICO
            const whatsappNumber = "527223834519";

            // 1. Extraemos solo los IDs numéricos: "1,3,5"
            const idsArray = list.map(item => item.id);
            const stringIds = idsArray.join(',');

            // 2. Nombres formateados para la lectura del mensaje
            // const stringProperties = list.map(item => `• ${item.name} (ID: ${item.id})`).join('\n');
            const stringProperties = list.map(item => `• ${item.name}`).join('\n');

            // 3. Generamos el enlace que abrirá el administrador en su panel
            const adminShareUrl = `${window.location.origin}/admin/shared-interests?ids=${stringIds}`;

            // 4. Redactamos el mensaje de WhatsApp con formato limpio
            const baseMessage =
                `¡Hola! Me gustaría solicitar información sobre la(s) siguiente(s) propiedad(es):\n\n${stringProperties}\n\nPuedes revisar la lista completa aquí:\n${adminShareUrl}`;

            const encodedMessage = encodeURIComponent(baseMessage);
            document.getElementById('whatsapp-btn').href = `https://wa.me/${whatsappNumber}?text=${encodedMessage}`;
        }

        function removeFromList(id) {
            let list = JSON.parse(localStorage.getItem('solar_interest_list')) || [];
            list = list.filter(item => item.id !== id);
            localStorage.setItem('solar_interest_list', JSON.stringify(list));
            renderInterests();
        }
    </script>
</x-visitor-layout>
