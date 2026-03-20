<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0a">
    <title>MyRaces — {{ __('landing.hero_title_1') }} {{ __('landing.hero_title_highlight') }} {{ __('landing.hero_title_2') }}</title>
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
        /* ── NAV ───────────────────────────────────────────────── */
        .lp-nav { background: rgba(10,10,10,0.85); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .lp-nav-link { color: rgba(255,255,255,0.45); font-size:.875rem; font-weight:500; transition:color .15s; letter-spacing:.01em; }
        .lp-nav-link:hover { color: #fff; }
        .lp-logo { font-style: italic; font-weight: 900; font-size: 1.25rem; letter-spacing: -.03em; color: #C8FA5F; }

        /* ── HERO ──────────────────────────────────────────────── */
        .lp-hero { background: #0a0a0a; overflow: hidden; }
        .hero-hl { color: #C8FA5F; }
        .hero-sub { color: rgba(255,255,255,0.45); }
        .hero-ghost { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.09); color: rgba(255,255,255,0.80); }
        .hero-ghost:hover { background: rgba(255,255,255,0.10); }

        /* ── BENTO ─────────────────────────────────────────────── */
        .bento-card { background: #161616; border: 1px solid rgba(255,255,255,0.07); border-radius: 1rem; }
        .bento-label { color: #C8FA5F; font-size: .625rem; font-weight: 700; letter-spacing: .2em; text-transform: uppercase; }

        /* ── FEATURES ──────────────────────────────────────────── */
        .feat-card { background: #161616; border: 1px solid rgba(255,255,255,0.06); transition: all .2s; }
        .feat-card:hover { border-color: rgba(255,255,255,0.12); transform: translateY(-2px); }
        .feat-icon-bg { background: rgba(200,250,95,0.10); }
        .feat-icon { color: #C8FA5F; }
        .soon-badge { background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.40); }

        /* ── CTA ───────────────────────────────────────────────── */
        .cta-card { background: linear-gradient(145deg,#1a1a1a 0%,#111 100%); border: 1px solid rgba(255,255,255,0.08); }

        /* ── FOOTER ────────────────────────────────────────────── */
        .lp-footer { background: #0a0a0a; border-top: 1px solid rgba(255,255,255,0.06); }
        .footer-link { color: rgba(255,255,255,0.38); font-size:.875rem; transition:color .15s; }
        .footer-link:hover { color: rgba(255,255,255,0.75); }
        .footer-copy { color: rgba(255,255,255,0.25); font-size:.8125rem; }

        /* ── CONTROLS ──────────────────────────────────────────── */
        .lp-lang-active { color: #C8FA5F; }
        .lp-lang-inactive { color: rgba(255,255,255,0.30); }
        .lp-lang-inactive:hover { color: rgba(255,255,255,0.65); }
        .lp-lang-dot { color: rgba(255,255,255,0.15); }
        .lp-theme-btn { color: rgba(255,255,255,0.40); }
        .lp-theme-btn:hover { background: rgba(255,255,255,0.07); }
        .nav-signin { color: rgba(255,255,255,0.50); }
        .nav-signin:hover { color: #fff; }

        /* ── LIGHT MODE ────────────────────────────────────────── */
        [data-theme="light"] body { background: #f5f5f7 !important; color: #0a0a0a !important; }
        [data-theme="light"] .lp-nav { background: rgba(255,255,255,0.92) !important; border-bottom-color: rgba(0,0,0,0.07) !important; }
        [data-theme="light"] .lp-nav-link { color: rgba(0,0,0,0.50) !important; }
        [data-theme="light"] .lp-nav-link:hover { color: #0a0a0a !important; }
        [data-theme="light"] .lp-logo { color: #4a7c00 !important; }
        [data-theme="light"] .lp-hero { background: #ffffff !important; }
        [data-theme="light"] .hero-hl { color: #3d6800 !important; }
        [data-theme="light"] .hero-sub { color: rgba(0,0,0,0.48) !important; }
        [data-theme="light"] .hero-ghost { background: rgba(0,0,0,0.05) !important; border-color: rgba(0,0,0,0.09) !important; color: #111 !important; }
        [data-theme="light"] .hero-ghost:hover { background: rgba(0,0,0,0.09) !important; }
        [data-theme="light"] h1, [data-theme="light"] h2, [data-theme="light"] h3 { color: #0a0a0a !important; }
        [data-theme="light"] .bento-card { background: #fff !important; border-color: rgba(0,0,0,0.07) !important; box-shadow: 0 1px 3px rgba(0,0,0,0.07), 0 4px 16px rgba(0,0,0,0.05); }
        [data-theme="light"] .bento-label { color: #4a7c00 !important; }
        [data-theme="light"] .feat-card { background: #fff !important; border-color: rgba(0,0,0,0.00) !important; box-shadow: 0 1px 3px rgba(0,0,0,0.07), 0 4px 16px rgba(0,0,0,0.05); }
        [data-theme="light"] .feat-icon-bg { background: #eeffd4 !important; }
        [data-theme="light"] .feat-icon { color: #4a7c00 !important; }
        [data-theme="light"] .soon-badge { background: rgba(0,0,0,0.06) !important; color: rgba(0,0,0,0.40) !important; }
        [data-theme="light"] .cta-card { background: linear-gradient(145deg,#1a1a1a 0%,#111 100%) !important; border-color: rgba(255,255,255,0.10) !important; }
        [data-theme="light"] .lp-footer { background: #fff !important; border-top-color: rgba(0,0,0,0.08) !important; }
        [data-theme="light"] .footer-link { color: rgba(0,0,0,0.50) !important; }
        [data-theme="light"] .footer-link:hover { color: rgba(0,0,0,0.85) !important; }
        [data-theme="light"] .footer-copy { color: rgba(0,0,0,0.38) !important; }
        [data-theme="light"] .lp-lang-active { color: #4a7c00 !important; }
        [data-theme="light"] .lp-lang-inactive { color: rgba(0,0,0,0.28) !important; }
        [data-theme="light"] .lp-lang-inactive:hover { color: rgba(0,0,0,0.60) !important; }
        [data-theme="light"] .lp-lang-dot { color: rgba(0,0,0,0.14) !important; }
        [data-theme="light"] .lp-theme-btn { color: rgba(0,0,0,0.42) !important; }
        [data-theme="light"] .lp-theme-btn:hover { background: rgba(0,0,0,0.06) !important; }
        [data-theme="light"] .nav-signin { color: rgba(0,0,0,0.50) !important; }
        [data-theme="light"] .nav-signin:hover { color: #0a0a0a !important; }
    </style>
</head>
<body class="font-sans antialiased">

    {{-- ── NAVIGATION ──────────────────────────────────────── --}}
    <header class="lp-nav fixed top-0 inset-x-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between gap-6">

            <a href="/" class="lp-logo">MyRaces.</a>

            <nav class="hidden md:flex items-center gap-8 flex-1">
                <a href="#features" class="lp-nav-link">{{ __('landing.nav_features') }}</a>
                <a href="#catalog"  class="lp-nav-link">{{ __('landing.nav_pricing') }}</a>
                <a href="#about"    class="lp-nav-link">{{ __('landing.nav_about') }}</a>
            </nav>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-1.5">
                    <a href="{{ route('language.switch', 'es') }}"
                       class="text-xs font-bold transition-colors {{ app()->getLocale() === 'es' ? 'lp-lang-active' : 'lp-lang-inactive' }}">ES</a>
                    <span class="lp-lang-dot text-[10px]">·</span>
                    <a href="{{ route('language.switch', 'en') }}"
                       class="text-xs font-bold transition-colors {{ app()->getLocale() === 'en' ? 'lp-lang-active' : 'lp-lang-inactive' }}">EN</a>
                </div>

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
                    <a href="{{ route('events.index') }}" class="btn btn-primary text-sm px-4 py-2">
                        {{ __('landing.go_to_dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-signin hidden sm:block text-sm font-medium transition">
                        {{ __('landing.sign_in') }}
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary text-sm px-5 py-2"
                       style="box-shadow: 0 0 20px rgba(200,250,95,0.2)">
                        {{ __('landing.register_free') }}
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- ── HERO ─────────────────────────────────────────────── --}}
    <section class="lp-hero pt-16 min-h-screen flex items-center relative">

        {{-- Background glow right --}}
        <div class="absolute top-0 right-0 -z-0 w-1/2 h-full pointer-events-none">
            <div class="absolute inset-0" style="background: linear-gradient(to left, rgba(200,250,95,0.04) 0%, transparent 60%)"></div>
        </div>
        {{-- Abstract lines --}}
        <svg class="absolute right-0 top-0 h-full w-1/2 opacity-20 pointer-events-none" viewBox="0 0 600 800" preserveAspectRatio="none">
            <line x1="0" y1="600" x2="600" y2="100" stroke="#C8FA5F" stroke-width="1.5" opacity="0.5"/>
            <line x1="0" y1="650" x2="600" y2="150" stroke="#C8FA5F" stroke-width="0.8" opacity="0.35"/>
            <line x1="0" y1="700" x2="600" y2="200" stroke="#C8FA5F" stroke-width="0.4" opacity="0.20"/>
            <circle cx="500" cy="100" r="200" fill="#C8FA5F" opacity="0.04"/>
        </svg>

        <div class="max-w-7xl mx-auto px-6 py-24 md:py-32 w-full relative z-10">
            <div class="max-w-2xl">

                <h1 class="text-white font-black italic tracking-tighter leading-[0.88] uppercase mb-8"
                    style="font-size: clamp(3rem, 8vw, 5.5rem)">
                    {{ __('landing.hero_title_1') }}<br>
                    {{ __('landing.hero_title_2') }}<br>
                    <span class="hero-hl">{{ __('landing.hero_title_highlight') }}</span>
                </h1>

                <p class="hero-sub text-lg md:text-xl leading-relaxed mb-10 max-w-md">
                    {{ __('landing.hero_subtitle') }}
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2 font-black italic text-black uppercase tracking-tighter px-8 py-4 rounded-full transition-all active:scale-[0.97]"
                       style="background:#C8FA5F; box-shadow:0 0 32px rgba(200,250,95,0.25); font-size:1.05rem">
                        {{ __('landing.cta_start') }}
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="{{ route('events.index') }}"
                       class="hero-ghost inline-flex items-center justify-center gap-2 font-bold text-base px-8 py-4 rounded-full transition">
                        {{ __('landing.cta_login') }}
                    </a>
                </div>

                <p class="mt-6 text-sm" style="color:rgba(255,255,255,0.25)">{{ __('landing.no_credit_card') }}</p>
            </div>
        </div>
    </section>

    {{-- ── BENTO GRID ───────────────────────────────────────── --}}
    <section class="py-12 max-w-7xl mx-auto px-6" id="catalog">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">

            {{-- Main card --}}
            <div class="md:col-span-8 bento-card p-8 relative overflow-hidden">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <span class="bento-label block mb-2">{{ __('landing.bento_catalog') }}</span>
                        <h2 class="text-white font-black italic text-2xl tracking-tighter uppercase">
                            {{ $featuredEvent?->name ?? __('landing.bento_main_title') }}
                        </h2>
                    </div>
                    @if($featuredEvent)
                        <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-md"
                              style="background:#1e1e1e; color:rgba(255,255,255,0.45)">
                            {{ $featuredEvent->category ?? 'Carretera' }}
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-3 gap-6 mb-8">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:rgba(255,255,255,0.35)">{{ __('landing.bento_events') }}</p>
                        <p class="text-white font-black italic text-4xl">{{ $totalEvents > 0 ? $totalEvents : '100+' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:rgba(255,255,255,0.35)">{{ __('landing.bento_distances') }}</p>
                        <p class="text-white font-black italic text-4xl">5K<span class="text-xl">→</span>100K</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:rgba(255,255,255,0.35)">{{ __('landing.bento_cost') }}</p>
                        <p class="font-black italic text-4xl" style="color:#C8FA5F">100%</p>
                    </div>
                </div>

                {{-- Progress bar accent --}}
                <div class="h-1 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06)">
                    <div class="h-full rounded-full" style="width:72%; background: linear-gradient(90deg,#C8FA5F,#a8e840)"></div>
                </div>
                <p class="text-[10px] font-medium mt-2" style="color:rgba(255,255,255,0.20)">{{ __('landing.bento_bar_label') }}</p>

                {{-- bg glow --}}
                <div class="absolute -bottom-10 -right-10 w-48 h-48 rounded-full pointer-events-none"
                     style="background:radial-gradient(circle,rgba(200,250,95,0.06) 0%,transparent 70%)"></div>
            </div>

            {{-- Side card --}}
            <div class="md:col-span-4 bento-card p-8 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(200,250,95,0.12)">
                            <svg class="w-4 h-4" style="color:#C8FA5F" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <span class="bento-label">{{ __('landing.bento_records') }}</span>
                    </div>
                    <h3 class="text-white font-black italic text-4xl tracking-tighter uppercase mb-1">{{ __('landing.bento_pb') }}</h3>
                    <p class="font-black italic" style="font-size:3.5rem; color:#C8FA5F; line-height:1">∞</p>
                </div>
                <p class="text-sm leading-relaxed mt-4" style="color:rgba(255,255,255,0.38)">{{ __('landing.bento_records_desc') }}</p>
            </div>

            {{-- Bottom 3 small cards --}}
            @foreach([
                ['icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'label' => 'landing.bento_road', 'value' => 'Asfalto'],
                ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'label' => 'landing.bento_trail', 'value' => 'Trail'],
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'landing.bento_community', 'value' => 'Comunidad'],
            ] as $card)
                <div class="md:col-span-4 bento-card p-6 flex items-center gap-5">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0"
                         style="background:rgba(200,250,95,0.08)">
                        <svg class="w-5 h-5" style="color:#C8FA5F" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:rgba(255,255,255,0.35)">{{ __($card['label']) }}</p>
                        <p class="text-white font-bold text-lg">{{ $card['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ── FEATURES ──────────────────────────────────────────── --}}
    <section class="py-24 max-w-7xl mx-auto px-6" id="features">
        <div class="text-center mb-14">
            <h2 class="text-white font-black italic text-4xl md:text-5xl tracking-tighter uppercase mb-4">
                {{ __('landing.features_title') }}
            </h2>
            <div class="h-1 w-20 mx-auto rounded-full" style="background:#C8FA5F; opacity:.8"></div>
            <p class="mt-5 text-lg max-w-lg mx-auto" style="color:rgba(255,255,255,0.42)">{{ __('landing.features_subtitle') }}</p>
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
                <div class="feat-card rounded-2xl p-7 relative">
                    @if($feat['soon'])
                        <span class="soon-badge absolute top-5 right-5 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full">
                            {{ __('landing.soon') }}
                        </span>
                    @endif
                    <div class="feat-icon-bg w-11 h-11 rounded-xl flex items-center justify-center mb-5">
                        <svg class="feat-icon w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feat['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="text-white font-bold text-lg mb-2">{{ __('landing.' . $feat['title']) }}</h3>
                    <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.42)">{{ __('landing.' . $feat['desc']) }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ── CTA CARD ──────────────────────────────────────────── --}}
    <section class="py-16 px-6 max-w-7xl mx-auto" id="about">
        <div class="cta-card rounded-3xl py-20 px-8 text-center">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-6"
                 style="background:#C8FA5F; box-shadow:0 8px 32px rgba(200,250,95,0.30)">
                <svg class="w-8 h-8 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h2 class="text-white font-black italic text-3xl md:text-4xl tracking-tighter uppercase mb-4">
                {{ __('landing.cta_title') }}
            </h2>
            <p class="text-lg mb-8 max-w-md mx-auto" style="color:rgba(255,255,255,0.45)">{{ __('landing.cta_subtitle') }}</p>
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 font-black italic text-black uppercase tracking-tighter px-9 py-4 rounded-full transition-all active:scale-[0.97]"
               style="background:#C8FA5F; box-shadow:0 8px 32px rgba(200,250,95,0.25); font-size:1.05rem">
                {{ __('landing.cta_btn') }}
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </section>

    {{-- ── FOOTER ────────────────────────────────────────────── --}}
    <footer class="lp-footer py-10">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <span class="lp-logo text-xl">MyRaces.</span>
                <p class="footer-copy mt-1">{{ str_replace(':year', now()->year, __('landing.footer_copy')) }}</p>
            </div>

            <nav class="flex items-center gap-7">
                <a href="#" class="footer-link text-sm">{{ __('landing.footer_terms') }}</a>
                <a href="#" class="footer-link text-sm">{{ __('landing.footer_privacy') }}</a>
                <a href="#" class="footer-link text-sm">{{ __('landing.footer_contact') }}</a>
                <div class="flex sm:hidden items-center gap-1.5">
                    <a href="{{ route('language.switch', 'es') }}"
                       class="text-xs font-bold transition-colors {{ app()->getLocale() === 'es' ? 'lp-lang-active' : 'lp-lang-inactive' }}">ES</a>
                    <span class="lp-lang-dot text-[10px]">·</span>
                    <a href="{{ route('language.switch', 'en') }}"
                       class="text-xs font-bold transition-colors {{ app()->getLocale() === 'en' ? 'lp-lang-active' : 'lp-lang-inactive' }}">EN</a>
                </div>
            </nav>

            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center transition-colors cursor-pointer"
                     style="background:rgba(255,255,255,0.07)"
                     onmouseenter="this.style.background='#C8FA5F';this.style.color='#253600'"
                     onmouseleave="this.style.background='rgba(255,255,255,0.07)';this.style.color=''">
                    <svg class="w-3.5 h-3.5 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                </div>
                <div class="w-8 h-8 rounded-full flex items-center justify-center transition-colors cursor-pointer"
                     style="background:rgba(255,255,255,0.07)"
                     onmouseenter="this.style.background='#C8FA5F';this.style.color='#253600'"
                     onmouseleave="this.style.background='rgba(255,255,255,0.07)';this.style.color=''">
                    <svg class="w-3.5 h-3.5 text-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
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
