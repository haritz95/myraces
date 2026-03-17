<x-app-layout>
    @section('page_title', __('stats.title'))

    <main class="px-6 py-8 max-w-2xl mx-auto w-full">

        {{-- ── PERSONAL RECORDS ──────────────────────────────── --}}
        <section class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold">{{ __('stats.personal_records') }}</h2>
                <span class="text-xs font-bold text-primary uppercase tracking-wider">Records</span>
            </div>

            @if(empty($personalRecords))
                <div class="bg-white rounded-xl border border-slate-100 shadow-card px-5 py-10 text-center">
                    <div class="w-14 h-14 rounded-lg bg-primary/10 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <p class="text-slate-400 text-sm font-medium">Completa carreras para ver tus récords personales</p>
                </div>
            @else
                <div class="grid grid-cols-2 gap-3">
                    @foreach([5 => '5K', 10 => '10K', 21.097 => 'Media', 42.195 => 'Maratón'] as $dist => $label)
                        @if(isset($personalRecords[$dist]))
                            @php $rec = $personalRecords[$dist]; @endphp
                            <a href="{{ route('races.show', $rec) }}" class="bg-white rounded-xl border border-slate-100 shadow-card hover:shadow-card-up hover:border-slate-200 transition-all overflow-hidden">
                                <div class="h-1 bg-primary w-full"></div>
                                <div class="px-4 py-4">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">{{ $label }}</p>
                                    <p class="text-3xl font-bold text-slate-900 tabnum leading-none">{{ $rec->formatted_time }}</p>
                                    @if($rec->pace)
                                        <p class="text-xs font-bold text-primary mt-1.5 tabnum">{{ $rec->pace }}/km</p>
                                    @endif
                                    <div class="mt-3 pt-3 border-t border-slate-50">
                                        <p class="text-xs font-semibold text-slate-700 truncate">{{ $rec->name }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $rec->date->translatedFormat('M Y') }}</p>
                                    </div>
                                </div>
                            </a>
                        @else
                            <div class="bg-white rounded-xl border-2 border-dashed border-slate-100 shadow-none flex flex-col items-center justify-center text-center py-8 min-h-[140px]">
                                <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">{{ $label }}</p>
                                <p class="text-4xl font-bold text-slate-200 mt-2 leading-none">—</p>
                                <p class="text-xs text-slate-300 mt-2">Sin registro</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </section>

        {{-- ── BY MODALITY ───────────────────────────────────── --}}
        @if($byModality->isNotEmpty())
            <section class="mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold">{{ __('stats.by_modality') }}</h2>
                </div>
                <div class="bg-white rounded-xl border border-slate-100 shadow-card divide-y divide-slate-50">
                    @foreach($byModality as $modality => $data)
                        <div class="flex items-center gap-4 px-5 py-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-900">{{ __('races.modalities.' . $modality) }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="chip">{{ $data['count'] }} {{ $data['count'] === 1 ? 'carrera' : 'carreras' }}</span>
                                    <span class="chip">{{ number_format($data['total_km'], 0) }} km</span>
                                </div>
                            </div>
                            @if($data['best_time'])
                                <div class="text-right flex-shrink-0">
                                    <p class="text-base font-bold text-slate-900 tabnum">{{ $data['best_time']->formatted_time }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $data['best_time']->formatted_distance }} km</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ── YEARLY SUMMARY ────────────────────────────────── --}}
        @if($yearlyStats->isNotEmpty())
            <section class="mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold">{{ __('stats.yearly_summary') }}</h2>
                </div>
                <div class="bg-white rounded-xl border border-slate-100 shadow-card overflow-hidden">
                    <div class="grid grid-cols-4 px-5 py-3 bg-slate-50 border-b border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Año</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Carreras</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Km</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Gasto</span>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @foreach($yearlyStats as $stat)
                            <div class="grid grid-cols-4 px-5 py-4">
                                <span class="text-sm font-bold text-slate-900">{{ $stat->year }}</span>
                                <span class="text-sm font-semibold text-slate-600 text-center tabnum">{{ $stat->count }}</span>
                                <span class="text-sm font-semibold text-slate-600 text-center tabnum">{{ number_format($stat->total_km, 0) }}</span>
                                <span class="text-sm font-semibold text-slate-600 text-right tabnum">{{ $stat->total_spent ? number_format($stat->total_spent, 0) . '€' : '—' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- ── TOTAL SPENT ────────────────────────────────────── --}}
        <section class="mb-20">
            <div class="relative overflow-hidden rounded-xl px-6 py-7 flex items-center justify-between"
                 style="background: linear-gradient(135deg, #221610 0%, #7c2d12 50%, #ec5b13 100%); box-shadow: 0 8px 24px rgba(236,91,19,0.35)">
                <div class="absolute inset-0 opacity-[0.08]" style="background-image: radial-gradient(circle at 80% 20%, white 1px, transparent 1px); background-size: 30px 30px"></div>
                <div class="relative">
                    <p class="text-[10px] font-bold uppercase tracking-widest mb-2" style="color:rgba(255,255,255,0.40)">{{ __('stats.total_spent') }}</p>
                    <p class="text-5xl font-bold text-white tabnum leading-none">
                        {{ number_format((float) $totalSpent, 0) }}<span class="text-2xl ml-1" style="color:rgba(255,255,255,0.40)">€</span>
                    </p>
                    <p class="text-xs font-medium mt-2" style="color:rgba(255,255,255,0.35)">en inscripciones totales</p>
                </div>
                <div class="w-14 h-14 rounded-lg flex items-center justify-center flex-shrink-0 relative" style="background:rgba(255,255,255,0.10)">
                    <svg class="w-7 h-7" style="color:rgba(255,255,255,0.25)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </section>

    </main>
</x-app-layout>
