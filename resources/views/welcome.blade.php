<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MyRaces — Tu historial de running</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-slate-900">

    {{-- NAV --}}
    <header class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-6xl mx-auto px-5 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="text-lg font-bold tracking-tight">My<span class="text-orange-500">Races</span></span>
            </a>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-400 text-white font-semibold px-4 py-2 rounded-lg transition text-sm">
                        Ir al panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition hidden sm:block">
                        Iniciar sesión
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-400 text-white font-semibold px-4 py-2 rounded-lg transition text-sm">
                        Registrarse gratis
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- HERO --}}
    <section class="pt-16 min-h-screen flex items-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-orange-500/5 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-6xl mx-auto px-5 py-24 md:py-32 relative w-full">
            <div class="max-w-3xl">
                <div class="inline-flex items-center gap-2 bg-orange-500/15 text-orange-400 text-sm font-semibold px-3 py-1.5 rounded-full mb-6 border border-orange-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Para corredores de competición
                </div>
                <h1 class="text-4xl md:text-6xl font-extrabold text-white leading-tight mb-6">
                    Lleva el control de<br>
                    <span class="text-orange-400">todas tus carreras</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-400 mb-10 leading-relaxed max-w-2xl">
                    Registra tus tiempos, récords personales, gastos e historial completo.
                    Tu diario de running de competición, siempre a mano.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-orange-500 hover:bg-orange-400 text-white font-bold px-8 py-4 rounded-xl transition text-lg shadow-xl shadow-orange-500/20">
                        Empezar gratis
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold px-8 py-4 rounded-xl transition text-lg border border-white/10">
                        Ya tengo cuenta
                    </a>
                </div>
                <p class="text-slate-600 text-sm mt-5">Sin tarjeta de crédito &middot; Gratis para siempre</p>
            </div>
        </div>
    </section>

    {{-- STATS STRIP --}}
    <section class="bg-orange-500 py-10">
        <div class="max-w-6xl mx-auto px-5">
            <div class="grid grid-cols-3 gap-6 text-center text-white">
                <div>
                    <p class="text-2xl md:text-4xl font-extrabold">5K → 42K</p>
                    <p class="text-orange-100 text-sm mt-1">Todas las distancias</p>
                </div>
                <div>
                    <p class="text-2xl md:text-4xl font-extrabold">100% Free</p>
                    <p class="text-orange-100 text-sm mt-1">Sin coste alguno</p>
                </div>
                <div>
                    <p class="text-2xl md:text-4xl font-extrabold">∞</p>
                    <p class="text-orange-100 text-sm mt-1">Carreras registradas</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES --}}
    <section class="py-24 bg-slate-50">
        <div class="max-w-6xl mx-auto px-5">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Todo lo que necesitas</h2>
                <p class="text-slate-500 text-lg max-w-xl mx-auto">Diseñado por y para corredores que compiten. Sin funciones innecesarias, solo lo que importa.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Calendario de carreras</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Visualiza todas tus carreras pasadas y futuras en un calendario. Nunca olvides una inscripción.</p>
                </div>
                <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Récords personales</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Tus mejores tiempos en 5K, 10K, Media Maratón y Maratón, actualizados automáticamente.</p>
                </div>
                <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Control de gastos</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Sabe exactamente cuánto inviertes en inscripciones cada año. Por carrera y en total.</p>
                </div>
                <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Road, Trail y más</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Asfalto, trail, montaña, pista, campo a través. Categoriza cada carrera por su modalidad.</p>
                </div>
                <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Mobile first</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Diseñado para funcionar perfectamente desde el móvil, justo al cruzar la meta.</p>
                </div>
                <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all">
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg mb-2">Red social (próx.)</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Comparte carreras, sigue a otros corredores y compara resultados. En desarrollo.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24 bg-slate-900">
        <div class="max-w-2xl mx-auto px-5 text-center">
            <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-2xl shadow-orange-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">¿Listo para empezar?</h2>
            <p class="text-slate-400 text-lg mb-8">Registra tu primera carrera hoy. Es gratis y siempre lo será.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-400 text-white font-bold px-10 py-4 rounded-xl transition text-lg shadow-xl shadow-orange-500/20">
                Crear cuenta gratis
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </section>

    <footer class="bg-slate-950 py-8 border-t border-slate-800">
        <div class="max-w-6xl mx-auto px-5 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-orange-500 rounded-md flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="text-slate-400 text-sm font-semibold">MyRaces</span>
            </div>
            <p class="text-slate-600 text-sm">Hecho con ❤️ para corredores de competición</p>
        </div>
    </footer>

</body>
</html>
