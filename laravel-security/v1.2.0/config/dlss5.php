<?php

return [
    'node_mode' => env('DLSS5_NODE_MODE', 'full'), // full | degraded
    'checkout_hosts' => array_filter(array_map('strtolower', explode(',', (string) env('DLSS5_CHECKOUT_HOSTS', 'funpay.com')))),
    'health_key_id' => env('DLSS5_HEALTH_KEY_ID', 'health-v1'),
    'health_signing_key_path' => env('DLSS5_HEALTH_SIGNING_KEY_PATH'),
    'delegate_signing_key_path' => env('DLSS5_DELEGATE_SIGNING_KEY_PATH'),
    'root_public_keys' => [
        // base64 raw Ed25519 public keys — owner fills before deploy
        'root-primary-v1' => env('DLSS5_ROOT_PUBLIC_PRIMARY'),
        'root-backup-v1' => env('DLSS5_ROOT_PUBLIC_BACKUP'),
    ],
    'github_auto_publish' => filter_var(env('DLSS5_GITHUB_AUTO_PUBLISH', false), FILTER_VALIDATE_BOOL),
];
