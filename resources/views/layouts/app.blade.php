<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ auth()->user()?->profile?->theme ?? 'dark' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0a0a0a">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

        <title>@yield('page_title', config('app.name', 'MyRaces')) — MyRaces</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=public-sans:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @if((auth()->user()?->profile?->theme ?? 'dark') === 'light')
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[style]').forEach(function (el) {
                var s = el.getAttribute('style');
                if (!s) return;
                var changed = false;
                var next = s;
                // Profile hero gradient
                if (next.indexOf('#0f1a00') !== -1) {
                    next = next.replace(/linear-gradient\([^;]+\)/, 'linear-gradient(135deg,#e4f5cc 0%,#cfe89e 50%,#b8db6e 100%)');
                    changed = true;
                }
                // FAB ring
                if (next.indexOf('rgba(10,10,10,0.96)') !== -1) {
                    next = next.replace('rgba(10,10,10,0.96)', 'rgba(242,242,247,0.96)');
                    changed = true;
                }
                if (changed) { el.setAttribute('style', next); }
            });
        });
        </script>
        @endif
    </head>
    <body class="font-sans antialiased bg-bg-app text-white min-h-screen flex flex-col">

        {{-- ── SIDEBAR (desktop ≥ md) ─────────────────────────── --}}
        <aside class="hidden md:flex flex-col fixed inset-y-0 left-0 w-64 z-50 bg-bg-app" style="border-right:1px solid rgba(255,255,255,0.06)">

            <div class="h-[60px] flex items-center px-5 flex-shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center" style="box-shadow:0 4px 12px rgba(200,250,95,0.35)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" class="text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="font-black text-[15px] text-white tracking-tight">My<span class="text-primary">Races</span></span>
                </a>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                @php
                    $navItems = [
                        ['route' => 'dashboard',              'match' => 'dashboard',                 'label' => __('races.dashboard'),
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
                        ['route' => 'races.index',            'match' => 'races.index|races.show',    'label' => __('races.my_races'),
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>'],
                        ['route' => 'races.create',           'match' => 'races.create|races.edit',   'label' => __('races.add_race'),
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4v16m8-8H4"/>'],
                        ['route' => 'calendar.index',         'match' => 'calendar.*',                'label' => 'Calendario',
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
                        ['route' => 'stats.index',            'match' => 'stats.*',                   'label' => __('races.stats'),
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>'],
                        ['route' => 'expenses.index',         'match' => 'expenses.*',                'label' => 'Gastos',
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                        ['route' => 'personal-records.index', 'match' => 'personal-records.*',        'label' => 'Récords',
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>'],
                        ['route' => 'gear.index',             'match' => 'gear.*',                    'label' => 'Material',
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>'],
                        ['route' => 'coach.index',            'match' => 'coach.*',                   'label' => 'RaceCoach',
                         'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 10V3L4 14h7v7l9-11h-7z"/>'],
                    ];
                @endphp
                @foreach($navItems as $item)
                    @php $active = collect(explode('|', $item['match']))->contains(fn($p) => request()->routeIs($p)); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-150
                              {{ $active ? 'bg-primary/15 text-primary' : 'hover:bg-white/[0.05] hover:text-white' }}"
                       style="{{ !$active ? 'color:rgba(255,255,255,0.40)' : '' }}">
                        <svg style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $item['icon'] !!}</svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="px-3 py-4 flex-shrink-0 space-y-0.5" style="border-top:1px solid rgba(255,255,255,0.06)">
                <div class="flex items-center gap-2 px-3.5 py-2 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;flex-shrink:0;color:rgba(255,255,255,0.20)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                    <a href="{{ route('language.switch', 'es') }}" class="text-[11px] font-bold px-1.5 py-0.5 rounded transition-colors {{ app()->getLocale() === 'es' ? 'text-primary' : 'hover:text-white/70' }}" style="{{ app()->getLocale() !== 'es' ? 'color:rgba(255,255,255,0.30)' : '' }}">ES</a>
                    <span style="color:rgba(255,255,255,0.15)" class="text-xs">/</span>
                    <a href="{{ route('language.switch', 'en') }}" class="text-[11px] font-bold px-1.5 py-0.5 rounded transition-colors {{ app()->getLocale() === 'en' ? 'text-primary' : 'hover:text-white/70' }}" style="{{ app()->getLocale() !== 'en' ? 'color:rgba(255,255,255,0.30)' : '' }}">EN</a>
                </div>
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-150 {{ request()->is('admin*') ? 'bg-primary/15 text-primary' : 'hover:bg-white/[0.05]' }}" style="{{ !request()->is('admin*') ? 'color:rgba(255,255,255,0.40)' : '' }}">
                        <svg style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Admin
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-150 {{ request()->routeIs('profile.*') ? 'bg-primary/15 text-primary' : 'hover:bg-white/[0.05]' }}" style="{{ !request()->routeIs('profile.*') ? 'color:rgba(255,255,255,0.40)' : '' }}">
                    <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-black font-black text-[11px] flex-shrink-0 overflow-hidden">
                        @if(auth()->user()->profile?->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" alt="" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <span class="truncate">{{ auth()->user()->name }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full px-3.5 py-2.5 rounded-xl text-[13px] font-semibold transition-all duration-150 hover:bg-white/[0.05]" style="color:rgba(255,255,255,0.30)">
                        <svg style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </aside>

        {{-- ── MAIN ────────────────────────────────────────────── --}}
        <div class="md:ml-64 min-h-screen flex flex-col">

            {{-- Mobile header --}}
            <header class="md:hidden sticky top-0 z-40 bg-bg-app/90 backdrop-blur-xl" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                <div class="relative flex items-center justify-between px-5 h-[58px]">
                    {{-- Left: back button or logo --}}
                    <div class="relative z-10">
                        @hasSection('back_url')
                            <a href="@yield('back_url')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/[0.06]">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </a>
                        @else
                            <div class="w-9 h-9 flex items-center justify-center">
                                <div class="w-7 h-7 bg-primary rounded-lg flex items-center justify-center" style="box-shadow:0 4px 12px rgba(200,250,95,0.30)">
                                    <svg class="text-black" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Title: always geometrically centred --}}
                    <h1 class="absolute inset-x-0 text-center text-[17px] font-black tracking-tight text-white pointer-events-none px-14 truncate">
                        @yield('page_title', config('app.name'))
                    </h1>

                    {{-- Right: action or avatar --}}
                    <div class="relative z-10 flex items-center gap-2">
                        @hasSection('header_action')
                            @yield('header_action')
                        @else
                            <a href="{{ route('profile.edit') }}"
                               class="w-9 h-9 rounded-full bg-primary flex items-center justify-center overflow-hidden flex-shrink-0">
                                @if(auth()->user()->profile?->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <span class="text-black font-black text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                @endif
                            </a>
                        @endif
                    </div>
                </div>
            </header>

            {{-- Desktop page header --}}
            <div class="hidden md:flex items-center justify-between h-[60px] px-6 sticky top-0 z-30" style="background:rgba(10,10,10,0.90);backdrop-filter:blur(16px);border-bottom:1px solid rgba(255,255,255,0.06)">
                <div class="flex items-center gap-3">
                    @hasSection('back_url')
                        <a href="@yield('back_url')" class="btn btn-ghost text-sm py-1.5 px-3 -ml-1 gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Volver
                        </a>
                        <span style="color:rgba(255,255,255,0.12)">|</span>
                    @endif
                    <h1 class="text-[15px] font-black text-white">@yield('page_title', config('app.name'))</h1>
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
                    <div class="text-white text-sm font-semibold px-4 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3" style="background:rgba(30,30,30,0.98);border:1px solid rgba(255,255,255,0.10)">
                        <div class="w-5 h-5 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                            <svg style="width:10px;height:10px" class="text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            {{-- Content --}}
            <main class="flex-1 pb-[80px] md:pb-0">
                {{ $slot }}
            </main>

            {{-- ── BOTTOM NAV (mobile) ──────────────────────────── --}}
            <div x-data="{ moreOpen: false }">

                {{-- More drawer overlay --}}
                <div x-show="moreOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     x-cloak
                     @click="moreOpen = false"
                     class="md:hidden fixed inset-0 z-40"
                     style="background:rgba(0,0,0,0.75);backdrop-filter:blur(4px)">
                </div>

                {{-- More drawer panel --}}
                <div x-show="moreOpen"
                     x-transition:enter="transition ease-out duration-250"
                     x-transition:enter-start="translate-y-full"
                     x-transition:enter-end="translate-y-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="translate-y-0"
                     x-transition:leave-end="translate-y-full"
                     x-cloak
                     class="md:hidden fixed bottom-0 left-0 right-0 z-50 rounded-t-3xl pb-safe"
                     style="background:#111111;border-top:1px solid rgba(255,255,255,0.08)">

                    <div class="px-5 pt-4 pb-2 flex items-center justify-between">
                        <p class="text-[11px] font-black uppercase tracking-[0.18em]" style="color:rgba(255,255,255,0.30)">Menú</p>
                        <button @click="moreOpen = false" class="w-8 h-8 flex items-center justify-center rounded-full" style="background:rgba(255,255,255,0.08)">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="px-4 pb-6 grid grid-cols-3 gap-2.5">
                        @foreach($mobileDrawer as $item)
                            @php $active = $item->isActive(); @endphp
                            <a href="{{ route($item->route_name) }}" @click="moreOpen = false"
                               class="flex flex-col items-center gap-2 py-4 px-2 rounded-2xl transition-all
                                      {{ $active ? 'bg-primary/15 text-primary' : 'text-white/50 hover:bg-white/[0.06] hover:text-white' }}"
                               style="background:{{ $active ? '' : 'rgba(255,255,255,0.04)' }}">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $item->icon_path }}"/>
                                </svg>
                                <span class="text-[11px] font-bold text-center leading-tight">{{ $item->label }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Bottom nav bar --}}
                @php
                    $bnItems   = $mobileBottomNav->values();
                    $leftItems = $bnItems->take(2);
                    $rightItems = $bnItems->slice(2);
                    $drawerActive = $mobileDrawer->contains(fn ($i) => $i->isActive());
                @endphp
                <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 flex items-center justify-around px-2 h-[72px]"
                     style="background:rgba(10,10,10,0.96);backdrop-filter:blur(20px);border-top:1px solid rgba(255,255,255,0.07)">

                    @foreach($leftItems as $item)
                        @php $active = $item->isActive(); @endphp
                        <a href="{{ route($item->route_name) }}"
                           class="flex flex-col items-center gap-1 w-14 {{ $active ? 'text-primary' : 'text-white/40' }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 style="width:24px;height:24px;stroke-width:{{ $active ? '2.25' : '1.75' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item->icon_path }}"/>
                            </svg>
                            <span class="text-[10px] font-bold leading-none">{{ $item->label }}</span>
                        </a>
                    @endforeach

                    {{-- FAB --}}
                    <div class="relative -top-5">
                        <a href="{{ route('races.create') }}"
                           class="w-14 h-14 bg-primary rounded-full flex items-center justify-center text-black active:scale-95 transition-transform"
                           style="box-shadow: 0 8px 24px rgba(200,250,95,0.45), 0 0 0 4px rgba(10,10,10,0.96)">
                            <svg style="width:26px;height:26px" class="text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                        </a>
                    </div>

                    @foreach($rightItems as $item)
                        @php $active = $item->isActive(); @endphp
                        <a href="{{ route($item->route_name) }}"
                           class="flex flex-col items-center gap-1 w-14 {{ $active ? 'text-primary' : 'text-white/40' }}">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 style="width:24px;height:24px;stroke-width:{{ $active ? '2.25' : '1.75' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item->icon_path }}"/>
                            </svg>
                            <span class="text-[10px] font-bold leading-none">{{ $item->label }}</span>
                        </a>
                    @endforeach

                    <button @click="moreOpen = true"
                            class="flex flex-col items-center gap-1 w-14 {{ $drawerActive ? 'text-primary' : 'text-white/40' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:24px;height:24px;stroke-width:1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <span class="text-[10px] font-bold leading-none">Más</span>
                    </button>

                </nav>
            </div>

        </div>

        {{-- ── COOKIE BANNER ────────────────────────────────────── --}}
        @php
            $cookieConsented = auth()->check() && auth()->user()->profile?->cookie_consented_at !== null;
        @endphp
        <div x-data="{
                show: false,
                panel: false,
                functional: true,
                analytics: true,
                isAuth: {{ auth()->check() ? 'true' : 'false' }},
                alreadyConsented: {{ $cookieConsented ? 'true' : 'false' }},
                init() {
                    if (this.alreadyConsented) { return; }
                    if (!this.isAuth) {
                        const saved = localStorage.getItem('cookie_consent');
                        if (saved) { return; }
                    }
                    setTimeout(() => { this.show = true; }, 600);
                },
                acceptAll() {
                    this.functional = true;
                    this.analytics  = true;
                    this.save();
                },
                acceptNecessary() {
                    this.functional = false;
                    this.analytics  = false;
                    this.save();
                },
                save() {
                    const payload = { functional: this.functional, analytics: this.analytics };
                    if (this.isAuth) {
                        fetch('{{ route('cookie.consent') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                            },
                            body: JSON.stringify(payload)
                        });
                    } else {
                        localStorage.setItem('cookie_consent', JSON.stringify({ ...payload, at: new Date().toISOString() }));
                    }
                    this.show = false;
                    this.panel = false;
                }
             }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-4"
             x-cloak
             class="fixed bottom-[80px] md:bottom-6 left-4 right-4 md:left-auto md:right-6 md:max-w-sm z-[60]"
             style="border-radius:20px;background:var(--color-bg-card);border:1px solid rgba(255,255,255,0.10);box-shadow:0 24px 60px rgba(0,0,0,0.30)">

            <div class="px-5 pt-5 pb-4">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl bg-primary/15 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-black text-white leading-tight">Privacidad y cookies</p>
                        <p class="text-xs mt-1 leading-relaxed" style="color:rgba(255,255,255,0.50)">Usamos cookies para mejorar tu experiencia. Puedes elegir qué tipos aceptas.</p>
                    </div>
                </div>

                {{-- Customization panel --}}
                <div x-show="panel" x-transition x-cloak class="mb-4 space-y-2.5 pt-2" style="border-top:1px solid rgba(255,255,255,0.07)">
                    <div class="flex items-center justify-between py-1">
                        <div>
                            <p class="text-xs font-bold text-white">Necesarias</p>
                            <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35)">Sesión, seguridad. Siempre activas.</p>
                        </div>
                        <div class="w-9 h-5 rounded-full bg-primary flex-shrink-0 opacity-60 cursor-not-allowed"></div>
                    </div>
                    <div class="flex items-center justify-between py-1">
                        <div>
                            <p class="text-xs font-bold text-white">Funcionales</p>
                            <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35)">Idioma, tema y preferencias.</p>
                        </div>
                        <button type="button" @click="functional = !functional"
                                class="w-9 h-5 rounded-full flex-shrink-0 transition-colors duration-200 relative"
                                :class="functional ? 'bg-primary' : 'bg-white/20'">
                            <span class="absolute left-0.5 top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform duration-200"
                                  :class="functional ? 'translate-x-4' : 'translate-x-0'"></span>
                        </button>
                    </div>
                    <div class="flex items-center justify-between py-1">
                        <div>
                            <p class="text-xs font-bold text-white">Analíticas</p>
                            <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35)">Estadísticas de uso anónimas.</p>
                        </div>
                        <button type="button" @click="analytics = !analytics"
                                class="w-9 h-5 rounded-full flex-shrink-0 transition-colors duration-200 relative"
                                :class="analytics ? 'bg-primary' : 'bg-white/20'">
                            <span class="absolute left-0.5 top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform duration-200"
                                  :class="analytics ? 'translate-x-4' : 'translate-x-0'"></span>
                        </button>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <div class="flex gap-2">
                        <button type="button" @click="acceptNecessary()"
                                class="flex-1 text-xs font-bold py-2.5 rounded-xl transition-colors"
                                style="background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.60)">
                            Solo necesarias
                        </button>
                        <button type="button"
                                @click="panel ? save() : acceptAll()"
                                class="flex-1 text-xs font-black py-2.5 rounded-xl bg-primary text-black transition-opacity hover:opacity-90">
                            <span x-text="panel ? 'Guardar' : 'Aceptar todo'"></span>
                        </button>
                    </div>
                    <button type="button" @click="panel = !panel"
                            class="text-[11px] font-semibold transition-colors text-center"
                            style="color:rgba(255,255,255,0.35)">
                        <span x-text="panel ? 'Ocultar opciones' : 'Personalizar cookies'"></span>
                    </button>
                </div>
            </div>
        </div>

    </body>
</html>
