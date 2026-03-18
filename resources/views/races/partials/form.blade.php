@php $isEdit = isset($race); @endphp

<div style="border-top:1px solid rgba(255,255,255,0.06)">

    {{-- Section: Basic info --}}
    <div class="px-5 py-6 space-y-5" style="border-bottom:1px solid rgba(255,255,255,0.06)">
        <p class="section-label">Información básica</p>

        <div>
            <label class="block text-sm font-bold text-white mb-2">
                {{ __('races.fields.name') }} <span class="text-primary">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $race->name ?? '') }}"
                   placeholder="Maratón de Madrid 2026"
                   class="input-field @error('name') error @enderror">
            @error('name') <p class="text-red-400 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-white mb-2">
                {{ __('races.fields.date') }} <span class="text-primary">*</span>
            </label>
            <input type="date" name="date"
                   value="{{ old('date', isset($race) ? $race->date->format('Y-m-d') : '') }}"
                   class="input-field @error('date') error @enderror">
            @error('date') <p class="text-red-400 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-white mb-2">
                {{ __('races.fields.distance') }} <span class="text-primary">*</span>
            </label>
            <div class="flex gap-2.5">
                <input type="number" name="distance" value="{{ old('distance', $race->distance ?? '') }}"
                       step="0.001" min="0.1" placeholder="42.195"
                       class="input-field flex-1 @error('distance') error @enderror">
                <select name="distance_unit" class="input-field w-20 flex-shrink-0">
                    <option value="km" {{ old('distance_unit', $race->distance_unit ?? 'km') === 'km' ? 'selected' : '' }}>km</option>
                    <option value="mi" {{ old('distance_unit', $race->distance_unit ?? 'km') === 'mi' ? 'selected' : '' }}>mi</option>
                </select>
            </div>
            <div class="flex flex-wrap gap-2 mt-3">
                @foreach([5 => '5K', 10 => '10K', 21.097 => 'Media', 42.195 => 'Maratón'] as $dist => $label)
                    <button type="button"
                            onclick="document.querySelector('[name=distance]').value='{{ $dist }}'"
                            class="text-xs px-4 py-2 rounded-full font-bold transition-colors"
                            style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.60);border:1px solid rgba(255,255,255,0.10)"
                            onmouseover="this.style.background='rgba(200,250,95,0.15)';this.style.color='#C8FA5F';this.style.borderColor='rgba(200,250,95,0.30)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.07)';this.style.color='rgba(255,255,255,0.60)';this.style.borderColor='rgba(255,255,255,0.10)'">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
            @error('distance') <p class="text-red-400 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-3 gap-2.5">
            <div class="col-span-2">
                <label class="block text-sm font-bold text-white mb-2">{{ __('races.fields.location') }}</label>
                <input type="text" name="location" value="{{ old('location', $race->location ?? '') }}"
                       placeholder="Madrid" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-bold text-white mb-2">País</label>
                <input type="text" name="country" value="{{ old('country', $race->country ?? '') }}"
                       placeholder="ES" maxlength="2"
                       class="input-field uppercase text-center tracking-widest font-bold">
            </div>
        </div>
    </div>

    {{-- Section: Modality & Status --}}
    <div class="px-5 py-6 space-y-5" style="border-bottom:1px solid rgba(255,255,255,0.06)">
        <p class="section-label">Tipo y estado</p>

        <div>
            <label class="block text-sm font-bold text-white mb-3">
                {{ __('races.fields.modality') }} <span class="text-primary">*</span>
            </label>
            <div class="grid grid-cols-3 gap-2">
                @foreach(['road', 'trail', 'mountain', 'track', 'cross', 'other'] as $mod)
                    <label class="cursor-pointer">
                        <input type="radio" name="modality" value="{{ $mod }}" class="sr-only peer"
                               {{ old('modality', $race->modality ?? 'road') === $mod ? 'checked' : '' }}>
                        <div class="text-center text-xs py-3 px-1 rounded-2xl font-bold transition-all cursor-pointer"
                             style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.40);border:2px solid transparent"
                             x-data
                             :class="$el.previousElementSibling.checked ? 'checked-mod' : ''">
                            {{ __('races.modalities.' . $mod) }}
                        </div>
                        <style>.sr-only:checked + div { background: rgba(200,250,95,0.12) !important; color: #C8FA5F !important; border-color: rgba(200,250,95,0.30) !important; }</style>
                    </label>
                @endforeach
            </div>
            @error('modality') <p class="text-red-400 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-white mb-3">
                {{ __('races.fields.status') }} <span class="text-primary">*</span>
            </label>
            <div class="grid grid-cols-2 gap-2">
                @foreach([
                    'upcoming'  => ['label' => __('races.statuses.upcoming'),  'color' => 'rgba(200,250,95,0.12)',  'text' => '#C8FA5F',  'border' => 'rgba(200,250,95,0.30)'],
                    'completed' => ['label' => __('races.statuses.completed'), 'color' => 'rgba(74,222,128,0.12)', 'text' => '#4ade80', 'border' => 'rgba(74,222,128,0.30)'],
                    'dnf'       => ['label' => __('races.statuses.dnf'),       'color' => 'rgba(248,113,113,0.12)','text' => '#f87171', 'border' => 'rgba(248,113,113,0.30)'],
                    'dns'       => ['label' => __('races.statuses.dns'),       'color' => 'rgba(107,114,128,0.15)','text' => '#9ca3af', 'border' => 'rgba(107,114,128,0.30)'],
                ] as $st => $cfg)
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="{{ $st }}" class="sr-only"
                               id="status_{{ $st }}"
                               {{ old('status', $race->status ?? 'upcoming') === $st ? 'checked' : '' }}>
                        <div class="text-center text-xs py-3.5 rounded-2xl font-bold transition-all cursor-pointer"
                             style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.40);border:2px solid transparent"
                             id="status_label_{{ $st }}">
                            {{ $cfg['label'] }}
                        </div>
                    </label>
                @endforeach
            </div>
            @error('status') <p class="text-red-400 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Section: Results --}}
    <div class="px-5 py-6 space-y-5" style="border-bottom:1px solid rgba(255,255,255,0.06)">
        <p class="section-label">Resultados</p>

        <div>
            <label class="block text-sm font-bold text-white mb-2">{{ __('races.fields.finish_time') }}</label>
            <input type="text" name="finish_time"
                   value="{{ old('finish_time', isset($race) && $race->formatted_time ? $race->formatted_time : '') }}"
                   placeholder="3:30:00"
                   class="input-field font-mono tabnum @error('finish_time') error @enderror">
            <p class="text-xs mt-2" style="color:rgba(255,255,255,0.30)">{{ __('races.fields.finish_time_hint') }}</p>
            @error('finish_time') <p class="text-red-400 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-bold text-white mb-2">{{ __('races.fields.position_overall') }}</label>
                <input type="number" name="position_overall"
                       value="{{ old('position_overall', $race->position_overall ?? '') }}"
                       min="1" placeholder="125" class="input-field tabnum">
            </div>
            <div>
                <label class="block text-sm font-bold text-white mb-2">{{ __('races.fields.position_category') }}</label>
                <input type="number" name="position_category"
                       value="{{ old('position_category', $race->position_category ?? '') }}"
                       min="1" placeholder="12" class="input-field tabnum">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-bold text-white mb-2">{{ __('races.fields.category') }}</label>
                <input type="text" name="category"
                       value="{{ old('category', $race->category ?? '') }}"
                       placeholder="M40" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-bold text-white mb-2">{{ __('races.fields.bib_number') }}</label>
                <input type="text" name="bib_number"
                       value="{{ old('bib_number', $race->bib_number ?? '') }}"
                       placeholder="456" class="input-field">
            </div>
        </div>
    </div>

    {{-- Section: Extra --}}
    <div class="px-5 py-6 space-y-5" style="border-bottom:1px solid rgba(255,255,255,0.06)">
        <p class="section-label">Detalles adicionales</p>

        <div>
            <label class="block text-sm font-bold text-white mb-2">{{ __('races.fields.cost') }}</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-sm pointer-events-none" style="color:rgba(255,255,255,0.30)">€</span>
                <input type="number" name="cost"
                       value="{{ old('cost', $race->cost ?? '') }}"
                       step="0.01" min="0" placeholder="0.00"
                       class="input-field pl-8 tabnum">
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-white mb-2">{{ __('races.fields.website') }}</label>
            <input type="url" name="website"
                   value="{{ old('website', $race->website ?? '') }}"
                   placeholder="https://..." class="input-field @error('website') error @enderror">
            @error('website') <p class="text-red-400 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-white mb-2">{{ __('races.fields.notes') }}</label>
            <textarea name="notes" rows="3" placeholder="Sensaciones, condiciones, logística..."
                      class="input-field resize-none">{{ old('notes', $race->notes ?? '') }}</textarea>
        </div>

        <div class="flex items-center justify-between rounded-2xl px-4 py-4"
             style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07)">
            <div>
                <p class="text-sm font-bold text-white">{{ __('races.fields.is_public') }}</p>
                <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">Visible en tu perfil público</p>
            </div>
            <label class="flex-shrink-0 ml-4 cursor-pointer">
                <input type="hidden" name="is_public" value="0">
                <input type="checkbox" name="is_public" value="1" class="sr-only" id="chk_is_public"
                       {{ old('is_public', $race->is_public ?? true) ? 'checked' : '' }}>
                <div id="toggle_track"
                     style="width:52px;height:30px;border-radius:999px;position:relative;transition:background 0.2s,box-shadow 0.2s;background:{{ old('is_public', $race->is_public ?? true) ? '#C8FA5F' : 'rgba(255,255,255,0.15)' }};box-shadow:{{ old('is_public', $race->is_public ?? true) ? '0 0 0 3px rgba(200,250,95,0.25)' : 'none' }}">
                    <div id="toggle_thumb"
                         style="position:absolute;top:3px;left:3px;width:24px;height:24px;border-radius:50%;transition:transform 0.2s,background 0.2s;transform:{{ old('is_public', $race->is_public ?? true) ? 'translateX(22px)' : 'translateX(0)' }};background:{{ old('is_public', $race->is_public ?? true) ? '#0a0a0a' : '#ffffff' }};box-shadow:0 1px 4px rgba(0,0,0,0.4)"></div>
                </div>
            </label>
        </div>
    </div>

    {{-- Section: Gear --}}
    @if(isset($gear) && $gear->isNotEmpty())
    @php
        $selectedGearIds = old('gear_ids', $isEdit ? ($race->gear->pluck('id')->toArray()) : []);
        $typeLabels = ['shoes' => 'Zapatillas', 'watch' => 'Reloj', 'clothing' => 'Ropa', 'accessories' => 'Accesorios', 'nutrition' => 'Nutrición', 'other' => 'Otros'];
    @endphp
    <div class="px-5 py-6 space-y-4" style="border-bottom:1px solid rgba(255,255,255,0.06)">
        <p class="section-label">Material utilizado</p>
        @foreach($gear->groupBy('type') as $type => $items)
            <div>
                <p class="text-[11px] font-black uppercase tracking-wider mb-2" style="color:rgba(255,255,255,0.30)">{{ $typeLabels[$type] ?? $type }}</p>
                <div class="space-y-2">
                    @foreach($items as $item)
                        <label class="flex items-center gap-3 px-4 py-3 rounded-2xl cursor-pointer transition-all gear-row"
                               id="gear_label_{{ $item->id }}"
                               style="background:{{ in_array($item->id, $selectedGearIds) ? 'rgba(200,250,95,0.08)' : 'rgba(255,255,255,0.04)' }};border:1px solid {{ in_array($item->id, $selectedGearIds) ? 'rgba(200,250,95,0.30)' : 'rgba(255,255,255,0.07)' }}">
                            <input type="checkbox" name="gear_ids[]" value="{{ $item->id }}"
                                   class="sr-only gear-checkbox"
                                   id="gear_{{ $item->id }}"
                                   {{ in_array($item->id, $selectedGearIds) ? 'checked' : '' }}>
                            <div class="w-4 h-4 rounded flex items-center justify-center flex-shrink-0 transition-all gear-box"
                                 id="gear_box_{{ $item->id }}"
                                 style="border:2px solid {{ in_array($item->id, $selectedGearIds) ? '#C8FA5F' : 'rgba(255,255,255,0.20)' }};background:{{ in_array($item->id, $selectedGearIds) ? '#C8FA5F' : 'transparent' }}">
                                <svg class="w-2.5 h-2.5 text-black {{ in_array($item->id, $selectedGearIds) ? '' : 'hidden' }} gear-check" id="gear_icon_{{ $item->id }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-white flex-1">{{ $item->brand }} {{ $item->model }}</span>
                            <span class="text-xs font-bold tabnum" style="color:rgba(255,255,255,0.35)">{{ number_format((float) $item->current_km, 0) }} km</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    @endif

    {{-- Submit --}}
    <div class="px-5 py-5">
        <button type="submit" class="btn btn-primary w-full py-4 text-base">
            {{ $isEdit ? __('races.save') : __('races.add_race') }}
        </button>
    </div>

</div>

<script>
    // Highlight radio buttons on selection
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        const updateStyle = () => {
            const group = document.querySelectorAll(`input[name="${radio.name}"]`);
            group.forEach(r => {
                const label = r.nextElementSibling;
                if (!label) { return; }
                if (r.checked) {
                    label.style.background = 'rgba(200,250,95,0.12)';
                    label.style.color = '#C8FA5F';
                    label.style.borderColor = 'rgba(200,250,95,0.30)';
                } else {
                    label.style.background = 'rgba(255,255,255,0.06)';
                    label.style.color = 'rgba(255,255,255,0.40)';
                    label.style.borderColor = 'transparent';
                }
            });
        };
        updateStyle();
        radio.addEventListener('change', updateStyle);
    });

    // Toggle switch
    (function () {
        const chk   = document.getElementById('chk_is_public');
        const track = document.getElementById('toggle_track');
        const thumb = document.getElementById('toggle_thumb');
        if (!chk || !track || !thumb) { return; }
        const update = () => {
            if (chk.checked) {
                track.style.background = '#C8FA5F';
                track.style.boxShadow  = '0 0 0 3px rgba(200,250,95,0.25)';
                thumb.style.transform  = 'translateX(22px)';
                thumb.style.background = '#0a0a0a';
            } else {
                track.style.background = 'rgba(255,255,255,0.15)';
                track.style.boxShadow  = 'none';
                thumb.style.transform  = 'translateX(0)';
                thumb.style.background = '#ffffff';
            }
        };
        chk.addEventListener('change', update);
    })();

    // Gear checkboxes
    document.querySelectorAll('.gear-checkbox').forEach(cb => {
        const id    = cb.id.replace('gear_', '');
        const row   = document.getElementById('gear_label_' + id);
        const box   = document.getElementById('gear_box_' + id);
        const icon  = document.getElementById('gear_icon_' + id);
        const apply = () => {
            if (cb.checked) {
                row.style.background  = 'rgba(200,250,95,0.08)';
                row.style.borderColor = 'rgba(200,250,95,0.30)';
                box.style.background  = '#C8FA5F';
                box.style.borderColor = '#C8FA5F';
                icon.classList.remove('hidden');
            } else {
                row.style.background  = 'rgba(255,255,255,0.04)';
                row.style.borderColor = 'rgba(255,255,255,0.07)';
                box.style.background  = 'transparent';
                box.style.borderColor = 'rgba(255,255,255,0.20)';
                icon.classList.add('hidden');
            }
        };
        cb.addEventListener('change', apply);
    });

</script>
