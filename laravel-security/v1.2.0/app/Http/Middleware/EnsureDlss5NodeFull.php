<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks paid DLSS5 API routes when DLSS5_NODE_MODE=degraded (cutover / legacy host).
 */
class EnsureDlss5NodeFull
{
    public function handle(Request $request, Closure $next): Response
    {
        if (strtolower((string) config('dlss5.node_mode', 'full')) !== 'full') {
            return response()->json([
                'error' => 'dlss5_node_degraded',
                'message' => 'Paid operations unavailable on degraded node.',
            ], 503);
        }
        return $next($request);
    }
}
