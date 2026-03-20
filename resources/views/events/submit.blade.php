<x-app-layout>
    @section('page_title', isset($event) ? 'Editar envío' : 'Proponer carrera')
    @section('back_url', route('events.my-submissions'))

    <form method="POST"
          action="{{ isset($event) ? route('events.submission.update', $event) : route('events.submit.store') }}"
          enctype="multipart/form-data"
          class="max-w-2xl mx-auto px-4 py-6 space-y-6"
          x-data="{
              imageMode: '{{ !empty($event->image_url ?? null) ? 'url' : 'upload' }}',
              modalities: {{ json_encode(
                  isset($event) && $event->modalities->isNotEmpty()
                      ? $event->modalities->map(fn($m) => [
                          'name' => $m->name,
                          'distance_km' => $m->distance_km ?? '',
                          'category' => $m->category ?? '',
                          'price' => $m->price ?? '',
                          'registration_url' => $m->registration_url ?? '',
                      ])->values()->toArray()
                      : []
              ) }}
          }">
        @csrf
        @if(isset($event))
            @method('PATCH')
        @endif

        {{-- Info banner --}}
        <div class="rounded-2xl px-5 py-4" style="background:rgb(var(--color-primary) / 0.08);border:1px solid rgb(var(--color-primary) / 0.20)">
            <p class="text-sm font-black text-primary mb-1">Propón una carrera a la comunidad</p>
            <p class="text-xs" style="color:rgba(255,255,255,0.50)">
                Nuestro equipo revisará tu envío antes de publicarlo. Te avisaremos cuando esté aprobado.
            </p>
        </div>

        {{-- Identity --}}
        <div>
            <p class="section-label">Información básica</p>
            <div class="settings-group divide-y divide-white/[0.05]">

                <div class="px-5 py-4 space-y-1.5">
                    <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Nombre de la carrera <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $event->name ?? '') }}" required maxlength="150"
                           placeholder="ej: Maratón de Bilbao 2026"
                           class="input-field @error('name') error @enderror">
                    @error('name')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
                </div>

                <div class="px-5 py-4 space-y-1.5">
                    <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Organizador</label>
                    <input type="text" name="organizer" value="{{ old('organizer', $event->organizer ?? '') }}" maxlength="150"
                           placeholder="ej: Club Atletismo Bilbao"
                           class="input-field">
                </div>

                <div class="px-5 py-4 space-y-1.5">
                    <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Descripción</label>
                    <textarea name="description" rows="3" maxlength="2000"
                              placeholder="Cuéntanos algo sobre la carrera..."
                              class="input-field resize-none">{{ old('description', $event->description ?? '') }}</textarea>
                </div>

                {{-- Image --}}
                <div class="px-5 py-4 space-y-3">
                    <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Imagen / Póster</label>
                    @if(isset($event) && $event->imageSource())
                        <img src="{{ $event->imageSource() }}" alt="" class="h-24 rounded-xl object-cover">
                    @endif
                    <div class="flex rounded-xl overflow-hidden w-fit" style="border:1px solid rgba(255,255,255,0.10)">
                        <button type="button" @click="imageMode='upload'"
                                class="px-4 py-1.5 text-xs font-bold transition-colors"
                                :style="imageMode==='upload' ? 'background:rgb(var(--color-primary) / 0.15);color:rgb(var(--color-primary))' : 'color:rgba(255,255,255,0.40)'">
                            Subir archivo
                        </button>
                        <button type="button" @click="imageMode='url'"
                                class="px-4 py-1.5 text-xs font-bold transition-colors"
                                :style="imageMode==='url' ? 'background:rgb(var(--color-primary) / 0.15);color:rgb(var(--color-primary))' : 'color:rgba(255,255,255,0.40)'">
                            URL externa
                        </button>
                    </div>
                    <div x-show="imageMode==='upload'" x-cloak>
                        <input type="file" name="image" accept="image/*"
                               class="input-field py-2 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary/20 file:text-primary">
                    </div>
                    <div x-show="imageMode==='url'" x-cloak>
                        <input type="url" name="image_url" value="{{ old('image_url', $event->image_url ?? '') }}"
                               placeholder="https://ejemplo.com/cartel.jpg"
                               class="input-field">
                    </div>
                </div>
            </div>
        </div>

        {{-- Date & location --}}
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
                    <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Lugar / Ciudad <span class="text-red-400">*</span></label>
                    <input type="text" name="location" value="{{ old('location', $event->location ?? '') }}" required maxlength="150"
                           placeholder="ej: Bilbao"
                           class="input-field @error('location') error @enderror">
                    @error('location')<p class="text-red-400 text-xs">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4 px-5 py-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Provincia</label>
                        <input type="text" name="province" value="{{ old('province', $event->province ?? '') }}" maxlength="100"
                               placeholder="ej: Vizcaya" class="input-field">
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
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Tipo <span class="text-red-400">*</span></label>
                        <select name="race_type" class="input-field">
                            @foreach($raceTypes as $val => $label)
                                <option value="{{ $val }}" {{ old('race_type', $event->race_type ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Categoría principal</label>
                        <select name="category" class="input-field">
                            <option value="">— Sin especificar —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $event->category ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 px-5 py-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Distancia (km)</label>
                        <input type="number" name="distance_km" value="{{ old('distance_km', $event->distance_km ?? '') }}"
                               min="0" step="0.001" placeholder="ej: 42.195" class="input-field">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Precio (€)</label>
                        <input type="number" name="price" value="{{ old('price', $event->price ?? '') }}"
                               min="0" step="0.01" placeholder="0 = gratis" class="input-field">
                    </div>
                </div>

                <div class="px-5 py-4 space-y-1.5">
                    <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Web oficial</label>
                    <input type="url" name="website_url" value="{{ old('website_url', $event->website_url ?? '') }}"
                           placeholder="https://..." class="input-field">
                </div>

                <div class="px-5 py-4 space-y-1.5">
                    <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Enlace de inscripción</label>
                    <input type="url" name="registration_url" value="{{ old('registration_url', $event->registration_url ?? '') }}"
                           placeholder="https://..." class="input-field">
                </div>
            </div>
        </div>

        {{-- Modalities --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <p class="section-label mb-0">Modalidades <span class="text-white/30 font-normal text-xs">(opcional)</span></p>
                <button type="button"
                        @click="modalities.push({name:'',distance_km:'',category:'',price:'',registration_url:''})"
                        class="flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-xl bg-primary/15 text-primary">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Añadir modalidad
                </button>
            </div>
            <p class="text-xs mb-3" style="color:rgba(255,255,255,0.35)">Si la carrera tiene varias distancias, añádelas aquí.</p>

            <div class="space-y-3">
                <template x-for="(mod, index) in modalities" :key="index">
                    <div class="settings-group overflow-hidden" x-data="{ expanded: !mod.name }">
                        <div class="flex items-center justify-between px-4 py-3 cursor-pointer" @click="expanded = !expanded">
                            <div class="flex items-center gap-2 min-w-0 flex-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0 transition-transform text-white/30"
                                     :class="expanded ? 'rotate-180' : ''"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                                <span class="text-sm font-black text-white truncate flex-1"
                                      x-text="mod.name || 'Nueva modalidad'"></span>
                                <span x-show="!expanded && mod.distance_km" class="text-[10px] font-bold text-primary flex-shrink-0"
                                      x-text="mod.distance_km + ' km'"></span>
                            </div>
                            <button type="button" @click.stop="modalities.splice(index,1)"
                                    class="flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-lg ml-2"
                                    style="background:rgba(248,113,113,0.15);color:#f87171">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        <div x-show="expanded" x-collapse class="border-t px-4 pb-4 pt-3 space-y-3" style="border-color:rgba(255,255,255,0.06)">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold" style="color:rgba(255,255,255,0.40)">Nombre <span class="text-red-400">*</span></label>
                                <input type="text" :name="'modalities['+index+'][name]'" x-model="mod.name"
                                       placeholder="ej: 10K, Media Maratón..." class="input-field text-sm">
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold" style="color:rgba(255,255,255,0.40)">Distancia (km)</label>
                                    <input type="number" :name="'modalities['+index+'][distance_km]'" x-model="mod.distance_km"
                                           min="0" step="0.001" class="input-field text-sm py-2">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold" style="color:rgba(255,255,255,0.40)">Precio (€)</label>
                                    <input type="number" :name="'modalities['+index+'][price]'" x-model="mod.price"
                                           min="0" step="0.01" placeholder="0 = gratis" class="input-field text-sm py-2">
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold" style="color:rgba(255,255,255,0.40)">Enlace de inscripción</label>
                                <input type="url" :name="'modalities['+index+'][registration_url]'" x-model="mod.registration_url"
                                       placeholder="https://..." class="input-field text-sm py-2">
                            </div>
                        </div>
                    </div>
                </template>

                <template x-if="modalities.length === 0">
                    <p class="text-xs py-2 px-1" style="color:rgba(255,255,255,0.25)">Sin modalidades adicionales.</p>
                </template>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-full py-3.5 text-sm font-black">
            {{ isset($event) ? 'Actualizar envío' : 'Enviar para revisión' }}
        </button>
    </form>
</x-app-layout>
