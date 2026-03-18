<x-app-layout>
    @section('page_title', 'RaceCoach IA')

    <main class="flex-1 overflow-y-auto max-w-2xl mx-auto w-full pb-[76px]"
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

        {{-- Coach Hero --}}
        <div class="px-4 pt-6 pb-4">
            <div class="relative overflow-hidden rounded-2xl p-5 mb-5" style="background: linear-gradient(135deg, #221610 0%, #7c2d12 40%, #ec5b13 100%)">
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 20% 80%, white 1px, transparent 1px); background-size: 30px 30px"></div>
                <div class="relative flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center flex-shrink-0" style="box-shadow: inset 0 1px 0 rgba(255,255,255,0.2)">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold text-lg leading-tight">RaceCoach</p>
                        <p class="text-white/60 text-sm">Tu entrenador personal con IA</p>
                    </div>
                </div>
                <div class="relative mt-4 grid grid-cols-3 gap-2">
                    <div class="bg-white/10 rounded-xl p-2.5 text-center">
                        <p class="text-white font-bold text-lg leading-none">{{ $races->count() }}</p>
                        <p class="text-white/60 text-[11px] mt-0.5">Carreras</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-2.5 text-center">
                        <p class="text-white font-bold text-lg leading-none">{{ $personalRecords->count() }}</p>
                        <p class="text-white/60 text-[11px] mt-0.5">Récords</p>
                    </div>
                    <div class="bg-white/10 rounded-xl p-2.5 text-center">
                        <p class="text-white font-bold text-lg leading-none">{{ number_format((float)$totalExpenses, 0) }}€</p>
                        <p class="text-white/60 text-[11px] mt-0.5">Invertido</p>
                    </div>
                </div>
            </div>

            {{-- Quick chips --}}
            <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-none">
                @foreach(['Analiza mis récords', 'Predice mi tiempo en 10K', 'Optimiza mis gastos', '¿Cuándo cambiar zapatillas?'] as $chip)
                    <button @click="input = '{{ $chip }}'; sendMessage()"
                            class="flex-shrink-0 text-xs font-medium px-3 py-2 bg-white border border-slate-200 rounded-full text-slate-600 hover:border-primary hover:text-primary transition-colors whitespace-nowrap">
                        {{ $chip }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Chat area --}}
        <div class="px-4 space-y-4 mb-4 min-h-[200px]">
            <template x-for="(msg, i) in messages" :key="i">
                <div :class="msg.role === 'coach' ? 'flex gap-3' : 'flex gap-3 flex-row-reverse'">
                    <div x-show="msg.role === 'coach'" class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div :class="msg.role === 'coach'
                        ? 'bg-white border border-slate-100 rounded-2xl rounded-tl-sm px-4 py-3 text-sm text-slate-800 max-w-[85%] shadow-sm'
                        : 'bg-primary text-white rounded-2xl rounded-tr-sm px-4 py-3 text-sm max-w-[85%]'"
                         x-text="msg.text">
                    </div>
                </div>
            </template>

            {{-- Typing indicator --}}
            <div x-show="loading" class="flex gap-3">
                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm flex items-center gap-1">
                    <span class="w-2 h-2 bg-slate-300 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                    <span class="w-2 h-2 bg-slate-300 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                    <span class="w-2 h-2 bg-slate-300 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                </div>
            </div>

            <div x-ref="chatEnd"></div>
        </div>

        {{-- Input area --}}
        <div class="sticky bottom-[76px] bg-bg-warm/95 backdrop-blur-sm border-t border-slate-200 px-4 py-3">
            <div class="flex items-center gap-2">
                <input type="text" x-model="input"
                       @keydown.enter="sendMessage()"
                       placeholder="Pregúntame sobre tu rendimiento…"
                       class="flex-1 px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                <button @click="sendMessage()" :disabled="loading || !input.trim()"
                        class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center text-white disabled:opacity-40 transition-opacity flex-shrink-0"
                        style="box-shadow: 0 4px 12px rgba(236,91,19,0.35)">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </div>
        </div>

    </main>
</x-app-layout>
