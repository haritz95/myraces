<x-app-layout>
    @section('page_title', 'Mis envíos')
    @section('back_url', route('events.index'))
    @section('header_action')
        <a href="{{ route('events.submit') }}" class="btn btn-primary text-sm py-2 px-3">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="hidden md:inline">Proponer</span>
        </a>
    @endsection

    <div class="max-w-2xl mx-auto px-4 py-5 space-y-4">

        @if($submissions->isEmpty())
            <div class="card px-6 py-16 text-center space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center mx-auto">
                    <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <p class="text-white font-black text-lg">Aún no has propuesto ninguna carrera</p>
                <p class="text-sm" style="color:rgba(255,255,255,0.40)">
                    Ayuda a la comunidad compartiendo carreras que conozcas.
                </p>
                <a href="{{ route('events.submit') }}" class="btn btn-primary inline-flex">
                    Proponer carrera
                </a>
            </div>
        @else
            @foreach($submissions as $event)
                @php
                    $statusConfig = match($event->status) {
                        'pending'  => ['label' => 'Pendiente de revisión', 'color' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.12)'],
                        'upcoming' => ['label' => 'Aprobada', 'color' => 'rgb(var(--color-primary))', 'bg' => 'rgb(var(--color-primary) / 0.12)'],
                        'open'     => ['label' => 'Aprobada · Abierta', 'color' => 'rgb(var(--color-primary))', 'bg' => 'rgb(var(--color-primary) / 0.12)'],
                        'rejected' => ['label' => 'Rechazada', 'color' => '#f87171', 'bg' => 'rgba(248,113,113,0.12)'],
                        default    => ['label' => ucfirst($event->status), 'color' => 'rgba(255,255,255,0.40)', 'bg' => 'rgba(255,255,255,0.06)'],
                    };
                @endphp
                <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)">
                    <div class="flex gap-0">
                        @if($event->imageSource())
                            <div class="w-20 flex-shrink-0">
                                <img src="{{ $event->imageSource() }}" alt="" class="w-full h-full object-cover" style="min-height:88px">
                            </div>
                        @endif
                        <div class="flex-1 min-w-0 px-4 py-3.5">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <p class="text-sm font-black text-white leading-tight">{{ $event->name }}</p>
                                <span class="text-[9px] font-black px-2 py-0.5 rounded-full flex-shrink-0"
                                      style="background:{{ $statusConfig['bg'] }};color:{{ $statusConfig['color'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </div>
                            <p class="text-xs" style="color:rgba(255,255,255,0.40)">
                                {{ $event->event_date->translatedFormat('d M Y') }} · {{ $event->location }}
                            </p>
                            @if($event->status === 'rejected' && $event->rejection_reason)
                                <p class="text-xs mt-2 px-3 py-2 rounded-xl" style="background:rgba(248,113,113,0.08);color:#f87171;border:1px solid rgba(248,113,113,0.15)">
                                    {{ $event->rejection_reason }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @if($event->status === 'pending')
                        <div class="px-4 py-2.5 flex items-center justify-between border-t" style="border-color:rgba(255,255,255,0.05)">
                            <p class="text-[10px]" style="color:rgba(255,255,255,0.25)">
                                Enviada {{ $event->created_at->diffForHumans() }}
                            </p>
                            <a href="{{ route('events.submission.edit', $event) }}"
                               class="text-xs font-bold text-primary/70 hover:text-primary transition-colors">
                                Editar
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach

            <div class="mt-2">{{ $submissions->links() }}</div>
        @endif
    </div>
</x-app-layout>
