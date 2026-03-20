{{-- Floating feedback widget --}}
<style>
    #fb-modal {
        display: none;
        position: fixed;
        background: #141414;
        border: 1px solid rgba(255,255,255,0.10);
        box-shadow: 0 24px 60px rgba(0,0,0,0.7);
        z-index: 10000;
        overflow: hidden;
    }
    /* Desktop: popover above the button */
    @media (min-width: 480px) {
        #fb-modal {
            bottom: 5rem;
            right: 1.25rem;
            width: 300px;
            border-radius: 1rem;
        }
    }
    /* Mobile: bottom sheet full-width */
    @media (max-width: 479px) {
        #fb-modal {
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            border-radius: 1rem 1rem 0 0;
            border-bottom: none;
            max-height: 90dvh;
            overflow-y: auto;
        }
    }
    #fb-widget {
        position: fixed;
        bottom: 5rem; /* above bottom nav */
        right: 1.25rem;
        z-index: 9999;
    }
    /* Desktop: button lower */
    @media (min-width: 480px) {
        #fb-widget { bottom: 1.25rem; }
    }
    /* Prevent iOS zoom — all inputs must be ≥16px */
    #fb-form textarea,
    #fb-form input[type="text"],
    #fb-form input[type="email"],
    #fb-form select {
        font-size: 16px !important;
    }
    #fb-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9998;
    }
    @media (min-width: 480px) {
        #fb-overlay { display: none !important; }
    }
</style>

{{-- Overlay (mobile only) --}}
<div id="fb-overlay" onclick="fbClose()"></div>

{{-- Modal --}}
<div id="fb-modal">
    {{-- Handle (mobile drag hint) --}}
    <div class="sm:hidden" style="padding:.6rem 0 .3rem;display:flex;justify-content:center">
        <div style="width:36px;height:4px;border-radius:2px;background:rgba(255,255,255,0.15)"></div>
    </div>

    {{-- Header --}}
    <div style="padding:.75rem 1rem;border-bottom:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;justify-content:space-between">
        <span style="font-size:.9rem;font-weight:800;color:#fff">Enviar feedback</span>
        <button onclick="fbClose()" style="width:28px;height:28px;border:none;background:rgba(255,255,255,0.06);cursor:pointer;color:rgba(255,255,255,0.5);display:flex;align-items:center;justify-content:center;border-radius:7px">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- Form --}}
    <form id="fb-form" onsubmit="fbSubmit(event)" style="padding:1rem;display:flex;flex-direction:column;gap:.75rem">
        @csrf

        {{-- Type selector --}}
        <div style="display:flex;gap:.5rem">
            @foreach(['bug' => ['🐛','Bug'], 'suggestion' => ['💡','Sugerencia'], 'other' => ['💬','Otro']] as $val => [$emoji, $label])
                <label style="flex:1;cursor:pointer">
                    <input type="radio" name="type" value="{{ $val }}" {{ $val === 'suggestion' ? 'checked' : '' }}
                           onchange="fbTypeChange(this)"
                           style="position:absolute;opacity:0;pointer-events:none;width:0;height:0">
                    <span class="fb-type-btn" data-val="{{ $val }}"
                          style="display:flex;flex-direction:column;align-items:center;gap:3px;padding:.6rem .25rem;border-radius:.6rem;
                                 border:1px solid {{ $val === 'suggestion' ? 'rgb(var(--color-primary) / 0.5)' : 'rgba(255,255,255,0.08)' }};
                                 background:{{ $val === 'suggestion' ? 'rgb(var(--color-primary) / 0.08)' : 'transparent' }};
                                 transition:all .15s;font-size:.72rem;font-weight:700;
                                 color:{{ $val === 'suggestion' ? 'rgb(var(--color-primary))' : 'rgba(255,255,255,0.45)' }}">
                        <span style="font-size:1.15rem;line-height:1">{{ $emoji }}</span>
                        {{ $label }}
                    </span>
                </label>
            @endforeach
        </div>

        {{-- Message --}}
        <textarea name="message" id="fb-message" rows="4" required maxlength="1000"
                  placeholder="Describe el problema o sugerencia..."
                  style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);
                         border-radius:.6rem;padding:.7rem .75rem;color:#e5e2e1;font-size:16px;
                         resize:none;outline:none;font-family:inherit;line-height:1.5;-webkit-appearance:none"
                  onfocus="this.style.borderColor='rgb(var(--color-primary) / 0.4)'"
                  onblur="this.style.borderColor='rgba(255,255,255,0.09)'"></textarea>

        <input type="hidden" name="url" value="">

        {{-- Submit --}}
        <button type="submit" id="fb-submit"
                style="width:100%;background:rgb(var(--color-primary));color:#0a0a0a;font-weight:800;
                       font-size:1rem;padding:.7rem;border:none;border-radius:.6rem;cursor:pointer;
                       transition:opacity .2s;-webkit-appearance:none">
            Enviar
        </button>

        <p id="fb-success" style="display:none;text-align:center;font-size:.85rem;font-weight:700;color:rgb(var(--color-primary))">
            ¡Gracias! Mensaje recibido.
        </p>
    </form>
</div>

{{-- Trigger button --}}
<div id="fb-widget">
    <button id="fb-btn" onclick="fbToggle()"
            title="Enviar feedback"
            style="width:48px;height:48px;border-radius:50%;border:none;cursor:pointer;
                   background:rgb(var(--color-primary));color:#0a0a0a;
                   display:flex;align-items:center;justify-content:center;
                   box-shadow:0 4px 20px rgb(var(--color-primary) / 0.35);
                   transition:transform .2s,box-shadow .2s;-webkit-appearance:none;touch-action:manipulation"
            onmouseover="this.style.transform='scale(1.08)'"
            onmouseout="this.style.transform='scale(1)'">
        <svg id="fb-icon-open" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h6m-6 4h10M5 5a2 2 0 00-2 2v12l4-4h12a2 2 0 002-2V7a2 2 0 00-2-2H5z"/>
        </svg>
        <svg id="fb-icon-close" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display:none">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<script>
    (function () {
        var modal   = document.getElementById('fb-modal');
        var overlay = document.getElementById('fb-overlay');
        var form    = document.getElementById('fb-form');
        var isMobile = window.matchMedia('(max-width: 479px)');

        form.querySelector('input[name="url"]').value = window.location.pathname;

        window.fbToggle = function () {
            var open = modal.style.display === 'block';
            if (open) { fbClose(); return; }

            modal.style.display = 'block';
            if (isMobile.matches) { overlay.style.display = 'block'; }
            document.getElementById('fb-icon-open').style.display  = 'none';
            document.getElementById('fb-icon-close').style.display = '';

            // Delay focus on mobile to avoid triggering zoom/keyboard instantly
            if (!isMobile.matches) {
                setTimeout(function () { document.getElementById('fb-message').focus(); }, 50);
            }
        };

        window.fbClose = function () {
            modal.style.display   = 'none';
            overlay.style.display = 'none';
            document.getElementById('fb-icon-open').style.display  = '';
            document.getElementById('fb-icon-close').style.display = 'none';
        };

        window.fbTypeChange = function (radio) {
            document.querySelectorAll('.fb-type-btn').forEach(function (btn) {
                var active = btn.dataset.val === radio.value;
                btn.style.borderColor = active ? 'rgb(var(--color-primary) / 0.5)' : 'rgba(255,255,255,0.08)';
                btn.style.background  = active ? 'rgb(var(--color-primary) / 0.08)' : 'transparent';
                btn.style.color       = active ? 'rgb(var(--color-primary))' : 'rgba(255,255,255,0.45)';
            });
        };

        window.fbSubmit = function (e) {
            e.preventDefault();
            var btn = document.getElementById('fb-submit');
            btn.disabled = true;
            btn.textContent = 'Enviando…';

            fetch('{{ route('feedback.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: new FormData(form),
            })
            .then(function (r) {
                if (r.status === 429) { throw new Error('rate_limit'); }
                if (!r.ok) { throw new Error('error'); }
                return r.json();
            })
            .then(function () {
                form.querySelector('textarea').value = '';
                document.getElementById('fb-success').style.display = 'block';
                btn.style.display = 'none';
                setTimeout(function () {
                    fbClose();
                    document.getElementById('fb-success').style.display = 'none';
                    btn.style.display = '';
                    btn.disabled = false;
                    btn.textContent = 'Enviar';
                }, 2000);
            })
            .catch(function (err) {
                btn.disabled = false;
                btn.textContent = err.message === 'rate_limit' ? 'Demasiados envíos, espera' : 'Error al enviar';
                setTimeout(function () { btn.textContent = 'Enviar'; }, 3000);
            });
        };
    })();
</script>
