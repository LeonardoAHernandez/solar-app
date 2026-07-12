@php
    $links = [
        [
            'name' => 'Propiedades',
            'icon' => 'fa-solid fa-house-chimney',
            'route' => route('visitor.accommodations.index'),
            'iconRoute' => 'page-resources/img/peHome.webp',
            'active' => request()->routeIs('visitor.accommodations.index'),
        ],
        [
            'name' => 'Intereses',
            'icon' => 'fa-solid fa-heart',
            'route' => route('visitor.accommodations.interests'),
            'iconRoute' => 'page-resources/img/peHome.webp',
            'active' => request()->routeIs('visitor.interests.index'),
        ],
    ];
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="mx-auto px-4 sm:px-6 lg:px-8 w-full h-18 bg-cover bg-center bg-no-repeat"
        style="background-image: url('{{ asset('page-resources/img/peFondoInicio.webp') }}');">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                    @foreach ($links as $link)
                        <x-nav-link href="{{ $link['route'] }}" :active="$link['active']" class="flex items-center gap-2 px-3 py-2 text-slate-800 hover:bg-orange-100 active:scale-95 transition-all duration-200 focus:outline-none select-none group text-lg">
                            <img src="{{ asset($link['iconRoute']) }}" alt="Home"
                                class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 object-contain transition-transform group-hover:scale-105" />
                            {{-- <i class="{{ $link['icon'] }} text-white"></i> --}}
                            {{ $link['name'] }}
                        </x-nav-link>
                    @endforeach

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">

                    @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button
                                        class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="size-8 rounded-full object-cover"
                                            src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                            {{ Auth::user()->name }}

                                            <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __('Manage Account') }}
                                </div>

                                <x-dropdown-link href="{{ route('profile.show') }}">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <div class="border-t border-gray-200"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf

                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <a href="{{ route('login') }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-full text-slate-800 hover:bg-orange-100 active:scale-95 transition-all duration-200 focus:outline-none select-none group">

                            <img src="{{ asset('page-resources/img/peIniciarSesion.webp') }}" alt="Usuario"
                                class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 object-contain transition-transform group-hover:scale-105" />

                            <span class="hidden sm:inline-block text-sm md:text-base font-medium text-solar-blue">
                                Iniciar sesión
                            </span>
                        </a>

                        {{-- <x-dropdown align="right" width="48">
                            <x-slot name="trigger">

                                <button
                                    class="flex items-center gap-2 px-3 py-2 rounded-full text-slate-800 hover:bg-orange-100 active:scale-95 transition-all duration-200 focus:outline-none select-none group">

                                    <img src="{{ asset('page-resources/img/peIniciarSesion.webp') }}" alt="Usuario"
                                        class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 object-contain transition-transform group-hover:scale-105" />

                                    <span class="hidden sm:inline-block text-sm md:text-base font-medium">
                                        Menu
                                    </span>

                                </button>

                            </x-slot>

                            <x-slot name="content">

                                <x-dropdown-link href="{{ route('login') }}">
                                    Iniciar sesión
                                </x-dropdown-link>

                                <x-dropdown-link href="{{ route('register') }}">
                                    Registrarse
                                </x-dropdown-link>

                            </x-slot>
                        </x-dropdown> --}}
                    @endauth



                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <img src="{{ asset('page-resources/img/peBarraDesplegable.webp') }}" alt="Hamburguer"
                                class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 object-contain transition-transform group-hover:scale-105" />
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">

            @foreach ($links as $link)
                <x-responsive-nav-link href="{{ $link['route'] }}" :active="$link['active']">
                    {{ $link['name'] }}
                </x-responsive-nav-link>
            @endforeach


            @auth
            @else
                <x-responsive-nav-link href="{{ route('login') }}">
                    Iniciar sesión
                </x-responsive-nav-link>

                {{-- <x-responsive-nav-link href="{{ route('register') }}">
                    Registrarse
                </x-responsive-nav-link> --}}
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="shrink-0 me-3">
                            <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                                alt="{{ Auth::user()->name }}" />
                        </div>
                    @endif

                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Account Management -->
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf

                        <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>

                </div>
            </div>
        @endauth
    </div>
</nav>
