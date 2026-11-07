# DLSS5 require signed releases

When `DLSS5_REQUIRE_SIGNED_RELEASES=true` in production `.env`:

- Admin cannot create or activate a release without `update_manifest_json`, signature, and key id.
- Existing inactive unsigned rows remain in the database; none are active while the policy is on.

Inspection helper (server): `php scripts/inspect_dlss5_releases.php` (read-only).

Set via: `php scripts/enable_require_signed_releases.php` (backs up `.env` first).
