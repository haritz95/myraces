<x-app-layout>
    @section('page_title', 'Gestión del menú')
    @section('back_url', route('admin.dashboard'))

    @php
    $iconOptions = [
        // Navegación y estructura
        ['name' => 'Inicio',        'path' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['name' => 'Menú',          'path' => 'M4 6h16M4 12h16M4 18h16'],
        ['name' => 'Grid',          'path' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
        ['name' => 'Lista',         'path' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
        ['name' => 'Búsqueda',      'path' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
        ['name' => 'Añadir',        'path' => 'M12 4v16m8-8H4'],
        // Deportes y running
        ['name' => 'Carrera',       'path' => 'M13 10V3L4 14h7v7l9-11h-7z'],
        ['name' => 'Trofeo',        'path' => 'M9 2v6a3 3 0 006 0V2M7 4H4a2 2 0 00-2 2v1c0 2.97 1.995 5.474 4.773 6.27M17 4h3a2 2 0 012 2v1c0 2.97-1.995 5.474-4.773 6.27M12 14v7m-3 0h6'],
        ['name' => 'Medalla',       'path' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
        ['name' => 'Bandera',       'path' => 'M3 21V5a2 2 0 012-2h13.5L17 7l1.5 4H5v10'],
        ['name' => 'Cronómetro',    'path' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['name' => 'Corazón',       'path' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
        ['name' => 'Fuego',         'path' => 'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z'],
        ['name' => 'Objetivo',      'path' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
        ['name' => 'Montaña',       'path' => 'M3 17l4-8 4 4 3-5 5 9H3z'],
        ['name' => 'Bici',          'path' => 'M12 6v2m0-2a2 2 0 110-4 2 2 0 010 4zM5 20a3 3 0 100-6 3 3 0 000 6zm14 0a3 3 0 100-6 3 3 0 000 6zm-7-6l-2-4H5m12 2l-2-4h-3M8 14h8'],
        ['name' => 'Natación',      'path' => 'M3 17.25a3.75 3.75 0 013.75-3.75c.98 0 1.875.376 2.546 1H12m0 0a3.75 3.75 0 017.5 0M12 14.5c.98 0 1.875-.376 2.546-1H18'],
        // Datos y contenido
        ['name' => 'Estadísticas',  'path' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
        ['name' => 'Tendencia',     'path' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
        ['name' => 'Calendario',    'path' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['name' => 'Récords',       'path' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z'],
        ['name' => 'Carreras',      'path' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['name' => 'Lista tareas',  'path' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
        ['name' => 'Carpeta',       'path' => 'M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z'],
        ['name' => 'Documento',     'path' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
        ['name' => 'Marcador',      'path' => 'M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z'],
        // Usuarios y social
        ['name' => 'Perfil',        'path' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ['name' => 'Usuarios',      'path' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
        ['name' => 'Grupo',         'path' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
        ['name' => 'Chat',          'path' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
        ['name' => 'Comentario',    'path' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z'],
        ['name' => 'Compartir',     'path' => 'M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z'],
        ['name' => 'Campana',       'path' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
        // Ubicación y mapas
        ['name' => 'Mapa',          'path' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7'],
        ['name' => 'Ubicación',     'path' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
        ['name' => 'Globo',         'path' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        // Finanzas
        ['name' => 'Gastos',        'path' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['name' => 'Tarjeta',       'path' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
        ['name' => 'Etiqueta',      'path' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
        // Material y equipo
        ['name' => 'Material',      'path' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
        ['name' => 'Ropa',          'path' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
        ['name' => 'Zapatilla',     'path' => 'M3 17l3-6 3 3 3-5 4 8H3zm0 0h18'],
        // Sistema y utilidades
        ['name' => 'Ajustes',       'path' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
        ['name' => 'Escudo',        'path' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
        ['name' => 'Premium',       'path' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
        ['name' => 'Idioma',        'path' => 'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129'],
        ['name' => 'Campana activa','path' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
        ['name' => 'Info',          'path' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['name' => 'Enlace',        'path' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
        ['name' => 'Descarga',      'path' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'],
        ['name' => 'Subida',        'path' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12'],
        ['name' => 'Imagen',        'path' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['name' => 'Ojo',           'path' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
        ['name' => 'Strava',        'path' => 'M15.387 17.944l-2.089-4.116h-3.065L15.387 24l5.15-10.172h-3.066m-7.008-5.599l2.836 5.598h4.172L10.463 0l-7 13.828h4.169'],
        ['name' => 'Anuncios',      'path' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
        ['name' => 'Pods',          'path' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
        ['name' => 'Envíos',        'path' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
    ];
    @endphp

    <div class="max-w-2xl mx-auto px-4 py-6 space-y-6">

        @if(session('success'))
            <div class="card px-4 py-3 text-sm font-semibold text-green-400 border-green-500/20 bg-green-500/10">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="card px-4 py-3 text-sm font-semibold text-red-400 border-red-500/20 bg-red-500/10">{{ session('error') }}</div>
        @endif

        {{-- Info --}}
        <div class="card px-5 py-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm" style="color:rgba(255,255,255,0.55)">
                Todos los elementos activos aparecen en el <strong class="text-white">sidebar de escritorio</strong> automáticamente. La <strong class="text-white">ubicación móvil</strong> controla si aparece en el menú inferior (máx. 4) o en el drawer ("Más"). Desactivar un elemento o marcarlo como premium lo oculta en todas las pantallas.
            </p>
        </div>

        {{-- Bottom nav --}}
        <section>
            <h2 class="text-sm font-black uppercase tracking-widest text-primary mb-3">Menú inferior</h2>
            <div class="card overflow-hidden">
                @forelse($items->get('bottom_nav', collect()) as $item)
                    @include('admin.nav-items._row', ['item' => $item, 'targetLocation' => 'drawer', 'targetLocationLabel' => 'Mover al drawer'])
                @empty
                    <div class="px-5 py-6 text-center text-sm" style="color:rgba(255,255,255,0.35)">Sin elementos</div>
                @endforelse
            </div>
        </section>

        {{-- Drawer --}}
        <section>
            <h2 class="text-sm font-black uppercase tracking-widest text-primary mb-3">Drawer (Más)</h2>
            <div class="card overflow-hidden">
                @forelse($items->get('drawer', collect()) as $item)
                    @include('admin.nav-items._row', ['item' => $item, 'targetLocation' => 'bottom_nav', 'targetLocationLabel' => 'Mover al menú inferior'])
                @empty
                    <div class="px-5 py-6 text-center text-sm" style="color:rgba(255,255,255,0.35)">Sin elementos</div>
                @endforelse
            </div>
        </section>

        {{-- Add new item --}}
        <section x-data="{
            open: {{ $errors->any() ? 'true' : 'false' }},
            selectedIcon: {{ $errors->any() ? json_encode(old('icon_path', '')) : "''" }},
            selectedIconName: '{{ $errors->any() && old('icon_path') ? 'Personalizado' : '' }}',
            icons: {{ Js::from($iconOptions) }}
        }">
            <button type="button" @click="open = !open"
                    class="w-full card-interactive flex items-center gap-3 px-5 py-4">
                <div class="w-8 h-8 rounded-xl bg-primary/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span class="text-sm font-bold text-white">Añadir elemento al menú</span>
                <svg class="w-4 h-4 ml-auto transition-transform duration-200" :class="open ? 'rotate-90' : ''"
                     style="color:rgba(255,255,255,0.30)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <div x-show="open" x-transition x-cloak class="card mt-2 overflow-hidden">
                <form method="POST" action="{{ route('admin.nav-items.store') }}">
                    @csrf

                    <div class="grid grid-cols-2 gap-4 px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.45)">Etiqueta <span class="text-red-400">*</span></label>
                            <input type="text" name="label" value="{{ old('label') }}" required maxlength="30"
                                   placeholder="Inicio" class="input-field @error('label') error @enderror">
                            @error('label')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.45)">Nombre de ruta <span class="text-red-400">*</span></label>
                            <input type="text" name="route_name" value="{{ old('route_name') }}" required
                                   placeholder="dashboard" class="input-field @error('route_name') error @enderror">
                            @error('route_name')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        <label class="block text-xs font-bold mb-1.5" style="color:rgba(255,255,255,0.45)">Patrón de coincidencia <span class="text-red-400">*</span></label>
                        <input type="text" name="match_pattern" value="{{ old('match_pattern') }}" required
                               placeholder="dashboard  o  races.index|races.show"
                               class="input-field @error('match_pattern') error @enderror">
                        <p class="text-[10px] mt-1.5" style="color:rgba(255,255,255,0.30)">Separa múltiples patrones con |</p>
                        @error('match_pattern')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
                    </div>

                    {{-- Icon picker --}}
                    <div class="px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        <div class="flex items-center gap-3 mb-3">
                            <label class="text-xs font-bold" style="color:rgba(255,255,255,0.45)">Icono <span class="text-red-400">*</span></label>
                            {{-- Preview --}}
                            <div class="flex items-center gap-2 ml-auto">
                                <template x-if="selectedIcon">
                                    <div class="flex items-center gap-2 px-2.5 py-1 rounded-lg" style="background:rgba(200,250,95,0.10)">
                                        <svg class="w-4 h-4 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" :d="selectedIcon"/>
                                        </svg>
                                        <span class="text-xs font-bold text-primary" x-text="selectedIconName"></span>
                                    </div>
                                </template>
                                <template x-if="!selectedIcon">
                                    <span class="text-xs" style="color:rgba(255,255,255,0.30)">Sin seleccionar</span>
                                </template>
                            </div>
                        </div>

                        <input type="hidden" name="icon_path" :value="selectedIcon" required>

                        <div class="grid grid-cols-6 gap-2">
                            <template x-for="icon in icons" :key="icon.name">
                                <button type="button"
                                        @click="selectedIcon = icon.path; selectedIconName = icon.name"
                                        class="flex flex-col items-center gap-1.5 p-2 rounded-xl transition-all duration-150"
                                        :class="selectedIcon === icon.path
                                            ? 'bg-primary/15 ring-1 ring-primary/40'
                                            : 'hover:bg-white/[0.06]'"
                                        style="background: selectedIcon === icon.path ? '' : 'rgba(255,255,255,0.03)'">
                                    <svg class="w-5 h-5 flex-shrink-0"
                                         :class="selectedIcon === icon.path ? 'text-primary' : ''"
                                         :style="selectedIcon !== icon.path ? 'color:rgba(255,255,255,0.50)' : ''"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" :d="icon.path"/>
                                    </svg>
                                    <span class="text-[9px] font-bold leading-tight text-center truncate w-full"
                                          :class="selectedIcon === icon.path ? 'text-primary' : ''"
                                          :style="selectedIcon !== icon.path ? 'color:rgba(255,255,255,0.35)' : ''"
                                          x-text="icon.name"></span>
                                </button>
                            </template>
                        </div>
                        @error('icon_path')<p class="text-red-400 text-xs mt-2">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.45)">Ubicación en móvil</label>
                            <select name="location" class="input-field">
                                <option value="drawer" {{ old('location', 'drawer') === 'drawer' ? 'selected' : '' }}>Drawer (Más)</option>
                                <option value="bottom_nav" {{ old('location') === 'bottom_nav' ? 'selected' : '' }}>Menú inferior</option>
                            </select>
                        </div>
                        <div class="flex items-end pb-1">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_premium" value="1" {{ old('is_premium') ? 'checked' : '' }}
                                       class="w-4 h-4 rounded accent-primary">
                                <span class="text-sm font-medium text-white">Solo premium</span>
                            </label>
                        </div>
                    </div>

                    <div class="px-5 py-4 flex gap-2 justify-end">
                        <button type="button" @click="open = false" class="btn btn-secondary text-xs py-2">Cancelar</button>
                        <button type="submit" class="btn btn-primary text-xs py-2">Añadir elemento</button>
                    </div>
                </form>
            </div>
        </section>

    </div>
</x-app-layout>
