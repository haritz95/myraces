@php
    $accentColor = match($race->status) {
        'upcoming'  => '#C8FA5F',
        'completed' => '#4ade80',
        'dnf'       => '#f87171',
        default     => '#6b7280',
    };
    $badgeClass = match($race->status) {
        'upcoming'  => 'badge-upcoming',
        'completed' => 'badge-completed',
        'dnf'       => 'badge-dnf',
        default     => 'badge-dns',
    };
@endphp
<a href="{{ route('races.show', $race) }}"
   class="flex items-center gap-4 card-interactive p-4 group">

    {{-- Date block --}}
    <div class="w-[52px] h-[52px] rounded-2xl flex flex-col items-center justify-center flex-shrink-0"
         style="background:{{ $accentColor }}18;border:1px solid {{ $accentColor }}30">
        <p class="text-lg font-black tabnum leading-none" style="color:{{ $accentColor }}">{{ $race->date->format('d') }}</p>
        <p class="text-[9px] font-black uppercase tracking-wider mt-0.5" style="color:{{ $accentColor }}80">{{ $race->date->translatedFormat('M') }}</p>
    </div>

    {{-- Info --}}
    <div class="flex-1 min-w-0">
        <h4 class="font-black text-sm text-white truncate leading-snug">{{ $race->name }}</h4>
        <p class="text-xs mt-0.5 truncate" style="color:rgba(255,255,255,0.40)">
            {{ $race->formatted_distance }} {{ $race->distance_unit }}
            · {{ __('races.modalities.' . $race->modality) }}
            @if($race->location) · {{ $race->location }}@endif
        </p>
        <div class="mt-2">
            @if($race->formatted_time)
                <div class="flex items-center gap-2">
                    <span class="text-sm font-black text-white tabnum">{{ $race->formatted_time }}</span>
                    @if($race->pace)
                        <span class="text-xs tabnum" style="color:rgba(255,255,255,0.35)">· {{ $race->pace }}/km</span>
                    @endif
                </div>
            @elseif($race->status === 'upcoming')
                @php $d = now()->startOfDay()->diffInDays($race->date->startOfDay()); @endphp
                <span class="text-[10px] font-black py-1 px-2.5 rounded-full uppercase" style="background:rgba(200,250,95,0.12);color:#C8FA5F">
                    {{ $d === 0 ? '¡Hoy!' : ($d === 1 ? 'Mañana' : "En {$d} días") }}
                </span>
            @else
                <span class="badge {{ $badgeClass }}">{{ __('races.statuses.' . $race->status) }}</span>
            @endif
        </div>
    </div>

    <svg class="w-4 h-4 flex-shrink-0 transition-transform group-hover:translate-x-0.5" style="color:rgba(255,255,255,0.20)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
    </svg>
</a>
