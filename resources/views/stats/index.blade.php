<x-app-layout>
    @section('page_title', __('stats.title'))

    <main class="px-5 py-6 max-w-2xl mx-auto w-full space-y-8">

        {{-- ── PERSONAL RECORDS ──────────────────────────────── --}}
        <section>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-black text-white">{{ __('stats.personal_records') }}</h2>
                <a href="{{ route('personal-records.index') }}" class="text-primary text-xs font-black uppercase tracking-widest">Ver todo →</a>
            </div>

            @if(empty($personalRecords))
                <div class="card px-5 py-10 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium" style="color:rgba(255,255,255,0.40)">Completa carreras para ver tus récords personales</p>
                </div>
            @else
                <div class="grid grid-cols-2 gap-3">
                    @foreach([5 => '5K', 10 => '10K', 21.097 => 'Media', 42.195 => 'Maratón'] as $dist => $label)
                        @if(isset($personalRecords[$dist]))
                            @php $rec = $personalRecords[$dist]; @endphp
                            <a href="{{ route('races.show', $rec) }}" class="card-interactive overflow-hidden">
                                <div class="h-1 bg-primary w-full"></div>
                                <div class="px-4 py-4">
                                    <p class="section-label mb-2">{{ $label }}</p>
                                    <p class="text-3xl font-black text-primary tabnum leading-none">{{ $rec->formatted_time }}</p>
                                    @if($rec->pace)
                                        <p class="text-xs font-bold mt-1.5 tabnum" style="color:rgba(255,255,255,0.40)">{{ $rec->pace }}/km</p>
                                    @endif
                                    <div class="mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,0.06)">
                                        <p class="text-xs font-bold text-white truncate">{{ $rec->name }}</p>
                                        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">{{ $rec->date->translatedFormat('M Y') }}</p>
                                    </div>
                                </div>
                            </a>
                        @else
                            <div class="card flex flex-col items-center justify-center text-center py-8 min-h-[140px]" style="border-style:dashed;opacity:0.5">
                                <p class="section-label mb-2">{{ $label }}</p>
                                <p class="text-4xl font-black leading-none" style="color:rgba(255,255,255,0.15)">—</p>
                                <p class="text-xs mt-2" style="color:rgba(255,255,255,0.20)">Sin registro</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </section>

        {{-- ── BY MODALITY ───────────────────────────────────── --}}
        @if($byModality->isNotEmpty())
            <section>
                <h2 class="text-lg font-black text-white mb-4">{{ __('stats.by_modality') }}</h2>
                <div class="card overflow-hidden">
                    @foreach($byModality as $modality => $data)
                        <div class="flex items-center gap-4 px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-black text-white">{{ __('races.modalities.' . $modality) }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="chip">{{ $data['count'] }} {{ $data['count'] === 1 ? 'carrera' : 'carreras' }}</span>
                                    <span class="chip">{{ number_format($data['total_km'], 0) }} km</span>
                                </div>
                            </div>
                            @if($data['best_time'])
                                <div class="text-right flex-shrink-0">
                                    <p class="text-base font-black text-white tabnum">{{ $data['best_time']->formatted_time }}</p>
                                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">{{ $data['best_time']->formatted_distance }} km</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ── YEARLY SUMMARY ────────────────────────────────── --}}
        @if($yearlyStats->isNotEmpty())
            <section>
                <h2 class="text-lg font-black text-white mb-4">{{ __('stats.yearly_summary') }}</h2>
                <div class="card overflow-hidden">
                    <div class="grid grid-cols-4 px-5 py-3" style="background:rgba(255,255,255,0.04);border-bottom:1px solid rgba(255,255,255,0.06)">
                        <span class="section-label mb-0">Año</span>
                        <span class="section-label mb-0 text-center">Carreras</span>
                        <span class="section-label mb-0 text-center">Km</span>
                        <span class="section-label mb-0 text-right">Gasto</span>
                    </div>
                    @foreach($yearlyStats as $stat)
                        <div class="grid grid-cols-4 px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.04)">
                            <span class="text-sm font-black text-white">{{ $stat->year }}</span>
                            <span class="text-sm font-bold text-center tabnum" style="color:rgba(255,255,255,0.60)">{{ $stat->count }}</span>
                            <span class="text-sm font-bold text-center tabnum" style="color:rgba(255,255,255,0.60)">{{ number_format($stat->total_km, 0) }}</span>
                            <span class="text-sm font-bold text-right tabnum" style="color:rgba(255,255,255,0.60)">{{ $stat->total_spent ? number_format($stat->total_spent, 0) . '€' : '—' }}</span>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ── TOTAL SPENT ────────────────────────────────────── --}}
        <section>
            <div class="relative overflow-hidden rounded-3xl px-6 py-8"
                 style="background:linear-gradient(135deg,#0f1a00 0%,#1a2d00 50%,#253d00 100%);border:1px solid rgba(200,250,95,0.15)">
                <div class="absolute inset-0 opacity-[0.06]" style="background-image:radial-gradient(circle at 80% 20%, #C8FA5F 1px, transparent 1px);background-size:30px 30px"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <p class="section-label mb-3">{{ __('stats.total_spent') }}</p>
                        <p class="text-5xl font-black text-white tabnum leading-none">
                            {{ number_format((float) $totalSpent, 0) }}<span class="text-2xl ml-1" style="color:rgba(255,255,255,0.35)">€</span>
                        </p>
                        <p class="text-xs font-medium mt-2" style="color:rgba(255,255,255,0.30)">en inscripciones totales</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0" style="background:rgba(200,250,95,0.10);border:1px solid rgba(200,250,95,0.20)">
                        <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

    </main>
</x-app-layout>
