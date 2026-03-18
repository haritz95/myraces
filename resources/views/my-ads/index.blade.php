<x-app-layout>
    @section('page_title', 'Mis anuncios')
    @section('back_url', route('dashboard'))

    @section('header_action')
        <a href="{{ route('my-ads.create') }}" class="btn btn-primary py-2 text-xs">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Nuevo anuncio
        </a>
    @endsection

    <div class="max-w-2xl mx-auto px-4 py-6 space-y-4">

        @if($ads->isEmpty())
            <div class="card px-6 py-12 text-center space-y-4">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto">
                    <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-white">Aún no tienes anuncios</p>
                    <p class="text-xs mt-1" style="color:rgba(255,255,255,0.40)">Promociona tu carrera o evento ante miles de corredores.</p>
                </div>
                <a href="{{ route('my-ads.create') }}" class="btn btn-primary mx-auto">Crear primer anuncio</a>
            </div>
        @else
            @foreach($ads as $ad)
                @php
                    $statusColors = [
                        'pending'  => ['rgba(251,191,36,0.15)', '#fbbf24'],
                        'approved' => ['rgba(200,250,95,0.12)', '#C8FA5F'],
                        'paused'   => ['rgba(255,255,255,0.08)', 'rgba(255,255,255,0.40)'],
                        'rejected' => ['rgba(248,113,113,0.12)', '#f87171'],
                    ];
                    [$bgColor, $textColor] = $statusColors[$ad->status] ?? ['rgba(255,255,255,0.06)', 'rgba(255,255,255,0.40)'];
                @endphp
                <div class="card overflow-hidden">
                    <div class="flex items-start gap-3 p-4">
                        @if($ad->imageUrl())
                            <img src="{{ $ad->imageUrl() }}" alt="" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                        @else
                            <div class="w-16 h-16 rounded-xl flex-shrink-0 flex items-center justify-center" style="background:rgba(255,255,255,0.06)">
                                <svg class="w-7 h-7" style="color:rgba(255,255,255,0.20)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-sm font-bold text-white">{{ $ad->title }}</p>
                                <span class="text-[10px] font-black px-2 py-0.5 rounded-full" style="background:{{ $bgColor }};color:{{ $textColor }}">
                                    {{ $ad->statusLabel() }}
                                </span>
                            </div>
                            @if($ad->subtitle)
                                <p class="text-xs mt-0.5 truncate" style="color:rgba(255,255,255,0.40)">{{ $ad->subtitle }}</p>
                            @endif
                            @if($ad->rejection_reason)
                                <p class="text-xs mt-1 text-red-400">Motivo: {{ $ad->rejection_reason }}</p>
                            @endif
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-[11px] tabnum" style="color:rgba(255,255,255,0.30)">{{ number_format($ad->impressions_count) }} imp</span>
                                <span class="text-[11px] tabnum" style="color:rgba(255,255,255,0.30)">{{ number_format($ad->clicks_count) }} clicks</span>
                                <span class="text-[11px] tabnum" style="color:rgba(255,255,255,0.30)">{{ $ad->ctr() }}% CTR</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between px-4 py-2.5" style="border-top:1px solid rgba(255,255,255,0.05)">
                        <span class="text-[10px]" style="color:rgba(255,255,255,0.25)">{{ $ad->created_at->format('d/m/Y') }}</span>
                        <form method="POST" action="{{ route('my-ads.destroy', $ad) }}"
                              onsubmit="return confirm('¿Eliminar este anuncio?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-[11px] font-bold text-red-400 hover:text-red-300 transition-colors">Eliminar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</x-app-layout>
