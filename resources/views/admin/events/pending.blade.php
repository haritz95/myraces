<x-app-layout>
    @section('page_title', 'Envíos pendientes')
    @section('back_url', route('admin.events.index'))

    <div class="max-w-2xl mx-auto px-4 py-5 space-y-4">

        @if($submissions->isEmpty())
            <div class="card px-6 py-16 text-center space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center mx-auto">
                    <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-white font-black text-lg">Todo al día</p>
                <p class="text-sm" style="color:rgba(255,255,255,0.40)">No hay envíos pendientes de revisión.</p>
            </div>
        @else
            @foreach($submissions as $event)
                <div class="rounded-2xl overflow-hidden" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)">

                    {{-- Header --}}
                    <div class="flex gap-0">
                        @if($event->imageSource())
                            <div class="w-20 flex-shrink-0">
                                <img src="{{ $event->imageSource() }}" alt="" class="w-full h-full object-cover" style="min-height:96px">
                            </div>
                        @endif
                        <div class="flex-1 min-w-0 px-4 py-3.5">
                            <p class="text-sm font-black text-white leading-tight mb-1">{{ $event->name }}</p>
                            <p class="text-xs" style="color:rgba(255,255,255,0.40)">
                                {{ $event->event_date->translatedFormat('d M Y') }} · {{ $event->location }}{{ $event->province ? ', ' . $event->province : '' }}
                            </p>
                            <div class="flex items-center gap-3 mt-1.5">
                                <span class="text-[10px] font-bold" style="color:rgba(255,255,255,0.35)">{{ $event->raceTypeLabel() }}</span>
                                @if($event->category)
                                    <span class="text-[10px] font-bold" style="color:rgba(255,255,255,0.35)">{{ $event->category }}</span>
                                @endif
                                @if($event->distance_km)
                                    <span class="text-[10px] font-bold text-primary">{{ $event->distance_km }} km</span>
                                @endif
                            </div>
                            <p class="text-[10px] mt-1.5" style="color:rgba(255,255,255,0.25)">
                                Enviado por
                                <span class="text-white/50 font-bold">{{ $event->submitter?->name ?? 'Usuario eliminado' }}</span>
                                · {{ $event->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    {{-- Extra info --}}
                    @if($event->description || $event->website_url || $event->modalities->isNotEmpty())
                        <div class="px-4 pb-3 space-y-2 border-t" style="border-color:rgba(255,255,255,0.05)">
                            @if($event->description)
                                <p class="text-xs mt-3" style="color:rgba(255,255,255,0.50)">{{ Str::limit($event->description, 180) }}</p>
                            @endif
                            @if($event->modalities->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5 mt-2">
                                    @foreach($event->modalities as $mod)
                                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full"
                                              style="background:rgb(var(--color-primary) / 0.10);color:rgb(var(--color-primary))">
                                            {{ $mod->name }}{{ $mod->distance_km ? ' · ' . $mod->distance_km . ' km' : '' }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            @if($event->website_url)
                                <a href="{{ $event->website_url }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1 text-xs font-bold mt-1"
                                   style="color:rgba(255,255,255,0.40)">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Web oficial
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="px-4 py-3 flex items-center gap-2 border-t" style="border-color:rgba(255,255,255,0.05)">
                        {{-- Approve --}}
                        <form method="POST" action="{{ route('admin.events.approve', $event) }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-1.5 text-xs font-black px-4 py-2 rounded-xl transition-colors"
                                    style="background:rgb(var(--color-primary) / 0.15);color:rgb(var(--color-primary))">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Aprobar
                            </button>
                        </form>

                        {{-- Reject --}}
                        <div x-data="{ open: false }" class="flex-1">
                            <button type="button" @click="open = !open"
                                    class="flex items-center gap-1.5 text-xs font-black px-4 py-2 rounded-xl transition-colors"
                                    style="background:rgba(248,113,113,0.12);color:#f87171">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Rechazar
                            </button>
                            <form method="POST" action="{{ route('admin.events.reject', $event) }}"
                                  x-show="open" x-collapse class="mt-2 space-y-2">
                                @csrf
                                <textarea name="rejection_reason" rows="2" maxlength="500"
                                          placeholder="Motivo (opcional, se lo mostramos al usuario)..."
                                          class="input-field resize-none text-sm w-full"></textarea>
                                <button type="submit" class="btn text-xs py-2 px-4"
                                        style="background:rgba(248,113,113,0.15);color:#f87171">
                                    Confirmar rechazo
                                </button>
                            </form>
                        </div>

                        {{-- View --}}
                        <a href="{{ route('admin.events.edit', $event) }}"
                           class="ml-auto text-xs font-bold transition-colors"
                           style="color:rgba(255,255,255,0.30)">
                            Editar
                        </a>
                    </div>
                </div>
            @endforeach

            <div class="mt-2">{{ $submissions->links() }}</div>
        @endif
    </div>
</x-app-layout>
