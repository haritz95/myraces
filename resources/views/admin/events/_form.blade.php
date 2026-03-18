{{-- Shared form fields for create & edit --}}

<div class="space-y-6">

    {{-- Identity --}}
    <div>
        <p class="section-label">Identidad</p>
        <div class="settings-group divide-y divide-white/[0.05]">

            <div class="px-5 py-4 space-y-1.5">
                <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Nombre <span class="text-red-400">*</span></label>
                <input type="text" name="name" value="{{ old('name', $event->name ?? '') }}" required maxlength="150"
                       placeholder="ej: Maratón de Madrid 2026"
                       class="input-field @error('name') error @enderror">
                @error('name')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
            </div>

            <div class="px-5 py-4 space-y-1.5">
                <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Descripción</label>
                <textarea name="description" rows="4" maxlength="2000"
                          placeholder="Historia, perfil del recorrido, premios..."
                          class="input-field resize-none">{{ old('description', $event->description ?? '') }}</textarea>
            </div>

            <div class="px-5 py-4 space-y-1.5">
                <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Imagen / Póster</label>
                @if(!empty($event->image ?? null))
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $event->image) }}" alt="" class="h-28 rounded-xl object-cover">
                        <p class="text-[10px] mt-1" style="color:rgba(255,255,255,0.30)">Subir nueva imagen reemplazará la actual.</p>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*"
                       class="input-field py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary/20 file:text-primary">
                @error('image')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
            </div>

            <div class="px-5 py-4 space-y-1.5">
                <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Organizador</label>
                <input type="text" name="organizer" value="{{ old('organizer', $event->organizer ?? '') }}" maxlength="150"
                       placeholder="ej: Rock'n'Roll Running Series"
                       class="input-field">
            </div>
        </div>
    </div>

    {{-- When & Where --}}
    <div>
        <p class="section-label">Fecha y lugar</p>
        <div class="settings-group divide-y divide-white/[0.05]">

            <div class="grid grid-cols-2 gap-4 px-5 py-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Fecha <span class="text-red-400">*</span></label>
                    <input type="datetime-local" name="event_date"
                           value="{{ old('event_date', isset($event) ? $event->event_date->format('Y-m-d\TH:i') : '') }}"
                           required class="input-field @error('event_date') error @enderror">
                    @error('event_date')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Límite inscripción</label>
                    <input type="date" name="registration_deadline"
                           value="{{ old('registration_deadline', isset($event) ? $event->registration_deadline?->format('Y-m-d') : '') }}"
                           class="input-field">
                </div>
            </div>

            <div class="px-5 py-4 space-y-1.5">
                <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Lugar / Recinto <span class="text-red-400">*</span></label>
                <input type="text" name="location" value="{{ old('location', $event->location ?? '') }}" required maxlength="150"
                       placeholder="ej: Parque del Retiro, Madrid"
                       class="input-field @error('location') error @enderror">
                @error('location')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4 px-5 py-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Provincia</label>
                    <input type="text" name="province" value="{{ old('province', $event->province ?? '') }}" maxlength="100"
                           placeholder="ej: Madrid"
                           class="input-field">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">País</label>
                    <input type="text" name="country" value="{{ old('country', $event->country ?? 'España') }}" maxlength="100"
                           class="input-field">
                </div>
            </div>
        </div>
    </div>

    {{-- Race details --}}
    <div>
        <p class="section-label">Datos de la carrera</p>
        <div class="settings-group divide-y divide-white/[0.05]">

            <div class="grid grid-cols-2 gap-4 px-5 py-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Categoría <span class="text-red-400">*</span></label>
                    <select name="category" class="input-field @error('category') error @enderror">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $event->category ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Tipo <span class="text-red-400">*</span></label>
                    <select name="race_type" class="input-field @error('race_type') error @enderror">
                        @foreach($raceTypes as $val => $label)
                            <option value="{{ $val }}" {{ old('race_type', $event->race_type ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 px-5 py-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Distancia (km)</label>
                    <input type="number" name="distance_km" value="{{ old('distance_km', $event->distance_km ?? '') }}"
                           min="0" step="0.001" placeholder="ej: 42.195"
                           class="input-field">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Precio (€)</label>
                    <input type="number" name="price" value="{{ old('price', $event->price ?? '') }}"
                           min="0" step="0.01" placeholder="0 = gratuita"
                           class="input-field">
                </div>
            </div>

            <div class="px-5 py-4 space-y-1.5">
                <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Máx. participantes</label>
                <input type="number" name="max_participants" value="{{ old('max_participants', $event->max_participants ?? '') }}"
                       min="1" placeholder="Dejar vacío si no hay límite"
                       class="input-field">
            </div>
        </div>
    </div>

    {{-- Links --}}
    <div>
        <p class="section-label">Enlaces</p>
        <div class="settings-group divide-y divide-white/[0.05]">

            <div class="px-5 py-4 space-y-1.5">
                <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Web oficial</label>
                <input type="url" name="website_url" value="{{ old('website_url', $event->website_url ?? '') }}"
                       placeholder="https://..."
                       class="input-field @error('website_url') error @enderror">
                @error('website_url')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
            </div>

            <div class="px-5 py-4 space-y-1.5">
                <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Enlace de inscripción</label>
                <input type="url" name="registration_url" value="{{ old('registration_url', $event->registration_url ?? '') }}"
                       placeholder="https://... (puede ser enlace de afiliado)"
                       class="input-field @error('registration_url') error @enderror">
                <p class="text-[10px]" style="color:rgba(255,255,255,0.25)">En el futuro este enlace puede incluir tu ID de afiliado para generar ingresos.</p>
                @error('registration_url')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- Status & visibility --}}
    <div>
        <p class="section-label">Estado</p>
        <div class="settings-group divide-y divide-white/[0.05]">

            <div class="px-5 py-4 space-y-1.5">
                <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Estado</label>
                <select name="status" class="input-field">
                    @foreach(['upcoming' => 'Próxima', 'open' => 'Inscripción abierta', 'cancelled' => 'Cancelada', 'past' => 'Finalizada'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $event->status ?? 'upcoming') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-between px-5 py-4">
                <div>
                    <p class="text-sm font-semibold text-white">Destacada</p>
                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">Aparece en la cabecera del catálogo.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" class="sr-only peer"
                           {{ old('is_featured', $event->is_featured ?? false) ? 'checked' : '' }}>
                    <div class="w-11 h-6 rounded-full peer-checked:bg-primary bg-white/20 peer-checked:after:translate-x-5 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                </label>
            </div>
        </div>
    </div>
</div>
