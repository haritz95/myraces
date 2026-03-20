<x-guest-layout>

    {{-- Hero --}}
    <div class="relative overflow-hidden rounded-2xl mb-8 mt-2 py-10 px-6"
         style="background:#111">
        <svg class="absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 400 160" preserveAspectRatio="none">
            <line x1="-20" y1="130" x2="420" y2="50"  stroke="rgb(var(--color-primary))" stroke-width="1.2" opacity="0.18"/>
            <line x1="-20" y1="155" x2="420" y2="75"  stroke="rgb(var(--color-primary))" stroke-width="0.6" opacity="0.10"/>
            <circle cx="370" cy="20" r="120" fill="rgb(var(--color-primary))" opacity="0.03"/>
        </svg>
        <div class="absolute inset-0 rounded-2xl" style="background:linear-gradient(to top,#0a0a0a 0%,transparent 55%)"></div>
        <div class="relative">
            <h1 class="font-black italic tracking-tighter uppercase leading-[0.88] text-white"
                style="font-size: clamp(2.2rem, 8vw, 3rem)">
                {{ __('auth.create_account') }}<span style="color:rgb(var(--color-primary))">.</span>
            </h1>
            <p class="mt-2 text-[10px] font-bold uppercase tracking-[0.2em]" style="color:rgba(255,255,255,0.30)">
                {{ __('auth.register_subtitle') }}
            </p>
        </div>
    </div>

    @if ($errors->has('social'))
        <div class="mb-5 px-4 py-3 rounded-xl text-sm" style="background:rgba(255,80,80,0.10); border:1px solid rgba(255,80,80,0.20); color:#f87171">
            {{ $errors->first('social') }}
        </div>
    @endif

    {{-- Social --}}
    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-center mb-3" style="color:rgba(255,255,255,0.25)">
        {{ __('auth.express_entry') }}
    </p>
    <div class="grid grid-cols-3 gap-3 mb-8">
        <a href="{{ route('social.redirect', 'google') }}"
           class="flex items-center justify-center py-4 rounded-xl transition-colors"
           style="background:#161616; border:1px solid rgba(255,255,255,0.07)" onmouseenter="this.style.borderColor='rgba(255,255,255,0.15)'" onmouseleave="this.style.borderColor='rgba(255,255,255,0.07)'">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
        </a>
        <a href="{{ route('social.redirect', 'facebook') }}"
           class="flex items-center justify-center py-4 rounded-xl transition-opacity hover:opacity-85"
           style="background:#1877F2">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
        </a>
        <a href="{{ route('social.redirect', 'strava') }}"
           class="flex items-center justify-center py-4 rounded-xl transition-opacity hover:opacity-85"
           style="background:#FC4C02">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M15.387 17.944l-2.089-4.116h-3.065L15.387 24l5.15-10.172h-3.066m-7.008-5.599l2.836 5.598h4.172L10.463 0l-7 13.828h4.169"/>
            </svg>
        </a>
    </div>

    {{-- Divider --}}
    <div class="flex items-center gap-4 mb-8">
        <div class="h-px flex-grow" style="background:rgba(255,255,255,0.06)"></div>
        <span class="text-[10px] font-bold uppercase tracking-widest" style="color:rgba(255,255,255,0.22)">{{ __('auth.or_with_email') }}</span>
        <div class="h-px flex-grow" style="background:rgba(255,255,255,0.06)"></div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-[10px] font-bold uppercase tracking-widest mb-2"
                   style="color:rgba(255,255,255,0.35)">{{ __('auth.name') }}</label>
            <div class="auth-field">
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                       required autofocus autocomplete="name"
                       class="auth-input" placeholder="Tu nombre">
            </div>
            @error('name') <p class="text-xs mt-1.5 font-medium" style="color:#f87171">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-[10px] font-bold uppercase tracking-widest mb-2"
                   style="color:rgba(255,255,255,0.35)">{{ __('auth.email') }}</label>
            <div class="auth-field">
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       required autocomplete="username"
                       class="auth-input" placeholder="runner@example.com">
            </div>
            @error('email') <p class="text-xs mt-1.5 font-medium" style="color:#f87171">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-[10px] font-bold uppercase tracking-widest mb-2"
                   style="color:rgba(255,255,255,0.35)">{{ __('auth.password') }}</label>
            <div class="auth-field">
                <input id="password" type="password" name="password"
                       required autocomplete="new-password"
                       class="auth-input" placeholder="••••••••">
            </div>
            @error('password') <p class="text-xs mt-1.5 font-medium" style="color:#f87171">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-[10px] font-bold uppercase tracking-widest mb-2"
                   style="color:rgba(255,255,255,0.35)">{{ __('auth.confirm_password') }}</label>
            <div class="auth-field">
                <input id="password_confirmation" type="password" name="password_confirmation"
                       required autocomplete="new-password"
                       class="auth-input" placeholder="••••••••">
            </div>
            @error('password_confirmation') <p class="text-xs mt-1.5 font-medium" style="color:#f87171">{{ $message }}</p> @enderror
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="w-full py-4 rounded-full font-black italic uppercase tracking-tighter text-black transition-all active:scale-[0.97] flex items-center justify-center gap-2"
                    style="background:rgb(var(--color-primary)); box-shadow:0 0 32px rgb(var(--color-primary) / 0.20); font-size:1.05rem">
                {{ __('auth.create_btn') }}
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </button>
        </div>
    </form>

    <p class="text-center text-sm mt-8 mb-2" style="color:rgba(255,255,255,0.30)">
        {{ __('auth.already_account') }}
        <a href="{{ route('login') }}" class="font-bold transition-colors ml-1"
           style="color:rgba(255,255,255,0.75)" onmouseenter="this.style.color='rgb(var(--color-primary))'" onmouseleave="this.style.color='rgba(255,255,255,0.75)'">
            {{ __('auth.sign_in_link') }}
        </a>
    </p>

</x-guest-layout>
