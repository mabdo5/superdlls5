<?php

namespace App\Http\Controllers\Api\Dlss5;

use App\Http\Controllers\Controller;
use App\Services\Dlss5ControlDocumentService;
use App\Services\Dlss5HealthProofService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BootstrapHealthController extends Controller
{
    public function __invoke(
        Request $request,
        Dlss5HealthProofService $health,
        Dlss5ControlDocumentService $documents
    ): Response {
        $nonce = (string) $request->query('nonce', '');
        if (strlen($nonce) < 32) {
            abort(422, 'Invalid nonce');
        }
        $oc = $documents->activeOperationalConfig();
        abort_unless($oc, 503);
        $payload = $health->buildPayload($nonce, $oc);
        $body = $payload['body'];
        $signature = $payload['signature'];
        return response($body, 200, [
            'Cache-Control' => 'no-store',
            'Content-Type' => 'application/json; charset=UTF-8',
            'X-DLSS5-Health-Signature' => $signature,
        ]);
    }
}
