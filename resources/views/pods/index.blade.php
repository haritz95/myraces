<x-app-layout>
    @section('page_title', 'Social Pods')

    @section('header_action')
        @if($canCreatePod)
            <a href="{{ route('pods.create') }}" class="btn btn-primary py-2 text-xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Crear Pod
            </a>
        @else
            <span class="text-[10px] font-bold px-3 py-2 rounded-xl" style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.35)">
                Límite (3/3)
            </span>
        @endif
    @endsection

    @php
        $statusGradients = [
            'active'    => 'from-primary/20 to-primary/5',
            'completed' => 'from-amber-500/20 to-amber-500/5',
            'archived'  => 'from-white/5 to-transparent',
        ];
        $statusDots = [
            'active'    => 'bg-primary',
            'completed' => 'bg-amber-400',
            'archived'  => 'bg-white/30',
        ];
    @endphp

    <div class="max-w-2xl mx-auto px-4 py-6 space-y-8">

        {{-- Streak banner --}}
        @if($streak && $streak->current_streak > 0)
        <div class="relative overflow-hidden rounded-2xl px-5 py-4"
             style="background:linear-gradient(135deg,rgba(200,250,95,0.15) 0%,rgba(200,250,95,0.05) 100%);border:1px solid rgba(200,250,95,0.25);backdrop-filter:blur(12px)">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-primary/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-white font-black text-lg leading-none">{{ $streak->current_streak }}-day streak</p>
                    <p class="text-xs mt-1" style="color:rgba(255,255,255,0.55)">
                        Multiplicador activo: <span class="text-primary font-black">×{{ number_format($streak->multiplier(), 1) }}</span>
                        · Mejor racha: {{ $streak->longest_streak }} días
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] uppercase tracking-widest font-bold" style="color:rgba(255,255,255,0.30)">Puntos/km</p>
                    <p class="text-primary font-black text-xl">{{ number_format($streak->multiplier(), 1) }}×</p>
                </div>
            </div>
            {{-- Formula --}}
            <p class="text-[10px] mt-3 font-mono" style="color:rgba(255,255,255,0.25)">
                Puntos = Distancia(km) × (1 + {{ $streak->current_streak }}/10) = ×{{ number_format($streak->multiplier(), 2) }}
            </p>
        </div>
        @endif

        @if(session('success'))
            <div class="card px-4 py-3 text-sm font-semibold text-green-400 bg-green-500/10 border-green-500/20">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="card px-4 py-3 text-sm font-semibold text-red-400 bg-red-500/10 border-red-500/20">{{ session('error') }}</div>
        @endif

        {{-- My pods --}}
        @if($myPods->isNotEmpty())
        <section>
            <p class="section-label mb-3">Mis Pods ({{ $myPods->count() }})</p>
            <div class="space-y-3">
                @foreach($myPods as $pod)
                    @php $gradient = $statusGradients[$pod->status] ?? $statusGradients['archived']; @endphp
                    <a href="{{ route('pods.show', $pod) }}"
                       class="block relative overflow-hidden rounded-2xl px-5 py-4 transition-all hover:scale-[1.01] active:scale-[0.99]"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);backdrop-filter:blur(12px);box-shadow:0 8px 32px rgba(0,0,0,0.25)">

                        {{-- Status glow --}}
                        <div class="absolute inset-0 bg-gradient-to-br {{ $gradient }} pointer-events-none rounded-2xl"></div>

                        <div class="relative">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $statusDots[$pod->status] ?? 'bg-white/30' }}"></span>
                                        <p class="text-white font-black text-[15px] truncate">{{ $pod->name }}</p>
                                    </div>
                                    <p class="text-xs truncate" style="color:rgba(255,255,255,0.45)">{{ $pod->goal }}</p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-xs font-bold text-white">{{ $pod->members_count }}/{{ $pod->max_members }}</p>
                                    <p class="text-[10px]" style="color:rgba(255,255,255,0.30)">miembros</p>
                                </div>
                            </div>

                            @if($pod->target_distance)
                                @php $pct = min(100, $pod->progressPercent()); @endphp
                                <div class="mt-3">
                                    <div class="flex justify-between text-[10px] mb-1" style="color:rgba(255,255,255,0.35)">
                                        <span>Progreso del equipo</span>
                                        <span class="font-bold text-white">{{ $pct }}%</span>
                                    </div>
                                    <div class="h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.08)">
                                        <div class="h-full rounded-full transition-all duration-700"
                                             style="width:{{ $pct }}%;background:{{ $pod->status === 'completed' ? '#f59e0b' : '#C8FA5F' }}"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Discover --}}
        @if($discover->isNotEmpty())
        <section>
            <p class="section-label mb-3">Descubrir Pods</p>
            <div class="space-y-3">
                @foreach($discover as $pod)
                    <div class="relative overflow-hidden rounded-2xl px-5 py-4"
                         style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);backdrop-filter:blur(12px)">
                        <div class="flex items-start gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-white font-bold text-sm">{{ $pod->name }}</p>
                                <p class="text-xs mt-0.5 truncate" style="color:rgba(255,255,255,0.40)">{{ $pod->goal }}</p>
                                @if($pod->description)
                                    <p class="text-xs mt-1 line-clamp-2" style="color:rgba(255,255,255,0.30)">{{ $pod->description }}</p>
                                @endif
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-[10px]" style="color:rgba(255,255,255,0.30)">
                                        {{ $pod->members_count }}/{{ $pod->max_members }} miembros
                                    </span>
                                    @if($pod->ends_at)
                                        <span class="text-[10px]" style="color:rgba(255,255,255,0.25)">hasta {{ $pod->ends_at->format('d/m/Y') }}</span>
                                    @endif
                                </div>
                            </div>
                            @if($pod->members_count < $pod->max_members)
                                <form method="POST" action="{{ route('pods.join', $pod) }}" class="flex-shrink-0">
                                    @csrf
                                    <button type="submit" class="btn btn-primary text-xs py-2 px-4">Unirse</button>
                                </form>
                            @else
                                <span class="text-xs font-bold px-3 py-2 rounded-xl flex-shrink-0"
                                      style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.35)">Lleno</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        @if($myPods->isEmpty() && $discover->isEmpty())
            <div class="card px-6 py-16 text-center space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center mx-auto">
                    <svg class="w-8 h-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-black text-xl">Sin Pods todavía</p>
                    <p class="text-sm mt-1" style="color:rgba(255,255,255,0.40)">Crea el primero e invita a tus compañeros de entreno.</p>
                </div>
                <a href="{{ route('pods.create') }}" class="btn btn-primary mx-auto">Crear mi primer Pod</a>
            </div>
        @endif

    </div>
</x-app-layout>
