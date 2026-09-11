<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    /**
     * Minimum acceptable v3 score (0.0 = likely a bot, 1.0 = likely human).
     * Google's own recommended starting point.
     */
    private const MIN_SCORE = 0.5;

    public function __construct(private ?string $expectedAction = null)
    {
    }

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (! config('services.recaptcha.secret_key')) {
            // Not configured yet — don't lock the form for every visitor
            // over a missing .env value; just skip the check silently.
            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('يرجى تأكيد أنك لست روبوتًا.');

            return;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        if (! $response->successful() || ! $response->json('success')) {
            $fail('تعذر التحقق من أنك لست روبوتًا، يرجى المحاولة مرة أخرى.');

            return;
        }

        if ((float) $response->json('score', 0) < self::MIN_SCORE) {
            $fail('تعذر التحقق من أنك لست روبوتًا، يرجى المحاولة مرة أخرى.');

            return;
        }

        if ($this->expectedAction && $response->json('action') !== $this->expectedAction) {
            $fail('تعذر التحقق من أنك لست روبوتًا، يرجى المحاولة مرة أخرى.');
        }
    }
}
