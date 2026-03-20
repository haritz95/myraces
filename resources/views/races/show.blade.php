<x-app-layout>
    @section('page_title', $race->name)
    @section('back_url', route('races.index'))
    @section('header_action')
        @can('update', $race)
            <a href="{{ route('races.edit', $race) }}" class="btn btn-secondary py-1.5 px-3.5 text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
        @endcan
    @endsection

    @php
        $accentColor = match($race->status) {
            'upcoming'  => 'rgb(var(--color-primary))',
            'completed' => '#4ade80',
            'dnf'       => '#f87171',
            default     => '#6b7280',
        };
        $heroBg = match($race->status) {
            'upcoming'  => 'background:linear-gradient(160deg,#0f1a00 0%,#1f2d00 50%,#2a3d00 100%)',
            'completed' => 'background:linear-gradient(160deg,#001a0a 0%,#003015 50%,#004d20 100%)',
            'dnf'       => 'background:linear-gradient(160deg,#1a0000 0%,#300000 50%,#4d0000 100%)',
            default     => 'background:linear-gradient(160deg,#0d0d0d 0%,#161616 50%,#1e1e1e 100%)',
        };
        $badgeClass = match($race->status) {
            'upcoming'  => 'badge-upcoming',
            'completed' => 'badge-completed',
            'dnf'       => 'badge-dnf',
            default     => 'badge-dns',
        };
        $isCompleted = $race->status === 'completed' && $race->formatted_time;
    @endphp

    {{-- ── HERO ──────────────────────────────────────────────── --}}
    <div class="relative overflow-hidden px-6 pt-8 pb-8" style="{{ $heroBg }};border-bottom:1px solid rgba(255,255,255,0.06)">
        <div class="absolute inset-0 opacity-[0.05]" style="background-image:radial-gradient(circle at 80% 20%, {{ $accentColor }} 1px, transparent 1px);background-size:40px 40px"></div>
        <div class="relative max-w-2xl mx-auto">
            <div class="flex items-start justify-between gap-3 mb-2">
                <h2 class="text-white font-black text-2xl leading-tight flex-1">{{ $race->name }}</h2>
                <span class="badge {{ $badgeClass }} flex-shrink-0 mt-1">{{ __('races.statuses.' . $race->status) }}</span>
            </div>
            <p class="text-sm flex items-center gap-2 flex-wrap mb-1" style="color:rgba(255,255,255,0.40)">
                {{ $race->date->translatedFormat('d \d\e F \d\e Y') }}
                @if($race->location)
                    <span style="color:rgba(255,255,255,0.20)">·</span>
                    {{ $race->location }}@if($race->country), {{ strtoupper($race->country) }}@endif
                @endif
            </p>

            @if($isCompleted)
                <div class="mt-8">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] mb-3" style="color:rgba(255,255,255,0.30)">Tiempo oficial</p>
                    <p class="text-[72px] font-black text-white tabnum leading-none" style="color:{{ $accentColor }}">{{ $race->formatted_time }}</p>
                </div>
                <div class="flex items-center gap-3 mt-5 flex-wrap">
                    @if($race->pace)
                        <div class="rounded-2xl px-4 py-3 min-w-[80px] text-center" style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.08)">
                            <p class="text-base font-black text-white tabnum leading-none">{{ $race->pace }}</p>
                            <p class="text-[10px] font-bold uppercase tracking-wide mt-1" style="color:rgba(255,255,255,0.35)">Ritmo/km</p>
                        </div>
                    @endif
                    <div class="rounded-2xl px-4 py-3 min-w-[80px] text-center" style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.08)">
                        <p class="text-base font-black text-white tabnum leading-none">{{ $race->formatted_distance }} <span class="text-sm" style="color:rgba(255,255,255,0.40)">{{ $race->distance_unit }}</span></p>
                        <p class="text-[10px] font-bold uppercase tracking-wide mt-1" style="color:rgba(255,255,255,0.35)">Distancia</p>
                    </div>
                    @if($race->position_overall)
                        <div class="rounded-2xl px-4 py-3 min-w-[80px] text-center" style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.08)">
                            <p class="text-base font-black text-white tabnum leading-none">#{{ $race->position_overall }}</p>
                            <p class="text-[10px] font-bold uppercase tracking-wide mt-1" style="color:rgba(255,255,255,0.35)">Posición</p>
                        </div>
                    @endif
                </div>

            @elseif($race->status === 'upcoming')
                @php $daysLeft = now()->startOfDay()->diffInDays($race->date->startOfDay()); @endphp
                <div class="mt-8 text-center">
                    @if($daysLeft === 0)
                        <p class="text-5xl font-black" style="color:{{ $accentColor }}">¡Hoy!</p>
                    @elseif($daysLeft === 1)
                        <p class="text-5xl font-black" style="color:{{ $accentColor }}">¡Mañana!</p>
                    @else
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] mb-3" style="color:rgba(255,255,255,0.30)">Faltan</p>
                        <p class="text-[80px] font-black tabnum leading-none" style="color:{{ $accentColor }}">{{ $daysLeft }}</p>
                        <p class="text-base font-semibold mt-2" style="color:rgba(255,255,255,0.35)">días para la carrera</p>
                    @endif
                </div>
            @else
                <div class="mt-6 rounded-2xl px-5 py-4" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08)">
                    <p class="text-white font-semibold text-sm">{{ $race->formatted_distance }} {{ $race->distance_unit }} · {{ __('races.modalities.' . $race->modality) }}</p>
                    <p class="text-xs mt-1" style="color:rgba(255,255,255,0.35)">{{ $race->status === 'dnf' ? 'No se completó la carrera' : 'No se llegó a la salida' }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ── DETAILS ───────────────────────────────────────────── --}}
    <main class="px-5 py-6 max-w-2xl mx-auto w-full space-y-4">

        <div class="card overflow-hidden">
            @php
                $rows = [
                    ['label' => 'Distancia', 'value' => $race->formatted_distance . ' ' . $race->distance_unit],
                    ['label' => 'Modalidad', 'value' => __('races.modalities.' . $race->modality)],
                ];
                if ($race->bib_number)        { $rows[] = ['label' => 'Dorsal',          'value' => $race->bib_number]; }
                if ($race->category)          { $rows[] = ['label' => 'Categoría',        'value' => $race->category]; }
                if ($race->position_category) { $rows[] = ['label' => 'Pos. categoría',   'value' => '#' . $race->position_category]; }
                if ($race->cost)              { $rows[] = ['label' => 'Inscripción',       'value' => number_format((float) $race->cost, 2) . ' €']; }
            @endphp
            @foreach($rows as $row)
                <div class="flex items-center justify-between px-5 py-3.5" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <span class="text-sm font-medium" style="color:rgba(255,255,255,0.45)">{{ $row['label'] }}</span>
                    <span class="text-sm font-black text-white">{{ $row['value'] }}</span>
                </div>
            @endforeach
        </div>

        @if($race->gear->isNotEmpty())
            @php
                $gearTypeLabels = ['shoes' => 'Zapatillas', 'watch' => 'Reloj', 'clothing' => 'Ropa', 'accessories' => 'Accesorios', 'nutrition' => 'Nutrición', 'other' => 'Otros'];
                $gearTypeColors = ['shoes' => '#60a5fa', 'watch' => '#a78bfa', 'clothing' => '#4ade80', 'accessories' => '#fb923c', 'nutrition' => '#34d399', 'other' => '#6b7280'];
                $gearTypeIcons  = [
                    'shoes'       => 'M13 10V3L4 14h7v7l9-11h-7z',
                    'watch'       => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    'clothing'    => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
                    'accessories' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
                    'nutrition'   => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                    'other'       => 'M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z',
                ];
            @endphp
            <div class="card overflow-hidden">
                <div class="px-5 py-3.5" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <p class="section-label">Material utilizado</p>
                </div>
                @foreach($race->gear as $item)
                    @php $c = $gearTypeColors[$item->type] ?? '#6b7280'; @endphp
                    <div class="flex items-center gap-3 px-5 py-3.5" style="border-bottom:1px solid rgba(255,255,255,0.04)">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:{{ $c }}18;border:1px solid {{ $c }}30">
                            <svg style="width:16px;height:16px;color:{{ $c }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $gearTypeIcons[$item->type] ?? $gearTypeIcons['other'] }}"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-white">{{ $item->brand }} {{ $item->model }}</p>
                            <p class="text-xs" style="color:rgba(255,255,255,0.35)">{{ $gearTypeLabels[$item->type] ?? $item->type }}</p>
                        </div>
                        <span class="text-xs font-bold tabnum" style="color:rgba(255,255,255,0.40)">{{ number_format((float) $item->current_km, 0) }} km</span>
                    </div>
                @endforeach
            </div>
        @endif

        @if($race->notes)
            <div class="card px-5 py-4">
                <p class="section-label">Notas</p>
                <p class="text-sm text-white/80 leading-relaxed">{{ $race->notes }}</p>
            </div>
        @endif

        @if($race->website)
            <a href="{{ $race->website }}" target="_blank" rel="noopener"
               class="card-interactive flex items-center gap-4 px-5 py-4">
                <div class="w-10 h-10 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center flex-shrink-0">
                    <svg style="width:16px;height:16px" class="text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </div>
                <span class="text-sm font-bold text-white flex-1">Web oficial</span>
                <svg class="w-4 h-4 flex-shrink-0" style="color:rgba(255,255,255,0.25)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endif

        @can('update', $race)
            <div class="space-y-2 pt-1">
                <a href="{{ route('races.edit', $race) }}" class="btn btn-secondary w-full py-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('races.edit') }}
                </a>
                <form method="POST" action="{{ route('races.destroy', $race) }}" onsubmit="return confirm('{{ __('races.confirm_delete') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full py-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        {{ __('races.delete') }}
                    </button>
                </form>
            </div>
        @endcan
    </main>
</x-app-layout>
