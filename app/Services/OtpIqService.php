<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class OtpIqService
{
    private const BASE_URL = 'https://api.otpiq.com/api/sms';

    /**
     * Send an OTP message via Otpiq.
     *
     * @param  string  $phoneNumber  E.164 without + (as your example) or with +; we will normalize.
     * @param  string|int  $verificationCode
     * @param  string  $provider  e.g. whatsapp | sms
     * @param  string  $smsType  e.g. verification
     * @return array{success: bool, status: int|null, response: mixed, error: string|null}
     */
    public function sendOtp(string $phoneNumber, string|int $verificationCode, string $provider = 'whatsapp', string $smsType = 'verification'): array
    {
        $token = (string) config('services.otpiq.secret_key');
        if ($token === '') {
            return [
                'success' => false,
                'status' => null,
                'response' => null,
                'error' => 'OTPIQ_SECRET_KEY is not configured.',
            ];
        }

        $phoneNumber = $this->normalizePhone($phoneNumber);

        try {
            $res = Http::withToken($token)
                ->acceptJson()
                ->asJson()
                ->post(self::BASE_URL, [
                    'phoneNumber' => $phoneNumber,
                    'smsType' => $smsType,
                    'provider' => $provider,
                    'verificationCode' => (string) $verificationCode,
                ])
                ->throw();

            return [
                'success' => true,
                'status' => $res->status(),
                'response' => $res->json(),
                'error' => null,
            ];
        } catch (RequestException $e) {
            return [
                'success' => false,
                'status' => $e->response?->status(),
                'response' => $e->response?->json() ?? $e->response?->body(),
                'error' => $e->getMessage(),
            ];
        }
    }

    private function normalizePhone(string $phone): string
    {
        $local = normalize_phone($phone) ?? trim($phone);

        // Otpiq expects international digits without + (e.g. 9647739004060)
        if (str_starts_with($local, '0')) {
            return '964' . substr($local, 1);
        }

        return preg_replace('/\D+/', '', $local) ?? $local;
    }
}

