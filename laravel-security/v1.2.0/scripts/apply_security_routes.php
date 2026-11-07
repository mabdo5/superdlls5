<?php
/** Apply v1.2.0 security route/kernel patches (idempotent). */
$core = $argv[1] ?? getcwd();
chdir($core);

function patch_once(string $path, callable $fn): void
{
    $text = file_get_contents($path);
    $out = $fn($text);
    if ($out !== $text) {
        file_put_contents($path, $out);
        echo "PATCHED $path\n";
    } else {
        echo "UNCHANGED $path\n";
    }
}

$kernel = $core . '/app/Http/Kernel.php';
patch_once($kernel, function (string $t): string {
    if (str_contains($t, 'dlss5.node.full')) {
        return $t;
    }
    return preg_replace(
        "/(\\s+'obs\\.nr\\.license'\\s*=>\\s*[^,]+,)/",
        "$1\n        'dlss5.node.full' => \\App\\Http\\Middleware\\EnsureDlss5NodeFull::class,",
        $t,
        1
    ) ?? $t;
});

$api = $core . '/routes/api.php';
patch_once($api, function (string $t): string {
    if (str_contains($t, 'dlss5.node.full')) {
        return $t;
    }
    return str_replace(
        "Route::middleware('auth.api:sanctum')->group",
        "Route::middleware(['auth.api:sanctum', 'dlss5.node.full'])->group",
        $t
    );
});

$web = $core . '/routes/web.php';
patch_once($web, function (string $t): string {
    if (!str_contains($t, "DesktopWebController@subscribeRedirect")) {
        return $t;
    }
    return str_replace(
        "Route::get('/dlls/subscribe', 'Dlss5\\DesktopWebController@subscribeRedirect')",
        "Route::get('/dlls/subscribe', 'Web\\Dlss5SubscribeController')",
        $t
    );
});

echo "OK apply_security_routes.php\n";
