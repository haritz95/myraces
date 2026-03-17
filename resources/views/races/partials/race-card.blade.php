@php
    $statusBg = match($race->status) {
        'upcoming'  => 'background: linear-gradient(160deg, #ec5b13, #c2410c)',
        'completed' => 'background: linear-gradient(160deg, #16a34a, #15803d)',
        'dnf'       => 'background: linear-gradient(160deg, #dc2626, #b91c1c)',
        default     => 'background: linear-gradient(160deg, #94a3b8, #64748b)',
    };
    $badgeClass = match($race->status) {
        'upcoming'  => 'badge-upcoming',
        'completed' => 'badge-completed',
        'dnf'       => 'badge-dnf',
        default     => 'badge-dns',
    };
@endphp
<a href="{{ route('races.show', $race) }}"
   class="flex items-center gap-3 bg-white p-3 rounded-xl shadow-card border border-slate-100 hover:shadow-card-up hover:border-slate-200 transition-all duration-200 group">

    {{-- Status date block (like collection thumbnail) --}}
    <div class="w-20 h-20 rounded-lg overflow-hidden shrink-0 flex flex-col items-center justify-center"
         style="{{ $statusBg }}">
        <p class="text-2xl font-bold text-white tabnum leading-none">{{ $race->date->format('d') }}</p>
        <p class="text-[10px] font-bold text-white/80 uppercase tracking-wide mt-1">{{ $race->date->translatedFormat('M') }}</p>
        <p class="text-[10px] text-white/60">{{ $race->date->format('Y') }}</p>
    </div>

    {{-- Info --}}
    <div class="flex-1 min-w-0">
        <h4 class="font-bold text-sm text-slate-900 truncate">{{ $race->name }}</h4>
        <p class="text-xs text-slate-500 mt-0.5 truncate">
            {{ $race->formatted_distance }} {{ $race->distance_unit }}
            · {{ __('races.modalities.' . $race->modality) }}
            @if($race->location) · {{ $race->location }}@endif
        </p>
        <div class="mt-2">
            @if($race->formatted_time)
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-slate-800 tabnum">{{ $race->formatted_time }}</span>
                    @if($race->pace)
                        <span class="text-xs text-slate-400 tabnum">· {{ $race->pace }}/km</span>
                    @endif
                </div>
            @elseif($race->status === 'upcoming')
                @php $d = now()->startOfDay()->diffInDays($race->date->startOfDay()); @endphp
                <span class="text-[10px] font-bold py-1 px-2.5 bg-primary/10 text-primary rounded-full uppercase">
                    {{ $d === 0 ? '¡Hoy!' : ($d === 1 ? 'Mañana' : "En {$d} días") }}
                </span>
            @else
                <span class="badge {{ $badgeClass }}">{{ __('races.statuses.' . $race->status) }}</span>
            @endif
        </div>
    </div>

    <svg class="w-5 h-5 text-slate-300 group-hover:text-primary transition-colors flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
</a>
