<x-app-layout>
    @section('page_title', $pod->name)
    @section('back_url', route('pods.index'))

    @php
        $statusGradient = match($pod->status) {
            'completed' => 'from-amber-500/20 via-amber-500/5 to-transparent',
            'archived'  => 'from-white/5 via-transparent to-transparent',
            default     => 'from-primary/15 via-primary/5 to-transparent',
        };
        $accentColor = match($pod->status) {
            'completed' => '#f59e0b',
            'archived'  => 'rgba(255,255,255,0.40)',
            default     => '#C8FA5F',
        };
        $topPoints = $members->first()?->pivot->points ?? 0;
    @endphp

    @section('header_action')
        @if($isMember)
            <form method="POST" action="{{ route('pods.leave', $pod) }}"
                  onsubmit="return confirm('¿Salir del Pod «{{ $pod->name }}»?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-xl"
                        style="background:rgba(248,113,113,0.10);color:#f87171">Salir</button>
            </form>
        @elseif(!$isFull && $pod->status === 'active')
            <form method="POST" action="{{ route('pods.join', $pod) }}">
                @csrf
                <button type="submit" class="btn btn-primary text-xs py-2">Unirse</button>
            </form>
        @endif
    @endsection

    <div class="max-w-2xl mx-auto px-4 py-6 space-y-5">

        @if(session('success'))
            <div class="card px-4 py-3 text-sm font-semibold text-green-400 bg-green-500/10 border-green-500/20">{{ session('success') }}</div>
        @endif

        {{-- Pod hero card --}}
        <div class="relative overflow-hidden rounded-2xl px-5 py-5"
             style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);backdrop-filter:blur(16px);box-shadow:0 16px 48px rgba(0,0,0,0.35)">
            <div class="absolute inset-0 bg-gradient-to-br {{ $statusGradient }} pointer-events-none rounded-2xl"></div>

            <div class="relative">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2 h-2 rounded-full" style="background:{{ $accentColor }}"></span>
                            <span class="text-[10px] font-black uppercase tracking-widest" style="color:{{ $accentColor }}">
                                {{ $pod->statusLabel() }}
                            </span>
                        </div>
                        <p class="text-white font-black text-xl leading-tight">{{ $pod->name }}</p>
                        <p class="text-sm mt-0.5" style="color:rgba(255,255,255,0.50)">{{ $pod->goal }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-2xl font-black" style="color:{{ $accentColor }}">{{ $members->count() }}/{{ $pod->max_members }}</p>
                        <p class="text-[10px]" style="color:rgba(255,255,255,0.35)">miembros</p>
                    </div>
                </div>

                @if($pod->description)
                    <p class="text-xs mb-3" style="color:rgba(255,255,255,0.40)">{{ $pod->description }}</p>
                @endif

                @if($pod->target_distance)
                    @php $pct = min(100, $pod->progressPercent()); @endphp
                    <div>
                        <div class="flex justify-between text-[10px] mb-1.5" style="color:rgba(255,255,255,0.40)">
                            <span>Progreso del equipo · {{ $pod->target_distance }} {{ $pod->target_unit }}</span>
                            <span class="font-black text-white">{{ $pct }}%</span>
                        </div>
                        <div class="h-2 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.08)">
                            <div class="h-full rounded-full transition-all duration-1000 relative overflow-hidden"
                                 style="width:{{ $pct }}%;background:{{ $accentColor }}">
                                <div class="absolute inset-0 animate-pulse opacity-50" style="background:linear-gradient(90deg,transparent 0%,rgba(255,255,255,0.4) 50%,transparent 100%)"></div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($pod->ends_at)
                    <p class="text-[10px] mt-3" style="color:rgba(255,255,255,0.25)">
                        Termina {{ $pod->ends_at->diffForHumans() }} · {{ $pod->ends_at->format('d/m/Y') }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Leaderboard --}}
        <div>
            <p class="section-label mb-3">Clasificación</p>
            <div class="card overflow-hidden">
                @foreach($members as $i => $member)
                    @php
                        $pts = $member->pivot->points ?? 0;
                        $pct = $topPoints > 0 ? round($pts / $topPoints * 100) : 0;
                        $isMe = $member->id === auth()->id();
                        $medals = ['1.', '2.', '3.'];
                    @endphp
                    <div class="px-4 py-3.5 {{ !$loop->last ? 'border-b' : '' }}"
                         style="{{ !$loop->last ? 'border-color:rgba(255,255,255,0.05)' : '' }}">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-base w-6 flex-shrink-0 text-center">
                                {{ $medals[$i] ?? ($i + 1) . '.' }}
                            </span>
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 font-black text-sm text-black"
                                 style="background:{{ $isMe ? '#C8FA5F' : 'rgba(255,255,255,0.15)' }};{{ $isMe ? '' : 'color:rgba(255,255,255,0.70)' }}">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-bold {{ $isMe ? 'text-primary' : 'text-white' }} truncate">
                                        {{ $member->name }}{{ $isMe ? ' (tú)' : '' }}
                                    </p>
                                    @if($member->pivot->role === 'leader')
                                        <span class="text-[9px] font-black px-1.5 py-0.5 rounded-full bg-primary/20 text-primary leading-none">LÍDER</span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-sm font-black tabnum flex-shrink-0" style="color:{{ $accentColor }}">
                                {{ number_format($pts) }} pts
                            </span>
                        </div>
                        {{-- Animated progress bar --}}
                        <div class="ml-9 h-1 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06)">
                            <div class="h-full rounded-full transition-all duration-700"
                                 x-data
                                 x-init="$el.style.width='0%'; setTimeout(()=>$el.style.width='{{ $pct }}%', {{ $loop->index * 120 + 200 }})"
                                 style="width:0%;background:{{ $isMe ? '#C8FA5F' : 'rgba(255,255,255,0.25)' }};transition:width 0.8s cubic-bezier(0.4,0,0.2,1)">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Your streak in this pod --}}
        @if($isMember && $streak)
        <div class="rounded-2xl px-5 py-4 flex items-center gap-4"
             style="background:rgba(200,250,95,0.06);border:1px solid rgba(200,250,95,0.12);backdrop-filter:blur(8px)">
            <div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-black text-white">Tu racha: <span class="text-primary">{{ $streak->current_streak }} días</span></p>
                <p class="text-xs" style="color:rgba(255,255,255,0.45)">
                    Multiplicador ×{{ number_format($streak->multiplier(), 2) }} ·
                    Tus puntos en este Pod: <span class="text-white font-bold">{{ number_format($userPoints) }}</span>
                </p>
            </div>
        </div>
        @endif

        {{-- Live feed --}}
        @if($isMember)
        <div
            x-data="{
                messages: {{ Js::from($messages) }},
                newMessage: '',
                sending: false,
                lastId: {{ $messages->last()?->id ?? 0 }},
                pollInterval: null,

                init() {
                    this.pollInterval = setInterval(() => this.poll(), 5000);
                },

                async poll() {
                    try {
                        const res = await fetch('{{ route('pods.messages', $pod) }}?since=' + this.lastId, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const data = await res.json();
                        if (data.length > 0) {
                            this.messages.push(...data);
                            this.lastId = data[data.length - 1].id;
                            this.$nextTick(() => this.scrollToBottom());
                            // Haptic feedback on new message from others
                            if (navigator.vibrate) { navigator.vibrate([30, 20, 30]); }
                        }
                    } catch(e) {}
                },

                async send() {
                    if (!this.newMessage.trim() || this.sending) return;
                    this.sending = true;
                    try {
                        const res = await fetch('{{ route('pods.messages.store', $pod) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ message: this.newMessage })
                        });
                        const msg = await res.json();
                        this.messages.push(msg);
                        this.lastId = msg.id;
                        this.newMessage = '';
                        this.$nextTick(() => this.scrollToBottom());
                    } catch(e) {}
                    this.sending = false;
                },

                scrollToBottom() {
                    const el = this.$refs.feed;
                    if (el) el.scrollTop = el.scrollHeight;
                },

                msgStyle(type) {
                    if (type === 'system') return 'text-center text-[11px] py-1.5 px-3 rounded-full mx-auto';
                    if (type === 'celebration') return 'w-full text-center text-xs py-2 px-4 rounded-2xl';
                    return '';
                }
            }"
            x-init="$nextTick(() => scrollToBottom())"
        >
            <div class="flex items-center justify-between mb-3">
                <p class="section-label">Feed del Pod</p>
                <span class="flex items-center gap-1.5 text-[10px] font-bold" style="color:rgba(255,255,255,0.30)">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                    En vivo
                </span>
            </div>

            {{-- Messages --}}
            <div class="card overflow-hidden">
                <div x-ref="feed" class="overflow-y-auto px-4 py-3 space-y-2.5" style="max-height:360px;min-height:180px">
                    <template x-for="msg in messages" :key="msg.id">
                        <div>
                            {{-- System / celebration messages --}}
                            <template x-if="msg.type === 'system'">
                                <div class="flex justify-center">
                                    <span class="text-[11px] px-3 py-1 rounded-full"
                                          style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.40)"
                                          x-text="msg.message"></span>
                                </div>
                            </template>
                            <template x-if="msg.type === 'celebration'">
                                <div class="rounded-2xl px-4 py-3 text-center"
                                     style="background:linear-gradient(135deg,rgba(200,250,95,0.12),rgba(200,250,95,0.05));border:1px solid rgba(200,250,95,0.20)">
                                    <p class="text-xs font-bold text-primary" x-text="msg.message"></p>
                                </div>
                            </template>
                            {{-- Regular text messages --}}
                            <template x-if="msg.type === 'text'">
                                <div class="flex items-start gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0 text-xs font-black text-white"
                                         x-text="msg.user ? msg.user.name.charAt(0).toUpperCase() : '?'"></div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-baseline gap-2 mb-0.5">
                                            <span class="text-xs font-bold text-white" x-text="msg.user?.name ?? 'Sistema'"></span>
                                            <span class="text-[10px]" style="color:rgba(255,255,255,0.25)" x-text="msg.created_at"></span>
                                        </div>
                                        <div class="inline-block rounded-2xl rounded-tl-sm px-3 py-2 text-xs"
                                             style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.85)"
                                             x-text="msg.message"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="messages.length === 0">
                        <div class="py-8 text-center">
                            <p class="text-sm" style="color:rgba(255,255,255,0.30)">Sé el primero en escribir algo</p>
                        </div>
                    </template>
                </div>

                {{-- Input --}}
                <div class="px-4 py-3" style="border-top:1px solid rgba(255,255,255,0.07)">
                    <div class="flex items-center gap-2">
                        <input type="text"
                               x-model="newMessage"
                               @keydown.enter.prevent="send()"
                               placeholder="Escribe un mensaje..."
                               maxlength="500"
                               class="input-field text-sm py-2.5 flex-1"
                               :disabled="sending">
                        <button @click="send()"
                                :disabled="sending || !newMessage.trim()"
                                class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-all"
                                :class="newMessage.trim() ? 'bg-primary text-black' : 'bg-white/[0.06] text-white/30'">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="card px-5 py-6 text-center">
            <p class="text-sm font-bold text-white mb-1">Feed privado del Pod</p>
            <p class="text-xs" style="color:rgba(255,255,255,0.40)">Únete para ver y participar en el feed del equipo.</p>
        </div>
        @endif

    </div>
</x-app-layout>
