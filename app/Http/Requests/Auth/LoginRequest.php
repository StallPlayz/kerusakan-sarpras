<?php

namespace App\Http\Requests\Auth;

use App\Rules\Recaptcha;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['required', new Recaptcha()],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {

            // --- AWAL LOGIKA ESCALATING PENALTY ---
            // 1. Buat nama kunci unik untuk mencatat "dosa" user ini (berdasarkan email & IP)
            $penaltyKey = $this->throttleKey() . '|penalties';

            // 2. Ambil total kegagalan sebelumnya, lalu tambah 1
            $totalFails = Cache::get($penaltyKey, 0) + 1;

            // 3. Simpan kembali ingatan kegagalan ini selama 24 jam ke depan
            Cache::put($penaltyKey, $totalFails, now()->addHours(24));

            if ($totalFails <= 5) {
                $decaySeconds = 60;
            } elseif ($totalFails <= 10) {
                $decaySeconds = 300;
            } elseif ($totalFails <= 15) {
                $decaySeconds = 900;
            } elseif ($totalFails <= 20) {
                $decaySeconds = 3600;
            } else {
                $decaySeconds = 86400;
            }

            // 5. Terapkan pukulan Rate Limiter dengan waktu yang sudah dihitung
            RateLimiter::hit($this->throttleKey(), $decaySeconds);
            // --- AKHIR LOGIKA ESCALATING PENALTY ---

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // JIKA BERHASIL LOGIN: Bersihkan semua dosa mereka (Rate Limiter & Cache Penalti)
        RateLimiter::clear($this->throttleKey());
        Cache::forget($this->throttleKey() . '|penalties');
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
