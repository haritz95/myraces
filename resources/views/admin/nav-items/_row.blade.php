<div x-data="{ editing: false }" class="border-b last:border-b-0" style="border-color:rgba(255,255,255,0.05)">

    {{-- ── Main row ──────────────────────────────────────────── --}}
    <div class="flex items-center gap-2 px-4 py-3">

        {{-- Reorder: stacked ▲▼ --}}
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
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                    @else
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

            {{-- Edit toggle --}}
            <button @click="editing = !editing"
                    class="w-7 h-7 flex items-center justify-center rounded-lg transition-colors"
                    :class="editing ? 'bg-primary/20 text-primary' : 'hover:bg-white/[0.08]'"
                    :style="editing ? '' : 'color:rgba(255,255,255,0.25)'"
                    title="Editar">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </button>

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

    {{-- ── Inline edit form ──────────────────────────────────── --}}
    <div x-show="editing"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-cloak
         class="px-4 pb-4"
         x-data="{
             selectedIcon: '{{ addslashes($item->icon_path) }}',
             icons: {{ Js::from($iconOptions) }}
         }">

        <div class="rounded-xl p-4 space-y-3" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07)">
            <p class="text-xs font-bold text-primary uppercase tracking-wider mb-1">Editar elemento</p>

            <form method="POST" action="{{ route('admin.nav-items.update', $item) }}" class="space-y-3">
                @csrf @method('PATCH')
                <input type="hidden" name="icon_path" :value="selectedIcon">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-label block mb-1">Etiqueta</label>
                        <input type="text" name="label" value="{{ $item->label }}" required maxlength="30"
                               class="input-field text-sm py-2">
                    </div>
                    <div>
                        <label class="section-label block mb-1">Ruta (route name)</label>
                        <input type="text" name="route_name" value="{{ $item->route_name }}" required maxlength="100"
                               class="input-field text-sm py-2">
                    </div>
                </div>

                <div>
                    <label class="section-label block mb-1">Patrón de coincidencia</label>
                    <input type="text" name="match_pattern" value="{{ $item->match_pattern }}" required maxlength="200"
                           class="input-field text-sm py-2"
                           placeholder="races.*|races.index">
                    <p class="text-[11px] mt-1" style="color:rgba(255,255,255,0.25)">Separa múltiples patrones con |</p>
                </div>

                {{-- Icon picker --}}
                <div>
                    <label class="section-label block mb-2">Icono</label>
                    <div class="grid grid-cols-7 gap-1.5">
                        <template x-for="icon in icons" :key="icon.name">
                            <button type="button"
                                    @click="selectedIcon = icon.path"
                                    :title="icon.name"
                                    class="w-9 h-9 rounded-xl flex items-center justify-center transition-all"
                                    :class="selectedIcon === icon.path
                                        ? 'bg-primary/20 ring-1 ring-primary/50'
                                        : 'hover:bg-white/[0.08]'"
                                    :style="selectedIcon === icon.path ? 'color:#C8FA5F' : 'color:rgba(255,255,255,0.35)'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" :d="icon.path"/>
                                </svg>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-1">
                    <button type="button" @click="editing = false"
                            class="btn btn-ghost text-xs px-3 py-1.5">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary text-xs px-4 py-1.5">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
