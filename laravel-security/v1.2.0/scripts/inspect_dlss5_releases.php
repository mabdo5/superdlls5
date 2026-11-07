<?php
/** Read-only release / policy inspection (no secrets). */
$core = $argv[1] ?? getcwd();
chdir($core);
require $core . '/vendor/autoload.php';
$app = require $core . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
foreach (\App\Models\Dlss5AppRelease::orderByDesc('id')->limit(10)->get() as $r) {
    $signed = !empty($r->update_manifest_json) && !empty($r->update_manifest_signature);
    echo $r->id . '|' . $r->version . '|active=' . ($r->active ? '1' : '0') . '|' . ($signed ? 'signed' : 'unsigned') . PHP_EOL;
}
echo 'config.require_signed_releases=' . (config('dlss5.require_signed_releases') ? 'true' : 'false') . PHP_EOL;
