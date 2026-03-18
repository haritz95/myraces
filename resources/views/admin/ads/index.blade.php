<x-app-layout>
    @section('page_title', 'Anuncios')
    @section('back_url', route('admin.dashboard'))

    <div class="max-w-4xl mx-auto px-4 py-6 space-y-6">

        {{-- Stats --}}
        @php
            $allAds = $ads->flatten();
            $totalImpressions = $allAds->sum('impressions_count');
            $totalClicks = $allAds->sum('clicks_count');
            $pendingCount = ($ads['pending'] ?? collect())->count();
        @endphp
        <div class="grid grid-cols-3 gap-3">
            @foreach([[$allAds->count(), 'Total'], [$pendingCount, 'Pendientes'], [$totalImpressions, 'Impresiones']] as [$val, $label])
                <div class="card px-4 py-4 text-center">
                    <p class="text-2xl font-black text-primary tabnum leading-none">{{ number_format($val) }}</p>
                    <p class="text-[10px] font-bold uppercase tracking-wider mt-2" style="color:rgba(255,255,255,0.30)">{{ $label }}</p>
                </div>
            @endforeach
        </div>

        {{-- Pending --}}
        @if(($ads['pending'] ?? collect())->isNotEmpty())
        <div>
            <p class="section-label">Pendientes de revisión ({{ ($ads['pending'])->count() }})</p>
            <div class="card overflow-hidden">
                @foreach($ads['pending'] as $ad)
                    <div class="px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        @include('admin.ads._row', ['ad' => $ad, 'actions' => 'pending'])
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Active --}}
        @if(($ads['approved'] ?? collect())->isNotEmpty())
        <div>
            <p class="section-label">Activos ({{ ($ads['approved'])->count() }})</p>
            <div class="card overflow-hidden">
                @foreach($ads['approved'] as $ad)
                    <div class="px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        @include('admin.ads._row', ['ad' => $ad, 'actions' => 'active'])
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Paused --}}
        @if(($ads['paused'] ?? collect())->isNotEmpty())
        <div>
            <p class="section-label">Pausados</p>
            <div class="card overflow-hidden">
                @foreach($ads['paused'] as $ad)
                    <div class="px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        @include('admin.ads._row', ['ad' => $ad, 'actions' => 'paused'])
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Rejected --}}
        @if(($ads['rejected'] ?? collect())->isNotEmpty())
        <div>
            <p class="section-label">Rechazados</p>
            <div class="card overflow-hidden">
                @foreach($ads['rejected'] as $ad)
                    <div class="px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        @include('admin.ads._row', ['ad' => $ad, 'actions' => 'rejected'])
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($allAds->isEmpty())
            <div class="card px-6 py-12 text-center">
                <p class="text-sm font-medium" style="color:rgba(255,255,255,0.35)">No hay anuncios aún.</p>
            </div>
        @endif

    </div>
</x-app-layout>
