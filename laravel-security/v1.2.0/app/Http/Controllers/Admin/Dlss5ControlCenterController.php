<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Control Center (Phase 3): step-up auth + TA import + delegate OC signing.
 * GitHub auto-publish default OFF — manual publish recommended.
 */
class Dlss5ControlCenterController extends Controller
{
    public function index()
    {
        return view('admin.dlss5.control_center', [
            'github_auto_publish' => (bool) config('dlss5.github_auto_publish', false),
        ]);
    }

    public function importTrustAnchor(Request $request)
    {
        $request->validate([
            'body' => 'required|string',
            'signature' => 'required|string',
            'key_id' => 'required|string',
            'step_up_password' => 'required|string',
        ]);
        // Owner: verify root signature server-side before storage (Dlss5ControlDocumentVerifier).
        abort(501, 'Implement root verification against pinned root public keys (OWNER deploy).');
    }
}
