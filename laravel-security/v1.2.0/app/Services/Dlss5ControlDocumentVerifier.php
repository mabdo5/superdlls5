<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Server-side TA verification before storing documents (Root signature only).
 * Private keys never touch the server except delegate/health signing key files.
 */
class Dlss5ControlDocumentVerifier
{
    private const TA_PREFIX = "SUPERDLLS5|control-ta|v1\x00";

    public function verifyTrustAnchorBytes(string $body, string $signatureB64, string $keyId): void
    {
        if (!extension_loaded('sodium')) {
            abort(503, 'ext-sodium required');
        }
        $pubB64 = config('dlss5.root_public_keys.' . $keyId);
        if (!$pubB64) {
            abort(422, 'Unknown root key id');
        }
        $pub = base64_decode($pubB64, true);
        if ($pub === false || strlen($pub) !== SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES) {
            abort(503, 'Invalid root public key configuration');
        }
        $sig = base64_decode($signatureB64, true);
        if ($sig === false) {
            abort(422, 'Invalid signature encoding');
        }
        $message = self::TA_PREFIX . $body;
        if (!sodium_crypto_sign_verify_detached($sig, $message, $pub)) {
            abort(422, 'Trust anchor signature invalid');
        }
    }
}
