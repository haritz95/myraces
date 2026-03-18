@props(['ad'])

@if($ad)
<a href="{{ route('ad.click', $ad) }}"
   target="_blank" rel="noopener sponsored"
   class="block rounded-2xl overflow-hidden transition-all duration-200 hover:opacity-90 active:scale-[0.99]"
   style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)">

    @if($ad->imageUrl())
        <div class="relative">
            <img src="{{ $ad->imageUrl() }}" alt="{{ $ad->title }}"
                 class="w-full object-cover" style="max-height:160px">
            <div class="ad-img-overlay absolute inset-0" style="background:linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 60%)"></div>
        </div>
    @endif

    <div class="px-4 py-3.5 flex items-center gap-3">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-[9px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded"
                      style="background:rgba(255,255,255,0.10);color:rgba(255,255,255,0.40);letter-spacing:0.12em">
                    Anuncio
                </span>
                <span class="text-[10px]" style="color:rgba(255,255,255,0.30)">{{ $ad->typeLabel() }}</span>
            </div>
            <p class="text-sm font-bold text-white leading-snug truncate">{{ $ad->title }}</p>
            @if($ad->subtitle)
                <p class="text-xs mt-0.5 truncate" style="color:rgba(255,255,255,0.45)">{{ $ad->subtitle }}</p>
            @endif
        </div>
        <span class="text-xs font-black flex-shrink-0 px-3.5 py-2 rounded-full bg-primary text-black whitespace-nowrap">
            {{ $ad->cta_label }}
        </span>
    </div>
</a>
@endif
