<?php
declare(strict_types=1);

namespace App\Services;

use function App\Helpers\config;

final class MpesaService
{
    public function initiateStkPush(string $phone, float $amount, string $accountReference, string $description): array
    {
        $settings = config('payments')['mpesa'] ?? [];
        $required = ['consumer_key', 'consumer_secret', 'shortcode', 'passkey', 'callback_url'];
        foreach ($required as $key) {
            if (empty($settings[$key])) {
                return [
                    'ok' => false,
                    'message' => 'M-Pesa sandbox credentials are not configured yet.',
                    'reference' => null,
                ];
            }
        }

        return [
            'ok' => true,
            'message' => 'M-Pesa STK Push scaffold accepted. Wire this method to Safaricom Daraja before production.',
            'reference' => 'MPESA-' . strtoupper(bin2hex(random_bytes(4))),
            'payload' => [
                'phone' => $phone,
                'amount' => $amount,
                'account_reference' => $accountReference,
                'description' => $description,
            ],
        ];
    }
}
