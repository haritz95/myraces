<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#f8f6f6">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">

        <title>@yield('page_title', config('app.name', 'MyRaces')) — MyRaces</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=public-sans:300,400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-bg-warm min-h-screen flex flex-col">

        {{-- ── SIDEBAR (desktop ≥ md) ─────────────────────────── --}}
        <aside class="hidden md:flex flex-col fixed inset-y-0 left-0 w-64 z-50" style="background:#221610">

            <div class="h-[60px] flex items-center px-5 flex-shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.07)">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center" style="box-shadow:0 4px 12px rgba(236,91,19,0.45)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" class="text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-[15px] text-white tracking-tight">My<span class="text-primary">Races</span></span>
                </a>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                @php
                    $navItems = [
                        ['route' => 'dashboard',           'match' => 'dashboard',                    'label' => __('races.dashboard'),
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
                        ['route' => 'races.index',         'match' => 'races.index|races.show',       'label' => __('races.my_races'),
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>'],
                        ['route' => 'races.create',        'match' => 'races.create|races.edit',      'label' => __('races.add_race'),
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4v16m8-8H4"/>'],
                        ['route' => 'calendar.index',      'match' => 'calendar.*',                   'label' => 'Calendario',
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
                        ['route' => 'stats.index',         'match' => 'stats.*',                      'label' => __('races.stats'),
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
                        ['route' => 'expenses.index',      'match' => 'expenses.*',                   'label' => 'Gastos',
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                        ['route' => 'personal-records.index', 'match' => 'personal-records.*',        'label' => 'Récords',
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>'],
                        ['route' => 'gear.index',          'match' => 'gear.*',                       'label' => 'Material',
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>'],
                        ['route' => 'coach.index',         'match' => 'coach.*',                      'label' => 'RaceCoach',
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 10V3L4 14h7v7l9-11h-7z"/>'],
                    ];
                @endphp
                @foreach($navItems as $item)
                    @php $active = collect(explode('|', $item['match']))->contains(fn($p) => request()->routeIs($p)); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-150
                              {{ $active ? 'bg-primary/15 text-primary' : 'text-white/45 hover:text-white/80 hover:bg-white/[0.05]' }}">
                        <svg style="width:18px;height:18px;flex-shrink:0" class="{{ $active ? 'text-primary' : 'text-current' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $item['icon'] !!}</svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="px-3 py-4 flex-shrink-0 space-y-0.5" style="border-top:1px solid rgba(255,255,255,0.07)">
                <div class="flex items-center gap-2 px-3.5 py-2 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" class="flex-shrink-0" style="color:rgba(255,255,255,0.2)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                    <a href="{{ route('language.switch', 'es') }}" class="text-[11px] font-bold px-1.5 py-0.5 rounded transition-colors {{ app()->getLocale() === 'es' ? 'text-primary' : 'hover:text-white/70' }}" style="{{ app()->getLocale() !== 'es' ? 'color:rgba(255,255,255,0.30)' : '' }}">ES</a>
                    <span style="color:rgba(255,255,255,0.15)" class="text-xs">/</span>
                    <a href="{{ route('language.switch', 'en') }}" class="text-[11px] font-bold px-1.5 py-0.5 rounded transition-colors {{ app()->getLocale() === 'en' ? 'text-primary' : 'hover:text-white/70' }}" style="{{ app()->getLocale() !== 'en' ? 'color:rgba(255,255,255,0.30)' : '' }}">EN</a>
                </div>
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-150 {{ request()->is('admin*') ? 'bg-primary/15 text-primary' : 'text-white/45 hover:text-white/80 hover:bg-white/[0.05]' }}">
                        <svg style="width:18px;height:18px;flex-shrink:0" class="{{ request()->is('admin*') ? 'text-primary' : 'text-current' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Admin
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-150 {{ request()->routeIs('profile.*') ? 'bg-primary/15 text-primary' : 'text-white/45 hover:text-white/80 hover:bg-white/[0.05]' }}">
                    <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-white font-bold text-[11px] flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="truncate">{{ auth()->user()->name }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-3.5 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-150" style="color:rgba(255,255,255,0.30)" onmouseover="this.style.color='rgba(255,255,255,0.70)';this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.color='rgba(255,255,255,0.30)';this.style.background=''">
                        <svg style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </aside>

        {{-- ── MAIN ────────────────────────────────────────────── --}}
        <div class="md:ml-64 min-h-screen flex flex-col">

            {{-- Mobile header (matches example exactly) --}}
            <header class="md:hidden sticky top-0 z-50 bg-bg-warm/80 backdrop-blur-md border-b border-slate-200">
                <div class="flex items-center justify-between px-6 py-4">
                    @hasSection('back_url')
                        <a href="@yield('back_url')" class="text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                    @else
                        <button class="text-slate-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    @endif

                    <h1 class="text-xl font-bold tracking-tight">@yield('page_title', config('app.name'))</h1>

                    <div class="flex items-center gap-2">
                        @hasSection('header_action')
                            @yield('header_action')
                        @else
                            <a href="{{ route('profile.edit') }}"
                               class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center overflow-hidden">
                                <span class="text-primary font-bold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </a>
                        @endif
                    </div>
                </div>
            </header>

            {{-- Desktop page header --}}
            <div class="hidden md:flex items-center justify-between h-[60px] px-6 bg-white sticky top-0 z-30 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    @hasSection('back_url')
                        <a href="@yield('back_url')" class="btn btn-ghost text-sm py-1.5 px-3 -ml-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Volver
                        </a>
                        <span class="text-slate-200">|</span>
                    @endif
                    <h1 class="text-[15px] font-bold text-slate-900">@yield('page_title', config('app.name'))</h1>
                </div>
                <div class="flex items-center gap-2">@yield('header_action')</div>
            </div>

            {{-- Toast --}}
            @if (session('success'))
                <div x-data="{ show: true }"
                     x-show="show"
                     x-init="setTimeout(() => show = false, 3500)"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-3"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     x-cloak
                     class="fixed bottom-[88px] md:bottom-6 md:right-6 inset-x-4 md:inset-x-auto z-50 pointer-events-none">
                    <div class="bg-bg-dark text-white text-sm font-medium px-4 py-3.5 rounded-xl shadow-2xl flex items-center gap-3">
                        <div class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg style="width:10px;height:10px" class="text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            {{-- Content --}}
            <main class="flex-1 pb-[76px] md:pb-0">
                {{ $slot }}
            </main>

            {{-- ── BOTTOM NAV (matches example) ────────────────── --}}
            <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-lg border-t border-slate-200 px-6 py-3 flex items-center justify-between z-50">

                <a href="{{ route('dashboard') }}"
                   class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-slate-400' }}">
                    <svg fill="{{ request()->routeIs('dashboard') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" style="width:24px;height:24px;stroke-width:{{ request()->routeIs('dashboard') ? '0' : '1.75' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="text-[10px] {{ request()->routeIs('dashboard') ? 'font-bold' : 'font-medium' }} leading-none">Inicio</span>
                </a>

                <a href="{{ route('races.index') }}"
                   class="flex flex-col items-center gap-1 {{ request()->routeIs('races.index') || request()->routeIs('races.show') ? 'text-primary' : 'text-slate-400' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         style="width:24px;height:24px;stroke-width:{{ request()->routeIs('races.index') || request()->routeIs('races.show') ? '2.25' : '1.75' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="text-[10px] {{ request()->routeIs('races.index') || request()->routeIs('races.show') ? 'font-bold' : 'font-medium' }} leading-none">Carreras</span>
                </a>

                {{-- FAB elevated (matches example's -top-6 pattern) --}}
                <div class="relative -top-6">
                    <a href="{{ route('races.create') }}"
                       class="w-14 h-14 bg-primary rounded-full flex items-center justify-center text-white border-4 border-bg-warm active:scale-95 transition-transform"
                       style="box-shadow: 0 8px 24px rgba(236,91,19,0.45)">
                        <svg style="width:26px;height:26px" class="text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </a>
                </div>

                <a href="{{ route('expenses.index') }}"
                   class="flex flex-col items-center gap-1 {{ request()->routeIs('expenses.*') ? 'text-primary' : 'text-slate-400' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         style="width:24px;height:24px;stroke-width:{{ request()->routeIs('expenses.*') ? '2.25' : '1.75' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-[10px] {{ request()->routeIs('expenses.*') ? 'font-bold' : 'font-medium' }} leading-none">Gastos</span>
                </a>

                <a href="{{ route('coach.index') }}"
                   class="flex flex-col items-center gap-1 {{ request()->routeIs('coach.*') ? 'text-primary' : 'text-slate-400' }}">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         style="width:24px;height:24px;stroke-width:{{ request()->routeIs('coach.*') ? '2.25' : '1.75' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-[10px] {{ request()->routeIs('coach.*') ? 'font-bold' : 'font-medium' }} leading-none">Coach</span>
                </a>

            </nav>
        </div>

        @livewireScripts
    </body>
</html>
