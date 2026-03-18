<x-app-layout>
    @section('page_title', 'Importar de Strava')
    @section('back_url', route('races.index'))

    <div class="max-w-lg mx-auto px-4 py-10 text-center space-y-5">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto" style="background:#FC4C02">
            <svg class="w-9 h-9 text-white" viewBox="0 0 24 24" fill="currentColor">
                <path d="M15.387 17.944l-2.089-4.116h-3.065L15.387 24l5.15-10.172h-3.066m-7.008-5.599l2.836 5.599h4.172L10.463 0l-7 13.828h4.169"/>
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-black text-white">Conecta con Strava</h1>
            <p class="text-sm mt-1" style="color:rgba(255,255,255,0.50)">Importa tus actividades de running directamente desde tu cuenta de Strava.</p>
        </div>
        <a href="{{ route('social.redirect', 'strava') }}" class="btn btn-primary inline-block">
            Conectar con Strava
        </a>
    </div>
</x-app-layout>
