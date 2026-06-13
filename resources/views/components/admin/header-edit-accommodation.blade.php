@props(['accommodation'])

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4" 
     x-data="{
        openModal: false,
        selectedStatus: '{{ $accommodation->status->value }}',
        statusName: '{{ $accommodation->status->name }}',
        pendingStatus: null,
        pendingName: ''
    }">

    <div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Propiedad: {{ $accommodation->name }}
        </h2>
    </div>

    <div class="flex items-center gap-2">
        <span class="text-sm font-medium text-gray-500">Estatus:</span>

        <select x-model="selectedStatus"
            @change="
                pendingStatus = $el.value;
                pendingName = $el.options[$el.selectedIndex].text;
                if(pendingStatus != '{{ $accommodation->status->value }}') {
                    openModal = true;
                }
            "
            :class="{
                'bg-amber-100 text-amber-800 border-amber-300 focus:ring-amber-500': selectedStatus == 1,
                'bg-emerald-100 text-emerald-800 border-emerald-300 focus:ring-emerald-500': selectedStatus == 2,
                'bg-rose-100 text-rose-800 border-rose-300 focus:ring-rose-500': selectedStatus == 3
            }"
            class="text-xs font-semibold rounded-full px-3 py-1.5 border focus:outline-none focus:ring-2 cursor-pointer transition-colors duration-200">

            @foreach (App\Enums\AccommodationStatus::cases() as $case)
                <option value="{{ $case->value }}">{{ $case->name }}</option>
            @endforeach
        </select>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity"
            @click="openModal = false; selectedStatus = '{{ $accommodation->status->value }}'"></div>

        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                @click.away="openModal = false; selectedStatus = '{{ $accommodation->status->value }}'">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-base font-semibold text-gray-900">¿Confirmar cambio de estatus?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Estás a punto de cambiar el estado de la propiedad de 
                                    <span class="font-bold text-gray-700">"{{ $accommodation->status->name }}"</span>
                                    a <span class="font-bold text-indigo-600" x-text="pendingName"></span>.
                                    ¿Deseas continuar?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <form action="{{ route('admin.accommodations.status', $accommodation) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" :value="pendingStatus">

                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:w-auto">
                            Confirmar Cambio
                        </button>
                    </form>

                    <button type="button" @click="openModal = false; selectedStatus = '{{ $accommodation->status->value }}'"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>