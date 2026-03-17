<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#221610">

        <title>{{ config('app.name', 'MyRaces') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=public-sans:300,400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased min-h-screen flex flex-col items-center justify-center px-4 py-10" style="background: #221610">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 mb-8">
            <div class="w-11 h-11 bg-primary rounded-xl flex items-center justify-center" style="box-shadow: 0 8px 24px rgba(236,91,19,0.45)">
                <svg style="width:20px;height:20px" class="text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="text-2xl font-bold text-white tracking-tight">My<span class="text-primary">Races</span></span>
        </a>

        {{-- Card --}}
        <div class="w-full max-w-[380px] bg-bg-warm rounded-xl overflow-hidden" style="box-shadow: 0 24px 64px rgba(0,0,0,0.50)">
            {{ $slot }}
        </div>

        <p class="mt-8 text-xs font-medium" style="color:rgba(255,255,255,0.15)">© {{ now()->year }} MyRaces · Todos los derechos reservados</p>

    </body>
</html>
