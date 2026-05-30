@php
    $links = [
        [
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-gauge',
            'route' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
        ],

        [
            'header' => 'Administrar pagina',
        ],

        [
            'name' => 'Usuarios',
            'icon' => 'fa-solid fa-users',
            'route' => '',
            'active' => false,
        ],

        [
            'name' => 'Empresa',
            'icon' => 'fa-solid fa-building',
            'active' => false,
            'submenu' => [
                [
                    'name' => 'Información',
                    'icon' => 'fa-regular fa-circle',
                    'route' => '',
                    'active' => false,
                ],
                [
                    'name' => 'Redes sociales',
                    'icon' => 'fa-regular fa-circle',
                    'route' => '',
                    'active' => false,
                ],
            ],
        ],
    ];
@endphp

<aside id="top-bar-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0"
    :class="{
        'transform-none': open,
        '-translate-x-full': !open,
    }" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-white border-e border-default">
        <a href="https://flowbite.com/" class="flex items-center ps-2.5 mb-5">
            <img src="https://flowbite.com/docs/images/logo.svg" class="h-6 me-3" alt="Flowbite Logo" />
            <span class="self-center text-lg text-heading font-semibold whitespace-nowrap">Flowbite</span>
        </a>
        <ul class="space-y-2 font-medium">

            @foreach ($links as $link)
                <li>
                    @isset($link['header'])
                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase">
                            {{ $link['header'] }}
                        </div>
                    @else
                        @isset($link['submenu'])
                            <div x-data="{
                                open: {{ $link['active'] ? 'true' : 'false' }}
                            }">

                                <button x-on:click="open = !open"
                                    class="flex items-center w-full px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group {{ $link['active'] ? 'bg-gray-100' : '' }}">
                                    <span class="inline-flex w-6 h-6 justify-center items-center">
                                        <i class="{{ $link['icon'] }} text-gray-500"></i>
                                    </span>
                                    <span class="ms-3 flex-1 text-left">
                                        {{ $link['name'] }}
                                    </span>


                                    <i class="fa-solid fa-angle-down"
                                    :class="{
                                        'fa-angle-down': !open,
                                        'fa-angle-up': open,
                                    }"></i>

                                </button>

                                <ul x-show="open" x-cloak>
                                    @foreach ($link['submenu'] as $sublink)
                                        <li class="pl-4">
                                            <a href="{{ $sublink['route'] }}"
                                                class="flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group {{ $sublink['active'] ? 'bg-gray-100' : '' }}">
                                                <span class="inline-flex w-6 h-6 justify-center items-center">
                                                    <i class="{{ $sublink['icon'] }} text-gray-500"></i>
                                                </span>
                                                <span class="ms-3">
                                                    {{ $sublink['name'] }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                        @else
                            <a href="{{ $link['route'] }}"
                                class="flex items-center px-2 py-1.5 text-body rounded-base hover:bg-neutral-tertiary hover:text-fg-brand group {{ $link['active'] ? 'bg-gray-100' : '' }}">
                                <span class="inline-flex w-6 h-6 justify-center items-center">
                                    <i class="{{ $link['icon'] }} text-gray-500"></i>
                                </span>
                                <span class="ms-3">
                                    {{ $link['name'] }}
                                </span>
                            </a>
                        @endisset
                    @endisset

                </li>
            @endforeach
        </ul>
    </div>
</aside>
