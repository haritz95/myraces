<x-app-layout>
    @section('page_title', 'Ajustes')
    @section('back_url', route('admin.dashboard'))

    @php
        $s = fn(string $key, mixed $default = '') => old($key, $settings->get($key) ?? $default);
        $checked = fn(string $key) => old($key, $settings->get($key, '1')) === '1';
    @endphp

    <div class="max-w-2xl mx-auto px-4 py-6">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            {{-- ── GENERAL ──────────────────────────────────────── --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-3.5 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgb(var(--color-primary) / 0.12)">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-black text-white">General</h2>
                </div>
                <div class="px-5 py-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="settings-label">Nombre de la app</label>
                            <input type="text" name="app_name" value="{{ $s('app_name', config('app.name')) }}"
                                   placeholder="MyRaces" class="settings-input">
                        </div>
                        <div>
                            <label class="settings-label">Tagline</label>
                            <input type="text" name="app_tagline" value="{{ $s('app_tagline') }}"
                                   placeholder="Organiza tus carreras" class="settings-input">
                        </div>
                    </div>
                    <div>
                        <label class="settings-label">Email de contacto</label>
                        <input type="email" name="contact_email" value="{{ $s('contact_email') }}"
                               placeholder="hola@myraces.app" class="settings-input">
                    </div>

                    <div style="border-top:1px solid rgba(255,255,255,0.05)" class="pt-4 space-y-3">
                        <label class="settings-toggle">
                            <span class="flex-1">
                                <span class="text-sm font-semibold text-white block">Permitir nuevos registros</span>
                                <span class="text-xs" style="color:rgba(255,255,255,0.35)">Desactívalo para cerrar la inscripción pública</span>
                            </span>
                            <input type="hidden" name="allow_registrations" value="0">
                            <button type="button" role="switch" onclick="toggleSwitch(this)"
                                    data-name="allow_registrations"
                                    class="toggle-btn {{ $checked('allow_registrations') ? 'toggle-on' : 'toggle-off' }}">
                                <span class="toggle-thumb {{ $checked('allow_registrations') ? 'translate-x-5' : 'translate-x-0.5' }}"></span>
                            </button>
                            <input type="hidden" name="allow_registrations" value="{{ $checked('allow_registrations') ? '1' : '0' }}" class="toggle-input">
                        </label>

                        <label class="settings-toggle">
                            <span class="flex-1">
                                <span class="text-sm font-semibold text-white block">Permitir envío de eventos</span>
                                <span class="text-xs" style="color:rgba(255,255,255,0.35)">Los usuarios pueden proponer nuevas carreras</span>
                            </span>
                            <input type="hidden" name="event_submissions_open" value="0">
                            <button type="button" role="switch" onclick="toggleSwitch(this)"
                                    data-name="event_submissions_open"
                                    class="toggle-btn {{ $checked('event_submissions_open') ? 'toggle-on' : 'toggle-off' }}">
                                <span class="toggle-thumb {{ $checked('event_submissions_open') ? 'translate-x-5' : 'translate-x-0.5' }}"></span>
                            </button>
                            <input type="hidden" name="event_submissions_open" value="{{ $checked('event_submissions_open') ? '1' : '0' }}" class="toggle-input">
                        </label>

                        <label class="settings-toggle">
                            <span class="flex-1">
                                <span class="text-sm font-semibold text-white block">Widget de feedback</span>
                                <span class="text-xs" style="color:rgba(255,255,255,0.35)">Botón flotante para que los usuarios envíen bugs y sugerencias</span>
                            </span>
                            <input type="hidden" name="feedback_widget_enabled" value="0">
                            <button type="button" role="switch" onclick="toggleSwitch(this)"
                                    data-name="feedback_widget_enabled"
                                    class="toggle-btn {{ $checked('feedback_widget_enabled') ? 'toggle-on' : 'toggle-off' }}">
                                <span class="toggle-thumb {{ $checked('feedback_widget_enabled') ? 'translate-x-5' : 'translate-x-0.5' }}"></span>
                            </button>
                            <input type="hidden" name="feedback_widget_enabled" value="{{ $checked('feedback_widget_enabled') ? '1' : '0' }}" class="toggle-input">
                        </label>
                    </div>

                    {{-- Maintenance --}}
                    <div style="border-top:1px solid rgba(255,255,255,0.05)" class="pt-4 space-y-3">
                        <label class="settings-toggle">
                            <span class="flex-1">
                                <span class="text-sm font-semibold flex items-center gap-2">
                                    <span style="color:{{ $checked('maintenance_mode') ? '#f87171' : 'white' }}">Modo mantenimiento</span>
                                    @if($checked('maintenance_mode'))
                                        <span class="text-[10px] font-black px-2 py-0.5 rounded-full" style="background:rgba(248,113,113,0.15);color:#f87171">ACTIVO</span>
                                    @endif
                                </span>
                                <span class="text-xs" style="color:rgba(255,255,255,0.35)">Muestra una página de mantenimiento a los visitantes (los admins siguen accediendo)</span>
                            </span>
                            <input type="hidden" name="maintenance_mode" value="0">
                            <button type="button" role="switch" onclick="toggleSwitch(this)"
                                    data-name="maintenance_mode"
                                    class="toggle-btn {{ $checked('maintenance_mode') ? 'toggle-on' : 'toggle-off' }}">
                                <span class="toggle-thumb {{ $checked('maintenance_mode') ? 'translate-x-5' : 'translate-x-0.5' }}"></span>
                            </button>
                            <input type="hidden" name="maintenance_mode" value="{{ $checked('maintenance_mode') ? '1' : '0' }}" class="toggle-input">
                        </label>
                        <div>
                            <label class="settings-label">Mensaje de mantenimiento</label>
                            <textarea name="maintenance_message" rows="2"
                                      placeholder="Estamos realizando tareas de mantenimiento. Volvemos pronto."
                                      class="settings-input resize-none">{{ $s('maintenance_message') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── SEO ──────────────────────────────────────────── --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-3.5 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(96,165,250,0.12)">
                        <svg class="w-4 h-4" style="color:#60a5fa" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-black text-white">SEO</h2>
                </div>
                <div class="px-5 py-4 space-y-4">
                    <div>
                        <label class="settings-label">Meta descripción del sitio
                            <span class="ml-1 font-normal" style="color:rgba(255,255,255,0.25)">(máx. 160 caracteres)</span>
                        </label>
                        <textarea name="seo_description" rows="2" maxlength="160"
                                  placeholder="MyRaces — la app para corredores. Organiza tus carreras de running, trail y triatlón."
                                  class="settings-input resize-none">{{ $s('seo_description') }}</textarea>
                    </div>
                    <div>
                        <label class="settings-label">Palabras clave
                            <span class="ml-1 font-normal" style="color:rgba(255,255,255,0.25)">(separadas por comas)</span>
                        </label>
                        <input type="text" name="seo_keywords" value="{{ $s('seo_keywords') }}"
                               placeholder="running, trail, carreras, atletismo, triatlón"
                               class="settings-input">
                    </div>
                </div>
            </div>

            {{-- ── ANALYTICS ────────────────────────────────────── --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-3.5 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(245,158,11,0.12)">
                        <svg class="w-4 h-4" style="color:#f59e0b" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-black text-white">Analytics</h2>
                </div>
                <div class="px-5 py-4 space-y-3">
                    <div>
                        <label class="settings-label">Google Analytics 4 — Measurement ID</label>
                        <input type="text" name="google_analytics_id" value="{{ $s('google_analytics_id') }}"
                               placeholder="G-XXXXXXXXXX" class="settings-input font-mono">
                        @error('google_analytics_id')
                            <p class="text-xs mt-1.5 text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="settings-hint">Admin → Flujos de datos en la consola de Google Analytics. Déjalo vacío para desactivar.</p>
                    </div>
                    @if($settings->get('google_analytics_id'))
                        <div class="flex items-center gap-2 px-3 py-2 rounded-lg" style="background:rgb(var(--color-primary) / 0.08);border:1px solid rgb(var(--color-primary) / 0.15)">
                            <div class="w-1.5 h-1.5 rounded-full bg-primary flex-shrink-0"></div>
                            <p class="text-xs font-semibold" style="color:rgb(var(--color-primary) / 0.85)">Activo — {{ $settings->get('google_analytics_id') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── REDES SOCIALES ───────────────────────────────── --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-3.5 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(244,114,182,0.12)">
                        <svg class="w-4 h-4" style="color:#f472b6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-black text-white">Redes sociales</h2>
                </div>
                <div class="px-5 py-4 space-y-3">
                    @foreach([
                        ['instagram', 'Instagram', 'https://instagram.com/myraces'],
                        ['twitter',   'X / Twitter','https://x.com/myraces'],
                        ['facebook',  'Facebook',   'https://facebook.com/myraces'],
                        ['strava',    'Strava',      'https://strava.com/clubs/myraces'],
                        ['youtube',   'YouTube',     'https://youtube.com/@myraces'],
                    ] as [$key, $label, $placeholder])
                    <div>
                        <label class="settings-label">{{ $label }}</label>
                        <input type="url" name="social_{{ $key }}" value="{{ $s('social_' . $key) }}"
                               placeholder="{{ $placeholder }}" class="settings-input">
                        @error('social_' . $key)
                            <p class="text-xs mt-1 text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── APARIENCIA ───────────────────────────────────── --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-3.5 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(167,139,250,0.12)">
                        <svg class="w-4 h-4" style="color:#a78bfa" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-white">Apariencia</h2>
                        <p class="text-[11px] mt-0.5" style="color:rgba(255,255,255,0.35)">Cambia el color principal de la web</p>
                    </div>
                </div>
                <div class="px-5 py-4 space-y-4">
                    <div>
                        <label class="settings-label">Color principal</label>
                        <div class="flex items-center gap-3 flex-wrap">
                            {{-- Presets --}}
                            @php
                                $presets = [
                                    '#C8FA5F' => 'Lima (por defecto)',
                                    '#60A5FA' => 'Azul',
                                    '#F472B6' => 'Rosa',
                                    '#FB923C' => 'Naranja',
                                    '#34D399' => 'Verde',
                                    '#A78BFA' => 'Violeta',
                                    '#F87171' => 'Rojo',
                                    '#FBBF24' => 'Amarillo',
                                    '#E2E8F0' => 'Blanco',
                                ];
                                $currentColor = $s('primary_color', '#C8FA5F') ?: '#C8FA5F';
                            @endphp
                            @foreach($presets as $hex => $name)
                                <button type="button"
                                        title="{{ $name }}"
                                        onclick="setColor('{{ $hex }}')"
                                        class="w-8 h-8 rounded-full border-2 transition-all hover:scale-110 flex-shrink-0"
                                        style="background:{{ $hex }};border-color:{{ strtolower($currentColor) === strtolower($hex) ? '#fff' : 'transparent' }};outline:{{ strtolower($currentColor) === strtolower($hex) ? '2px solid rgba(255,255,255,0.4)' : 'none' }};outline-offset:2px">
                                </button>
                            @endforeach

                            {{-- Custom color picker --}}
                            <label class="relative w-8 h-8 rounded-full overflow-hidden cursor-pointer border-2 flex-shrink-0 hover:scale-110 transition-all"
                                   title="Color personalizado"
                                   style="border-color:rgba(255,255,255,0.20)">
                                <span class="absolute inset-0 flex items-center justify-center text-white/50 text-xs font-bold pointer-events-none">+</span>
                                <input type="color" id="color-picker-input"
                                       value="{{ $currentColor }}"
                                       onchange="setColor(this.value)"
                                       class="opacity-0 absolute inset-0 w-full h-full cursor-pointer">
                            </label>
                        </div>

                        <div class="flex items-center gap-2 mt-3">
                            <div id="color-preview" class="w-5 h-5 rounded-full border border-white/20 flex-shrink-0"
                                 style="background:{{ $currentColor }}"></div>
                            <input type="text" id="color-hex-input" name="primary_color"
                                   value="{{ $currentColor }}"
                                   placeholder="#C8FA5F"
                                   maxlength="7"
                                   oninput="onHexInput(this.value)"
                                   class="settings-input font-mono w-32">
                            <span class="text-xs" style="color:rgba(255,255,255,0.30)">Color HEX</span>
                        </div>
                        @error('primary_color')
                            <p class="text-xs mt-1 text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="settings-hint">Afecta a botones, iconos, badges y acentos de toda la app. Elige un color con buen contraste sobre fondo oscuro.</p>
                    </div>
                </div>
            </div>

            {{-- ── EVENTOS ──────────────────────────────────────── --}}
            <div class="card overflow-hidden">
                <div class="px-5 py-3.5 flex items-center gap-3" style="border-bottom:1px solid rgba(255,255,255,0.06)">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(52,211,153,0.12)">
                        <svg class="w-4 h-4" style="color:#34d399" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-black text-white">Eventos</h2>
                </div>
                <div class="px-5 py-4 space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="settings-label">Eventos por página</label>
                            <input type="number" name="events_per_page" value="{{ $s('events_per_page', 12) }}"
                                   min="6" max="100" class="settings-input">
                        </div>
                        <div>
                            <label class="settings-label">Eventos destacados</label>
                            <input type="number" name="featured_events_count" value="{{ $s('featured_events_count', 3) }}"
                                   min="1" max="10" class="settings-input">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pb-2">
                <button type="submit" class="btn btn-primary px-8 py-2.5 text-sm">
                    Guardar todos los ajustes
                </button>
            </div>
        </form>
    </div>

    <style>
        .settings-label { display:block; font-size:.7rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; margin-bottom:.375rem; color:rgba(255,255,255,0.45); }
        .settings-input { width:100%; font-size:.875rem; padding:.625rem .875rem; border-radius:.625rem; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.08); color:#fff; outline:none; transition:border-color .15s; }
        .settings-input:focus { border-color:rgb(var(--color-primary) / 0.40); }
        .settings-input::placeholder { color:rgba(255,255,255,0.20); }
        .settings-hint { font-size:.7rem; margin-top:.375rem; color:rgba(255,255,255,0.28); }
        .settings-toggle { display:flex; align-items:center; gap:1rem; cursor:pointer; }
        .toggle-btn { position:relative; display:inline-flex; width:2.5rem; height:1.375rem; border-radius:9999px; flex-shrink:0; transition:background .2s; border:none; cursor:pointer; }
        .toggle-on  { background:rgb(var(--color-primary)); }
        .toggle-off { background:rgba(255,255,255,0.15); }
        .toggle-thumb { position:absolute; top:.1875rem; width:1rem; height:1rem; border-radius:9999px; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.3); transition:transform .2s; }
    </style>

    <script>
        function setColor(hex) {
            document.getElementById('color-hex-input').value = hex;
            document.getElementById('color-picker-input').value = hex;
            document.getElementById('color-preview').style.background = hex;
            // Update preset button borders
            document.querySelectorAll('[onclick^="setColor"]').forEach(function(btn) {
                var btnHex = btn.getAttribute('onclick').match(/'(#[^']+)'/)[1];
                btn.style.borderColor = btnHex.toLowerCase() === hex.toLowerCase() ? '#fff' : 'transparent';
                btn.style.outline = btnHex.toLowerCase() === hex.toLowerCase() ? '2px solid rgba(255,255,255,0.4)' : 'none';
            });
        }

        function onHexInput(val) {
            if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
                document.getElementById('color-picker-input').value = val;
                document.getElementById('color-preview').style.background = val;
            }
        }

        function toggleSwitch(btn) {
            var isOn = btn.classList.contains('toggle-on');
            btn.classList.toggle('toggle-on', !isOn);
            btn.classList.toggle('toggle-off', isOn);
            var thumb = btn.querySelector('.toggle-thumb');
            thumb.classList.toggle('translate-x-5', !isOn);
            thumb.classList.toggle('translate-x-0.5', isOn);
            // Update the hidden input that follows the button
            var input = btn.nextElementSibling;
            input.value = isOn ? '0' : '1';
        }
    </script>
</x-app-layout>
