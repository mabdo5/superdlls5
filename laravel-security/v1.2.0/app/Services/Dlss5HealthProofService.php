<?php

namespace App\Services;

/**
 * Signs health proofs with service identity key from path in .env (never in DB).
 */
class Dlss5HealthProofService
{
    private const PREFIX = "SUPERDLLS5|health|v1\x00";

    public function buildPayload(string $nonce, array $operationalConfig): array
    {
        if (!extension_loaded('sodium')) {
            abort(503, 'ext-sodium required');
        }
        $canonicalHost = parse_url(config('app.url'), PHP_URL_HOST);
        $ocJson = json_decode((string) ($operationalConfig['body'] ?? ''), true);
        $ocSequence = is_array($ocJson) ? (int) ($ocJson['oc_sequence'] ?? 0) : 0;
        $bodyArr = [
            'nonce' => $nonce,
            'host' => $canonicalHost,
            'product_id' => 'super-dlls-5',
            'environment' => 'production',
            'oc_sequence' => $ocSequence,
            'server_time' => now()->toIso8601String(),
            'key_id' => env('DLSS5_HEALTH_KEY_ID', config('dlss5.health_key_id', 'health-v1')),
        ];
        $body = json_encode($bodyArr, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $keyPath = env('DLSS5_HEALTH_SIGNING_KEY_PATH', config('dlss5.health_signing_key_path'));
        if (!$keyPath || !is_readable($keyPath)) {
            abort(503, 'Health signing key not configured');
        }
        $priv = file_get_contents($keyPath);
        if ($priv === false || $priv === '') {
            abort(503, 'Invalid health signing key file');
        }
        if (strlen($priv) === SODIUM_CRYPTO_SIGN_SEEDBYTES) {
            $keypair = sodium_crypto_sign_seed_keypair($priv);
            $secret = sodium_crypto_sign_secretkey($keypair);
        } elseif (strlen($priv) >= SODIUM_CRYPTO_SIGN_SECRETKEYBYTES) {
            $secret = $priv;
        } else {
            abort(503, 'Invalid health signing key length');
        }
        $message = self::PREFIX . $body;
        $signature = sodium_crypto_sign_detached($message, $secret);
        return [
            'body' => $body,
            'signature' => base64_encode($signature),
        ];
    }
}
