<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use App\Models\User;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string')]
    public string $id_user = '';

    #[Validate('required|string')]
    public string $password = '';


    /**
     * Handle an incoming authentication request.
     */
     public function login(): void
    {
        $this->validate();
        $this->ensureIsNotRateLimited();

        $user = User::whereRaw("
            AES_DECRYPT(id_user, 'nur') = ? 
            AND AES_DECRYPT(password, 'windi') = ?", 
            [$this->id_user, $this->password]
        )->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'id_user' => __('auth.failed'),
            ]);
        }

        // Step 3: Login the user
        Auth::login($user);
        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'id_user' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->id_user).'|'.request()->ip());
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="id_user"
            :label="__('Email address')"
            type="id_user"
            required
            autofocus
            autocomplete="id_user"
            placeholder="id_user"
        />

        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Password')"
            />

            @if (Route::has('password.request'))
                <flux:link class="absolute right-0 top-0 text-sm" :href="route('password.request')" wire:navigate>
                    {{ __('Forgot your password?') }}
                </flux:link>
            @endif
        </div>

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" :label="__('Remember me')" />

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full">{{ __('Log in') }}</flux:button>
        </div>
    </form>

    @if (Route::has('register'))
        <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('Don\'t have an account?') }}
            <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
        </div>
    @endif
</div>
