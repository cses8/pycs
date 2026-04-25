# Audit Remediation Plan

- [x] Reconfirm current frontend/backend audit gaps and affected files.
- [x] Fix frontend dependency audit by upgrading or overriding vulnerable packages without breaking Nuxt build.
- [x] Harden backend authorization: add user roles, enforce admin-only mutations/uploads, and test forbidden paths.
- [x] Harden backend HTTP security: security headers, stricter CORS defaults, API write/upload throttles, explicit Argon2id hashing config, and Log Viewer gating.
- [x] Harden backend input handling: sanitize rich HTML and constrain image uploads.
- [x] Align frontend Nuxt SSR/hybrid configuration and remove contradictory `.nuxtrc` settings.
- [x] Add frontend test tooling with Vitest/Nuxt test utils and Playwright smoke coverage.
- [x] Add frontend rich-content sanitization and improve SSR-safe data loading where audit identified client-only fetching.
- [x] Run mandatory `cd back && bun run quality:check`.
- [x] Run mandatory `cd front && bun run quality:check`.
- [x] Run production build/audit checks: frontend build, frontend audit, backend audit.
- [x] Browser-verify `http://localhost:4000` after frontend changes.
- [x] Update README, audit report, and task review with delivered status.

## Review / Results

- Backend quality gate passed: 19 tests, 70 assertions.
- Frontend quality gate passed: ESLint/vue-tsc/Vitest pass, with 230 existing lint warnings and 0.12% coverage still visible.
- Browser verification passed: Playwright Chromium home-page smoke against `http://localhost:4000`.
- Production verification passed: Nuxt build completes and built Nitro server returns HTTP 200 with security/cache headers on `http://localhost:4000/`.
- Security audits passed at release-blocking level: Composer reports no advisories; frontend `bun audit --audit-level high` reports no high advisories.
- Remaining unproven claims: 100% Lighthouse, 90% frontend coverage, 100k concurrent users, and sub-200 ms p99 latency require dedicated instrumentation and production-like load/Lighthouse runs.

## Rich Text Rendering Fix

- [x] Audit all rich-text output paths for PrimeVue Editor HTML.
- [x] Fix sanitized HTML rendering so announcement, gallery, school calendar, and school update text render as HTML on SSR and client.
- [x] Add or update frontend tests for escaped HTML, rendered editor HTML, and unsafe HTML stripping.
- [x] Run mandatory `cd front && bun run quality:check`.
- [x] Browser-verify `http://localhost:4000` after frontend changes.

### Review

- Restored sanitized rich text rendering for PrimeVue Editor output without allowing unsafe scripts or `javascript:` links.
- Verified with `cd front && bun run quality:check`, `cd front && bun run test:e2e`, and `cd front && bun run build`.
- Existing frontend lint warning volume and low coverage are unchanged from the broader audit pass.

## Frontend Dev Server Fix

- [x] Reproduce the `bun run dev` warnings/errors from `front/`.
- [x] Fix the frontend source or config that prevents the site from running.
- [x] Run mandatory `cd front && bun run quality:check`.
- [x] Verify dev server behavior at `http://localhost:4000`.
- [x] Record review/results.

### Review

- Fixed Nuxt dev stale SSR output by limiting SWR route caching to production.
- Replaced node_modules CSS runtime links with a local vendor stylesheet and public PrimeIcons font assets so Windows dev does not emit broken `/_nuxt/@fsD:/...` URLs.
- Added E2E coverage that fails on browser console errors and page errors.
- Verified `bun run quality:check`, `bun run build`, and Chromium against `http://localhost:4000`.

## Browser Console Cleanup

- [x] Reproduce and classify console output from `http://localhost:4000`.
- [x] Remove browser bundling of server-only `sanitize-html`.
- [x] Fix Iconify remote fetch blocked by CSP for announcement controls.
- [x] Remove local debug console output where it comes from app code.
- [x] Run mandatory `cd front && bun run quality:check`.
- [x] Browser-verify `http://localhost:4000` console output.

### Review

- Replaced the `sanitize-html` client import with an SSR/client-safe allowlist sanitizer inside `AppSafeHtml`.
- Swapped lazy announcement carousel arrows from remote-loaded Nuxt Icon entries to local PrimeIcons classes.
- Disabled local Nuxt DevTools output and only loads delay-hydration/Critters in production.
- Added E2E checks that fail on the reported console patterns: `sanitize-html`, browser externalization, CSP icon failures, Nuxt DevTools, delay-hydration, and store-installed logs.
- Verified `bun run quality:check`, `bun run build`, and Chromium against `http://localhost:4000`.

## Frontend Terminal Warning Fix

- [x] Reproduce and classify frontend dev terminal warnings.
- [x] Stop initial auth identity fetch from warning when the Laravel backend is unavailable.
- [x] Remove unresolved `DelayHydration` component warnings.
- [x] Run mandatory `cd front && bun run quality:check`.
- [x] Browser-verify `http://localhost:4000`.

### Review

- Disabled Sanctum's automatic initial identity request so public dev pages do not call `http://localhost:8000/api/user` when the Laravel server is unavailable.
- Removed the stale `DelayHydration` wrapper from the home page and removed the delay-hydration module reference from Nuxt config.
- Captured a fresh `bun run dev` session and verified no `/api/user`, `DelayHydration`, missing-render, non-function slot, or failed component resolution warnings appear.
- Verified `bun run quality:check`, Playwright Chromium E2E, and HTTP 200 from `http://localhost:4000`.

## Local Solar Icon Fix

- [x] Audit Nuxt Icon usage and identify non-Solar collections.
- [x] Install/configure the Solar icon collection locally so Nuxt Icon does not fetch `api.iconify.design`.
- [x] Replace remaining non-Solar Nuxt Icon names with Solar equivalents.
- [x] Add browser/E2E protection against Iconify API fetches.
- [x] Run mandatory `cd front && bun run quality:check`.
- [x] Browser-verify `http://localhost:4000`.

### Review

- Added `@iconify-json/solar` and confirmed it is the only local Iconify JSON collection installed.
- Configured Nuxt Icon with `provider: 'none'`, `fallbackToApi: false`, `collections: ['solar']`, disabled the server bundle, and enabled client-bundle scanning.
- Replaced Lucide, MDI, Material Symbols, Logos, Tabler, Fluent, Flat Color, Healthicons, PH, LA, FA, and BI icon names with Solar equivalents.
- Updated the home E2E test to fail on any `api.iconify.design` or `/api/_nuxt_icon` request.
- Verified `bun run quality:check`, Playwright Chromium E2E, and `bun run build`.

## Home Announcement Rendering Fix

- [x] Decode editor HTML entities before safe rendering.
- [x] Make the home announcement card responsive and prevent encoded/long text overflow.
- [x] Add unit coverage for encoded editor output.
- [x] Run mandatory `cd front && bun run quality:check`.
- [x] Browser-verify `http://localhost:4000`.

### Review

- Decoded editor entities such as `&nbsp;`, `&amp;`, and encoded tags before safe HTML rendering.
- Made the home announcement carousel narrower, single-column until `xl`, and scroll-safe for long rich text.
- Removed the above-the-fold lazy wrapper so the announcement fetch hydrates immediately.
- Replaced invalid Solar icon names with valid local Solar names and confirmed no invalid `solar:` references remain in `app/`.
- Verified `bun run quality:check`, Playwright Chromium E2E at the reported 728px viewport with no horizontal overflow, production `bun run build`, and HTTP 200 from `http://localhost:4000`.

## Backend HTML Sanitizer Alignment

- [x] Compare backend HTMLPurifier allowed elements with frontend `AppSafeHtml`.
- [x] Align backend sanitizer config and include `span` if frontend allows it.
- [x] Add or update backend coverage for the aligned allowlist.
- [x] Run mandatory `cd back && bun run quality:check`.

### Review

- Confirmed the issue existed as an allowlist drift: backend stripped Quill classes and both sides lacked `span`.
- Added `span` to frontend `AppSafeHtml` and backend HTMLPurifier.
- Restricted backend classes to the same Quill class allowlist used by the frontend renderer.
- Restricted backend URI schemes to the frontend-supported safe schemes and aligned allowed link targets.
- Added backend and frontend tests for `span`, safe Quill classes, unsafe class stripping, unsafe attributes, and blocked URL schemes.
- Verified `php artisan test --filter='html sanitizer allows'`, `cd back && bun run quality:check`, `cd front && bun run quality:check`, and Playwright Chromium E2E.

## Frontend SafeHtml URL Scheme Hardening

- [x] Verify whether `data:` URI payloads are accepted by `AppSafeHtml`.
- [x] Make dangerous URL scheme rejection explicit in `SafeHtml`.
- [x] Add regression coverage for `data:` URLs and obfuscated schemes.
- [x] Run mandatory `cd front && bun run quality:check`.
- [x] Browser-verify `http://localhost:4000`.

### Review

- Verified plain `data:` was already denied by the generic unknown-scheme check, so the direct bypass did not exist.
- Added an explicit blocked scheme set for `data`, `javascript`, and `vbscript` to make the security behavior clear and harder to regress.
- Added unit coverage for `data:` link/image payloads, whitespace-obfuscated `d a t a:`, entity-obfuscated `javascript:`, safe relative links, and safe relative images.
- Verified `cd front && bun run quality:check`, Playwright Chromium E2E, and HTTP 200 from `http://localhost:4000`.

## Announcement Route Model Binding Check

- [x] Verify announcement update/destroy route parameter names against controller signatures.
- [x] Add route naming or test coverage needed to prevent parameter drift.
- [x] Run mandatory `cd back && bun run quality:check`.

### Review

- Verified the reported mismatch does not exist in the current code: update, patch, and destroy routes all use `{announcement}`, matching `Announcement $announcement`.
- Named the destroy route as `announcements.destroy` so both mutation routes can be referenced consistently.
- Added feature coverage that asserts the update and destroy routes expose the `announcement` parameter and that Laravel route model binding updates/deletes the bound model.
- Verified `cd back && bun run quality:check`.

## Admin Middleware Null User Check

- [x] Verify `EnsureUserIsAdmin` null-user handling.
- [x] Replace null-safe authorization with an explicit unauthenticated/non-admin check.
- [x] Add focused middleware regression coverage.
- [x] Run mandatory `cd back && bun run quality:check`.

### Review

- Verified the issue existed: `EnsureUserIsAdmin` used `! $request->user()?->isAdmin()`.
- Replaced it with an explicit `! $request->user() || ! $request->user()->isAdmin()` check.
- Added direct middleware tests for unauthenticated, non-admin, and admin users.
- Verified `cd back && bun run quality:check`.

## Legacy Bcrypt Login Compatibility

- [x] Verify Argon2id login failure for existing bcrypt password hashes.
- [x] Add Fortify authentication compatibility for bcrypt and Argon2id only.
- [x] Rehash legacy bcrypt passwords to the configured driver after successful login.
- [x] Add login regression coverage.
- [x] Run mandatory `cd back && bun run quality:check`.

### Review

- Confirmed the failure mode: `HASH_DRIVER=argon2id` with hash verification rejects existing bcrypt password rows before Fortify can authenticate.
- Added a Fortify custom authenticator that accepts only bcrypt and Argon2id hashes, rejects invalid credentials normally, and rehashes legacy bcrypt passwords to the configured driver after a successful login.
- Added regression tests for successful legacy bcrypt login and invalid legacy bcrypt login.
- Verified `cd back && bun run quality:check`.
