<x-visitor-layout>

    <div class="w-full [mask-image:linear-gradient(to_bottom,black_85%,transparent_100%)]"
        style="background-image: url('{{ asset('page-resources/img/peFondoInicio.webp') }}')">
        <img src="page-resources/img/LogoSOLAR.webp" class="pt-12 p-24">
    </div>

    <div class="w-full text-center mt-9">
        <h1 class="text-3xl text-solar-brown font-tenor">Tu Próxima Historia comienza aquí</h1>
    </div>

    <div class="container py-5">

        @foreach ($groupedAccommodations as $category => $accommodations)
            <div class="category-section mb-5">
                <h2 class="h4 mb-4 text-capitalize">
                    <span class="text-warning">➔</span> {{ $category }}
                </h2>

                <div class="row g-4">
                    @foreach ($accommodations as $accommodation)
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                                <img src="{{ asset('images/placeholder.jpg') }}" class="card-img-top"
                                    alt="{{ $accommodation->name }}">

                                <div class="card-body bg-warning text-dark d-flex flex-column justify-content-between">
                                    <div>
                                        <h5 class="card-title fw-bold mb-1">{{ $accommodation->name }}</h5>
                                        <p class="card-text small mb-2">${{ number_format($accommodation->price, 2) }}
                                            MXN por noche</p>
                                    </div>
                                    <div class="d-flex justify-content-between align-contents-center small">
                                        <span>★ 3.3</span> {{-- Aquí puedes meter lógica de reseñas si la tienes --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-3">
                    <a href="#" class="btn btn-link text-decoration-none text-warning fw-bold">
                        &lt; Ver más &gt;
                    </a>
                </div>
            </div>
        @endforeach

    </div>

</x-visitor-layout>
