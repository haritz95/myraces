<x-app-layout>
    @section('page_title', 'Menú móvil')
    @section('back_url', route('admin.dashboard'))

    <div class="max-w-2xl mx-auto px-4 py-6 space-y-6">

        @if(session('success'))
            <div class="card px-4 py-3 text-sm font-semibold text-green-400 border-green-500/20 bg-green-500/10">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="card px-4 py-3 text-sm font-semibold text-red-400 border-red-500/20 bg-red-500/10">
                {{ session('error') }}
            </div>
        @endif

        {{-- Info --}}
        <div class="card px-5 py-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm" style="color:rgba(255,255,255,0.55)">
                El <strong class="text-white">menú inferior</strong> admite hasta 4 elementos (el FAB de añadir y el botón "Más" se muestran siempre). El <strong class="text-white">drawer</strong> puede tener elementos ilimitados.
            </p>
        </div>

        {{-- Bottom nav --}}
        <section>
            <h2 class="text-sm font-black uppercase tracking-widest text-primary mb-3">Menú inferior</h2>
            <div class="card overflow-hidden">
                @forelse($items->get('bottom_nav', collect()) as $item)
                    <div class="flex items-center gap-3 px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        {{-- Icon preview --}}
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ $item->is_enabled ? 'bg-primary/10' : 'bg-white/[0.05]' }}">
                            <svg class="w-5 h-5 {{ $item->is_enabled ? 'text-primary' : '' }}" style="{{ !$item->is_enabled ? 'color:rgba(255,255,255,0.25)' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $item->icon_path }}"/>
                            </svg>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-black text-white">{{ $item->label }}</span>
                                @if($item->is_premium)
                                    <span class="text-[10px] font-black px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-400">PREMIUM</span>
                                @endif
                                @if(!$item->is_enabled)
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/10 text-white/40">DESACTIVADO</span>
                                @endif
                            </div>
                            <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">{{ $item->route_name }}</p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            {{-- Reorder --}}
                            <form method="POST" action="{{ route('admin.nav-items.move', $item) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="direction" value="up">
                                <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-white/[0.08] transition-colors" style="color:rgba(255,255,255,0.35)">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.nav-items.move', $item) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="direction" value="down">
                                <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-white/[0.08] transition-colors" style="color:rgba(255,255,255,0.35)">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </form>

                            {{-- Move to drawer --}}
                            <form method="POST" action="{{ route('admin.nav-items.location', $item) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="location" value="drawer">
                                <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-white/[0.08] transition-colors" style="color:rgba(255,255,255,0.35)" title="Mover al drawer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19h16"/></svg>
                                </button>
                            </form>

                            {{-- Toggle enable --}}
                            <form method="POST" action="{{ route('admin.nav-items.toggle', $item) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl transition-colors {{ $item->is_enabled ? 'bg-primary/20 text-primary' : 'bg-white/[0.06] text-white/30' }}">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($item->is_enabled)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                        @endif
                                    </svg>
                                </button>
                            </form>

                            {{-- Toggle premium --}}
                            <form method="POST" action="{{ route('admin.nav-items.premium', $item) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl transition-colors {{ $item->is_premium ? 'bg-amber-500/20 text-amber-400' : 'bg-white/[0.06] text-white/20' }}" title="Toggle premium">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-6 text-center text-sm" style="color:rgba(255,255,255,0.35)">Sin elementos en el menú inferior</div>
                @endforelse
            </div>
        </section>

        {{-- Drawer --}}
        <section>
            <h2 class="text-sm font-black uppercase tracking-widest text-primary mb-3">Drawer (Más)</h2>
            <div class="card overflow-hidden">
                @forelse($items->get('drawer', collect()) as $item)
                    <div class="flex items-center gap-3 px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        {{-- Icon preview --}}
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ $item->is_enabled ? 'bg-primary/10' : 'bg-white/[0.05]' }}">
                            <svg class="w-5 h-5 {{ $item->is_enabled ? 'text-primary' : '' }}" style="{{ !$item->is_enabled ? 'color:rgba(255,255,255,0.25)' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $item->icon_path }}"/>
                            </svg>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-black text-white">{{ $item->label }}</span>
                                @if($item->is_premium)
                                    <span class="text-[10px] font-black px-2 py-0.5 rounded-full bg-amber-500/20 text-amber-400">PREMIUM</span>
                                @endif
                                @if(!$item->is_enabled)
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/10 text-white/40">DESACTIVADO</span>
                                @endif
                            </div>
                            <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">{{ $item->route_name }}</p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <form method="POST" action="{{ route('admin.nav-items.move', $item) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="direction" value="up">
                                <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-white/[0.08] transition-colors" style="color:rgba(255,255,255,0.35)">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.nav-items.move', $item) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="direction" value="down">
                                <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-white/[0.08] transition-colors" style="color:rgba(255,255,255,0.35)">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                            </form>

                            {{-- Move to bottom nav --}}
                            <form method="POST" action="{{ route('admin.nav-items.location', $item) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="location" value="bottom_nav">
                                <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-white/[0.08] transition-colors" style="color:rgba(255,255,255,0.35)" title="Mover al menú inferior">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h16"/></svg>
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.nav-items.toggle', $item) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl transition-colors {{ $item->is_enabled ? 'bg-primary/20 text-primary' : 'bg-white/[0.06] text-white/30' }}">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if($item->is_enabled)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                        @endif
                                    </svg>
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.nav-items.premium', $item) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl transition-colors {{ $item->is_premium ? 'bg-amber-500/20 text-amber-400' : 'bg-white/[0.06] text-white/20' }}" title="Toggle premium">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-6 text-center text-sm" style="color:rgba(255,255,255,0.35)">Sin elementos en el drawer</div>
                @endforelse
            </div>
        </section>

    </div>
</x-app-layout>
