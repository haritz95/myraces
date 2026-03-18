<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0a0a0a">

        <title>{{ config('app.name', 'MyRaces') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=public-sans:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Apply saved theme before first paint to avoid flash --}}
        <script>
            (function () {
                var t = localStorage.getItem('theme') || 'dark';
                document.documentElement.setAttribute('data-theme', t);
            })();
        </script>
    </head>
    <body class="font-sans antialiased min-h-screen flex flex-col items-center justify-center px-4 py-12 bg-bg-app">

        {{-- Top-right controls: language + theme --}}
        <div class="fixed top-4 right-4 flex items-center gap-3 z-50">
            <div class="flex items-center gap-1.5">
                <a href="{{ route('language.switch', 'es') }}"
                   class="text-xs font-bold transition-colors {{ app()->getLocale() === 'es' ? 'text-primary' : 'text-white/40 hover:text-white/70' }}">ES</a>
                <span class="text-white/20 text-[10px]">·</span>
                <a href="{{ route('language.switch', 'en') }}"
                   class="text-xs font-bold transition-colors {{ app()->getLocale() === 'en' ? 'text-primary' : 'text-white/40 hover:text-white/70' }}">EN</a>
            </div>

            <button onclick="toggleGuestTheme()" aria-label="Toggle theme"
                    class="w-8 h-8 flex items-center justify-center rounded-lg transition-colors hover:bg-white/[0.08]"
                    style="color:rgba(255,255,255,0.45)">
                {{-- Sun icon (shown in dark mode) --}}
                <svg id="icon-sun" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/>
                </svg>
                {{-- Moon icon (shown in light mode) --}}
                <svg id="icon-moon" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>
        </div>

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 mb-8">
            <div class="w-11 h-11 bg-primary rounded-xl flex items-center justify-center"
                 style="box-shadow: 0 8px 24px rgba(200,250,95,0.35)">
                <svg style="width:20px;height:20px" class="text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="text-2xl font-bold text-white tracking-tight">My<span class="text-primary">Races</span></span>
        </a>

        {{-- Card --}}
        <div class="w-full max-w-[390px] card overflow-hidden" style="box-shadow: 0 24px 64px rgba(0,0,0,0.55)">
            {{ $slot }}
        </div>

        <p class="mt-8 text-xs font-medium text-subtle">
            © {{ now()->year }} MyRaces · {{ __('auth.rights') }}
        </p>

        <script>
            function toggleGuestTheme() {
                var html = document.documentElement;
                var next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-theme', next);
                localStorage.setItem('theme', next);
                document.getElementById('icon-sun').classList.toggle('hidden', next === 'light');
                document.getElementById('icon-moon').classList.toggle('hidden', next === 'dark');
            }

            // Sync icons on load
            (function () {
                var t = document.documentElement.getAttribute('data-theme') || 'dark';
                var sun  = document.getElementById('icon-sun');
                var moon = document.getElementById('icon-moon');
                if (sun && moon) {
                    sun.classList.toggle('hidden', t === 'light');
                    moon.classList.toggle('hidden', t === 'dark');
                }
            })();
        </script>

    </body>
</html>
