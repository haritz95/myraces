<div x-data="{ rejectOpen: false }">
    <div class="flex items-start gap-3">
        {{-- Image thumbnail --}}
        @if($ad->imageUrl())
            <img src="{{ $ad->imageUrl() }}" alt="" class="w-14 h-14 rounded-xl object-cover flex-shrink-0">
        @else
            <div class="w-14 h-14 rounded-xl flex-shrink-0 flex items-center justify-center" style="background:rgba(255,255,255,0.06)">
                <svg class="w-6 h-6" style="color:rgba(255,255,255,0.20)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <p class="text-sm font-bold text-white truncate">{{ $ad->title }}</p>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded" style="background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.40)">{{ $ad->typeLabel() }}</span>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded" style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.30)">{{ $ad->location === 'feed' ? 'Feed' : 'Dashboard' }}</span>
            </div>
            @if($ad->subtitle)
                <p class="text-xs mt-0.5 truncate" style="color:rgba(255,255,255,0.40)">{{ $ad->subtitle }}</p>
            @endif
            <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                <span class="text-[11px]" style="color:rgba(255,255,255,0.30)">{{ $ad->user?->name ?? 'Admin' }}</span>
                <span class="text-[11px] tabnum" style="color:rgba(255,255,255,0.25)">{{ number_format($ad->impressions_count) }} imp · {{ number_format($ad->clicks_count) }} clicks · {{ $ad->ctr() }}% CTR</span>
                @if($ad->ends_at)
                    <span class="text-[11px]" style="color:rgba(255,255,255,0.25)">hasta {{ $ad->ends_at->format('d/m/Y') }}</span>
                @endif
                @if($ad->rejection_reason)
                    <span class="text-[11px] text-red-400">{{ $ad->rejection_reason }}</span>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-1.5 flex-shrink-0">
            <a href="{{ route('admin.ads.show', $ad) }}"
               class="text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1"
               style="background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.60)">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Ver
            </a>
            @if($actions === 'pending')
                <form method="POST" action="{{ route('admin.ads.approve', $ad) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-full bg-primary text-black">Aprobar</button>
                </form>
                <button @click="rejectOpen = !rejectOpen" class="text-xs font-bold px-3 py-1.5 rounded-full" style="background:rgba(248,113,113,0.15);color:#f87171">Rechazar</button>
            @elseif($actions === 'active')
                <form method="POST" action="{{ route('admin.ads.pause', $ad) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-full" style="background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.60)">Pausar</button>
                </form>
            @elseif($actions === 'paused')
                <form method="POST" action="{{ route('admin.ads.pause', $ad) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-full bg-primary text-black">Reactivar</button>
                </form>
            @elseif($actions === 'rejected')
                <form method="POST" action="{{ route('admin.ads.approve', $ad) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-full bg-primary text-black">Aprobar</button>
                </form>
            @endif
            <form method="POST" action="{{ route('admin.ads.destroy', $ad) }}" onsubmit="return confirm('¿Eliminar este anuncio?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full" style="background:rgba(248,113,113,0.10)">
                    <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Reject form --}}
    <div x-show="rejectOpen" x-transition x-cloak class="mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,0.06)">
        <form method="POST" action="{{ route('admin.ads.reject', $ad) }}" class="flex gap-2">
            @csrf @method('PATCH')
            <input type="text" name="reason" placeholder="Motivo del rechazo (opcional)"
                   class="input-field flex-1 py-2 text-xs">
            <button type="submit" class="text-xs font-bold px-4 py-2 rounded-xl flex-shrink-0" style="background:rgba(248,113,113,0.15);color:#f87171">Confirmar</button>
            <button type="button" @click="rejectOpen = false" class="text-xs font-bold px-4 py-2 rounded-xl flex-shrink-0" style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.50)">Cancelar</button>
        </form>
    </div>
</div>
