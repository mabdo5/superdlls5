<?php
/** One-shot import: active TA + OC from signed files (paths on server only). */
$core = $argv[1] ?? '';
$taFile = $argv[2] ?? '';
$ocFile = $argv[3] ?? '';
if ($core === '' || $taFile === '' || $ocFile === '') {
    fwrite(STDERR, "usage: php import_control_documents.php <core> <ta.json> <c1.json>\n");
    exit(2);
}
chdir($core);
require $core . '/vendor/autoload.php';
$app = require $core . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

function loadBundle(string $jsonPath): array
{
    $body = file_get_contents($jsonPath);
    if ($body === false) {
        throw new RuntimeException("Cannot read $jsonPath");
    }
    $sig = file_get_contents($jsonPath . '.sig');
    $kid = trim((string) file_get_contents($jsonPath . '.key_id'));
    if ($sig === false || $kid === '') {
        throw new RuntimeException("Missing sidecars for $jsonPath");
    }
    $doc = json_decode($body, true);
    if (!is_array($doc)) {
        throw new RuntimeException("Invalid JSON: $jsonPath");
    }
    $seq = (int) ($doc['ta_sequence'] ?? $doc['oc_sequence'] ?? 0);
    return [
        'body' => $body,
        'body_b64' => base64_encode($body),
        'signature_b64' => trim($sig),
        'key_id' => $kid,
        'sequence' => $seq,
    ];
}

$ta = loadBundle($taFile);
$oc = loadBundle($ocFile);

DB::transaction(function () use ($ta, $oc) {
    DB::table('dlss5_control_documents')->where('document_type', 'ta')->update(['is_active' => 0]);
    DB::table('dlss5_control_documents')->where('document_type', 'oc')->update(['is_active' => 0]);
    DB::table('dlss5_control_documents')->insert([
        'document_type' => 'ta',
        'sequence' => $ta['sequence'],
        'body_b64' => $ta['body_b64'],
        'signature_b64' => $ta['signature_b64'],
        'key_id' => $ta['key_id'],
        'is_active' => 1,
        'created_at' => now(),
    ]);
    DB::table('dlss5_control_documents')->insert([
        'document_type' => 'oc',
        'sequence' => $oc['sequence'],
        'body_b64' => $oc['body_b64'],
        'signature_b64' => $oc['signature_b64'],
        'key_id' => $oc['key_id'],
        'is_active' => 1,
        'created_at' => now(),
    ]);
});

echo "OK imported TA seq={$ta['sequence']} OC seq={$oc['sequence']}\n";
