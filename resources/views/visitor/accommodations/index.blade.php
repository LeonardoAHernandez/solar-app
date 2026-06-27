<x-visitor-layout>

    <div class="w-full [mask-image:linear-gradient(to_bottom,black_85%,transparent_100%)]"
        style="background-image: url('{{ asset('page-resources/img/peFondoInicio.webp') }}')">
        <img src="page-resources/img/LogoSOLAR.webp" class="pt-12 p-24">
    </div>

    <div class="w-full text-center mt-9">
        <h1 class="text-3xl text-solar-brown font-tenor">Tu Próxima Historia comienza aquí</h1>
    </div>

    @foreach ($groupedAccommodations as $category => $accommodations)
        <div class="mt-4 mx-5">
            <div class="flex items-center gap-2">
                <img src="page-resources/img/peFlechaS1.webp" class="w-8 aspect-square">
                <span class="font-raleway">{{ $category }}</span>
            </div>
        </div>

        <div class="columns-3 gap-6 mt-4 mx-5">
            @foreach ($accommodations as $accommodation)
                <article
                    class="bg-solar-yellow rounded-2xl border border-gray-100 overflow-hidden hover:border-blue-100 shadow-xs hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                    <a href="{{ route('visitor.accommodations.show', $accommodation->id) }}" class="flex flex-col md:flex-row h-full">

                        <div
                            class="relative w-full md:w-64 lg:w-72 flex-shrink-0 aspect-square md:aspect-square overflow-hidden bg-gray-900">
                            <img src="{{ $accommodation->image }}" class="w-full h-full object-cover object-center"
                                alt="{{ $accommodation->name }}">
                        </div>

                        <div class="flex-1 flex flex-col justify-between p-6 sm:p-2">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 h-full">

                                <div class="lg:col-span-2 flex flex-col justify-between space-y-4">
                                    <div>
                                        <h3 class="text-lg text-gray-900 mb-2">{{ $accommodation->name }}</h3>
                                    </div>
                                </div>

                                {{-- Precio --}}
                                <div
                                    class="flex flex-row lg:flex-col justify-between lg:justify-between items-center lg:items-end pt-2 lg:pt-0 lg:pl-6 h-full">
                                    <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg">
                                        <i class="fa-solid fa-star text-gray-900 text-xs"></i>
                                        <span class="text-xs font-bold text-gray-900">5.0</span>
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="text-[10px] text-gray-900 uppercase font-bold tracking-wider mb-0.5 font-raleway">
                                            Precio por noche</p>
                                        <p class="text-xl font-black text-gray-900 tracking-tight">
                                            ${{ number_format($accommodation->price, 2) }} <span
                                                class="text-xs font-semibold text-gray-900 font-raleway">MXN</span>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </a>
                </article>
            @endforeach
        </div>

        <div class="text-center mt-3">
            <a href="#" class="btn btn-link text-decoration-none text-warning fw-bold">
                &lt; Ver más &gt;
            </a>
        </div>
    @endforeach

</x-visitor-layout>
