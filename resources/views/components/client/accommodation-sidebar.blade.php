@props(['accommodation'])

@php
    $links = [
        [
            'name' => 'Información General',
            'url' => route('client.accommodations.edit', $accommodation),
            'active' => request()->routeIs('client.accommodations.edit'),
        ],
        [
            'name' => 'Imagenes',
            'url' => route('client.accommodations.images', $accommodation),
            'active' => request()->routeIs('client.accommodations.images'),
        ],
        [
            'name' => 'Etiquetas',
            'url' => route('client.accommodations.tags.index', $accommodation),
            'active' => request()->routeIs('client.accommodations.tags.index'),
        ],
        [
            'name' => 'Servicios',
            'url' => route('client.accommodations.services.index', $accommodation),
            'active' => request()->routeIs('client.accommodations.services.index'),
        ],
    ];
@endphp

<x-container class="py-8">

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
    
        <aside class="col-span-1">
    
            <h1 class="text-xl font-semibold mb-4">Edición de Propiedad</h1>
    
            <nav>
                <ul class="space-y-2">
                    @foreach ($links as $link)
                        <li class="border-l-4 {{ $link['active'] ? 'border-indigo-500' : 'border-transparent' }} pl-3">
                            <a href="{{ $link['url'] }}">
                                {{ $link['name'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
    
        </aside>
    
        <div class="col-span-1 lg:col-span-4">
    
            <div class="card">
    
                {{ $slot }}
    
            </div>
    
        </div>
    
    </div>

</x-container>

