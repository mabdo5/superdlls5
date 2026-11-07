<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * Returns exact stored bytes for TA/OC (never JSON-cast / re-serialize).
 */
class Dlss5ControlDocumentService
{
    public function activeTrustAnchor(): ?array
    {
        $row = DB::table('dlss5_control_documents')
            ->where('document_type', 'ta')
            ->where('is_active', 1)
            ->orderByDesc('sequence')
            ->first();
        if (!$row) {
            return null;
        }
        return [
            'body' => base64_decode($row->body_b64, true) ?: '',
            'signature' => (string) $row->signature_b64,
            'key_id' => (string) $row->key_id,
        ];
    }

    public function activeOperationalConfig(): ?array
    {
        $row = DB::table('dlss5_control_documents')
            ->where('document_type', 'oc')
            ->where('is_active', 1)
            ->orderByDesc('sequence')
            ->first();
        if (!$row) {
            return null;
        }
        return [
            'body' => base64_decode($row->body_b64, true) ?: '',
            'signature' => (string) $row->signature_b64,
            'key_id' => (string) $row->key_id,
        ];
    }
}
