<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //Bersifat sementara, dalam dunia nyata, sebaiknya ganti Http::asForm()->post saja, tanpa tanpa withoutVerifying()
        $response = Http::withoutVerifying()->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $value,
            'remoteip' => request()->ip()
        ]);

        if (! $response->json('success')) {
            $fail('Google reCAPTCHA verification failed. Please check the box.');
        }
    }
}
