# SUPER DLLS 5 — public bootstrap (unsigned templates)

**Default branch (verified read-only):** `main`  
**Repository:** https://github.com/mabdo5/superdlls5

| File | Raw URL |
|------|---------|
| Operational config template | https://raw.githubusercontent.com/mabdo5/superdlls5/main/dlls5/c1.json |
| OC signature sidecar | https://raw.githubusercontent.com/mabdo5/superdlls5/main/dlls5/c1.json.sig |
| OC key id sidecar | https://raw.githubusercontent.com/mabdo5/superdlls5/main/dlls5/c1.json.key_id |

**OWNER ACTION:** Sign documents offline using `delivery/tools_control_plane/` and `delivery/owner_signing_runbook.md`. Do not commit private keys. Use `.gitattributes` so CRLF cannot break signatures on Windows.

Release binaries belong in **GitHub Releases** (immutable tag e.g. `v1.2.0`), not in this tree.
