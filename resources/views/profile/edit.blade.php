<x-app-layout>
    @section('page_title', 'Mi Perfil')

    {{-- ── HERO ─────────────────────────────────────────────── --}}
    <div class="relative overflow-hidden" style="background: linear-gradient(135deg, #221610 0%, #7c2d12 50%, #ec5b13 100%); padding: 32px 24px 36px">
        <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(circle at 20% 80%, white 1px, transparent 1px); background-size: 30px 30px"></div>

        <div class="flex items-center gap-4 relative">
            <div class="w-16 h-16 rounded-xl flex items-center justify-center text-white font-bold text-2xl flex-shrink-0 bg-primary"
                 style="box-shadow: 0 8px 24px rgba(236,91,19,0.45)">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <h2 class="text-white font-bold text-lg leading-tight truncate">{{ $user->name }}</h2>
                <p class="text-sm mt-0.5 truncate" style="color:rgba(255,255,255,0.40)">{{ $user->email }}</p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 mt-5 relative">
            <div class="rounded-lg px-3 py-4 text-center" style="background:rgba(255,255,255,0.12)">
                <p class="text-xl font-bold text-white leading-none tabnum">{{ $stats['races'] }}</p>
                <p class="text-[9px] font-bold uppercase tracking-wider mt-1.5" style="color:rgba(255,255,255,0.35)">Carreras</p>
            </div>
            <div class="rounded-lg px-3 py-4 text-center" style="background:rgba(255,255,255,0.12)">
                <p class="text-xl font-bold text-white leading-none tabnum">{{ number_format($stats['km']) }}</p>
                <p class="text-[9px] font-bold uppercase tracking-wider mt-1.5" style="color:rgba(255,255,255,0.35)">Km</p>
            </div>
            <div class="rounded-lg px-3 py-4 text-center" style="background:rgba(255,255,255,0.12)">
                <p class="text-xl font-bold text-white leading-none tabnum">{{ $user->created_at->format('Y') }}</p>
                <p class="text-[9px] font-bold uppercase tracking-wider mt-1.5" style="color:rgba(255,255,255,0.35)">Desde</p>
            </div>
        </div>
    </div>

    {{-- ── SETTINGS BODY ─────────────────────────────────────── --}}
    <div class="max-w-lg mx-auto px-4 py-6 space-y-5">

        {{-- Personal info --}}
        <div>
            <p class="section-label">Información personal</p>
            <div class="settings-group">

                {{-- Name --}}
                <div x-data="{ open: {{ $errors->has('name') ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open" class="settings-row w-full">
                        <div class="settings-icon" style="background: #fff4ed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="settings-row-label">Nombre</span>
                        <span class="settings-row-value" x-show="!open">{{ old('name', $user->name) }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-300 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-cloak>
                        <form method="POST" action="{{ route('profile.update') }}" class="px-5 pb-4 pt-2 bg-slate-50 border-t border-slate-100">
                            @csrf
                            @method('patch')
                            <input type="hidden" name="_update_field" value="name">
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                   required autofocus autocomplete="name"
                                   class="input-field @error('name') error @enderror">
                            @error('name') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            <div class="flex gap-2 mt-3">
                                <button type="button" @click="open = false" class="btn btn-secondary flex-1 py-2 text-xs">Cancelar</button>
                                <button type="submit" class="btn btn-primary flex-1 py-2 text-xs">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Email --}}
                <div x-data="{ open: {{ $errors->has('email') ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open" class="settings-row w-full">
                        <div class="settings-icon" style="background: #fff4ed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="settings-row-label">Email</span>
                        <span class="settings-row-value" x-show="!open">{{ old('email', $user->email) }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-300 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-cloak>
                        <form method="POST" action="{{ route('profile.update') }}" class="px-5 pb-4 pt-2 bg-slate-50 border-t border-slate-100">
                            @csrf
                            @method('patch')
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                   required autocomplete="username"
                                   class="input-field @error('email') error @enderror">
                            @error('email') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            <div class="flex gap-2 mt-3">
                                <button type="button" @click="open = false" class="btn btn-secondary flex-1 py-2 text-xs">Cancelar</button>
                                <button type="submit" class="btn btn-primary flex-1 py-2 text-xs">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            @if(session('status') === 'profile-updated')
                <p class="text-xs text-emerald-600 mt-2 px-1 flex items-center gap-1.5 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Perfil actualizado correctamente.
                </p>
            @endif
        </div>

        {{-- Security --}}
        <div>
            <p class="section-label">Seguridad</p>
            <div class="settings-group">
                <div x-data="{ open: {{ $errors->updatePassword->isNotEmpty() ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open" class="settings-row w-full">
                        <div class="settings-icon" style="background: #fff4ed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <span class="settings-row-label">Contraseña</span>
                        <span class="settings-row-value" x-show="!open">••••••••</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-300 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-cloak>
                        <form method="POST" action="{{ route('password.update') }}" class="px-5 pb-4 pt-3 bg-slate-50 border-t border-slate-100 space-y-3">
                            @csrf
                            @method('put')
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Contraseña actual</label>
                                <input id="current_password" type="password" name="current_password" autocomplete="current-password"
                                       class="input-field @error('current_password', 'updatePassword') error @enderror">
                                @error('current_password', 'updatePassword') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Nueva contraseña</label>
                                <input id="update_password" type="password" name="password" autocomplete="new-password"
                                       class="input-field @error('password', 'updatePassword') error @enderror">
                                @error('password', 'updatePassword') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Confirmar contraseña</label>
                                <input id="update_password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"
                                       class="input-field @error('password_confirmation', 'updatePassword') error @enderror">
                                @error('password_confirmation', 'updatePassword') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex gap-2 pt-1">
                                <button type="button" @click="open = false" class="btn btn-secondary flex-1 py-2 text-xs">Cancelar</button>
                                <button type="submit" class="btn btn-primary flex-1 py-2 text-xs">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @if(session('status') === 'password-updated')
                <p class="text-xs text-emerald-600 mt-2 px-1 flex items-center gap-1.5 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Contraseña actualizada.
                </p>
            @endif
        </div>

        {{-- Preferences --}}
        <div>
            <p class="section-label">Preferencias</p>
            <div class="settings-group">
                <div class="settings-row">
                    <div class="settings-icon" style="background: #fff4ed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                    </div>
                    <span class="settings-row-label">Idioma</span>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('language.switch', 'es') }}"
                           class="text-xs font-bold px-3 py-1.5 rounded-full transition-colors
                                  {{ app()->getLocale() === 'es' ? 'bg-primary text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                            ES
                        </a>
                        <a href="{{ route('language.switch', 'en') }}"
                           class="text-xs font-bold px-3 py-1.5 rounded-full transition-colors
                                  {{ app()->getLocale() === 'en' ? 'bg-primary text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                            EN
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Account --}}
        <div>
            <p class="section-label">Cuenta</p>
            <div class="settings-group">

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="settings-row w-full">
                        <div class="settings-icon bg-slate-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <span class="settings-row-label">Cerrar sesión</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </form>

                {{-- Delete account --}}
                <div x-data="{ open: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open" class="settings-row w-full">
                        <div class="settings-icon bg-rose-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </div>
                        <span class="settings-row-label text-rose-500">Eliminar cuenta</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-300 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-cloak>
                        <form method="POST" action="{{ route('profile.destroy') }}"
                              class="px-5 pb-4 pt-3 bg-rose-50/50 border-t border-rose-100 space-y-3" autocomplete="off">
                            @csrf
                            @method('delete')
                            <p class="text-xs text-rose-500 font-medium">Esta acción es irreversible. Se borrarán todas tus carreras y datos.</p>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Confirma con tu contraseña</label>
                                <input id="delete_password" type="password" name="password" autocomplete="off"
                                       placeholder="Contraseña"
                                       class="input-field @error('password', 'userDeletion') error @enderror">
                                @error('password', 'userDeletion') <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex gap-2">
                                <button type="button" @click="open = false" class="btn btn-secondary flex-1 py-2 text-xs">Cancelar</button>
                                <button type="submit" class="btn btn-danger flex-1 py-2 text-xs">Eliminar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
