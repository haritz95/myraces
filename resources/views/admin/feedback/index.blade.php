<x-app-layout>
    @section('page_title', 'Feedback')
    @section('back_url', route('admin.dashboard'))

    <div class="max-w-2xl mx-auto px-4 py-5 space-y-3">

        @if($feedbacks->isEmpty())
            <div class="card px-6 py-16 text-center space-y-3">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center mx-auto">
                    <svg class="w-7 h-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-white font-black text-lg">Sin feedback</p>
                <p class="text-sm" style="color:rgba(255,255,255,0.40)">Aún no has recibido ningún mensaje.</p>
            </div>
        @else
            @foreach($feedbacks as $item)
                @php
                    $typeColor = match($item->type) {
                        'bug'        => ['bg' => 'rgba(248,113,113,0.12)', 'text' => '#f87171', 'label' => 'Bug'],
                        'suggestion' => ['bg' => 'rgba(96,165,250,0.12)',  'text' => '#60a5fa', 'label' => 'Sugerencia'],
                        default      => ['bg' => 'rgba(255,255,255,0.08)', 'text' => 'rgba(255,255,255,0.5)', 'label' => 'Otro'],
                    };
                @endphp
                <div class="card overflow-hidden">
                    <div class="px-4 py-3 flex items-start gap-3">
                        <span class="text-xs font-black px-2.5 py-1 rounded-full flex-shrink-0 mt-0.5"
                              style="background:{{ $typeColor['bg'] }};color:{{ $typeColor['text'] }}">
                            {{ $typeColor['label'] }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-white leading-relaxed">{{ $item->message }}</p>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-2">
                                <span class="text-xs" style="color:rgba(255,255,255,0.35)">
                                    {{ $item->user?->name ?? 'Usuario eliminado' }}
                                </span>
                                <span class="text-xs" style="color:rgba(255,255,255,0.20)">·</span>
                                <span class="text-xs" style="color:rgba(255,255,255,0.35)">
                                    {{ $item->created_at->diffForHumans() }}
                                </span>
                                @if($item->url)
                                    <span class="text-xs" style="color:rgba(255,255,255,0.20)">·</span>
                                    <span class="text-xs truncate max-w-[180px]" style="color:rgba(255,255,255,0.30)"
                                          title="{{ $item->url }}">{{ $item->url }}</span>
                                @endif
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.feedback.destroy', $item) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg flex-shrink-0 transition-colors"
                                    style="color:rgba(255,255,255,0.25)" onmouseover="this.style.color='#f87171';this.style.background='rgba(248,113,113,0.1)'" onmouseout="this.style.color='rgba(255,255,255,0.25)';this.style.background='transparent'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="pt-2">{{ $feedbacks->links() }}</div>
        @endif
    </div>
</x-app-layout>
