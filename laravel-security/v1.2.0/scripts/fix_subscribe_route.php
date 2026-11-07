<?php
$core = $argv[1] ?? getcwd();
$web = $core . '/routes/web.php';
$t = file_get_contents($web);
$t = str_replace(
    "Route::get('/dlls/subscribe', \\App\\Http\\Controllers\\Web\\Dlss5SubscribeController::class)",
    "Route::get('/dlls/subscribe', 'Web\\Dlss5SubscribeController')",
    $t
);
file_put_contents($web, $t);
echo "OK fix subscribe route\n";
