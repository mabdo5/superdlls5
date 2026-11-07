<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Dlss5SubscribeController extends Controller
{
    public function __invoke(Request $request)
    {
        $allowed = config('dlss5.checkout_hosts', []);
        $next = (string) $request->query('next', '');
        if ($next !== '') {
            $parts = parse_url($next);
            if (($parts['scheme'] ?? '') !== 'https') {
                abort(400, 'Invalid next URL');
            }
            if (!empty($parts['user'] ?? '') || !empty($parts['pass'] ?? '')) {
                abort(400, 'Invalid next URL');
            }
            $host = strtolower($parts['host'] ?? '');
            if (!in_array($host, $allowed, true)) {
                abort(400, 'Checkout host not allowed');
            }
            return redirect()->away($next);
        }
        abort(404, 'Purchase gateway unavailable');
    }
}
