<x-guest-layout>
    <div class="px-6 pt-8 pb-7">
        <h2 class="text-xl font-extrabold text-white mb-1">{{ __('auth.welcome_back') }}</h2>
        <p class="text-sm text-muted mb-7">{{ __('auth.sign_in_subtitle') }}</p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        @if ($errors->has('social'))
            <div class="mb-5 px-4 py-3 bg-red-500/15 border border-red-500/30 rounded-xl text-sm text-red-400">
                {{ $errors->first('social') }}
            </div>
        @endif

        {{-- Social buttons --}}
        <div class="space-y-2.5 mb-6">
            <a href="{{ route('social.redirect', 'google') }}"
               class="flex items-center justify-center gap-3 w-full bg-bg-elevated border border-white/10 rounded-full px-4 py-3 text-sm font-semibold text-white hover:bg-bg-surface hover:border-white/20 transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                {{ __('auth.continue_google') }}
            </a>

            <a href="{{ route('social.redirect', 'facebook') }}"
               class="flex items-center justify-center gap-3 w-full bg-[#1877F2] hover:bg-[#166FE5] rounded-full px-4 py-3 text-sm font-semibold text-white transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                {{ __('auth.continue_facebook') }}
            </a>

            <a href="{{ route('social.redirect', 'strava') }}"
               class="flex items-center justify-center gap-3 w-full bg-[#FC4C02] hover:bg-[#e84400] rounded-full px-4 py-3 text-sm font-semibold text-white transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M15.387 17.944l-2.089-4.116h-3.065L15.387 24l5.15-10.172h-3.066m-7.008-5.599l2.836 5.598h4.172L10.463 0l-7 13.828h4.169"/>
                </svg>
                {{ __('auth.continue_strava') }}
            </a>
        </div>

        <div class="relative mb-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-white/[0.08]"></div>
            </div>
            <div class="relative flex justify-center text-xs">
                <span class="px-3 bg-bg-card text-subtle font-semibold">{{ __('auth.or_with_email') }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-semibold text-white mb-1.5">{{ __('auth.email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       required autofocus autocomplete="username"
                       class="input-field @error('email') error @enderror">
                @error('email') <p class="text-red-400 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-white mb-1.5">{{ __('auth.password') }}</label>
                <input id="password" type="password" name="password"
                       required autocomplete="current-password"
                       class="input-field @error('password') error @enderror">
                @error('password') <p class="text-red-400 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2.5 text-sm text-muted cursor-pointer select-none">
                    <input type="checkbox" name="remember"
                           class="w-4 h-4 rounded border-white/20 text-primary focus:ring-primary/30 bg-bg-input">
                    {{ __('auth.remember_me') }}
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-primary font-semibold transition-colors hover:opacity-80">
                        {{ __('auth.forgot_password') }}
                    </a>
                @endif
            </div>

            <button type="submit" class="btn btn-primary w-full py-3.5 text-base mt-2">
                {{ __('auth.sign_in') }}
            </button>
        </form>

        <p class="text-center text-sm text-muted mt-6">
            {{ __('auth.no_account') }}
            <a href="{{ route('register') }}" class="text-primary font-bold transition-colors hover:opacity-80">{{ __('auth.sign_up_link') }}</a>
        </p>
    </div>
</x-guest-layout>
