@php $isEdit = isset($race); @endphp

<div class="divide-y divide-slate-50">

    {{-- Section: Basic info --}}
    <div class="px-5 py-6 space-y-5">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.18em]">Información básica</p>

        <div>
            <label class="block text-sm font-bold text-slate-800 mb-2">
                {{ __('races.fields.name') }} <span class="text-violet-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $race->name ?? '') }}"
                   placeholder="Maratón de Madrid 2026"
                   class="input-field @error('name') error @enderror">
            @error('name') <p class="text-rose-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-800 mb-2">
                {{ __('races.fields.date') }} <span class="text-violet-500">*</span>
            </label>
            <input type="date" name="date"
                   value="{{ old('date', isset($race) ? $race->date->format('Y-m-d') : '') }}"
                   class="input-field @error('date') error @enderror">
            @error('date') <p class="text-rose-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-800 mb-2">
                {{ __('races.fields.distance') }} <span class="text-violet-500">*</span>
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
                            class="text-xs px-4 py-2 rounded-full bg-slate-100 text-slate-600 hover:bg-violet-100 hover:text-violet-700 font-semibold transition-colors">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
            @error('distance') <p class="text-rose-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-3 gap-2.5">
            <div class="col-span-2">
                <label class="block text-sm font-bold text-slate-800 mb-2">{{ __('races.fields.location') }}</label>
                <input type="text" name="location" value="{{ old('location', $race->location ?? '') }}"
                       placeholder="Madrid" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-800 mb-2">País</label>
                <input type="text" name="country" value="{{ old('country', $race->country ?? '') }}"
                       placeholder="ES" maxlength="2"
                       class="input-field uppercase text-center tracking-widest font-bold">
            </div>
        </div>
    </div>

    {{-- Section: Modality & Status --}}
    <div class="px-5 py-6 space-y-5">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.18em]">Tipo y estado</p>

        <div>
            <label class="block text-sm font-bold text-slate-800 mb-3">
                {{ __('races.fields.modality') }} <span class="text-violet-500">*</span>
            </label>
            <div class="grid grid-cols-3 gap-2">
                @foreach(['road', 'trail', 'mountain', 'track', 'cross', 'other'] as $mod)
                    <label class="cursor-pointer">
                        <input type="radio" name="modality" value="{{ $mod }}" class="sr-only peer"
                               {{ old('modality', $race->modality ?? 'road') === $mod ? 'checked' : '' }}>
                        <div class="text-center text-xs py-3 px-1 rounded-2xl border-2 border-transparent bg-slate-100 font-semibold text-slate-500
                                    peer-checked:border-violet-500 peer-checked:bg-violet-50 peer-checked:text-violet-700
                                    hover:bg-slate-200 transition-colors cursor-pointer">
                            {{ __('races.modalities.' . $mod) }}
                        </div>
                    </label>
                @endforeach
            </div>
            @error('modality') <p class="text-rose-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-800 mb-3">
                {{ __('races.fields.status') }} <span class="text-violet-500">*</span>
            </label>
            <div class="grid grid-cols-2 gap-2">
                @foreach([
                    'upcoming'  => ['label' => __('races.statuses.upcoming'),  'active' => 'peer-checked:border-blue-500   peer-checked:bg-blue-50   peer-checked:text-blue-800'],
                    'completed' => ['label' => __('races.statuses.completed'), 'active' => 'peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-800'],
                    'dnf'       => ['label' => __('races.statuses.dnf'),       'active' => 'peer-checked:border-rose-500    peer-checked:bg-rose-50    peer-checked:text-rose-700'],
                    'dns'       => ['label' => __('races.statuses.dns'),       'active' => 'peer-checked:border-slate-400   peer-checked:bg-slate-100  peer-checked:text-slate-700'],
                ] as $st => $cfg)
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="{{ $st }}" class="sr-only peer"
                               {{ old('status', $race->status ?? 'upcoming') === $st ? 'checked' : '' }}>
                        <div class="text-center text-xs py-3.5 rounded-2xl border-2 border-transparent bg-slate-100 font-semibold text-slate-500
                                    {{ $cfg['active'] }} hover:bg-slate-200 transition-colors cursor-pointer">
                            {{ $cfg['label'] }}
                        </div>
                    </label>
                @endforeach
            </div>
            @error('status') <p class="text-rose-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Section: Results --}}
    <div class="px-5 py-6 space-y-5">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.18em]">Resultados</p>

        <div>
            <label class="block text-sm font-bold text-slate-800 mb-2">{{ __('races.fields.finish_time') }}</label>
            <input type="text" name="finish_time"
                   value="{{ old('finish_time', isset($race) && $race->formatted_time ? $race->formatted_time : '') }}"
                   placeholder="3:30:00"
                   class="input-field font-mono tabnum @error('finish_time') error @enderror">
            <p class="text-xs text-slate-400 mt-2">{{ __('races.fields.finish_time_hint') }}</p>
            @error('finish_time') <p class="text-rose-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-bold text-slate-800 mb-2">{{ __('races.fields.position_overall') }}</label>
                <input type="number" name="position_overall"
                       value="{{ old('position_overall', $race->position_overall ?? '') }}"
                       min="1" placeholder="125" class="input-field tabnum">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-800 mb-2">{{ __('races.fields.position_category') }}</label>
                <input type="number" name="position_category"
                       value="{{ old('position_category', $race->position_category ?? '') }}"
                       min="1" placeholder="12" class="input-field tabnum">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-bold text-slate-800 mb-2">{{ __('races.fields.category') }}</label>
                <input type="text" name="category"
                       value="{{ old('category', $race->category ?? '') }}"
                       placeholder="M40" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-800 mb-2">{{ __('races.fields.bib_number') }}</label>
                <input type="text" name="bib_number"
                       value="{{ old('bib_number', $race->bib_number ?? '') }}"
                       placeholder="456" class="input-field">
            </div>
        </div>
    </div>

    {{-- Section: Extra --}}
    <div class="px-5 py-6 space-y-5">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.18em]">Detalles adicionales</p>

        <div>
            <label class="block text-sm font-bold text-slate-800 mb-2">{{ __('races.fields.cost') }}</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm pointer-events-none">€</span>
                <input type="number" name="cost"
                       value="{{ old('cost', $race->cost ?? '') }}"
                       step="0.01" min="0" placeholder="0.00"
                       class="input-field pl-8 tabnum">
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-800 mb-2">{{ __('races.fields.website') }}</label>
            <input type="url" name="website"
                   value="{{ old('website', $race->website ?? '') }}"
                   placeholder="https://..." class="input-field @error('website') error @enderror">
            @error('website') <p class="text-rose-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-800 mb-2">{{ __('races.fields.notes') }}</label>
            <textarea name="notes" rows="3" placeholder="Sensaciones, condiciones, logística..."
                      class="input-field resize-none">{{ old('notes', $race->notes ?? '') }}</textarea>
        </div>

        <div class="flex items-center justify-between bg-slate-50 rounded-2xl px-4 py-4">
            <div>
                <p class="text-sm font-bold text-slate-800">{{ __('races.fields.is_public') }}</p>
                <p class="text-xs text-slate-400 mt-0.5">Visible en tu perfil público</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                <input type="hidden" name="is_public" value="0">
                <input type="checkbox" name="is_public" value="1" class="sr-only peer"
                       {{ old('is_public', $race->is_public ?? true) ? 'checked' : '' }}>
                <div class="w-12 h-6.5 rounded-full peer transition-colors
                            bg-slate-200 peer-checked:bg-violet-600
                            after:content-[''] after:absolute after:top-0.5 after:left-0.5
                            after:bg-white after:rounded-full after:h-5.5 after:w-5.5
                            after:transition-all after:shadow
                            peer-checked:after:translate-x-5.5"
                     style="width:48px;height:28px"
                     x-data
                     :style="'position:relative'">
                </div>
            </label>
        </div>
    </div>

    {{-- Submit --}}
    <div class="px-5 py-5">
        <button type="submit" class="btn btn-primary w-full py-4 text-base">
            {{ $isEdit ? __('races.save') : __('races.add_race') }}
        </button>
    </div>

</div>
