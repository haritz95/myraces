<x-app-layout>
    @section('page_title', 'Premium')

    <div class="max-w-lg mx-auto px-4 py-8 space-y-6">

        {{-- Header --}}
        <div class="text-center space-y-3">
            <div class="w-16 h-16 rounded-2xl bg-primary/15 border border-primary/30 flex items-center justify-center mx-auto">
                <svg class="w-8 h-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            @if(session('premium_required'))
                <p class="text-xs font-bold px-3 py-1.5 rounded-full inline-block" style="background:rgba(248,113,113,0.12);color:#f87171">
                    Necesitas premium para acceder a esta sección
                </p>
            @endif
            <h1 class="text-2xl font-black text-white">MyRaces Premium</h1>
            <p class="text-sm" style="color:rgba(255,255,255,0.50)">Desbloquea todo el potencial de tu entrenamiento</p>
        </div>

        {{-- Features --}}
        <div class="card divide-y" style="divide-color:rgba(255,255,255,0.05)">
            @foreach([
                ['Coach IA', 'Análisis personalizado de tu rendimiento con inteligencia artificial', 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z'],
                ['Estadísticas avanzadas', 'Gráficas de progreso, tendencias y comparativas entre temporadas', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['Planes de entrenamiento', 'Acceso a planes estructurados hacia tu próxima carrera objetivo', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['Pods ilimitados', 'Crea todos los grupos de entrenamiento que necesites', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['Sin publicidad', 'Experiencia limpia sin anuncios en toda la aplicación', 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'],
            ] as [$title, $desc, $icon])
            <div class="flex items-start gap-4 px-5 py-4">
                <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4.5 h-4.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-white">{{ $title }}</p>
                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.45)">{{ $desc }}</p>
                </div>
                <svg class="w-4 h-4 text-primary flex-shrink-0 mt-1 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            @endforeach
        </div>

        {{-- CTA --}}
        @if(auth()->user()->is_premium)
            <div class="card px-5 py-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-bold text-white">Ya tienes acceso premium activo</p>
            </div>
        @else
            <div class="card px-5 py-5 space-y-3 text-center">
                <p class="text-sm" style="color:rgba(255,255,255,0.55)">El acceso premium es gestionado por el equipo de MyRaces. Escríbenos para activarlo.</p>
                <a href="mailto:hola@myraces.app?subject=Solicitud%20Premium"
                   class="btn btn-primary w-full block">
                    Solicitar premium
                </a>
            </div>
        @endif

    </div>
</x-app-layout>
