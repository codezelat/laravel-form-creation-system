<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TurnstileService
{
    private string $secretKey;
    private string $verifyUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function __construct()
    {
        $this->secretKey = config('services.turnstile.secret_key');
    }

    /**
     * Verify the Turnstile token
     *
     * @param string $token
     * @param string|null $remoteIp
     * @return bool
     */
    public function verify(string $token, ?string $remoteIp = null): bool
    {
        try {
            $response = Http::asForm()->post($this->verifyUrl, [
                'secret' => $this->secretKey,
                'response' => $token,
                'remoteip' => $remoteIp,
            ]);

            if (!$response->successful()) {
                Log::warning('Turnstile API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            $data = $response->json();
            
            // Log verification attempt for debugging
            Log::info('Turnstile verification attempt', [
                'success' => $data['success'] ?? false,
                'error_codes' => $data['error-codes'] ?? [],
                'remote_ip' => $remoteIp,
            ]);

            return $data['success'] ?? false;

        } catch (\Exception $e) {
            Log::error('Turnstile verification exception', [
                'message' => $e->getMessage(),
                'token' => substr($token, 0, 10) . '...',
            ]);
            return false;
        }
    }

    /**
     * Get the site key for frontend use
     *
     * @return string
     */
    public function getSiteKey(): string
    {
        return config('services.turnstile.site_key');
    }
}