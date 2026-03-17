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
        $heroBg = match($race->status) {
            'upcoming'  => 'background: linear-gradient(135deg, #221610 0%, #7c2d12 40%, #ec5b13 100%)',
            'completed' => 'background: linear-gradient(135deg, #052e16 0%, #166534 50%, #16a34a 100%)',
            'dnf'       => 'background: linear-gradient(135deg, #450a0a 0%, #7f1d1d 50%, #dc2626 100%)',
            default     => 'background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #475569 100%)',
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
    <div class="relative overflow-hidden" style="{{ $heroBg }}; padding: 32px 24px {{ $isCompleted ? '36px' : '28px' }}">
        <div class="absolute inset-0 opacity-[0.08]" style="background-image: radial-gradient(circle at 20% 80%, white 1px, transparent 1px), radial-gradient(circle at 80% 20%, white 1px, transparent 1px); background-size: 40px 40px"></div>
        <div class="relative">
            <div class="flex items-start justify-between gap-3 mb-2">
                <h2 class="text-white font-bold text-xl leading-snug flex-1">{{ $race->name }}</h2>
                <span class="badge {{ $badgeClass }} flex-shrink-0 mt-1">{{ __('races.statuses.' . $race->status) }}</span>
            </div>
            <p class="flex items-center gap-1.5 flex-wrap mb-1 text-sm" style="color:rgba(255,255,255,0.50)">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $race->date->translatedFormat('d \d\e F \d\e Y') }}
                @if($race->location)
                    <span style="color:rgba(255,255,255,0.25)">·</span>
                    {{ $race->location }}@if($race->country), {{ strtoupper($race->country) }}@endif
                @endif
            </p>

            @if($isCompleted)
                <div class="mt-8 mb-2">
                    <p class="text-[10px] font-bold uppercase tracking-widest mb-3" style="color:rgba(255,255,255,0.30)">Tiempo oficial</p>
                    <p class="text-[64px] font-bold text-white tabnum leading-none tracking-tight">{{ $race->formatted_time }}</p>
                </div>
                <div class="flex items-center gap-3 mt-5 flex-wrap">
                    @if($race->pace)
                        <div class="rounded-lg px-4 py-3 min-w-[80px] text-center" style="background:rgba(255,255,255,0.12)">
                            <p class="text-base font-bold text-white tabnum leading-none">{{ $race->pace }}</p>
                            <p class="text-[10px] font-bold uppercase tracking-wide mt-1" style="color:rgba(255,255,255,0.40)">Ritmo/km</p>
                        </div>
                    @endif
                    <div class="rounded-lg px-4 py-3 min-w-[80px] text-center" style="background:rgba(255,255,255,0.12)">
                        <p class="text-base font-bold text-white tabnum leading-none">{{ $race->formatted_distance }} <span class="text-sm" style="color:rgba(255,255,255,0.50)">{{ $race->distance_unit }}</span></p>
                        <p class="text-[10px] font-bold uppercase tracking-wide mt-1" style="color:rgba(255,255,255,0.40)">Distancia</p>
                    </div>
                    @if($race->position_overall)
                        <div class="rounded-lg px-4 py-3 min-w-[80px] text-center" style="background:rgba(255,255,255,0.12)">
                            <p class="text-base font-bold text-white tabnum leading-none">#{{ $race->position_overall }}</p>
                            <p class="text-[10px] font-bold uppercase tracking-wide mt-1" style="color:rgba(255,255,255,0.40)">Posición</p>
                        </div>
                    @endif
                </div>

            @elseif($race->status === 'upcoming')
                @php $daysLeft = now()->startOfDay()->diffInDays($race->date->startOfDay()); @endphp
                <div class="mt-8 text-center">
                    @if($daysLeft === 0)
                        <p class="text-4xl font-bold text-white">¡Hoy es el día!</p>
                    @elseif($daysLeft === 1)
                        <p class="text-4xl font-bold text-white">¡Mañana!</p>
                    @else
                        <p class="text-[10px] font-bold uppercase tracking-widest mb-3" style="color:rgba(255,255,255,0.30)">Faltan</p>
                        <p class="text-[72px] font-bold text-white tabnum leading-none">{{ $daysLeft }}</p>
                        <p class="text-base font-medium mt-2" style="color:rgba(255,255,255,0.40)">días para la carrera</p>
                    @endif
                </div>
            @else
                <div class="mt-5 rounded-lg px-5 py-4" style="background:rgba(255,255,255,0.10)">
                    <p class="text-white font-medium text-sm">{{ $race->formatted_distance }} {{ $race->distance_unit }} · {{ __('races.modalities.' . $race->modality) }}</p>
                    <p class="text-xs mt-1" style="color:rgba(255,255,255,0.40)">{{ $race->status === 'dnf' ? 'No se completó la carrera' : 'No se llegó a la salida' }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ── DETAILS ───────────────────────────────────────────── --}}
    <main class="px-6 py-6 max-w-2xl mx-auto w-full space-y-4 mb-20">

        <div class="bg-white rounded-xl border border-slate-100 shadow-card divide-y divide-slate-50">
            @php
                $rows = [
                    ['label' => 'Distancia', 'value' => $race->formatted_distance . ' ' . $race->distance_unit],
                    ['label' => 'Modalidad', 'value' => __('races.modalities.' . $race->modality)],
                ];
                if ($race->bib_number)        { $rows[] = ['label' => 'Dorsal',        'value' => $race->bib_number]; }
                if ($race->category)          { $rows[] = ['label' => 'Categoría',      'value' => $race->category]; }
                if ($race->position_category) { $rows[] = ['label' => 'Pos. categoría', 'value' => '#' . $race->position_category]; }
                if ($race->cost)              { $rows[] = ['label' => 'Inscripción',     'value' => number_format((float) $race->cost, 2) . ' €']; }
            @endphp
            @foreach($rows as $row)
                <div class="flex items-center justify-between px-5 py-3.5">
                    <span class="text-sm text-slate-500">{{ $row['label'] }}</span>
                    <span class="text-sm font-bold text-slate-900">{{ $row['value'] }}</span>
                </div>
            @endforeach
        </div>

        @if($race->notes)
            <div class="bg-white rounded-xl border border-slate-100 shadow-card px-5 py-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Notas</p>
                <p class="text-sm text-slate-700 leading-relaxed">{{ $race->notes }}</p>
            </div>
        @endif

        @if($race->website)
            <a href="{{ $race->website }}" target="_blank" rel="noopener"
               class="flex items-center gap-3.5 bg-white rounded-xl border border-slate-100 shadow-card px-5 py-4 hover:shadow-card-up hover:border-slate-200 transition-all">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                    <svg style="width:16px;height:16px" class="text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-slate-800 flex-1">Web oficial</span>
                <svg class="w-5 h-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @endif

        @can('update', $race)
            <div class="space-y-2.5 pt-1">
                <a href="{{ route('races.edit', $race) }}" class="btn btn-secondary w-full py-3">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('races.edit') }}
                </a>
                <form method="POST" action="{{ route('races.destroy', $race) }}" onsubmit="return confirm('{{ __('races.confirm_delete') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 text-sm font-semibold text-red-500 bg-red-50 rounded-full hover:bg-red-100 transition-colors border border-red-100">
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
