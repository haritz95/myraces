<div class="flex items-center gap-2 px-4 py-3">

    {{-- Reorder: stacked ▲▼ on the far left --}}
    <div class="flex flex-col gap-0.5 flex-shrink-0">
        <form method="POST" action="{{ route('admin.nav-items.move', $item) }}">
            @csrf @method('PATCH')
            <input type="hidden" name="direction" value="up">
            <button type="submit"
                    class="w-6 h-6 flex items-center justify-center rounded-md transition-colors hover:bg-white/[0.08]"
                    style="color:rgba(255,255,255,0.30)">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                </svg>
            </button>
        </form>
        <form method="POST" action="{{ route('admin.nav-items.move', $item) }}">
            @csrf @method('PATCH')
            <input type="hidden" name="direction" value="down">
            <button type="submit"
                    class="w-6 h-6 flex items-center justify-center rounded-md transition-colors hover:bg-white/[0.08]"
                    style="color:rgba(255,255,255,0.30)">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </form>
    </div>

    {{-- Icon preview --}}
    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0
                {{ $item->is_enabled ? 'bg-primary/10' : 'bg-white/[0.04]' }}">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
             style="{{ $item->is_enabled ? 'color:#C8FA5F' : 'color:rgba(255,255,255,0.20)' }}">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $item->icon_path }}"/>
        </svg>
    </div>

    {{-- Label + meta --}}
    <div class="flex-1 min-w-0">
        <p class="text-sm font-bold text-white leading-tight">{{ $item->label }}</p>
        <p class="text-[11px] truncate mt-0.5" style="color:rgba(255,255,255,0.30)">{{ $item->route_name }}</p>
    </div>

    {{-- Badges --}}
    <div class="flex flex-col items-end gap-1 flex-shrink-0">
        @if($item->is_premium)
            <span class="text-[9px] font-black px-1.5 py-0.5 rounded-full bg-amber-500/20 text-amber-400 leading-none">PRO</span>
        @endif
        @if(!$item->is_enabled)
            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-white/8 leading-none" style="color:rgba(255,255,255,0.35)">OFF</span>
        @endif
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-1 flex-shrink-0 ml-1">

        {{-- Move location --}}
        <form method="POST" action="{{ route('admin.nav-items.location', $item) }}" title="{{ $targetLocationLabel }}">
            @csrf @method('PATCH')
            <input type="hidden" name="location" value="{{ $targetLocation }}">
            <button type="submit"
                    class="w-7 h-7 flex items-center justify-center rounded-lg transition-colors hover:bg-white/[0.08]"
                    style="color:rgba(255,255,255,0.25)">
                @if($targetLocation === 'drawer')
                    {{-- Arrow down to drawer --}}
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                    </svg>
                @else
                    {{-- Arrow up to bottom nav --}}
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8M4 18h16"/>
                    </svg>
                @endif
            </button>
        </form>

        {{-- Enable/disable --}}
        <form method="POST" action="{{ route('admin.nav-items.toggle', $item) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="w-7 h-7 flex items-center justify-center rounded-lg transition-colors
                           {{ $item->is_enabled ? 'bg-primary/20 text-primary' : 'hover:bg-white/[0.08]' }}"
                    style="{{ !$item->is_enabled ? 'color:rgba(255,255,255,0.25)' : '' }}"
                    title="{{ $item->is_enabled ? 'Desactivar' : 'Activar' }}">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="{{ $item->is_enabled ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}"/>
                </svg>
            </button>
        </form>

        {{-- Premium --}}
        <form method="POST" action="{{ route('admin.nav-items.premium', $item) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="w-7 h-7 flex items-center justify-center rounded-lg transition-colors
                           {{ $item->is_premium ? 'bg-amber-500/20 text-amber-400' : 'hover:bg-white/[0.08]' }}"
                    style="{{ !$item->is_premium ? 'color:rgba(255,255,255,0.20)' : '' }}"
                    title="Toggle premium">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
            </button>
        </form>

        {{-- Delete --}}
        <form method="POST" action="{{ route('admin.nav-items.destroy', $item) }}"
              onsubmit="return confirm('¿Eliminar «{{ $item->label }}»?')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="w-7 h-7 flex items-center justify-center rounded-lg transition-colors hover:bg-red-500/15"
                    style="color:rgba(255,255,255,0.18)"
                    title="Eliminar">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </form>

    </div>
</div>
