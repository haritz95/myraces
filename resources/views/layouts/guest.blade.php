<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0a0a0a">

        <title>{{ config('app.name', 'MyRaces') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=public-sans:300,400,500,600,700,800,900i,800i,700i&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script>
            (function () {
                var t = localStorage.getItem('theme') || 'dark';
                document.documentElement.setAttribute('data-theme', t);
            })();
        </script>

        <style>
            /* Match landing nav logo */
            .auth-logo { font-style: italic; font-weight: 900; font-size: 1.25rem; letter-spacing: -.03em; color: #C8FA5F; }

            /* Input underline animation */
            .auth-field { position: relative; }
            .auth-field::after {
                content: '';
                position: absolute;
                bottom: 0; left: 0;
                width: 0; height: 2px;
                background: #C8FA5F;
                transition: width 0.35s ease;
                border-radius: 0 0 2px 2px;
            }
            .auth-field:focus-within::after { width: 100%; }
            .auth-input {
                width: 100%;
                background: transparent;
                border: none;
                border-bottom: 1px solid rgba(255,255,255,0.10);
                padding: 0.75rem 0.25rem;
                color: inherit;
                font-size: 1rem;
                outline: none;
                transition: border-color 0.2s;
            }
            .auth-input:focus { border-bottom-color: rgba(200,250,95,0.30); }
            .auth-input::placeholder { color: rgba(255,255,255,0.22); }

            /* Nav controls */
            .auth-lang-active   { color: #C8FA5F; }
            .auth-lang-inactive { color: rgba(255,255,255,0.28); }
            .auth-lang-inactive:hover { color: rgba(255,255,255,0.60); }
            .auth-theme-btn { color: rgba(255,255,255,0.38); }
            .auth-theme-btn:hover { background: rgba(255,255,255,0.07); }

            /* Light mode */
            [data-theme="light"] body { background: #ffffff !important; color: #0a0a0a !important; }
            [data-theme="light"] .auth-logo { color: #3d6800 !important; }
            [data-theme="light"] .auth-input { color: #0a0a0a; border-bottom-color: rgba(0,0,0,0.12); }
            [data-theme="light"] .auth-input:focus { border-bottom-color: rgba(61,104,0,0.35); }
            [data-theme="light"] .auth-input::placeholder { color: rgba(0,0,0,0.22); }
            [data-theme="light"] .auth-lang-active   { color: #3d6800 !important; }
            [data-theme="light"] .auth-lang-inactive { color: rgba(0,0,0,0.26) !important; }
            [data-theme="light"] .auth-lang-inactive:hover { color: rgba(0,0,0,0.55) !important; }
            [data-theme="light"] .auth-theme-btn { color: rgba(0,0,0,0.40) !important; }
            [data-theme="light"] .auth-theme-btn:hover { background: rgba(0,0,0,0.06) !important; }
            [data-theme="light"] h1 { color: #0a0a0a !important; }
        </style>
    </head>
    <body class="font-sans antialiased flex flex-col" style="min-height:100dvh; background:#0a0a0a; color:#e5e2e1">

        {{-- Ambient glows (same as landing) --}}
        <div class="fixed top-0 right-0 pointer-events-none -z-10"
             style="width:600px;height:600px;background:radial-gradient(circle,rgba(200,250,95,0.04) 0%,transparent 65%);transform:translate(30%,-30%)"></div>
        <div class="fixed bottom-0 left-0 pointer-events-none -z-10"
             style="width:500px;height:500px;background:radial-gradient(circle,rgba(200,250,95,0.03) 0%,transparent 65%);transform:translate(-30%,30%)"></div>

        {{-- Header — identical style to landing nav --}}
        <header class="w-full flex items-center justify-between px-6 py-5">
            <a href="{{ route('home') }}" class="auth-logo">MyRaces.</a>

            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1.5">
                    <a href="{{ route('language.switch', 'es') }}"
                       class="text-xs font-bold transition-colors {{ app()->getLocale() === 'es' ? 'auth-lang-active' : 'auth-lang-inactive' }}">ES</a>
                    <span class="text-[10px]" style="color:rgba(255,255,255,0.14)">·</span>
                    <a href="{{ route('language.switch', 'en') }}"
                       class="text-xs font-bold transition-colors {{ app()->getLocale() === 'en' ? 'auth-lang-active' : 'auth-lang-inactive' }}">EN</a>
                </div>

                <button onclick="toggleGuestTheme()" aria-label="Toggle theme"
                        class="auth-theme-btn w-8 h-8 flex items-center justify-center rounded-lg transition-colors">
                    <svg id="icon-sun" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/>
                    </svg>
                    <svg id="icon-moon" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
            </div>
        </header>

        {{-- Main content --}}
        <main class="w-full max-w-md mx-auto px-6 flex-grow flex flex-col pb-12">
            {{ $slot }}
        </main>

        {{-- Bottom accent — same as landing --}}
        <div class="w-full h-px" style="background: linear-gradient(90deg, transparent 0%, #C8FA5F 50%, transparent 100%); opacity: 0.22"></div>

        <script>
            function toggleGuestTheme() {
                var html = document.documentElement;
                var next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-theme', next);
                localStorage.setItem('theme', next);
                document.getElementById('icon-sun').classList.toggle('hidden', next === 'light');
                document.getElementById('icon-moon').classList.toggle('hidden', next === 'dark');
            }
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
