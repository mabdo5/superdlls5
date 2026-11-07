<?php

use Illuminate\Support\Facades\Route;

Route::prefix('dlss5/bootstrap')->middleware('throttle:60,1')->group(function () {
    Route::get('ta', [\App\Http\Controllers\Api\Dlss5\BootstrapController::class, 'trustAnchor']);
    Route::get('ta.sig', [\App\Http\Controllers\Api\Dlss5\BootstrapController::class, 'trustAnchorSignature']);
    Route::get('ta.key_id', [\App\Http\Controllers\Api\Dlss5\BootstrapController::class, 'trustAnchorKeyId']);

    Route::get('oc', [\App\Http\Controllers\Api\Dlss5\BootstrapController::class, 'operationalConfig']);
    Route::get('oc.sig', [\App\Http\Controllers\Api\Dlss5\BootstrapController::class, 'operationalConfigSignature']);
    Route::get('oc.key_id', [\App\Http\Controllers\Api\Dlss5\BootstrapController::class, 'operationalConfigKeyId']);

    Route::get('health', [\App\Http\Controllers\Api\Dlss5\BootstrapHealthController::class, '__invoke']);
});
