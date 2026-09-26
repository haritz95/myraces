@php($es = app()->getLocale() !== 'en')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f4ef">
    <title>{{ $es ? 'Política de privacidad' : 'Privacy policy' }} — MyRaces</title>
    <meta name="description" content="{{ $es ? 'Qué datos guarda la app MyRaces, para qué y cómo puedes borrarlos.' : 'What data the MyRaces app stores, why, and how you can delete it.' }}">
    <link rel="canonical" href="{{ rtrim(config('app.url'), '/') }}/privacidad">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=barlow-condensed:700,800&family=barlow:400,500,600,700&display=swap" rel="stylesheet">
    <style>
        :root { --red:#d9202c; --ink:#171719; --paper:#f7f4ef; --cream:#fffdf9; --line:#ded9d0; --muted:#5f5c56; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--paper); color:var(--ink); font-family:'Barlow',sans-serif; font-size:17px; line-height:1.65; }
        a { color:var(--red); }
        header { border-bottom:1px solid var(--line); background:var(--cream); }
        .bar { max-width:760px; margin:0 auto; padding:18px 20px; display:flex; justify-content:space-between; align-items:center; }
        .brand { font-weight:700; color:var(--ink); text-decoration:none; letter-spacing:-.03em; }
        .langs a { color:var(--muted); text-decoration:none; font-size:14px; font-weight:600; margin-left:10px; }
        .langs a.on { color:var(--ink); }
        main { max-width:760px; margin:0 auto; padding:40px 20px 72px; }
        .kicker { color:var(--red); font-size:13px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; }
        h1 { font-family:'Barlow Condensed',sans-serif; font-weight:800; text-transform:uppercase; font-size:clamp(44px,9vw,72px); line-height:.9; letter-spacing:-.03em; margin:10px 0 14px; }
        h2 { font-family:'Barlow Condensed',sans-serif; font-weight:800; text-transform:uppercase; font-size:28px; letter-spacing:-.01em; margin:40px 0 8px; }
        .updated { color:var(--muted); font-size:15px; }
        .summary { background:var(--cream); border:2px solid var(--ink); padding:18px 22px; margin:28px 0; }
        .summary ul { margin:6px 0 0; padding-left:20px; }
        li { margin:4px 0; }
        footer { border-top:1px solid var(--line); color:var(--muted); font-size:14px; }
    </style>
</head>
<body>
<header>
    <div class="bar">
        <a class="brand" href="{{ route('home') }}">myraces</a>
        <nav class="langs">
            <a class="{{ $es ? 'on' : '' }}" href="{{ route('language.switch', 'es') }}">ES</a>
            <a class="{{ $es ? '' : 'on' }}" href="{{ route('language.switch', 'en') }}">EN</a>
        </nav>
    </div>
</header>
<main>
@if ($es)
    <p class="kicker">App MyRaces para iPhone</p>
    <h1>Política de privacidad</h1>
    <p class="updated">Última actualización: 26 de septiembre de 2026</p>

    <div class="summary">
        <strong>En resumen</strong>
        <ul>
            <li>Solo guardamos lo necesario para que la app funcione: tu cuenta, tus carreras y lo que tú decidas publicar.</li>
            <li>Sin anuncios, sin analítica de terceros y sin vender ni ceder tus datos a nadie.</li>
            <li>Tus datos están en un servidor en la Unión Europea, con el disco y las copias cifrados.</li>
            <li>Puedes borrar tu cuenta y todos tus datos desde la app, en cualquier momento.</li>
        </ul>
    </div>

    <h2>Quién es el responsable</h2>
    <p>MyRaces es una app desarrollada por Haritz López (España). Para cualquier cuestión sobre tus datos, escribe a <a href="mailto:hola@myraces.app">hola@myraces.app</a>.</p>

    <h2>Qué datos tratamos</h2>
    <ul>
        <li><strong>Cuenta:</strong> tu email y tu contraseña (guardada de forma que ni nosotros podemos leerla).</li>
        <li><strong>Perfil:</strong> nombre, club, ciudad y foto, si los añades.</li>
        <li><strong>Tus carreras y zapatillas:</strong> lo que apuntas en la app (fechas, tiempos, posiciones, dorsales, costes, notas…).</li>
        <li><strong>Datos del reloj:</strong> si lo pides en una carrera, importamos de Apple Salud o de Strava el tiempo del reloj, la distancia, el desnivel, las pulsaciones, los parciales por kilómetro y el recorrido GPS de esa actividad.</li>
        <li><strong>Lo que publicas:</strong> si haces público tu perfil, tus carreras públicas, los anuncios de compañeros y de dorsales, tus valoraciones y los contactos que decides compartir.</li>
        <li><strong>Seguridad de la comunidad:</strong> las denuncias que envías y los usuarios que bloqueas.</li>
        <li><strong>Propuestas:</strong> las carreras que propones para el directorio.</li>
        <li><strong>Notificaciones:</strong> un identificador de tu iPhone para enviarte avisos, si los permites.</li>
        <li><strong>Compras:</strong> las gestiona Apple. No recibimos tus datos de pago; la app solo comprueba con Apple si tienes MyRaces Pro.</li>
    </ul>
    <p>Si usas la app <strong>sin cuenta</strong> (modo invitado), tus datos se quedan solo en tu iPhone y no se envían a ningún servidor.</p>

    <h2>Para qué los usamos</h2>
    <ul>
        <li>Para prestarte el servicio: guardar tus carreras en tu cuenta y tenerlas en tus dispositivos (ejecución del contrato).</li>
        <li>Para mostrar a otros corredores lo que tú decides hacer público (tu consentimiento, que puedes retirar haciéndolo privado).</li>
        <li>Para mantener la comunidad segura y evitar abusos (interés legítimo).</li>
    </ul>
    <p>No usamos tus datos para publicidad ni para crear perfiles comerciales.</p>

    <h2>Apple Salud</h2>
    <p>La app solo lee de Salud cuando tú lo pides al importar una carrera, y nunca escribe en Salud. Los datos importados se guardan en tu cuenta para que los veas en tus dispositivos, <strong>no son públicos</strong>, no se usan para publicidad ni se venden o ceden a terceros.</p>

    <h2>Strava</h2>
    <p>Conectar Strava es opcional. Solo leemos tus actividades, nunca publicamos nada en Strava. Los permisos de acceso se guardan en nuestro servidor y los datos que importas <strong>solo los ves tú</strong>. Si una carrera es pública, como mucho se muestra un enlace “Ver en Strava”, y solo si tú lo activas. Puedes desconectar Strava en la app (Perfil › Strava); al borrar tu cuenta también se retira el permiso.</p>

    <h2>Dónde se guardan y cómo se protegen</h2>
    <p>En un servidor propio alojado por Hetzner en Finlandia (Unión Europea). El disco está cifrado, todas las conexiones van cifradas (HTTPS) y las reglas de la base de datos impiden que nadie vea tus datos privados. Hacemos copias de seguridad diarias, también cifradas.</p>

    <h2>Con quién se comparten</h2>
    <ul>
        <li><strong>Hetzner</strong> (alojamiento del servidor), como encargado del tratamiento.</li>
        <li><strong>Apple</strong>, para las compras y las notificaciones.</li>
        <li><strong>Strava</strong>, solo si la conectas.</li>
        <li><strong>Otros usuarios</strong> ven únicamente lo que tú haces público.</li>
    </ul>

    <h2>Cuánto tiempo los guardamos</h2>
    <p>Mientras tengas cuenta. Al borrarla (Perfil › Cuenta y seguridad › Borrar cuenta) se eliminan al momento tu cuenta, tus carreras, tu perfil, tus fotos y todo lo que publicaste. Las copias de seguridad se renuevan y desaparecen en unos 14 días. Si alguien denunció algo tuyo, la denuncia se conserva, pero sin datos que te identifiquen.</p>

    <h2>Tus derechos</h2>
    <p>Puedes acceder a tus datos, corregirlos, borrarlos, llevártelos (la app exporta tus carreras en CSV), oponerte o pedir que limitemos su uso. Casi todo lo puedes hacer desde la app; para lo demás, escribe a <a href="mailto:hola@myraces.app">hola@myraces.app</a>. Si crees que no hemos tratado bien tus datos, puedes reclamar ante la <a href="https://www.aepd.es">Agencia Española de Protección de Datos</a>.</p>

    <h2>Menores</h2>
    <p>Para crear una cuenta necesitas tener al menos 14 años.</p>

    <h2>Cambios</h2>
    <p>Si cambiamos esta política, actualizaremos la fecha de arriba y, si el cambio es importante, te avisaremos en la app.</p>
@else
    <p class="kicker">MyRaces iPhone app</p>
    <h1>Privacy policy</h1>
    <p class="updated">Last updated: September 26, 2026</p>

    <div class="summary">
        <strong>In short</strong>
        <ul>
            <li>We only store what the app needs to work: your account, your races and whatever you choose to make public.</li>
            <li>No ads, no third-party analytics, and we never sell or share your data.</li>
            <li>Your data is stored on a server in the European Union, with an encrypted disk and encrypted backups.</li>
            <li>You can delete your account and all your data from the app at any time.</li>
        </ul>
    </div>

    <h2>Who is responsible</h2>
    <p>MyRaces is an app developed by Haritz López (Spain). For any question about your data, write to <a href="mailto:hola@myraces.app">hola@myraces.app</a>.</p>

    <h2>What data we process</h2>
    <ul>
        <li><strong>Account:</strong> your email and password (stored in a way that not even we can read it).</li>
        <li><strong>Profile:</strong> name, club, city and photo, if you add them.</li>
        <li><strong>Your races and shoes:</strong> what you log in the app (dates, times, positions, bibs, costs, notes…).</li>
        <li><strong>Watch data:</strong> if you ask for it on a race, we import from Apple Health or Strava the watch time, distance, elevation, heart rate, per-kilometer splits and GPS route of that activity.</li>
        <li><strong>What you publish:</strong> if you make your profile public, your public races, running-partner and bib ads, your reviews and the contact details you choose to share.</li>
        <li><strong>Community safety:</strong> reports you send and users you block.</li>
        <li><strong>Proposals:</strong> races you suggest for the directory.</li>
        <li><strong>Notifications:</strong> an identifier for your iPhone to send you alerts, if you allow them.</li>
        <li><strong>Purchases:</strong> handled by Apple. We don't receive your payment details; the app only checks with Apple whether you have MyRaces Pro.</li>
    </ul>
    <p>If you use the app <strong>without an account</strong> (guest mode), your data stays only on your iPhone and is never sent to a server.</p>

    <h2>What we use it for</h2>
    <ul>
        <li>To provide the service: saving your races to your account and syncing them across your devices (performance of a contract).</li>
        <li>To show other runners what you choose to make public (your consent, which you can withdraw by making it private).</li>
        <li>To keep the community safe and prevent abuse (legitimate interest).</li>
    </ul>
    <p>We don't use your data for advertising or to build commercial profiles.</p>

    <h2>Apple Health</h2>
    <p>The app only reads from Health when you ask it to import a race, and never writes to Health. Imported data is stored in your account so you can see it on your devices, <strong>is never public</strong>, and is not used for advertising or sold or shared with third parties.</p>

    <h2>Strava</h2>
    <p>Connecting Strava is optional. We only read your activities and never post anything to Strava. Access permissions are stored on our server and the data you import is <strong>only visible to you</strong>. If a race is public, at most a “View on Strava” link is shown, and only if you turn it on. You can disconnect Strava in the app (Profile › Strava); deleting your account also revokes access.</p>

    <h2>Where it's stored and how it's protected</h2>
    <p>On our own server hosted by Hetzner in Finland (European Union). The disk is encrypted, all connections are encrypted (HTTPS) and database rules prevent anyone from seeing your private data. We make daily backups, also encrypted.</p>

    <h2>Who we share it with</h2>
    <ul>
        <li><strong>Hetzner</strong> (server hosting), as data processor.</li>
        <li><strong>Apple</strong>, for purchases and notifications.</li>
        <li><strong>Strava</strong>, only if you connect it.</li>
        <li><strong>Other users</strong> only see what you make public.</li>
    </ul>

    <h2>How long we keep it</h2>
    <p>For as long as you have an account. When you delete it (Profile › Account and security › Delete account), your account, races, profile, photos and everything you published are deleted immediately. Backups rotate and disappear within about 14 days. If someone reported something of yours, the report is kept, but without data that identifies you.</p>

    <h2>Your rights</h2>
    <p>You can access, correct, delete or take your data with you (the app exports your races as CSV), object to or ask us to restrict its use. Almost everything can be done from the app; for anything else, write to <a href="mailto:hola@myraces.app">hola@myraces.app</a>. If you think we haven't handled your data properly, you can file a complaint with the <a href="https://www.aepd.es">Spanish Data Protection Agency (AEPD)</a>.</p>

    <h2>Minors</h2>
    <p>You must be at least 14 years old to create an account.</p>

    <h2>Changes</h2>
    <p>If we change this policy, we'll update the date above and, if the change is significant, let you know in the app.</p>
@endif
</main>
<footer>
    <div class="bar">© {{ now()->year }} MyRaces</div>
</footer>
</body>
</html>
