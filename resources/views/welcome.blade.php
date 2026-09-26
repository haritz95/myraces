{{-- Portada de myraces.app: la app MyRaces para iPhone. Mismo aspecto que la app: rojo "pista", papel,
     Barlow Condensed en los títulos, la mascota y capturas reales. Sin cuentas ni inicio de sesión. --}}
@php
    // El idioma elegido en la web; si no se ha elegido, el del navegador o del iPhone.
    if (! session()->has('locale')) {
        app()->setLocale(request()->getPreferredLanguage(['es', 'en']) ?? 'es');
    }
    $en = app()->getLocale() === 'en';
    $features = [
        ['f1', 'home'], ['f2', 'directory'], ['f3', 'plan'], ['f4', 'race'],
        ['f5', 'stats'], ['f6', 'partners'], ['f7', 'share'],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ $en ? 'en' : 'es' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#d9202c">
    <title>{{ __('home.title') }}</title>
    <meta name="description" content="{{ __('home.description') }}">
    <link rel="canonical" href="https://myraces.app/">
    <link rel="icon" type="image/png" href="/images/favicon.png">
    <link rel="apple-touch-icon" href="/images/apple-touch-icon.png">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ __('home.title') }}">
    <meta property="og:description" content="{{ __('home.description') }}">
    <meta property="og:image" content="https://myraces.app/images/app-icon.png">
    <meta name="twitter:card" content="summary">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=barlow-condensed:700,800&family=barlow:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        :root { --red:#d9202c; --red-dark:#b41021; --ink:#171719; --paper:#f7f4ef; --cream:#fffdf9; --line:#ded9d0; --muted:#63605a; }
        * { box-sizing:border-box; margin:0; }
        html { scroll-behavior:smooth; }
        body { background:var(--paper); color:var(--ink); font-family:'Barlow',system-ui,sans-serif; font-size:17px; line-height:1.6; -webkit-font-smoothing:antialiased; }
        a { color:inherit; }
        img { display:block; max-width:100%; }
        .wrap { max-width:1160px; margin:0 auto; padding:0 20px; }
        .display { font-family:'Barlow Condensed',sans-serif; font-weight:800; text-transform:uppercase; letter-spacing:-.02em; line-height:.9; }
        .kicker { font-size:13px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--red); }
        .btn { display:inline-flex; align-items:center; gap:10px; min-height:52px; padding:0 22px; border:2px solid var(--ink); font-weight:700; text-decoration:none; transition:transform .18s ease; }
        .btn:hover { transform:translateY(-2px); }
        .btn-dark { background:var(--ink); color:#fff; }
        .btn-light { background:var(--cream); }
        .btn-white { background:#fff; color:var(--ink); border-color:#fff; }
        :focus-visible { outline:3px solid var(--red); outline-offset:3px; }

        /* Cabecera */
        header { position:sticky; top:0; z-index:10; background:rgba(247,244,239,.92); backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px); border-bottom:1px solid var(--line); }
        .bar { display:flex; align-items:center; justify-content:space-between; height:68px; }
        .brand { display:flex; align-items:center; gap:10px; font-weight:800; font-size:21px; letter-spacing:-.03em; text-decoration:none; }
        .brand img { width:38px; height:38px; border-radius:22%; }
        .nav { display:flex; align-items:center; gap:22px; font-size:15px; font-weight:600; }
        .nav a { text-decoration:none; }
        .nav a:hover { color:var(--red); }
        .langs a { color:#8a867f; font-size:13px; } .langs a.on { color:var(--ink); }
        .nav .hide-sm { display:none; }
        @media (min-width:720px) { .nav .hide-sm { display:inline; } }

        /* Portada */
        .hero { display:grid; gap:40px; padding:48px 20px 0; }
        .hero h1 { font-size:clamp(64px,13vw,132px); margin:14px 0 22px; }
        .hero h1 span { color:var(--red); }
        .hero p.lead { font-size:19px; max-width:520px; color:#4d4b46; }
        .hero .actions { display:flex; flex-wrap:wrap; gap:12px; margin-top:30px; }
        .hero .note { margin-top:16px; font-size:14px; font-weight:600; color:var(--muted); }
        .stage { position:relative; background:var(--red); overflow:hidden; min-height:540px; display:flex; align-items:flex-end; justify-content:center; padding-top:48px; margin:0 -20px; }
        .stage::before, .stage::after { content:''; position:absolute; border:2px solid rgba(255,255,255,.4); border-radius:50%; transform:rotate(-21deg); }
        .stage::before { right:-10%; top:-26%; width:70%; height:140%; }
        .stage::after { right:-18%; top:-36%; width:92%; height:165%; }
        .stage .mascot { position:absolute; left:4%; bottom:36px; width:min(36%,220px); z-index:3; transform:rotate(-6deg); filter:drop-shadow(0 12px 18px rgba(0,0,0,.18)); }
        @media (min-width:960px) { .hero { grid-template-columns:1fr .95fr; align-items:end; padding-top:72px; } .hero .copy { padding-bottom:64px; } .stage { min-height:640px; margin:0; } }

        /* iPhone */
        .phone { position:relative; z-index:2; width:min(74%,300px); background:#111; padding:10px; border-radius:46px; box-shadow:0 30px 60px rgba(0,0,0,.28); }
        .phone img { border-radius:36px; width:100%; height:auto; }
        .stage .phone { margin-bottom:-140px; }

        /* Franja */
        .strip { background:var(--cream); border-block:1px solid var(--line); }
        .strip .wrap { display:grid; }
        .strip .item { display:flex; align-items:center; gap:14px; padding:20px 0; font-weight:700; }
        .strip .item + .item { border-top:1px solid var(--line); }
        .strip b { font-family:'Barlow Condensed',sans-serif; font-size:30px; color:var(--red); }
        @media (min-width:720px) { .strip .wrap { grid-template-columns:repeat(3,1fr); } .strip .item + .item { border-top:0; border-left:1px solid var(--line); padding-left:24px; } }

        /* Funciones */
        section { padding:88px 0; }
        .section-title { font-size:clamp(46px,8vw,86px); margin-top:12px; max-width:760px; }
        .feature { display:grid; gap:32px; align-items:center; margin-top:72px; }
        .feature .shot { background:var(--cream); border:2px solid var(--ink); display:flex; justify-content:center; padding:40px 20px 0; overflow:hidden; height:500px; }
        .feature .shot .phone { width:min(78%,270px); height:max-content; box-shadow:0 20px 40px rgba(0,0,0,.18); }
        .feature h3 { font-size:clamp(38px,6vw,56px); margin:10px 0 14px; }
        .feature p { max-width:480px; color:#4d4b46; font-size:18px; }
        @media (min-width:900px) {
            .feature { grid-template-columns:1fr 1fr; gap:72px; }
            .feature:nth-child(even) .shot { order:2; }
            .feature .shot { height:560px; }
        }

        /* Pro */
        .pro { background:var(--ink); color:#fff; }
        .pro .grid { display:grid; gap:40px; }
        .pro ul { list-style:none; padding:0; display:grid; gap:12px; margin-top:24px; }
        .pro li { display:flex; gap:12px; align-items:flex-start; font-weight:600; font-size:18px; }
        .pro li::before { content:''; flex:none; width:22px; height:22px; margin-top:3px; background:var(--red) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3.2'%3E%3Cpath d='M5 12.5l4.5 4.5L19 7.5'/%3E%3C/svg%3E") center/15px no-repeat; }
        .pro .badge { display:inline-block; background:#fff; color:var(--ink); font-weight:800; letter-spacing:.14em; font-size:13px; padding:4px 10px; }
        .pro .card { border:2px solid rgba(255,255,255,.25); padding:28px; }
        @media (min-width:900px) { .pro .grid { grid-template-columns:1fr 1fr; align-items:center; gap:72px; } }

        /* Final */
        .final { background:var(--red); color:#fff; overflow:hidden; }
        .final .wrap { display:grid; gap:28px; align-items:center; }
        .final h2 { font-size:clamp(50px,9vw,104px); }
        .final p { font-size:19px; max-width:520px; opacity:.9; margin-top:18px; }
        .final img { width:min(56%,300px); justify-self:center; transform:rotate(-6deg); filter:drop-shadow(0 16px 24px rgba(0,0,0,.2)); }
        @media (min-width:900px) { .final .wrap { grid-template-columns:1.3fr .7fr; } }

        footer { padding:32px 0 44px; font-size:15px; color:var(--muted); }
        footer .wrap { display:flex; flex-wrap:wrap; gap:14px 28px; align-items:center; justify-content:space-between; }
        footer nav { display:flex; gap:20px; }
        footer .brand { font-size:18px; color:var(--ink); }
        footer .brand img { width:30px; height:30px; }
        @media (prefers-reduced-motion:reduce) { * { transition:none!important; scroll-behavior:auto!important; } }
    </style>
</head>
<body>
<header>
    <div class="wrap bar">
        <a class="brand" href="/" aria-label="MyRaces"><img src="/images/apple-touch-icon.png" alt="" width="38" height="38">myraces</a>
        <nav class="nav">
            <a class="hide-sm" href="#app">{{ __('home.nav_features') }}</a>
            <a class="hide-sm" href="#pro">{{ __('home.nav_pro') }}</a>
            <span class="langs"><a class="{{ $en ? '' : 'on' }}" href="{{ route('language.switch', 'es') }}">ES</a> / <a class="{{ $en ? 'on' : '' }}" href="{{ route('language.switch', 'en') }}">EN</a></span>
        </nav>
    </div>
</header>

<main>
    <div class="wrap hero">
        <div class="copy">
            <p class="kicker">{{ __('home.kicker') }}</p>
            <h1 class="display">{{ __('home.hero_title') }}</h1>
            <p class="lead">{{ __('home.hero_text') }}</p>
            <div class="actions">
                <span class="btn btn-dark" role="img" aria-label="{{ __('home.soon_aria') }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.05 12.54c-.03-2.62 2.14-3.89 2.24-3.95-1.22-1.78-3.11-2.02-3.78-2.05-1.59-.17-3.14.95-3.95.95-.83 0-2.08-.93-3.42-.9-1.73.03-3.35 1.03-4.24 2.59-1.84 3.18-.47 7.85 1.3 10.44.89 1.27 1.93 2.69 3.3 2.64 1.34-.06 1.84-.85 3.46-.85 1.6 0 2.07.85 3.47.82 1.44-.02 2.35-1.28 3.2-2.57 1.03-1.46 1.44-2.9 1.46-2.98-.03-.01-2.78-1.06-2.84-4.14Z"/></svg>
                    {{ __('home.soon') }}
                </span>
                <a class="btn btn-light" href="#app">{{ __('home.hero_cta') }}</a>
            </div>
            <p class="note">{{ __('home.hero_note') }}</p>
        </div>
        <div class="stage">
            <img class="mascot" src="/images/mascot.png" alt="{{ __('home.mascot_alt') }}" width="220" height="220">
            <div class="phone"><img src="/images/screens/home.jpg" alt="{{ __('home.screen_alt', ['name' => __('home.f1_title')]) }}" width="590" height="1279"></div>
        </div>
    </div>

    <div class="strip">
        <div class="wrap">
            @foreach (['strip_1', 'strip_2', 'strip_3'] as $item)
                <div class="item"><b>0{{ $loop->iteration }}</b>{{ __('home.'.$item) }}</div>
            @endforeach
        </div>
    </div>

    <section id="app">
        <div class="wrap">
            <p class="kicker">{{ __('home.features_kicker') }}</p>
            <h2 class="display section-title">{{ __('home.features_title') }}</h2>
            <div>
                @foreach ($features as [$key, $screen])
                    <article class="feature">
                        <div class="shot">
                            <div class="phone"><img src="/images/screens/{{ $screen }}.jpg" alt="{{ __('home.screen_alt', ['name' => __('home.'.$key.'_title')]) }}" loading="lazy" width="590" height="1279"></div>
                        </div>
                        <div>
                            <p class="kicker">{{ __('home.'.$key.'_kicker') }}</p>
                            <h3 class="display">{{ __('home.'.$key.'_title') }}</h3>
                            <p>{{ __('home.'.$key.'_text') }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="pro" id="pro">
        <div class="wrap grid">
            <div>
                <span class="badge">PRO</span>
                <h2 class="display section-title">{{ __('home.pro_title') }}</h2>
            </div>
            <div class="card">
                <p style="font-size:18px;opacity:.85">{{ __('home.pro_text') }}</p>
                <ul>
                    @foreach (['pro_1', 'pro_2', 'pro_3', 'pro_4'] as $item)
                        <li>{{ __('home.'.$item) }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <section class="final">
        <div class="wrap">
            <div>
                <h2 class="display">{{ __('home.final_title') }}</h2>
                <p>{{ __('home.final_text') }}</p>
                <span class="btn btn-white" style="margin-top:28px" role="img" aria-label="{{ __('home.soon_aria') }}">{{ __('home.soon') }}</span>
            </div>
            <img src="/images/mascot.png" alt="" width="300" height="300" loading="lazy">
        </div>
    </section>
</main>

<footer>
    <div class="wrap">
        <a class="brand" href="/"><img src="/images/apple-touch-icon.png" alt="" width="30" height="30">myraces</a>
        <nav>
            <a href="mailto:hola@myraces.app">{{ __('home.footer_contact') }}</a>
            <a href="{{ route('privacy') }}">{{ __('home.footer_privacy') }}</a>
        </nav>
        <span>© {{ now()->year }} MyRaces</span>
    </div>
</footer>
</body>
</html>
