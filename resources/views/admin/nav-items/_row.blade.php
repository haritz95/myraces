<div x-data="{ editing: false }" class="border-b last:border-b-0" style="border-color:rgba(255,255,255,0.05)">

    {{-- ── Main row ──────────────────────────────────────────── --}}
    <div class="flex items-center gap-2 px-4 py-3">

        {{-- Reorder --}}
        <div class="flex flex-col gap-0.5 flex-shrink-0">
            <form method="POST" action="{{ route('admin.nav-items.move', $item) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="direction" value="up">
                <button type="submit" class="w-6 h-6 flex items-center justify-center rounded-md transition-colors hover:bg-white/[0.08]" style="color:rgba(255,255,255,0.30)">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                </button>
            </form>
            <form method="POST" action="{{ route('admin.nav-items.move', $item) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="direction" value="down">
                <button type="submit" class="w-6 h-6 flex items-center justify-center rounded-md transition-colors hover:bg-white/[0.08]" style="color:rgba(255,255,255,0.30)">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
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
            <p class="text-sm font-bold leading-tight {{ $item->is_enabled ? 'text-white' : '' }}" style="{{ !$item->is_enabled ? 'color:rgba(255,255,255,0.35)' : '' }}">
                {{ $item->label }}
            </p>
            <p class="text-[10px] truncate mt-0.5" style="color:rgba(255,255,255,0.25)">{{ $item->route_name }}</p>
        </div>

        {{-- Surface badges: Desktop + Mobile --}}
        <div class="flex items-center gap-1 flex-shrink-0">

            {{-- Desktop toggle --}}
            <form method="POST" action="{{ route('admin.nav-items.desktop', $item) }}" title="{{ $item->show_desktop ? 'Visible en escritorio' : 'Oculto en escritorio' }}">
                @csrf @method('PATCH')
                <button type="submit"
                        class="flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-black transition-colors"
                        style="{{ $item->show_desktop ? 'background:rgba(96,165,250,0.15);color:#60a5fa' : 'background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.20)' }}">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    PC
                </button>
            </form>

            {{-- Mobile location selector --}}
            <form method="POST" action="{{ route('admin.nav-items.location', $item) }}" class="flex items-center">
                @csrf @method('PATCH')
                <div class="flex rounded-lg overflow-hidden" style="border:1px solid rgba(255,255,255,0.08)">
                    {{-- Off --}}
                    <button type="submit" name="location" value="none" title="No mostrar en móvil"
                            class="px-2 py-1 text-[10px] font-black transition-colors"
                            style="{{ $item->location === null ? 'background:rgba(255,255,255,0.10);color:rgba(255,255,255,0.60)' : 'color:rgba(255,255,255,0.20)' }}">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </button>
                    {{-- Barra inferior --}}
                    <button type="submit" name="location" value="bottom_nav" title="Menú inferior"
                            class="px-2 py-1 text-[10px] font-black transition-colors border-l"
                            style="border-color:rgba(255,255,255,0.08);{{ $item->location === 'bottom_nav' ? 'background:rgba(200,250,95,0.15);color:#C8FA5F' : 'color:rgba(255,255,255,0.20)' }}">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </button>
                    {{-- Drawer --}}
                    <button type="submit" name="location" value="drawer" title="Drawer (Más)"
                            class="px-2 py-1 text-[10px] font-black transition-colors border-l"
                            style="border-color:rgba(255,255,255,0.08);{{ $item->location === 'drawer' ? 'background:rgba(200,250,95,0.15);color:#C8FA5F' : 'color:rgba(255,255,255,0.20)' }}">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                    </button>
                </div>
            </form>

        </div>

        {{-- Right actions --}}
        <div class="flex items-center gap-1 flex-shrink-0 ml-1">

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
                    <div class="grid grid-cols-8 gap-1.5">
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

                {{-- Where to show --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-xl p-3 space-y-2" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07)">
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" style="color:#60a5fa" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-[11px] font-bold text-white">Escritorio</span>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="show_desktop" value="1" {{ $item->show_desktop ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-primary">
                            <span class="text-xs" style="color:rgba(255,255,255,0.55)">Sidebar</span>
                        </label>
                    </div>
                    <div class="rounded-xl p-3 space-y-2" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07)">
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-[11px] font-bold text-white">Móvil</span>
                        </div>
                        <select name="location" class="input-field text-xs py-1.5">
                            <option value="" {{ $item->location === null ? 'selected' : '' }}>Sin menú móvil</option>
                            <option value="bottom_nav" {{ $item->location === 'bottom_nav' ? 'selected' : '' }}>Barra inferior</option>
                            <option value="drawer" {{ $item->location === 'drawer' ? 'selected' : '' }}>Drawer (Más)</option>
                        </select>
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
