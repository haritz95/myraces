<x-app-layout>
    @section('page_title', 'RaceCoach IA')

    {{-- Chat container: fixed height so messages scroll and input stays pinned --}}
    <div class="coach-chat flex flex-col max-w-2xl mx-auto w-full"
         x-data="{
             messages: [
                 { role: 'coach', text: '¡Hola! Soy RaceCoach, tu entrenador IA personal. Puedo analizar tus récords, predecir tiempos de carrera y ayudarte a optimizar tu rendimiento. ¿En qué te puedo ayudar hoy?' }
             ],
             input: '',
             loading: false,
             async sendMessage() {
                 if (!this.input.trim() || this.loading) return;
                 const msg = this.input.trim();
                 this.input = '';
                 this.messages.push({ role: 'user', text: msg });
                 this.loading = true;
                 this.$nextTick(() => this.$refs.chatEnd?.scrollIntoView({ behavior: 'smooth' }));
                 try {
                     const res = await fetch('{{ route('coach.chat') }}', {
                         method: 'POST',
                         headers: {
                             'Content-Type': 'application/json',
                             'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                             'Accept': 'application/json',
                         },
                         body: JSON.stringify({ message: msg })
                     });
                     const data = await res.json();
                     this.messages.push({ role: 'coach', text: data.response });
                 } catch(e) {
                     this.messages.push({ role: 'coach', text: 'Hubo un error al conectar. Por favor, inténtalo de nuevo.' });
                 } finally {
                     this.loading = false;
                     this.$nextTick(() => this.$refs.chatEnd?.scrollIntoView({ behavior: 'smooth' }));
                 }
             }
         }">

        {{-- Scrollable area --}}
        <div class="flex-1 overflow-y-auto min-h-0">

            {{-- Coach Hero --}}
            <div class="px-5 pt-6 pb-4">
                <div class="relative overflow-hidden rounded-3xl p-5 mb-5"
                     style="background:linear-gradient(135deg,#0f1a00 0%,#1a2d00 50%,#253d00 100%);border:1px solid rgb(var(--color-primary) / 0.15)">
                    <div class="absolute inset-0 opacity-[0.05]" style="background-image:radial-gradient(circle at 20% 80%, rgb(var(--color-primary)) 1px, transparent 1px);background-size:30px 30px"></div>
                    <div class="relative flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0"
                             style="background:rgb(var(--color-primary) / 0.12);border:1px solid rgb(var(--color-primary) / 0.25)">
                            <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-black text-lg leading-tight">RaceCoach</p>
                            <p class="text-sm" style="color:rgba(255,255,255,0.45)">Tu entrenador personal con IA</p>
                        </div>
                    </div>
                    <div class="relative grid grid-cols-3 gap-2">
                        <div class="rounded-2xl p-3 text-center" style="background:rgba(255,255,255,0.07)">
                            <p class="text-white font-black text-xl leading-none">{{ $races->count() }}</p>
                            <p class="text-[11px] mt-1 font-bold" style="color:rgba(255,255,255,0.40)">Carreras</p>
                        </div>
                        <div class="rounded-2xl p-3 text-center" style="background:rgba(255,255,255,0.07)">
                            <p class="text-white font-black text-xl leading-none">{{ $personalRecords->count() }}</p>
                            <p class="text-[11px] mt-1 font-bold" style="color:rgba(255,255,255,0.40)">Récords</p>
                        </div>
                        <div class="rounded-2xl p-3 text-center" style="background:rgba(255,255,255,0.07)">
                            <p class="text-white font-black text-xl leading-none tabnum">{{ number_format((float)$totalExpenses, 0) }}€</p>
                            <p class="text-[11px] mt-1 font-bold" style="color:rgba(255,255,255,0.40)">Invertido</p>
                        </div>
                    </div>
                </div>

                {{-- Quick chips --}}
                <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                    @foreach(['Analiza mis récords', 'Predice mi tiempo en 10K', 'Optimiza mis gastos', '¿Cuándo cambiar zapatillas?'] as $chip)
                        <button @click="input = '{{ $chip }}'; sendMessage()"
                                class="flex-shrink-0 text-xs font-bold px-3.5 py-2 rounded-full whitespace-nowrap transition-colors"
                                style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.60);border:1px solid rgba(255,255,255,0.10)"
                                onmouseover="this.style.background='rgb(var(--color-primary) / 0.12)';this.style.color='rgb(var(--color-primary))';this.style.borderColor='rgb(var(--color-primary) / 0.25)'"
                                onmouseout="this.style.background='rgba(255,255,255,0.07)';this.style.color='rgba(255,255,255,0.60)';this.style.borderColor='rgba(255,255,255,0.10)'">
                            {{ $chip }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Messages --}}
            <div class="px-5 space-y-4 pb-4">
                <template x-for="(msg, i) in messages" :key="i">
                    <div :class="msg.role === 'coach' ? 'flex gap-3' : 'flex gap-3 flex-row-reverse'">
                        <div x-show="msg.role === 'coach'"
                             class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1"
                             style="background:rgb(var(--color-primary) / 0.12);border:1px solid rgb(var(--color-primary) / 0.20)">
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div :class="msg.role === 'coach'
                                ? 'rounded-3xl rounded-tl-md px-4 py-3 text-sm max-w-[85%]'
                                : 'rounded-3xl rounded-tr-md px-4 py-3 text-sm max-w-[85%] text-black font-semibold'"
                             :style="msg.role === 'coach'
                                ? 'background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.85);border:1px solid rgba(255,255,255,0.08)'
                                : 'background:rgb(var(--color-primary))'"
                             x-text="msg.text">
                        </div>
                    </div>
                </template>

                {{-- Typing indicator --}}
                <div x-show="loading" class="flex gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                         style="background:rgb(var(--color-primary) / 0.12);border:1px solid rgb(var(--color-primary) / 0.20)">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="rounded-3xl rounded-tl-md px-4 py-3 flex items-center gap-1.5" style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.08)">
                        <span class="w-2 h-2 rounded-full animate-bounce bg-primary" style="animation-delay:0ms"></span>
                        <span class="w-2 h-2 rounded-full animate-bounce bg-primary" style="animation-delay:150ms"></span>
                        <span class="w-2 h-2 rounded-full animate-bounce bg-primary" style="animation-delay:300ms"></span>
                    </div>
                </div>

                <div x-ref="chatEnd"></div>
            </div>
        </div>

        {{-- Input bar — always pinned at bottom --}}
        <div class="flex-shrink-0 px-5 py-3" style="background:#0a0a0a;border-top:1px solid rgba(255,255,255,0.07)">
            <div class="flex items-center gap-2">
                <input type="text" x-model="input"
                       @keydown.enter="sendMessage()"
                       placeholder="Pregúntame sobre tu rendimiento…"
                       class="flex-1 input-field">
                <button @click="sendMessage()" :disabled="loading || !input.trim()"
                        class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 text-black font-black transition-all disabled:opacity-30 bg-primary active:scale-95"
                        style="box-shadow:0 4px 12px rgb(var(--color-primary) / 0.35)">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </div>
        </div>

    </div>

    <style>
        /* Mobile: fill space between header (58px) and bottom nav (72px) */
        .coach-chat { height: calc(100dvh - 58px - 72px); }
        /* Desktop: fill space below the top header bar (60px), no bottom nav */
        @media (min-width: 768px) { .coach-chat { height: calc(100dvh - 60px); } }
    </style>

</x-app-layout>
