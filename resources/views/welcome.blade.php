<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0a">
    <title>MyRaces — {{ __('landing.hero_title_1') }} {{ __('landing.hero_title_highlight') }} {{ __('landing.hero_title_2') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=public-sans:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Apply saved theme before first paint --}}
    <script>
        (function () {
            var t = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>

    <style>
        /* ── LANDING BASE STYLES ───────────────────────────────── */
        .lp-nav        { background: rgba(10,10,10,0.88); border-bottom: 1px solid rgba(255,255,255,0.06); }
        .lp-nav-link   { color: rgba(255,255,255,0.55); font-size:.875rem; font-weight:500; transition:color .15s; }
        .lp-nav-link:hover { color: #fff; }
        .lp-body       { background: #0a0a0a; }

        /* Hero */
        .lp-hero       { background: linear-gradient(160deg, #0a0a0a 0%, #0f1a00 60%, #0a0a0a 100%); }
        .hero-badge    { background: rgba(200,250,95,0.10); color: #C8FA5F; border: 1px solid rgba(200,250,95,0.20); }
        .hero-hl       { color: #C8FA5F; }
        .hero-sub      { color: rgba(255,255,255,0.50); }
        .hero-ghost    { background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.10); color: rgba(255,255,255,0.80); }
        .hero-ghost:hover { background: rgba(255,255,255,0.12); }
        .hero-note     { color: rgba(255,255,255,0.28); }
        .hero-visual   { background: linear-gradient(145deg,#181818 0%,#0d0d0d 100%); border: 1px solid rgba(255,255,255,0.07); }
        .pb-card       { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.10); backdrop-filter: blur(12px); }

        /* Stats */
        .lp-stats      { background: #0a0a0a; }
        .stat-card     { background: #161616; border: 1px solid rgba(255,255,255,0.07); }
        .stat-label    { color: #C8FA5F; }
        .stat-number   { color: #ffffff; }
        .stat-desc     { color: rgba(255,255,255,0.45); }

        /* Features */
        .lp-features       { background: #0f0f0f; }
        .features-h2       { color: #ffffff; }
        .features-sub      { color: rgba(255,255,255,0.45); }
        .feat-card         { background: #161616; border: 1px solid rgba(255,255,255,0.06); }
        .feat-card:hover   { border-color: rgba(255,255,255,0.12); transform: translateY(-2px); }
        .feat-icon-bg      { background: rgba(200,250,95,0.10); }
        .feat-icon         { color: #C8FA5F; }
        .feat-title        { color: #ffffff; }
        .feat-desc         { color: rgba(255,255,255,0.45); }
        .soon-badge        { background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.45); }

        /* CTA card — gradient in class so global [style*] override can't touch it */
        .lp-cta-wrap   { background: #0a0a0a; }
        .cta-card      { background: linear-gradient(145deg,#1c1c1c 0%,#111111 100%); border: 1px solid rgba(255,255,255,0.08); }
        .cta-title     { color: #ffffff; }
        .cta-sub       { color: rgba(255,255,255,0.45); }

        /* Footer */
        .lp-footer     { background: #0a0a0a; border-top: 1px solid rgba(255,255,255,0.06); }
        .footer-link   { color: rgba(255,255,255,0.40); font-size:.875rem; transition:color .15s; }
        .footer-link:hover { color: rgba(255,255,255,0.75); }
        .footer-copy   { color: rgba(255,255,255,0.28); font-size:.8125rem; }

        /* Controls */
        .lp-lang-active   { color: #C8FA5F; }
        .lp-lang-inactive { color: rgba(255,255,255,0.35); }
        .lp-lang-inactive:hover { color: rgba(255,255,255,0.70); }
        .lp-lang-dot      { color: rgba(255,255,255,0.16); }
        .lp-theme-btn     { color: rgba(255,255,255,0.45); }
        .lp-theme-btn:hover { background: rgba(255,255,255,0.07); }

        /* ── LIGHT MODE OVERRIDES ──────────────────────────────── */
        [data-theme="light"] body                  { background: #f5f5f7 !important; color: #0a0a0a !important; }
        [data-theme="light"] .lp-nav               { background: rgba(255,255,255,0.94) !important; border-bottom-color: rgba(0,0,0,0.07) !important; box-shadow: 0 1px 0 rgba(0,0,0,0.04); }
        [data-theme="light"] .lp-nav-link          { color: rgba(0,0,0,0.52) !important; }
        [data-theme="light"] .lp-nav-link:hover    { color: #0a0a0a !important; }
        [data-theme="light"] .lp-body              { background: #ffffff !important; }

        /* Hero — white with subtle lime aura */
        [data-theme="light"] .lp-hero {
            background: radial-gradient(ellipse 65% 50% at 95% 5%, rgba(200,250,95,0.20) 0%, transparent 55%),
                        linear-gradient(180deg,#ffffff 0%,#f7fff0 100%) !important;
        }
        [data-theme="light"] .hero-badge  { background: rgba(74,124,0,0.08) !important; color: #4a7c00 !important; border-color: rgba(74,124,0,0.18) !important; }
        [data-theme="light"] .hero-h1     { color: #0a0a0a !important; }
        [data-theme="light"] .hero-hl {
            color: transparent !important;
            -webkit-text-fill-color: transparent !important;
            background: linear-gradient(120deg,#3d6800 0%,#5a9900 100%) !important;
            -webkit-background-clip: text !important;
            background-clip: text !important;
        }
        [data-theme="light"] .hero-sub    { color: rgba(0,0,0,0.50) !important; }
        [data-theme="light"] .hero-ghost  { background: rgba(0,0,0,0.05) !important; border-color: rgba(0,0,0,0.09) !important; color: #111111 !important; }
        [data-theme="light"] .hero-ghost:hover { background: rgba(0,0,0,0.09) !important; }
        [data-theme="light"] .hero-note   { color: rgba(0,0,0,0.34) !important; }
        /* Hero visual stays dark — it's the "product screenshot" area */

        /* Stats */
        [data-theme="light"] .lp-stats    { background: #f5f5f7 !important; }
        [data-theme="light"] .stat-card   { background: #ffffff !important; border-color: rgba(0,0,0,0.00) !important; box-shadow: 0 1px 3px rgba(0,0,0,0.07), 0 4px 16px rgba(0,0,0,0.06), 0 0 0 1px rgba(0,0,0,0.05) !important; }
        [data-theme="light"] .stat-label  { color: #4a7c00 !important; }
        [data-theme="light"] .stat-number { color: #0a0a0a !important; }
        [data-theme="light"] .stat-desc   { color: rgba(0,0,0,0.48) !important; }

        /* Features */
        [data-theme="light"] .lp-features   { background: #f5f5f7 !important; }
        [data-theme="light"] .features-h2   { color: #0a0a0a !important; }
        [data-theme="light"] .features-sub  { color: rgba(0,0,0,0.48) !important; }
        [data-theme="light"] .feat-card     { background: #ffffff !important; border-color: rgba(0,0,0,0.00) !important; box-shadow: 0 1px 3px rgba(0,0,0,0.07), 0 4px 16px rgba(0,0,0,0.06), 0 0 0 1px rgba(0,0,0,0.05) !important; }
        [data-theme="light"] .feat-card:hover { box-shadow: 0 2px 6px rgba(0,0,0,0.08), 0 10px 28px rgba(0,0,0,0.09), 0 0 0 1px rgba(0,0,0,0.05) !important; }
        [data-theme="light"] .feat-icon-bg  { background: #eeffd4 !important; }
        [data-theme="light"] .feat-icon     { color: #4a7c00 !important; }
        [data-theme="light"] .feat-title    { color: #0a0a0a !important; }
        [data-theme="light"] .feat-desc     { color: rgba(0,0,0,0.50) !important; }
        [data-theme="light"] .soon-badge    { background: rgba(0,0,0,0.06) !important; color: rgba(0,0,0,0.42) !important; }

        /* CTA — force dark even in light mode (no inline style = no global override risk) */
        [data-theme="light"] .lp-cta-wrap  { background: #f0f0f5 !important; }
        [data-theme="light"] .cta-card     { background: linear-gradient(145deg,#1c1c1c 0%,#111111 100%) !important; border-color: rgba(255,255,255,0.10) !important; }
        [data-theme="light"] .cta-title    { color: #ffffff !important; }
        [data-theme="light"] .cta-sub      { color: rgba(255,255,255,0.55) !important; }

        /* Hero visual — keep dark interior readable in light mode */
        /* The hero-visual bg stays dark (it's a class-based gradient, not inline) */
        [data-theme="light"] .hero-visual  { box-shadow: 0 32px 80px rgba(0,0,0,0.22), 0 8px 24px rgba(0,0,0,0.14) !important; }
        /* Protect text-white inside the dark visual */
        [data-theme="light"] .hero-visual .text-white { color: #ffffff !important; }
        /* Prevent global CSS from turning the inner mockup card white */
        [data-theme="light"] .hero-visual [style*="background:#1e1e1e"] { background: #1e1e1e !important; }
        [data-theme="light"] .hero-visual [style*="background:rgba(255,255,255"] { background: rgba(255,255,255,0.05) !important; }
        /* Protect inline text colors inside the dark visual */
        [data-theme="light"] .hero-visual [style*="color:rgba(255,255,255"] { color: inherit !important; }
        /* pb-card: solid dark bg so it always reads well */
        [data-theme="light"] .pb-card      { background: rgba(10,10,10,0.75) !important; border-color: rgba(255,255,255,0.14) !important; }

        /* Footer — light mode */
        [data-theme="light"] .lp-footer    { background: #ffffff !important; border-top-color: rgba(0,0,0,0.08) !important; }
        [data-theme="light"] .footer-link  { color: rgba(0,0,0,0.55) !important; }
        [data-theme="light"] .footer-link:hover { color: rgba(0,0,0,0.85) !important; }
        [data-theme="light"] .footer-copy  { color: rgba(0,0,0,0.42) !important; }
        [data-theme="light"] .lp-lang-active   { color: #4a7c00 !important; }
        [data-theme="light"] .lp-lang-inactive { color: rgba(0,0,0,0.30) !important; }
        [data-theme="light"] .lp-lang-inactive:hover { color: rgba(0,0,0,0.65) !important; }
        [data-theme="light"] .lp-lang-dot  { color: rgba(0,0,0,0.15) !important; }
        [data-theme="light"] .lp-theme-btn { color: rgba(0,0,0,0.44) !important; }
        [data-theme="light"] .lp-theme-btn:hover { background: rgba(0,0,0,0.06) !important; }
        [data-theme="light"] .nav-signin   { color: rgba(0,0,0,0.52) !important; }
        [data-theme="light"] .nav-signin:hover { color: #0a0a0a !important; }
    </style>
</head>
<body class="lp-body font-sans antialiased">

    {{-- ── NAVIGATION ──────────────────────────────────────── --}}
    <header class="lp-nav fixed top-0 inset-x-0 z-50 backdrop-blur-md">
        <div class="max-w-6xl mx-auto px-5 h-16 flex items-center justify-between gap-6">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2 flex-shrink-0">
                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center" style="box-shadow:0 4px 12px rgba(200,250,95,0.35)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="text-white text-[15px] font-bold tracking-tight">My<span class="text-primary">Races</span></span>
            </a>

            {{-- Nav links (desktop) --}}
            <nav class="hidden md:flex items-center gap-6 flex-1">
                <a href="#features" class="lp-nav-link">{{ __('landing.nav_features') }}</a>
                <a href="#pricing"  class="lp-nav-link">{{ __('landing.nav_pricing') }}</a>
                <a href="#about"    class="lp-nav-link">{{ __('landing.nav_about') }}</a>
            </nav>

            {{-- Controls --}}
            <div class="flex items-center gap-2.5">
                {{-- Language --}}
                <div class="hidden sm:flex items-center gap-1.5">
                    <a href="{{ route('language.switch', 'es') }}"
                       class="text-xs font-bold transition-colors {{ app()->getLocale() === 'es' ? 'lp-lang-active' : 'lp-lang-inactive' }}">ES</a>
                    <span class="lp-lang-dot text-[10px]">·</span>
                    <a href="{{ route('language.switch', 'en') }}"
                       class="text-xs font-bold transition-colors {{ app()->getLocale() === 'en' ? 'lp-lang-active' : 'lp-lang-inactive' }}">EN</a>
                </div>

                {{-- Theme toggle --}}
                <button onclick="toggleLpTheme()" aria-label="Toggle theme"
                        class="lp-theme-btn w-8 h-8 flex items-center justify-center rounded-lg transition-colors">
                    <svg id="lp-sun" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/>
                    </svg>
                    <svg id="lp-moon" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary text-sm px-4 py-2">
                        {{ __('landing.go_to_dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-signin hidden sm:block text-sm font-medium text-white/55 hover:text-white transition">
                        {{ __('landing.sign_in') }}
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary text-sm px-4 py-2">
                        {{ __('landing.register_free') }}
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- ── HERO ─────────────────────────────────────────────── --}}
    <section class="lp-hero pt-16 min-h-screen flex items-center">
        <div class="max-w-6xl mx-auto px-5 py-20 md:py-28 w-full">
            <div class="grid md:grid-cols-2 gap-12 lg:gap-20 items-center">

                {{-- Left: Copy --}}
                <div>
                    <div class="hero-badge inline-flex items-center gap-2 text-sm font-semibold px-3 py-1.5 rounded-full mb-6">
                        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-80"></span>
                        {{ __('landing.badge') }}
                    </div>

                    <h1 class="hero-h1 text-white text-4xl md:text-5xl lg:text-6xl font-black leading-[1.08] tracking-tight mb-6">
                        {{ __('landing.hero_title_1') }}
                        <span class="hero-hl"> {{ __('landing.hero_title_highlight') }}</span>
                        {{ __('landing.hero_title_2') }}
                    </h1>

                    <p class="hero-sub text-lg leading-relaxed mb-10 max-w-lg">
                        {{ __('landing.hero_subtitle') }}
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="btn btn-primary text-base px-7 py-3.5 shadow-fab">
                            {{ __('landing.cta_start') }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="{{ route('login') }}" class="hero-ghost inline-flex items-center justify-center gap-2 font-semibold text-base px-7 py-3.5 rounded-full transition">
                            {{ __('landing.cta_login') }}
                        </a>
                    </div>

                    <p class="hero-note text-sm mt-5">{{ __('landing.no_credit_card') }}</p>
                </div>

                {{-- Right: Product visual --}}
                <div class="hero-visual rounded-3xl overflow-hidden relative" style="min-height:420px">

                    {{-- Subtle lime glow --}}
                    <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full blur-3xl pointer-events-none" style="background:rgba(200,250,95,0.06)"></div>

                    {{-- App mockup card --}}
                    <div class="absolute inset-0 flex items-center justify-center p-8">
                        <div class="w-full max-w-xs rounded-2xl p-5" style="background:#1e1e1e;border:1px solid rgba(255,255,255,0.08)">
                            {{-- Race header --}}
                            <div class="flex items-center justify-between mb-4">
                                <span class="badge badge-completed">{{ __('landing.mockup_status') }}</span>
                                <span class="text-[11px] font-medium" style="color:rgba(255,255,255,0.30)">42.195 km</span>
                            </div>
                            <p class="text-white font-black text-xl mb-4 tracking-tight">{{ __('landing.mockup_race') }}</p>

                            {{-- Stats row --}}
                            <div class="grid grid-cols-3 gap-3 mb-5">
                                <div class="rounded-xl px-3 py-2.5 text-center" style="background:rgba(255,255,255,0.05)">
                                    <p class="text-[10px] font-semibold mb-1" style="color:rgba(255,255,255,0.35)">{{ __('landing.mockup_time') }}</p>
                                    <p class="text-white font-black text-lg tabnum leading-none">3:42</p>
                                </div>
                                <div class="rounded-xl px-3 py-2.5 text-center" style="background:rgba(255,255,255,0.05)">
                                    <p class="text-[10px] font-semibold mb-1" style="color:rgba(255,255,255,0.35)">{{ __('landing.mockup_distance') }}</p>
                                    <p class="text-white font-black text-lg tabnum leading-none">42K</p>
                                </div>
                                <div class="rounded-xl px-3 py-2.5 text-center" style="background:rgba(255,255,255,0.05)">
                                    <p class="text-[10px] font-semibold mb-1" style="color:rgba(255,255,255,0.35)">{{ __('landing.mockup_position') }}</p>
                                    <p class="text-white font-black text-lg tabnum leading-none">#347</p>
                                </div>
                            </div>

                            {{-- Mini bar chart --}}
                            <div class="flex items-end gap-1 h-14 mb-1">
                                @foreach([28,42,55,35,68,82,100,75,60,88] as $h)
                                    <div class="flex-1 rounded-sm transition-all"
                                         style="height:{{ $h }}%;background:rgba(200,250,95,{{ 0.15 + ($h/100)*0.65 }})"></div>
                                @endforeach
                            </div>
                            <p class="text-[10px] font-medium text-center" style="color:rgba(255,255,255,0.20)">Ritmo por km</p>
                        </div>
                    </div>

                    {{-- Floating PB card --}}
                    <div class="pb-card absolute bottom-6 right-6 rounded-2xl px-4 py-3 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(74,222,128,0.15)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" style="color:#4ade80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold tracking-widest uppercase" style="color:rgba(255,255,255,0.35)">{{ __('landing.pb_label') }}</p>
                            <p class="text-white font-black text-2xl tabnum leading-none">38:42</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── STATS CARDS ─────────────────────────────────────── --}}
    <section class="lp-stats py-10" id="pricing">
        <div class="max-w-6xl mx-auto px-5">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                {{-- Distances --}}
                <div class="stat-card rounded-2xl px-7 py-6 transition-all">
                    <p class="stat-label text-xs font-bold uppercase tracking-widest mb-3">{{ __('landing.stat_label_distances') }}</p>
                    <p class="stat-number text-4xl font-black tabnum mb-2">5K → 42K</p>
                    <div class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" style="color:#C8FA5F" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                        <p class="stat-desc text-sm font-medium">{{ __('landing.stat_distances') }}</p>
                    </div>
                </div>

                {{-- Cost --}}
                <div class="stat-card rounded-2xl px-7 py-6 transition-all">
                    <p class="stat-label text-xs font-bold uppercase tracking-widest mb-3">{{ __('landing.stat_label_cost') }}</p>
                    <p class="stat-number text-4xl font-black tabnum mb-2">100% Free</p>
                    <div class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" style="color:#4ade80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="stat-desc text-sm font-medium">{{ __('landing.stat_free') }}</p>
                    </div>
                </div>

                {{-- Records --}}
                <div class="stat-card rounded-2xl px-7 py-6 transition-all">
                    <p class="stat-label text-xs font-bold uppercase tracking-widest mb-3">{{ __('landing.stat_label_records') }}</p>
                    <p class="stat-number text-4xl font-black tabnum mb-2">∞</p>
                    <div class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" style="color:#4ade80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <p class="stat-desc text-sm font-medium">{{ __('landing.stat_races') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── FEATURES ─────────────────────────────────────────── --}}
    <section class="lp-features py-24" id="features">
        <div class="max-w-6xl mx-auto px-5">
            {{-- Left-aligned heading --}}
            <div class="mb-12">
                <h2 class="features-h2 text-3xl md:text-4xl font-black tracking-tight mb-3">{{ __('landing.features_title') }}</h2>
                <p class="features-sub text-lg max-w-lg">{{ __('landing.features_subtitle') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach ([
                    ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => 'feat_calendar_title', 'desc' => 'feat_calendar_desc', 'soon' => false],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'feat_records_title', 'desc' => 'feat_records_desc', 'soon' => false],
                    ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'feat_expenses_title', 'desc' => 'feat_expenses_desc', 'soon' => false],
                    ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'feat_trail_title', 'desc' => 'feat_trail_desc', 'soon' => false],
                    ['icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z', 'title' => 'feat_mobile_title', 'desc' => 'feat_mobile_desc', 'soon' => false],
                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'feat_social_title', 'desc' => 'feat_social_desc', 'soon' => true],
                ] as $feat)
                    <div class="feat-card rounded-2xl p-7 transition-all duration-200 relative">
                        @if($feat['soon'])
                            <span class="soon-badge absolute top-5 right-5 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                                {{ __('landing.soon') }}
                            </span>
                        @endif
                        <div class="feat-icon-bg w-11 h-11 rounded-xl flex items-center justify-center mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="feat-icon w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feat['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="feat-title font-bold text-lg mb-2">{{ __('landing.' . $feat['title']) }}</h3>
                        <p class="feat-desc text-sm leading-relaxed">{{ __('landing.' . $feat['desc']) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── CTA CARD ─────────────────────────────────────────── --}}
    <section class="lp-cta-wrap py-16 px-5" id="about">
        <div class="max-w-6xl mx-auto">
            <div class="cta-card rounded-3xl py-20 px-8 text-center">
                <div class="w-14 h-14 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-6"
                     style="box-shadow:0 8px 28px rgba(200,250,95,0.30)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h2 class="cta-title text-3xl md:text-4xl font-black tracking-tight mb-4">{{ __('landing.cta_title') }}</h2>
                <p class="cta-sub text-lg mb-8 max-w-md mx-auto">{{ __('landing.cta_subtitle') }}</p>
                <a href="{{ route('register') }}" class="btn btn-primary text-base px-9 py-4 shadow-fab">
                    {{ __('landing.cta_btn') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ── FOOTER ───────────────────────────────────────────── --}}
    <footer class="lp-footer py-7">
        <div class="max-w-6xl mx-auto px-5 flex flex-col md:flex-row items-center justify-between gap-5">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2">
                <div class="w-6 h-6 bg-primary rounded-md flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="footer-link font-semibold">MyRaces</span>
            </a>

            {{-- Footer links --}}
            <nav class="flex items-center gap-6">
                <a href="#" class="footer-link">{{ __('landing.footer_terms') }}</a>
                <a href="#" class="footer-link">{{ __('landing.footer_privacy') }}</a>
                <a href="#" class="footer-link">{{ __('landing.footer_contact') }}</a>
                {{-- Mobile lang switcher --}}
                <div class="flex sm:hidden items-center gap-1.5 ml-2">
                    <a href="{{ route('language.switch', 'es') }}"
                       class="text-xs font-bold transition-colors {{ app()->getLocale() === 'es' ? 'lp-lang-active' : 'lp-lang-inactive' }}">ES</a>
                    <span class="lp-lang-dot text-[10px]">·</span>
                    <a href="{{ route('language.switch', 'en') }}"
                       class="text-xs font-bold transition-colors {{ app()->getLocale() === 'en' ? 'lp-lang-active' : 'lp-lang-inactive' }}">EN</a>
                </div>
            </nav>

            {{-- Copyright --}}
            <p class="footer-copy">{{ str_replace(':year', now()->year, __('landing.footer_copy')) }}</p>
        </div>
    </footer>

    <script>
        function toggleLpTheme() {
            var html = document.documentElement;
            var next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            document.getElementById('lp-sun').classList.toggle('hidden', next === 'light');
            document.getElementById('lp-moon').classList.toggle('hidden', next === 'dark');
        }
        (function () {
            var t = document.documentElement.getAttribute('data-theme') || 'dark';
            var s = document.getElementById('lp-sun');
            var m = document.getElementById('lp-moon');
            if (s && m) {
                s.classList.toggle('hidden', t === 'light');
                m.classList.toggle('hidden', t === 'dark');
            }
        })();
    </script>

</body>
</html>
