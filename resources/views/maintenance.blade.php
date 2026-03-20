<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Mantenimiento — MyRaces</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=public-sans:700,900i&display=swap" rel="stylesheet"/>
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{background:#0a0a0a;color:#e5e2e1;font-family:'Public Sans',sans-serif;min-height:100dvh;display:flex;align-items:center;justify-content:center;text-align:center;padding:2rem}
        .icon{width:64px;height:64px;background:rgb(var(--color-primary));border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;box-shadow:0 8px 32px rgb(var(--color-primary) / 0.25)}
        h1{font-style:italic;font-weight:900;font-size:clamp(2rem,6vw,3.5rem);letter-spacing:-.03em;text-transform:uppercase;line-height:.9;color:#fff;margin-bottom:1rem}
        h1 span{color:rgb(var(--color-primary))}
        p{color:rgba(255,255,255,0.45);font-size:1.05rem;max-width:32rem;margin:0 auto}
        .dot{display:inline-block;width:6px;height:6px;border-radius:50%;background:rgb(var(--color-primary));animation:pulse 1.4s ease-in-out infinite}
        .dot:nth-child(2){animation-delay:.2s}
        .dot:nth-child(3){animation-delay:.4s}
        @keyframes pulse{0%,80%,100%{transform:scale(0);opacity:.4}40%{transform:scale(1);opacity:1}}
        .dots{display:flex;align-items:center;justify-content:center;gap:6px;margin-top:2rem}
    </style>
</head>
<body>
    <div>
        <div class="icon">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#000" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <h1>My<span>Races</span><br>en mantenimiento</h1>
        <p>{{ $message }}</p>
        <div class="dots">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>
</body>
</html>
