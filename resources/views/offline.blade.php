<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0a0a0a">
    <title>Sin conexión — MyRaces</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #0a0a0a;
            color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            min-height: 100svh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            text-align: center;
            max-width: 320px;
            width: 100%;
        }
        .icon {
            width: 72px;
            height: 72px;
            background: rgb(var(--color-primary) / 0.10);
            border: 1px solid rgb(var(--color-primary) / 0.20);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }
        h1 { font-size: 22px; font-weight: 900; margin-bottom: 8px; }
        p { font-size: 14px; color: rgba(255,255,255,0.45); line-height: 1.6; margin-bottom: 28px; }
        button {
            background: rgb(var(--color-primary));
            color: #000;
            border: none;
            border-radius: 14px;
            padding: 14px 28px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            width: 100%;
        }
        button:active { opacity: 0.85; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="rgb(var(--color-primary))" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636a9 9 0 010 12.728M15.536 8.464a5 5 0 010 7.072M3 3l18 18M10.584 10.584A2 2 0 0013.415 13.415"/>
            </svg>
        </div>
        <h1>Sin conexión</h1>
        <p>Parece que no tienes internet ahora mismo. Revisa tu conexión e inténtalo de nuevo.</p>
        <button onclick="window.location.reload()">Reintentar</button>
    </div>
</body>
</html>
