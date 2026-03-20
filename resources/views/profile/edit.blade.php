<x-app-layout>
    @section('page_title', 'Mi Perfil')

    {{-- ── HERO ─────────────────────────────────────────────── --}}
    <div class="relative overflow-hidden px-6 pt-8 pb-8"
         style="background:linear-gradient(135deg,#0f1a00 0%,#1a2d00 50%,#253d00 100%);border-bottom:1px solid rgba(255,255,255,0.06)">
        <div class="absolute inset-0 opacity-[0.05]" style="background-image:radial-gradient(circle at 20% 80%, #C8FA5F 1px, transparent 1px);background-size:30px 30px"></div>
        <div class="flex items-center gap-4 relative max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-2xl flex-shrink-0 overflow-hidden bg-primary flex items-center justify-center"
                 style="box-shadow:0 8px 24px rgba(200,250,95,0.35)">
                @if($user->profile?->avatar)
                    <img src="{{ asset('storage/' . $user->profile->avatar) }}" alt="" class="w-full h-full object-cover">
                @else
                    <span class="text-black font-black text-2xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                @endif
            </div>
            <div class="min-w-0">
                <h2 class="text-white font-black text-xl leading-tight truncate">{{ $user->name }}</h2>
                <p class="text-sm mt-0.5 truncate" style="color:rgba(255,255,255,0.40)">{{ $user->email }}</p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 mt-6 relative max-w-lg mx-auto">
            @foreach([[$stats['races'], 'Carreras'], [number_format($stats['km']), 'Km'], [$user->created_at->format('Y'), 'Desde']] as [$val, $label])
                <div class="rounded-2xl px-3 py-4 text-center" style="background:rgba(255,255,255,0.07)">
                    <p class="text-xl font-black text-white leading-none tabnum">{{ $val }}</p>
                    <p class="text-[10px] font-black uppercase tracking-wider mt-1.5" style="color:rgba(255,255,255,0.30)">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ── SETTINGS BODY ─────────────────────────────────────── --}}
    <div class="max-w-lg mx-auto px-5 py-6 space-y-6">

        {{-- Personal info --}}
        <div>
            <p class="section-label">Información personal</p>
            <div class="settings-group">

                <div x-data="{ open: {{ $errors->has('name') ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open" class="settings-row w-full">
                        <div class="settings-icon">
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="settings-row-label">Nombre</span>
                        <span class="settings-row-value" x-show="!open">{{ old('name', $user->name) }}</span>
                        <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-90' : ''" style="color:rgba(255,255,255,0.20)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition x-cloak>
                        <form method="POST" action="{{ route('profile.update') }}" class="px-5 pb-4 pt-3 space-y-3" style="background:rgba(255,255,255,0.03);border-top:1px solid rgba(255,255,255,0.06)">
                            @csrf @method('patch')
                            <input type="hidden" name="_update_field" value="name">
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                   required autofocus autocomplete="name"
                                   class="input-field @error('name') error @enderror">
                            @error('name') <p class="text-red-400 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            <div class="flex gap-2 mt-3">
                                <button type="button" @click="open = false" class="btn btn-secondary flex-1 py-2 text-xs">Cancelar</button>
                                <button type="submit" class="btn btn-primary flex-1 py-2 text-xs">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div x-data="{ open: {{ $errors->has('email') ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open" class="settings-row w-full">
                        <div class="settings-icon">
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="settings-row-label">Email</span>
                        <span class="settings-row-value" x-show="!open">{{ old('email', $user->email) }}</span>
                        <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-90' : ''" style="color:rgba(255,255,255,0.20)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition x-cloak>
                        <form method="POST" action="{{ route('profile.update') }}" class="px-5 pb-4 pt-3" style="background:rgba(255,255,255,0.03);border-top:1px solid rgba(255,255,255,0.06)">
                            @csrf @method('patch')
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                   required autocomplete="username"
                                   class="input-field @error('email') error @enderror">
                            @error('email') <p class="text-red-400 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            <div class="flex gap-2 mt-3">
                                <button type="button" @click="open = false" class="btn btn-secondary flex-1 py-2 text-xs">Cancelar</button>
                                <button type="submit" class="btn btn-primary flex-1 py-2 text-xs">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @if(session('status') === 'profile-updated')
                <p class="text-xs text-primary mt-2 px-1 flex items-center gap-1.5 font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Perfil actualizado correctamente.
                </p>
            @endif
        </div>

        {{-- Extended profile --}}
        @php $p = $user->profile; @endphp
        <div x-data="{ open: false }">
            <form method="POST" action="{{ route('profile.data') }}" enctype="multipart/form-data">
                @csrf

                {{-- Avatar + username + bio --}}
                <p class="section-label">Perfil público</p>
                <div class="settings-group">

                    {{-- Avatar --}}
                    <div class="settings-row">
                        <div class="settings-icon overflow-hidden">
                            @if($p?->avatar)
                                <img src="{{ asset('storage/' . $p->avatar) }}" alt="" class="w-full h-full object-cover rounded-xl">
                            @else
                                <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="settings-row-label">Foto de perfil</p>
                            @error('avatar') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <label class="text-xs font-bold text-primary cursor-pointer hover:opacity-80 transition-opacity">
                            Cambiar
                            <input type="file" name="avatar" accept="image/*" class="hidden"
                                   onchange="this.closest('form').querySelector('[data-avatar-name]').textContent = this.files[0]?.name ?? ''">
                        </label>
                    </div>
                    <p class="text-[10px] px-5 pb-2" style="color:rgba(255,255,255,0.25)" data-avatar-name></p>

                    {{-- Username --}}
                    <div class="px-5 py-3.5 space-y-1.5" style="border-top:1px solid rgba(255,255,255,0.05)">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.45)">Nombre de usuario</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold" style="color:rgba(255,255,255,0.30)">@</span>
                            <input type="text" name="username" value="{{ old('username', $p?->username) }}"
                                   maxlength="30" placeholder="tu_usuario"
                                   class="input-field pl-8 @error('username') error @enderror">
                        </div>
                        @error('username') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                    </div>

                    {{-- Bio --}}
                    <div class="px-5 py-3.5 space-y-1.5" style="border-top:1px solid rgba(255,255,255,0.05)">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.45)">Bio <span class="font-normal" style="color:rgba(255,255,255,0.25)">máx. 300 caracteres</span></label>
                        <textarea name="bio" rows="3" maxlength="300" placeholder="Cuéntanos algo sobre ti..."
                                  class="input-field resize-none @error('bio') error @enderror">{{ old('bio', $p?->bio) }}</textarea>
                        @error('bio') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                    </div>

                    {{-- Is public --}}
                    <label class="settings-row cursor-pointer" style="border-top:1px solid rgba(255,255,255,0.05)">
                        <div class="settings-icon">
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="settings-row-label">Perfil público</p>
                            <p class="text-xs" style="color:rgba(255,255,255,0.35)">Otros usuarios pueden ver tu perfil</p>
                        </div>
                        <input type="hidden" name="is_public" value="0">
                        <input type="checkbox" name="is_public" value="1" {{ old('is_public', $p?->is_public) ? 'checked' : '' }}
                               class="w-4 h-4 rounded accent-primary">
                    </label>
                </div>

                {{-- Location + physical stats --}}
                <p class="section-label mt-6">Datos personales</p>
                <div class="settings-group">

                    {{-- City + Country --}}
                    <div class="grid grid-cols-2 gap-3 px-5 py-3.5">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.45)">Ciudad</label>
                            <input type="text" name="city" value="{{ old('city', $p?->city) }}"
                                   maxlength="80" placeholder="Barcelona"
                                   class="input-field @error('city') error @enderror">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.45)">País</label>
                            <input type="text" name="country" value="{{ old('country', $p?->country) }}"
                                   maxlength="80" placeholder="España"
                                   class="input-field @error('country') error @enderror">
                        </div>
                    </div>

                    {{-- Birth date + Gender --}}
                    <div class="grid grid-cols-2 gap-3 px-5 py-3.5" style="border-top:1px solid rgba(255,255,255,0.05)">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.45)">Fecha de nacimiento</label>
                            <input type="date" name="birth_date"
                                   value="{{ old('birth_date', $p?->birth_date?->format('Y-m-d')) }}"
                                   class="input-field @error('birth_date') error @enderror">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.45)">Género</label>
                            <select name="gender" class="input-field @error('gender') error @enderror">
                                <option value="">—</option>
                                <option value="male"         {{ old('gender', $p?->gender) === 'male'         ? 'selected' : '' }}>Hombre</option>
                                <option value="female"       {{ old('gender', $p?->gender) === 'female'       ? 'selected' : '' }}>Mujer</option>
                                <option value="other"        {{ old('gender', $p?->gender) === 'other'        ? 'selected' : '' }}>Otro</option>
                                <option value="prefer_not"   {{ old('gender', $p?->gender) === 'prefer_not'   ? 'selected' : '' }}>Prefiero no indicar</option>
                            </select>
                        </div>
                    </div>

                    {{-- Height + Weight --}}
                    <div class="grid grid-cols-2 gap-3 px-5 py-3.5" style="border-top:1px solid rgba(255,255,255,0.05)">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.45)">Altura (cm)</label>
                            <input type="number" name="height_cm" value="{{ old('height_cm', $p?->height_cm) }}"
                                   min="100" max="250" placeholder="175"
                                   class="input-field @error('height_cm') error @enderror">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.45)">Peso (kg)</label>
                            <input type="number" name="weight_kg" value="{{ old('weight_kg', $p?->weight_kg) }}"
                                   min="30" max="300" step="0.1" placeholder="70"
                                   class="input-field @error('weight_kg') error @enderror">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-full">Guardar datos del perfil</button>
                </div>

                @if(session('status') === 'profile-data-updated')
                    <p class="text-xs text-primary mt-3 px-1 flex items-center gap-1.5 font-bold">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Datos guardados correctamente.
                    </p>
                @endif
            </form>
        </div>

        {{-- Security --}}
        <div>
            <p class="section-label">Seguridad</p>
            <div class="settings-group">
                <div x-data="{ open: {{ $errors->updatePassword->isNotEmpty() ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open" class="settings-row w-full">
                        <div class="settings-icon">
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <span class="settings-row-label">Contraseña</span>
                        <span class="settings-row-value" x-show="!open">••••••••</span>
                        <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-90' : ''" style="color:rgba(255,255,255,0.20)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition x-cloak>
                        <form method="POST" action="{{ route('password.update') }}" class="px-5 pb-4 pt-3 space-y-3" style="background:rgba(255,255,255,0.03);border-top:1px solid rgba(255,255,255,0.06)">
                            @csrf @method('put')
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color:rgba(255,255,255,0.45)">Contraseña actual</label>
                                <input type="password" name="current_password" autocomplete="current-password"
                                       class="input-field @error('current_password', 'updatePassword') error @enderror">
                                @error('current_password', 'updatePassword') <p class="text-red-400 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color:rgba(255,255,255,0.45)">Nueva contraseña</label>
                                <input type="password" name="password" autocomplete="new-password"
                                       class="input-field @error('password', 'updatePassword') error @enderror">
                                @error('password', 'updatePassword') <p class="text-red-400 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color:rgba(255,255,255,0.45)">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation" autocomplete="new-password"
                                       class="input-field @error('password_confirmation', 'updatePassword') error @enderror">
                                @error('password_confirmation', 'updatePassword') <p class="text-red-400 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
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
                <p class="text-xs text-primary mt-2 px-1 flex items-center gap-1.5 font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Contraseña actualizada.
                </p>
            @endif
        </div>

        {{-- Preferences --}}
        <div>
            <p class="section-label">Preferencias</p>
            <div class="settings-group">

                {{-- Language --}}
                <div class="settings-row">
                    <div class="settings-icon">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                    </div>
                    <span class="settings-row-label">Idioma</span>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('language.switch', 'es') }}"
                           class="text-xs font-black px-3 py-1.5 rounded-full transition-colors
                                  {{ app()->getLocale() === 'es' ? 'bg-primary text-black' : 'text-white/50 hover:text-white' }}"
                           style="{{ app()->getLocale() !== 'es' ? 'background:rgba(255,255,255,0.07)' : '' }}">ES</a>
                        <a href="{{ route('language.switch', 'en') }}"
                           class="text-xs font-black px-3 py-1.5 rounded-full transition-colors
                                  {{ app()->getLocale() === 'en' ? 'bg-primary text-black' : 'text-white/50 hover:text-white' }}"
                           style="{{ app()->getLocale() !== 'en' ? 'background:rgba(255,255,255,0.07)' : '' }}">EN</a>
                    </div>
                </div>

                {{-- Theme --}}
                @php $currentTheme = $user->profile?->theme ?? 'dark'; @endphp
                <div class="settings-row">
                    <div class="settings-icon">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <span class="settings-row-label">Tema</span>
                    <div class="flex items-center gap-1.5">
                        <form method="POST" action="{{ route('profile.theme') }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="theme" value="dark">
                            <button type="submit"
                                    class="text-xs font-black px-3 py-1.5 rounded-full transition-colors
                                           {{ $currentTheme === 'dark' ? 'bg-primary text-black' : 'text-white/50 hover:text-white' }}"
                                    style="{{ $currentTheme !== 'dark' ? 'background:rgba(255,255,255,0.07)' : '' }}">
                                Oscuro
                            </button>
                        </form>
                        <form method="POST" action="{{ route('profile.theme') }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="theme" value="light">
                            <button type="submit"
                                    class="text-xs font-black px-3 py-1.5 rounded-full transition-colors
                                           {{ $currentTheme === 'light' ? 'bg-primary text-black' : 'text-white/50 hover:text-white' }}"
                                    style="{{ $currentTheme !== 'light' ? 'background:rgba(255,255,255,0.07)' : '' }}">
                                Claro
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Attend → add race preference --}}
                @php $attendPref = $user->profile?->attend_add_race ?? 'ask'; @endphp
                <form method="POST" action="{{ route('profile.data') }}" class="settings-row" style="border-top:1px solid rgba(255,255,255,0.05)">
                    @csrf
                    <div class="settings-icon">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="settings-row-label">Al apuntarme a un evento</p>
                        <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35)">Añadir también a mis carreras</p>
                    </div>
                    <select name="attend_add_race" onchange="this.form.submit()"
                            class="text-xs font-bold rounded-lg px-2 py-1.5 border-0 outline-none cursor-pointer"
                            style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.70)">
                        <option value="ask"    {{ $attendPref === 'ask'    ? 'selected' : '' }}>Preguntar</option>
                        <option value="always" {{ $attendPref === 'always' ? 'selected' : '' }}>Siempre</option>
                        <option value="never"  {{ $attendPref === 'never'  ? 'selected' : '' }}>Nunca</option>
                    </select>
                </form>

                {{-- Push notifications --}}
                <div class="settings-row" style="border-top:1px solid rgba(255,255,255,0.05)"
                     x-data="{ enabled: false, loading: false }"
                     x-init="
                        if ('Notification' in window && 'serviceWorker' in navigator) {
                            navigator.serviceWorker.ready.then(reg => {
                                reg.pushManager.getSubscription().then(sub => { enabled = !!sub; });
                            });
                        }
                     ">
                    <div class="settings-icon">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="settings-row-label">Notificaciones push</span>
                        <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35)">Recordatorios de carreras</p>
                    </div>
                    <button type="button" :disabled="loading"
                            @click="
                                if (!('Notification' in window)) return;
                                loading = true;
                                if (enabled) {
                                    unsubscribeFromPush().then(() => { enabled = false; loading = false; });
                                } else {
                                    Notification.requestPermission().then(async perm => {
                                        if (perm === 'granted') {
                                            const ok = await subscribeToPush();
                                            enabled = ok;
                                        }
                                        loading = false;
                                    });
                                }
                            "
                            class="w-9 h-5 rounded-full flex-shrink-0 transition-colors duration-200 relative disabled:opacity-50"
                            :class="enabled ? 'bg-primary' : 'bg-white/20'">
                        <span class="absolute left-0.5 top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform duration-200"
                              :class="enabled ? 'translate-x-4' : 'translate-x-0'"></span>
                    </button>
                </div>

            </div>
            @if(session('status') === 'theme-updated')
                <p class="text-xs text-primary mt-2 px-1 flex items-center gap-1.5 font-bold">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    Tema actualizado.
                </p>
            @endif
        </div>

        {{-- Privacy & Cookies --}}
        @php
            $profile = $user->profile;
            $consented = $profile?->cookie_consented_at !== null;
        @endphp
        <div
            x-data="{
                functional: {{ $consented && $profile->cookie_functional ? 'true' : 'false' }},
                analytics:  {{ $consented && $profile->cookie_analytics  ? 'true' : 'false' }},
                saved: false,
                save() {
                    fetch('{{ route('cookie.consent') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify({ functional: this.functional, analytics: this.analytics })
                    }).then(() => {
                        this.saved = true;
                        setTimeout(() => { this.saved = false; }, 3000);
                    });
                }
            }">
            <p class="section-label">Privacidad y cookies</p>
            <div class="settings-group">
                <div class="settings-row">
                    <div class="settings-icon">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="settings-row-label">Necesarias</span>
                    <div class="w-9 h-5 rounded-full bg-primary flex-shrink-0 opacity-60 cursor-not-allowed"></div>
                </div>
                <div class="settings-row">
                    <div class="settings-icon">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="settings-row-label">Funcionales</span>
                        <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35)">Idioma, tema y preferencias</p>
                    </div>
                    <button type="button" @click="functional = !functional; save()"
                            class="w-9 h-5 rounded-full flex-shrink-0 transition-colors duration-200 relative"
                            :class="functional ? 'bg-primary' : 'bg-white/20'">
                        <span class="absolute left-0.5 top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform duration-200"
                              :class="functional ? 'translate-x-4' : 'translate-x-0'"></span>
                    </button>
                </div>
                <div class="settings-row">
                    <div class="settings-icon">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="settings-row-label">Analíticas</span>
                        <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35)">Estadísticas de uso anónimas</p>
                    </div>
                    <button type="button" @click="analytics = !analytics; save()"
                            class="w-9 h-5 rounded-full flex-shrink-0 transition-colors duration-200 relative"
                            :class="analytics ? 'bg-primary' : 'bg-white/20'">
                        <span class="absolute left-0.5 top-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform duration-200"
                              :class="analytics ? 'translate-x-4' : 'translate-x-0'"></span>
                    </button>
                </div>
            </div>
            @if($consented)
                <p class="text-[10px] mt-2 px-1" style="color:rgba(255,255,255,0.25)">
                    Consentimiento otorgado el {{ $profile->cookie_consented_at->format('d/m/Y') }}.
                </p>
            @endif
            <p x-show="saved" x-transition x-cloak class="text-xs text-primary mt-2 px-1 flex items-center gap-1.5 font-bold">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                Preferencias guardadas.
            </p>
        </div>

        {{-- Strava --}}
        @php
            $hasStrava = auth()->user()->socialAccounts()->where('provider', 'strava')->exists();
        @endphp
        <div>
            <p class="section-label">Integraciones</p>
            <div class="settings-group">
                <div class="settings-row">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#FC4C02">
                        <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15.387 17.944l-2.089-4.116h-3.065L15.387 24l5.15-10.172h-3.066m-7.008-5.599l2.836 5.599h4.172L10.463 0l-7 13.828h4.169"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="settings-row-label">Strava</p>
                        <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.35)">
                            {{ $hasStrava ? 'Cuenta conectada' : 'No conectado' }}
                        </p>
                    </div>
                    @if($hasStrava)
                        <a href="{{ route('strava.import') }}"
                           class="text-xs font-bold px-3 py-1.5 rounded-lg transition"
                           style="background:rgba(252,76,2,0.15);color:#FC4C02">
                            Importar
                        </a>
                    @else
                        <a href="{{ route('social.redirect', 'strava') }}"
                           class="text-xs font-bold px-3 py-1.5 rounded-lg transition"
                           style="background:rgba(252,76,2,0.15);color:#FC4C02">
                            Conectar
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Account --}}
        <div>
            <p class="section-label">Cuenta</p>
            <div class="settings-group">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="settings-row w-full">
                        <div class="settings-icon">
                            <svg class="w-4 h-4" style="color:rgba(255,255,255,0.50)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </div>
                        <span class="settings-row-label">Cerrar sesión</span>
                        <svg class="w-4 h-4 flex-shrink-0" style="color:rgba(255,255,255,0.20)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </form>

                <div x-data="{ open: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
                    <button type="button" @click="open = !open" class="settings-row w-full">
                        <div class="settings-icon" style="background:rgba(248,113,113,0.10)">
                            <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </div>
                        <span class="settings-row-label text-red-400">Eliminar cuenta</span>
                        <svg class="w-4 h-4 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-90' : ''" style="color:rgba(255,255,255,0.20)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition x-cloak>
                        <form method="POST" action="{{ route('profile.destroy') }}"
                              class="px-5 pb-4 pt-3 space-y-3" style="background:rgba(248,113,113,0.05);border-top:1px solid rgba(248,113,113,0.15)" autocomplete="off">
                            @csrf @method('delete')
                            <p class="text-xs text-red-400 font-medium">Esta acción es irreversible. Se borrarán todas tus carreras y datos.</p>
                            <div>
                                <label class="block text-xs font-bold mb-1.5" style="color:rgba(255,255,255,0.45)">Confirma con tu contraseña</label>
                                <input type="password" name="password" autocomplete="off" placeholder="Contraseña"
                                       class="input-field @error('password', 'userDeletion') error @enderror">
                                @error('password', 'userDeletion') <p class="text-red-400 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
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
