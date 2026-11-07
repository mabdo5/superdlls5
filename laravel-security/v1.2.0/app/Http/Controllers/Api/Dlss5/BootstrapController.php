<?php

namespace App\Http\Controllers\Api\Dlss5;

use App\Http\Controllers\Controller;
use App\Services\Dlss5ControlDocumentService;
use Illuminate\Http\Response;

class BootstrapController extends Controller
{
    public function trustAnchor(Dlss5ControlDocumentService $documents): Response
    {
        $doc = $documents->activeTrustAnchor();
        abort_unless($doc, 404);
        return response($doc['body'], 200, $this->noStoreHeaders());
    }

    public function trustAnchorSignature(Dlss5ControlDocumentService $documents): Response
    {
        $doc = $documents->activeTrustAnchor();
        abort_unless($doc, 404);
        return response($doc['signature'], 200, $this->noStoreHeaders());
    }

    public function trustAnchorKeyId(Dlss5ControlDocumentService $documents): Response
    {
        $doc = $documents->activeTrustAnchor();
        abort_unless($doc, 404);
        return response($doc['key_id'], 200, $this->noStoreHeaders());
    }

    public function operationalConfig(Dlss5ControlDocumentService $documents): Response
    {
        $doc = $documents->activeOperationalConfig();
        abort_unless($doc, 404);
        return response($doc['body'], 200, $this->noStoreHeaders());
    }

    public function operationalConfigSignature(Dlss5ControlDocumentService $documents): Response
    {
        $doc = $documents->activeOperationalConfig();
        abort_unless($doc, 404);
        return response($doc['signature'], 200, $this->noStoreHeaders());
    }

    public function operationalConfigKeyId(Dlss5ControlDocumentService $documents): Response
    {
        $doc = $documents->activeOperationalConfig();
        abort_unless($doc, 404);
        return response($doc['key_id'], 200, $this->noStoreHeaders());
    }

    private function noStoreHeaders(): array
    {
        return [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Content-Type' => 'application/octet-stream',
        ];
    }
}
