<x-app-layout>
    @section('page_title', 'Admin')

    <div class="max-w-5xl mx-auto px-4 py-6 space-y-6">

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
            @foreach([
                [$stats['total_users'],                   'Usuarios',    '#C8FA5F'],
                [$stats['total_races'],                   'Carreras',    '#C8FA5F'],
                [number_format($stats['total_km'], 0).'km','Km totales', 'rgba(255,255,255,0.7)'],
                ['€'.number_format($stats['total_spent'], 0), 'Invertido','rgba(255,255,255,0.7)'],
                [$stats['premium_users'],                  'Premium',     '#a78bfa'],
                [$stats['banned_users'],                  'Baneados',    '#f87171'],
            ] as [$val, $label, $color])
                <div class="card px-4 py-4 text-center">
                    <p class="text-2xl font-black leading-none tabnum" style="color:{{ $color }}">{{ $val }}</p>
                    <p class="text-[10px] font-bold uppercase tracking-wider mt-2" style="color:rgba(255,255,255,0.30)">{{ $label }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid md:grid-cols-2 gap-4">

            {{-- Recent users --}}
            <div class="card overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3.5" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                    <h2 class="text-sm font-black text-white">Últimos usuarios</h2>
                    <a href="{{ route('admin.users') }}" class="text-xs font-bold text-primary hover:text-primary/80">Ver todos →</a>
                </div>
                <div class="divide-y" style="divide-color:rgba(255,255,255,0.05)">
                    @foreach($stats['recent_users'] as $user)
                        <div class="flex items-center gap-3 px-5 py-3">
                            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-black font-black text-xs flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-white truncate flex items-center gap-1.5">
                                    {{ $user->name }}
                                    @if($user->is_admin)
                                        <span class="text-[10px] bg-primary/20 text-primary px-1.5 py-0.5 rounded font-bold">Admin</span>
                                    @endif
                                    @if($user->is_banned)
                                        <span class="text-[10px] bg-red-500/20 text-red-400 px-1.5 py-0.5 rounded font-bold">Baneado</span>
                                    @endif
                                </p>
                                <p class="text-xs truncate" style="color:rgba(255,255,255,0.35)">{{ $user->email }}</p>
                            </div>
                            <p class="text-xs flex-shrink-0" style="color:rgba(255,255,255,0.25)">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent races --}}
            <div class="card overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3.5" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                    <h2 class="text-sm font-black text-white">Últimas carreras</h2>
                    <a href="{{ route('admin.races') }}" class="text-xs font-bold text-primary hover:text-primary/80">Ver todas →</a>
                </div>
                <div class="divide-y" style="divide-color:rgba(255,255,255,0.05)">
                    @foreach($stats['recent_races'] as $race)
                        @php
                            $statusColor = match($race->status) {
                                'completed' => '#4ade80',
                                'dnf'       => '#f87171',
                                'dns'       => 'rgba(255,255,255,0.30)',
                                default     => '#C8FA5F',
                            };
                        @endphp
                        <div class="flex items-center gap-3 px-5 py-3">
                            <div class="w-10 text-center flex-shrink-0">
                                <p class="text-sm font-black text-white leading-none">{{ $race->date->format('d') }}</p>
                                <p class="text-[10px] uppercase" style="color:rgba(255,255,255,0.30)">{{ $race->date->translatedFormat('M') }}</p>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-white truncate">{{ $race->name }}</p>
                                <p class="text-xs truncate" style="color:rgba(255,255,255,0.35)">{{ $race->user->name }} · {{ $race->distance }} km</p>
                            </div>
                            <span class="text-[10px] font-bold flex-shrink-0 w-2 h-2 rounded-full" style="background:{{ $statusColor }}"></span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Quick links --}}
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('admin.users') }}" class="card-interactive flex items-center gap-4 p-5">
                <div class="w-10 h-10 rounded-xl bg-primary/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-white">Gestionar usuarios</p>
                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">Ban, admin, eliminar</p>
                </div>
            </a>
            <a href="{{ route('admin.races') }}" class="card-interactive flex items-center gap-4 p-5">
                <div class="w-10 h-10 rounded-xl bg-primary/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-white">Gestionar carreras</p>
                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">Ver y eliminar carreras</p>
                </div>
            </a>
            <a href="{{ route('admin.ads') }}" class="card-interactive flex items-center gap-4 p-5">
                <div class="w-10 h-10 rounded-xl bg-primary/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-white">Anuncios</p>
                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">Revisar, aprobar, pausar</p>
                </div>
            </a>
            <a href="{{ route('admin.events.index') }}" class="card-interactive flex items-center gap-4 p-5">
                <div class="w-10 h-10 rounded-xl bg-primary/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-white">Catálogo de carreras</p>
                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">Añadir, editar, destacar</p>
                </div>
            </a>
            <a href="{{ route('admin.events.pending') }}" class="card-interactive flex items-center gap-4 p-5 relative">
                <div class="w-10 h-10 rounded-xl bg-primary/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-white">Envíos pendientes</p>
                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">Aprobar o rechazar</p>
                </div>
                @if($stats['pending_events'] > 0)
                    <span class="flex-shrink-0 text-xs font-black px-2 py-0.5 rounded-full"
                          style="background:rgba(245,158,11,0.20);color:#f59e0b">
                        {{ $stats['pending_events'] }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.pods.index') }}" class="card-interactive flex items-center gap-4 p-5">
                <div class="w-10 h-10 rounded-xl bg-primary/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-white">Social Pods</p>
                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">Ver, editar, moderar</p>
                </div>
            </a>
            <a href="{{ route('admin.nav-items') }}" class="card-interactive flex items-center gap-4 p-5">
                <div class="w-10 h-10 rounded-xl bg-primary/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-white">Menú móvil</p>
                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">Orden, visibilidad, premium</p>
                </div>
            </a>
        </div>

    </div>
</x-app-layout>
