<?php
/** Set DLSS5_REQUIRE_SIGNED_RELEASES=true in .env (idempotent). Backs up .env first. */
$core = $argv[1] ?? getcwd();
$env = $core . '/.env';
if (!is_file($env)) {
    fwrite(STDERR, "Missing .env\n");
    exit(2);
}
$stamp = date('Ymd-His');
$bak = $core . '/.env.bak.' . $stamp;
copy($env, $bak);
$lines = file($env, FILE_IGNORE_NEW_LINES);
$found = false;
$out = [];
foreach ($lines as $line) {
    if (preg_match('/^\s*DLSS5_REQUIRE_SIGNED_RELEASES\s*=/', $line)) {
        $out[] = 'DLSS5_REQUIRE_SIGNED_RELEASES=true';
        $found = true;
    } else {
        $out[] = $line;
    }
}
if (!$found) {
    $out[] = 'DLSS5_REQUIRE_SIGNED_RELEASES=true';
}
file_put_contents($env, implode(PHP_EOL, $out) . PHP_EOL);
echo "OK require_signed_releases=true backup=$bak\n";
