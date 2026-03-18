<x-app-layout>
    @section('page_title', 'Nuevo anuncio')
    @section('back_url', route('my-ads.index'))

    <div class="max-w-lg mx-auto px-5 py-6 space-y-6">

        <form method="POST" action="{{ route('my-ads.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Ad content --}}
            <div>
                <p class="section-label">Contenido del anuncio</p>
                <div class="settings-group divide-y divide-white/[0.05]">

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Título <span class="text-red-400">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required maxlength="80"
                               placeholder="ej: Maratón de Barcelona 2026"
                               class="input-field @error('title') error @enderror">
                        @error('title') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Subtítulo</label>
                        <input type="text" name="subtitle" value="{{ old('subtitle') }}" maxlength="160"
                               placeholder="ej: 8 de junio · Inscripciones abiertas"
                               class="input-field @error('subtitle') error @enderror">
                        @error('subtitle') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Imagen (opcional, máx. 2 MB)</label>
                        <input type="file" name="image" accept="image/*"
                               class="input-field text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-primary/20 file:text-primary cursor-pointer">
                        @error('image') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Texto del botón <span class="text-red-400">*</span></label>
                        <input type="text" name="cta_label" value="{{ old('cta_label', 'Ver más') }}" required maxlength="30"
                               class="input-field @error('cta_label') error @enderror">
                        @error('cta_label') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">URL de destino <span class="text-red-400">*</span></label>
                        <input type="url" name="target_url" value="{{ old('target_url') }}" required
                               placeholder="https://tu-web.com/inscripcion"
                               class="input-field @error('target_url') error @enderror">
                        @error('target_url') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Configuration --}}
            <div>
                <p class="section-label">Configuración</p>
                <div class="settings-group divide-y divide-white/[0.05]">

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Tipo de anuncio</label>
                        <select name="type" class="input-field">
                            @foreach(['race' => 'Carrera', 'product' => 'Producto', 'service' => 'Servicio', 'event' => 'Evento'] as $val => $label)
                                <option value="{{ $val }}" {{ old('type', 'race') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Dónde mostrarlo</label>
                        <select name="location" class="input-field">
                            <option value="feed" {{ old('location', 'feed') === 'feed' ? 'selected' : '' }}>Entre carreras (feed)</option>
                            <option value="dashboard" {{ old('location') === 'dashboard' ? 'selected' : '' }}>Dashboard principal</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 px-5 py-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Fecha inicio</label>
                            <input type="date" name="starts_at" value="{{ old('starts_at') }}" class="input-field @error('starts_at') error @enderror">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Fecha fin</label>
                            <input type="date" name="ends_at" value="{{ old('ends_at') }}" class="input-field @error('ends_at') error @enderror">
                        </div>
                        @error('ends_at') <p class="text-red-400 text-xs col-span-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="px-5 py-4 space-y-1.5">
                        <label class="block text-xs font-bold" style="color:rgba(255,255,255,0.50)">Máx. impresiones (0 = ilimitado)</label>
                        <input type="number" name="max_impressions" value="{{ old('max_impressions', 0) }}" min="0"
                               class="input-field @error('max_impressions') error @enderror">
                    </div>
                </div>
            </div>

            {{-- Legal notice --}}
            <div class="rounded-xl px-4 py-3.5 text-xs leading-relaxed" style="background:rgba(255,255,255,0.04);color:rgba(255,255,255,0.40);border:1px solid rgba(255,255,255,0.07)">
                Tu anuncio será revisado por nuestro equipo antes de publicarse. Se mostrará claramente como «Anuncio» cumpliendo la normativa vigente (Ley 34/2002 LSSI-CE).
            </div>

            <button type="submit" class="btn btn-primary w-full">Enviar para revisión</button>
        </form>
    </div>
</x-app-layout>
