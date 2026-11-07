# DLSS5 Admin Control Center (v1.2.0)

**Status: not deployed to Hostinger.**

The controller stub `Dlss5ControlCenterController` references view `admin.dlss5.control_center`, which is not present in the security bundle. `importTrustAnchor()` returns HTTP 501 until root verification is wired to `Dlss5ControlDocumentVerifier`.

Routes are **not** registered on production to avoid exposing an incomplete admin surface.

Owner follow-up: ship the Blade view, step-up auth integration, and completed import handler before admin routes are added behind existing `admin` middleware.
