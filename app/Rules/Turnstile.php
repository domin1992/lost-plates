<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class Turnstile implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $uuid = Uuid::uuid4()->toString();

            $response = Http::asForm()
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => config('services.cloudflare.turnstile.secret_key'),
                    'response' => $value,
                    'remoteip' => request()->getClientIp(),
                    'idempotency_key' => $uuid,
                ]);

            $responseContent = json_decode($response->getBody()->getContents());

            if (!$responseContent->success) {
                Log::channel('turnstile')->error(sprintf(
                    'STATUS: %s | RESPONSE: %s | REMOTE IP: %s | IDEMPOTENCY KEY: %s',
                    $response->status(),
                    (string)$response->getBody(),
                    request()->getClientIp(),
                    $uuid
                ));

                if (
                    isset($responseContent->{'error-codes'})
                    && is_array($responseContent->{'error-codes'})
                    && count($responseContent->{'error-codes'})
                ) {
                    $fail(__('turnstile.' . $responseContent->{'error-codes'}[0]));
                }

                $fail(__('turnstile.default'));
            }
        } catch (\Exception $e) {
            Log::channel('turnstile')->error(sprintf(
                'MESSAGE: %s | REMOTE IP: %s | IDEMPOTENCY KEY: %s',
                $e->getMessage(),
                request()->getClientIp(),
                $uuid
            ));

            $fail(__('turnstile.default'));
        }
    }
}
