<?php


namespace App\Livewire\Auth;
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
use Illuminate\Support\Facades\Crypt;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $id = '';

    #[Validate('required|string')]
    public string $password = '';

    /**
     * Handle an incoming authentication request.
     */
    public function login():void{
        $this->validate([
            'id' => 'required|string',
            'password' => 'required|string',
        ]);

        $this->ensureIsNotRateLimited();

        $user = User::whereRaw("AES_DECRYPT(id, 'windi') = ?", [$this->id])->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'id' => __('auth.failed'),
            ]);
        }

        $decryptedPassword = decrypt($user->password);

        if ($decryptedPassword !== $this->password) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'password' => __('auth.failed'),
            ]);
        }

        Auth::login($user);
        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(route('dashboard'));
    }

    protected function ensureIsNotRateLimited(): void{
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        throw ValidationException::withMessages([
            'id' => __('auth.throttle', [
                'seconds' => RateLimiter::availableIn($this->throttleKey()),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }

    // public function render() {
    //     return view('livewire.auth.login');
    // }
    
}; ?>

