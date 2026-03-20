{{-- Floating feedback widget --}}
<div id="fb-widget" style="position:fixed;bottom:1.25rem;right:1.25rem;z-index:9999;font-family:inherit">

    {{-- Modal --}}
    <div id="fb-modal" style="display:none;position:absolute;bottom:calc(100% + .75rem);right:0;width:300px;
         background:#141414;border:1px solid rgba(255,255,255,0.10);border-radius:1rem;
         box-shadow:0 24px 60px rgba(0,0,0,0.6);overflow:hidden">

        {{-- Header --}}
        <div style="padding:.85rem 1rem .75rem;border-bottom:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;justify-content:space-between">
            <span style="font-size:.85rem;font-weight:800;color:#fff">Enviar feedback</span>
            <button onclick="fbClose()" style="width:24px;height:24px;border:none;background:transparent;cursor:pointer;color:rgba(255,255,255,0.35);display:flex;align-items:center;justify-content:center;border-radius:6px" onmouseover="this.style.background='rgba(255,255,255,0.07)'" onmouseout="this.style.background='transparent'">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
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
                               style="position:absolute;opacity:0;pointer-events:none">
                        <span class="fb-type-btn" data-val="{{ $val }}"
                              style="display:flex;flex-direction:column;align-items:center;gap:2px;padding:.5rem .25rem;border-radius:.6rem;border:1px solid {{ $val === 'suggestion' ? 'rgb(var(--color-primary) / 0.5)' : 'rgba(255,255,255,0.08)' }};background:{{ $val === 'suggestion' ? 'rgb(var(--color-primary) / 0.08)' : 'transparent' }};transition:all .15s;font-size:.7rem;font-weight:700;color:{{ $val === 'suggestion' ? 'rgb(var(--color-primary))' : 'rgba(255,255,255,0.45)' }}">
                            <span style="font-size:1rem">{{ $emoji }}</span>{{ $label }}
                        </span>
                    </label>
                @endforeach
            </div>

            {{-- Message --}}
            <textarea name="message" id="fb-message" rows="4" required maxlength="1000"
                      placeholder="Describe el problema o sugerencia..."
                      style="width:100%;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);border-radius:.6rem;
                             padding:.65rem .75rem;color:#e5e2e1;font-size:.85rem;resize:none;outline:none;font-family:inherit;line-height:1.5"
                      onfocus="this.style.borderColor='rgb(var(--color-primary) / 0.4)'"
                      onblur="this.style.borderColor='rgba(255,255,255,0.09)'"></textarea>

            {{-- URL hidden --}}
            <input type="hidden" name="url" value="">

            {{-- Submit --}}
            <button type="submit" id="fb-submit"
                    style="width:100%;background:rgb(var(--color-primary));color:#0a0a0a;font-weight:800;font-size:.85rem;
                           padding:.6rem;border:none;border-radius:.6rem;cursor:pointer;transition:opacity .2s"
                    onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                Enviar
            </button>

            {{-- Success msg --}}
            <p id="fb-success" style="display:none;text-align:center;font-size:.8rem;font-weight:700;color:rgb(var(--color-primary))">
                ¡Gracias! Mensaje recibido.
            </p>
        </form>
    </div>

    {{-- Trigger button --}}
    <button id="fb-btn" onclick="fbToggle()"
            title="Enviar feedback"
            style="width:48px;height:48px;border-radius:50%;border:none;cursor:pointer;
                   background:rgb(var(--color-primary));color:#0a0a0a;
                   display:flex;align-items:center;justify-content:center;
                   box-shadow:0 4px 20px rgb(var(--color-primary) / 0.35);
                   transition:transform .2s,box-shadow .2s"
            onmouseover="this.style.transform='scale(1.08)';this.style.boxShadow='0 6px 28px rgb(var(--color-primary) / 0.45)'"
            onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 4px 20px rgb(var(--color-primary) / 0.35)'">
        <svg id="fb-icon-open" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h6m-6 4h10M5 5a2 2 0 00-2 2v12l4-4h12a2 2 0 002-2V7a2 2 0 00-2-2H5z"/>
        </svg>
        <svg id="fb-icon-close" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display:none">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<script>
    (function () {
        var modal = document.getElementById('fb-modal');
        var form  = document.getElementById('fb-form');

        // Pre-fill hidden URL field
        form.querySelector('input[name="url"]').value = window.location.pathname;

        window.fbToggle = function () {
            var open = modal.style.display === 'block';
            modal.style.display = open ? 'none' : 'block';
            document.getElementById('fb-icon-open').style.display  = open ? '' : 'none';
            document.getElementById('fb-icon-close').style.display = open ? 'none' : '';
            if (!open) { document.getElementById('fb-message').focus(); }
        };

        window.fbClose = function () {
            modal.style.display = 'none';
            document.getElementById('fb-icon-open').style.display  = '';
            document.getElementById('fb-icon-close').style.display = 'none';
        };

        window.fbTypeChange = function (radio) {
            document.querySelectorAll('.fb-type-btn').forEach(function (btn) {
                var active = btn.dataset.val === radio.value;
                btn.style.borderColor  = active ? 'rgb(var(--color-primary) / 0.5)' : 'rgba(255,255,255,0.08)';
                btn.style.background   = active ? 'rgb(var(--color-primary) / 0.08)'  : 'transparent';
                btn.style.color        = active ? 'rgb(var(--color-primary))' : 'rgba(255,255,255,0.45)';
            });
        };

        window.fbSubmit = function (e) {
            e.preventDefault();
            var btn = document.getElementById('fb-submit');
            btn.disabled = true;
            btn.textContent = 'Enviando…';

            var data = new FormData(form);

            fetch('{{ route('feedback.store') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
                body: data,
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
