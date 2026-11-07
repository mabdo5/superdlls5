<?php
/**
 * One-shot additive merge of v1.2.0 control-plane config keys into existing config/dlss5.php.
 * Run from Laravel core root: php scripts/merge_dlss5_config.php
 */
$core = $argv[1] ?? getcwd();
chdir($core);
$path = $core . '/config/dlss5.php';
if (!is_file($path)) {
    fwrite(STDERR, "Missing config/dlss5.php\n");
    exit(2);
}
$text = file_get_contents($path);
if (str_contains($text, "'node_mode'")) {
    echo "SKIP: node_mode already present\n";
    exit(0);
}
$block = <<<'PHP'

    /*
    | v1.2.0 control plane (additive — do not remove legacy keys above).
    */
    'node_mode' => env('DLSS5_NODE_MODE', 'full'),
    'checkout_hosts' => array_filter(array_map('strtolower', explode(',', (string) env('DLSS5_CHECKOUT_HOSTS', 'funpay.com')))),
    'health_key_id' => env('DLSS5_HEALTH_KEY_ID', 'health-v1'),
    'health_signing_key_path' => env('DLSS5_HEALTH_SIGNING_KEY_PATH'),
    'delegate_signing_key_path' => env('DLSS5_DELEGATE_SIGNING_KEY_PATH'),
    'root_public_keys' => [
        'root-primary-v1' => env('DLSS5_ROOT_PUBLIC_PRIMARY'),
        'root-backup-v1' => env('DLSS5_ROOT_PUBLIC_BACKUP'),
    ],
    'github_auto_publish' => filter_var(env('DLSS5_GITHUB_AUTO_PUBLISH', false), FILTER_VALIDATE_BOOL),
PHP;
$pos = strrpos($text, '];');
if ($pos === false) {
    fwrite(STDERR, "Could not find closing array in dlss5.php\n");
    exit(3);
}
$new = substr($text, 0, $pos) . $block . "\n];\n";
file_put_contents($path, $new);
echo "OK merged control-plane keys into config/dlss5.php\n";
