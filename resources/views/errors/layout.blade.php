<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $title }} — MyRaces</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=public-sans:400,700,900i&display=swap" rel="stylesheet"/>
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        :root{--color-primary:{{ \App\Models\Setting::primaryColorChannels() }}}
        body{background:#0a0a0a;color:#e5e2e1;font-family:'Public Sans',sans-serif;min-height:100dvh;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:2rem}
        .logo{font-style:italic;font-weight:900;font-size:1.25rem;letter-spacing:-.03em;color:rgb(var(--color-primary));position:fixed;top:1.5rem;left:1.5rem;text-decoration:none}
        .code{font-style:italic;font-weight:900;font-size:clamp(5rem,20vw,10rem);letter-spacing:-.05em;line-height:1;color:rgba(255,255,255,0.04);position:absolute;top:50%;left:50%;transform:translate(-50%,-60%);pointer-events:none;user-select:none;white-space:nowrap}
        .card{position:relative;z-index:1}
        .icon-wrap{width:56px;height:56px;background:rgb(var(--color-primary));border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;box-shadow:0 8px 32px rgb(var(--color-primary) / 0.25)}
        h1{font-style:italic;font-weight:900;font-size:clamp(1.6rem,5vw,2.6rem);letter-spacing:-.03em;text-transform:uppercase;line-height:1;color:#fff;margin-bottom:.75rem}
        h1 span{color:rgb(var(--color-primary))}
        p{color:rgba(255,255,255,0.42);font-size:1rem;max-width:30rem;margin:0 auto 2rem;line-height:1.6}
        .btn{display:inline-flex;align-items:center;gap:.5rem;background:rgb(var(--color-primary));color:#0a0a0a;font-weight:700;font-size:.9rem;padding:.65rem 1.4rem;border-radius:9999px;text-decoration:none;transition:opacity .2s}
        .btn:hover{opacity:.85}
        .glow{position:fixed;pointer-events:none;border-radius:50%}
        .glow-1{top:0;right:0;width:600px;height:600px;background:radial-gradient(circle,rgb(var(--color-primary) / 0.04) 0%,transparent 65%);transform:translate(30%,-30%)}
        .glow-2{bottom:0;left:0;width:500px;height:500px;background:radial-gradient(circle,rgb(var(--color-primary) / 0.03) 0%,transparent 65%);transform:translate(-30%,30%)}
    </style>
</head>
<body>
    <div class="glow glow-1"></div>
    <div class="glow glow-2"></div>

    <a href="/" class="logo">MyRaces.</a>

    <div style="position:relative">
        <div class="code">{{ $code }}</div>
        <div class="card">
            <div class="icon-wrap">{!! $icon !!}</div>
            <h1>{!! $heading !!}</h1>
            <p>{{ $message }}</p>
            <a href="/" class="btn">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
