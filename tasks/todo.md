# Audit Remediation Plan

## Humble Beginnings Founders UI Fix
- [x] Inspect the reported `/about/humble-beginnings-pycs/` founders section and identify the layout cause.
- [x] Keep the founders section inside the main content column without overlapping the sidebar.
- [x] Tune founder cards for stable desktop and mobile rendering.
- [x] Run `cd front && bun run quality:check`.
- [x] Browser-verify `/about/humble-beginnings-pycs/` on `http://localhost:4000` at desktop and mobile widths.
### Review
- Root cause: `.founders-showcase` used viewport breakout positioning (`left: 50%`, wide viewport-based width, and translate centering) while nested inside the main article column, so it crossed into the right sidebar at laptop widths.
- Fixed the section to stay within the article column, added a calm bordered panel treatment, and changed the founder cards to an auto-fit grid with a 260px minimum card width.
- Verified `cd front && bun run quality:check` passes with the existing 230 warnings and 6 unit tests passing.
- Browser-verified `http://localhost:4000/about/humble-beginnings-pycs/` at 1440px and 390px: 9 cards/images rendered, horizontal overflow was 0, and the desktop founders panel no longer overlapped the sidebar.

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

## Deployment Bundle Script

- [x] Confirm the expected deployment bundle shape for backend source and frontend static output.
- [x] Add a repo-level script that creates `back/` plus `front/` from `front/.output/public`.
- [x] Make backend source collection respect declared Git ignore rules.
- [x] Add a direct command for running the deployment packager.
- [x] Verify the generated bundle structure and ignored-file behavior.

### Review

- Added `ops/create-deployment.mjs`, runnable with `bun ops/create-deployment.mjs`.
- The script creates `deployment/back` from backend source files while applying Git ignore rules, including strict filtering for paths declared ignored.
- The script creates `deployment/front` from the contents of `front/.output/public`.
- Added root `.gitignore` coverage for `/deployment/` so generated bundles are not committed accidentally.
- Verified with `bun ops/create-deployment.mjs --out $env:TEMP/pycs-deployment-check`: output contains only `back/` and `front/`; backend `.env`, `vendor`, and `node_modules` are absent; `back/artisan`, `back/composer.json`, `front/index.html`, and `front/_nuxt` are present.

## Root Deployment Commands

- [x] Add root-level Bun command shortcuts for deployment packaging.
- [x] Keep the root shortcut delegated to the existing `ops/create-deployment.mjs` implementation.
- [x] Verify the root command creates the same bundle shape.

### Review

- Added `deploy.mjs` at the repo root, so `bun deploy.mjs` runs the deployment packager.
- Added root `package.json` scripts: `deploy`, `deploy:bundle`, `deploy:generate`, `front:generate`, `front:quality`, and `back:quality`.
- Verified `bun deploy.mjs --out $env:TEMP/pycs-root-deploy-direct`.
- Verified `bun run deploy --out $env:TEMP/pycs-root-deploy-script`.
- Confirmed the root command output includes `back/artisan` and `front/index.html`, while excluding backend `.env` and `vendor`.

## Deploy Generates Frontend

- [x] Make the default root deploy command run frontend generation first.
- [x] Keep a packaging-only command for reusing an existing `front/.output/public`.
- [x] Verify deploy still creates the expected bundle shape.

### Review

- Updated `deploy.mjs` so the default root deploy flow runs `bun run --cwd front generate` before packaging.
- Updated root `package.json` so `bun run deploy` and `bun run deploy:generate` run generate plus bundle, while `bun run deploy:bundle` only packages existing output.
- Verified `bun run deploy --out $env:TEMP/pycs-deploy-generates-front`; it generated `front/.output/public` and created the deployment bundle.
- Confirmed the bundle includes `back/artisan` and `front/index.html`, while excluding backend `.env` and `vendor`.
- Checked exact `bun deploy`; Bun rejects it as a reserved future subcommand and tells callers to use `bun run deploy`.

## Deployment Zip Artifact

- [x] Create a zip file when the deployment bundle is generated.
- [x] Keep backend files inside the zip under top-level `back/`.
- [x] Keep frontend static files inside the zip under top-level `front/`.
- [x] Verify the zip file exists and contains expected backend/frontend entries.

### Review

- Updated `ops/create-deployment.mjs` so every deployment bundle also creates a sibling `.zip` file.
- The default output now produces both `deployment/` and `deployment.zip`.
- Verified `bun run deploy:bundle --out $env:TEMP/pycs-deployment-zip-check` creates `pycs-deployment-zip-check.zip`.
- Verified full `bun run deploy --out $env:TEMP/pycs-deployment-full-zip-check` runs frontend generation, creates the bundle, and creates `pycs-deployment-full-zip-check.zip`.
- Confirmed zip entries include `back/artisan`, `back/composer.json`, and `front/index.html`, while excluding backend `.env` and `vendor`.
- Noted Nuxt generation still logs the existing production API 404 for `/api/school-updates`, but exits successfully and produces the artifact.
## Plain-Language Changelog
- [x] Review recent commit messages and existing documentation for user-facing changes.
- [x] Create a layperson-friendly changelog file.
- [x] Cross-check the changelog against the commit history.
### Review
- Added `CHANGELOG.md` with a non-technical summary of recent changes through April 26, 2026.
- Cross-checked sections against recent commit messages for school updates, school years, calendar, galleries, homepage activity, security, login compatibility, frontend/backend foundations, deployment packaging, and maintenance work.
- No frontend or backend code changed, so `bun run quality:check` was not required.

## Production Login CSRF Mismatch
- [x] Inspect backend session, CORS, Sanctum, and login route configuration.
- [x] Inspect frontend login API flow and deployed runtime config assumptions.
- [x] Patch the smallest root-cause fix for production CSRF token mismatch.
- [x] Run mandatory backend quality checks for touched backend config.
- [x] Confirm browser verification is not required because no `front/` files changed.

### Review
- Confirmed login is Fortify's `POST /login` behind web CSRF, while the Nuxt app uses `nuxt-auth-sanctum` cookie mode.
- Updated Sanctum defaults to include the configured frontend host when `SANCTUM_STATEFUL_DOMAINS` is not set.
- Added production env guidance for shared `SESSION_DOMAIN`, exact CORS origin, stateful domains, secure cookies, and config cache refresh.
- Verified `cd back && bun run quality:check` passes with 26 tests.
- Verified Laravel registers Fortify `POST /login` and Sanctum `GET /sanctum/csrf-cookie`.
- Verified Sanctum config now includes the configured frontend/app hosts without malformed comma-prefixed entries.

## PYCS Production Env Files
- [x] Check existing backend/frontend env files.
- [x] Create production env files for `pycs.school` and `api.pycs.school`.
- [x] Review the files for CSRF/Sanctum correctness.

### Review
- Added `back/.env.pycs.production` for the production API host `https://api.pycs.school`.
- Updated `front/.env.prod` to use `https://pycs.school` and `NUXT_SITE_ENV="production"`.
- Confirmed frontend production scripts load `front/.env.prod`.
- Verified `cd front && bun run quality:check` passes with existing warnings.
- Verified `cd front && bun run generate` completes with existing CSS/chunk warnings.
- Browser-verified `http://localhost:4000/` loads with page title `PYCS`.

## School Updates UI Refresh

- [x] Compare `/school-updates` with `/school-calendar` and identify the plain areas.
- [x] Refresh the School Updates hero, stat/action panel, filters, feed cards, and sidebar while preserving existing data behavior.
- [x] Keep the design aligned with `DESIGN.md` and existing welcome-page styling.
- [x] Run mandatory `cd front && bun run quality:check`.
- [x] Browser-verify `http://localhost:4000/school-updates`.
- [x] Record review/results.

### Review

- Reworked the School Updates page to use an image-backed hero, glass stat/action panel, stronger featured-update treatment, clearer feed filtering, and rounded sidebar panels aligned with School Calendar.
- Added active-filter state and a clear-filter action without changing the existing fetch, CRUD, or reader behavior.
- Verified `cd front && bun run quality:check` passes; the repo still reports its existing 230 lint warnings and low coverage summary.
- Browser-verified `http://localhost:4000/school-updates` at desktop and 390px mobile widths with no console warnings/errors and no horizontal overflow on mobile.

## SSG Navigation Failure

- [x] Inspect Nuxt routing, SSG configuration, route rules, and base URL settings.
- [x] Inspect navigation components and NuxtLink/href definitions.
- [x] Reproduce navigation in dev and generated production output with browser console/network checks.
- [x] Verify generated static files exist for key internal routes.
- [x] Implement the smallest root-cause fix.
- [x] Run mandatory `cd front && bun run quality:check`.
- [x] Verify generated SSG navigation works with URL/history updates.
- [x] Record root cause and verification results.

### Review

- Root cause: production-only `swr` route rules cached SSG HTML for `/`, `/galleries`, `/school-calendar/**`, and `/school-updates/**`. A later generate emitted new `_nuxt` assets but served stale HTML with old build IDs and old asset hashes, so the browser 404'd the client bundle and Vue Router never hydrated.
- Confirmed `NuxtLink` hrefs and generated route files were valid; the failure was an HTML/assets mismatch in static output, not a bad link definition.
- Removed the SSG-incompatible SWR route rules from `front/nuxt.config.ts` while keeping the global security headers.
- Verified `bun run generate` succeeds and generated HTML no longer references stale assets such as `entry.ekFPX4Y6.css` or old JS chunks.
- Verified generated files exist for `/`, `/about/humble-beginnings-pycs`, `/school-calendar`, `/school-updates`, and `/galleries`.
- Browser-verified generated static navigation from `http://127.0.0.1:4173/` to `/about/humble-beginnings-pycs`, `/school-calendar`, `/school-updates`, and `/galleries`; URLs update and target pages render.
- Browser-verified all `_nuxt` script/style assets return 200 on the sampled generated pages.
- Browser-verified dev navigation from `http://localhost:4000/` to `/about/humble-beginnings-pycs` still works.
- `cd front && bun run quality:check` passes with the repo's existing 230 lint warnings and 6 passing unit tests.
- Remaining local static-preview console errors are API CORS errors from `127.0.0.1:4173` against `https://api.pycs.school`, not Nuxt routing or missing build assets.

## Homepage Announcement Text Layout
- [x] Locate the homepage announcement/card component shown in the screenshot.
- [x] Make the image fill/stretch its visual area without breaking the card layout.
- [x] Render the announcement body as normal paragraph copy instead of broken short lines.
- [x] Run `cd front && bun run quality:check`.
- [x] Browser-verify the reported view at `http://localhost:4000`.
### Review
- Updated the active announcement card so the PrimeVue image root and inner image fill the panel; the poster now uses `object-fill` instead of `object-cover`.
- Flattened homepage announcement descriptions into plain paragraph text before rendering in the card, so line-by-line pasted rich text displays as one readable paragraph.
- Verified `cd front && bun run quality:check` passes with the existing 230 lint warnings and 6 unit tests passing.
- Browser-verified `http://localhost:4000` in Chromium. The live API currently returns no active announcements, so the empty state appears with real data; a mocked active-announcement browser check confirmed the stretched image and paragraph rendering.

## Homepage Announcement Fixed Poster Frame
- [x] Set a fixed desktop poster frame so all announcement images render at the same size.
- [x] Remove image transition movement that makes the banner feel unfixed.
- [x] Browser-verify the homepage at exactly 1440px wide.
- [x] Run `cd front && bun run quality:check`.
### Review
- Desktop announcement posters now render in a fixed `260px x 390px` frame at the `xl` breakpoint, so every banner image has the same visible size.
- Removed scale, rotation, and vertical image motion from the carousel transition so the poster stays fixed while announcements change.
- Browser-verified a mocked active announcement at `1440px` wide: rendered image and frame both measured `260x390`, paragraph text was flattened, and there were no console warnings/errors.
- `cd front && bun run quality:check` passes with the repo's existing 230 lint warnings and 6 unit tests passing.

## Homepage Hero Desktop Alignment
- [x] Move the left hero copy closer to the left desktop edge at 1440px.
- [x] Enlarge the announcement card and poster frame for the first-screen banner.
- [x] Give the announcement description column more width toward the right desktop edge.
- [x] Browser-verify the homepage at exactly 1440px wide.
- [x] Run `cd front && bun run quality:check`.
### Review
- Changed the desktop hero wrapper to a full-width grid with equal side padding at 1440px.
- Enlarged the announcement card to `680px` wide and fixed the poster at `330px x 420px`.
- Overrode the card's desktop auto margins so the card aligns to the right hero boundary instead of centering in the right column.
- Browser-verified a mocked active announcement at `1440px`: hero padding `60px` left/right, left headline `x=60`, card `x=700` to `x=1380`, image `330x420`, and description text from `x=1061` to `x=1361`, with no console warnings/errors.
- `cd front && bun run quality:check` passes with the repo's existing 230 lint warnings and 6 unit tests passing.

## Homepage Announcement Taller Banner
- [x] Increase the desktop announcement poster height slightly.
- [x] Browser-verify the homepage at exactly 1440px wide.
- [x] Run `cd front && bun run quality:check`.
### Review
- Increased the desktop announcement poster from `330px x 420px` to `330px x 460px`.
- Browser-verified at `1440px` with a mocked active announcement: card stayed `680px` wide, card height became `492px`, image rendered at `330px x 460px`, and the card kept its right edge at `x=1380`.
- `cd front && bun run quality:check` passes with the repo's existing 230 lint warnings and 6 unit tests passing.

## Gallery School Year Bug
- [x] Map gallery creation flow and identify where school year is chosen.
- [x] Fix gallery creation so it uses the currently selected/new SY 2026-2027 instead of defaulting to 2025-2026.
- [x] Run mandatory quality checks for changed app areas.
- [x] Browser-verify gallery creation on `http://localhost:4000`.
### Review
- Gallery creation was using the module-level `GALLERY` default date, which resolves to the current date and put April 2026 drafts under SY 2025-2026 even when the UI selected SY 2026-2027.
- New gallery drafts now derive their default date from the selected school-year range, falling back to today only when today belongs to that selected range or no range can be parsed.
- The gallery form is keyed by operation, draft id, and draft start date so each create/edit open remounts with the correct initial values.
- Fixed gallery submit normalization so `end` is saved as end-of-day instead of start-of-day.
- `cd front && bun run quality:check` passes with the repo's existing 230 lint warnings and 6 unit tests passing.
- Browser-verified `http://localhost:4000/galleries` with selected `SY 2026-2027`; an intercepted create request sent `start: 2026-06-01 00:00:00` and `end: 2026-06-01 23:59:59` without creating a real gallery record.

## Gallery School Year Bug Deep Investigation
- [x] Reproduce the real create flow with SY 2026-2027 present in school-year data.
- [x] Trace where selected school year can be reset or lost.
- [x] Patch the root cause.
- [x] Run `cd back && bun run quality:check`.
- [x] Run `cd front && bun run quality:check`.
- [x] Browser-verify the exact gallery creation flow.
### Review
- Root cause: galleries had no `school_year_id`, so the gallery page inferred school year from `start`. That made new-gallery placement depend on date defaults and form state instead of the selected school year.
- Added nullable `school_year_id` to galleries with a foreign key, model fillable/cast support, request validation, and `SchoolYear`/`Gallery` relationships.
- The gallery form now carries `school_year_id`; new drafts set it from the selected school year and submit it with the create/update payload.
- Gallery filtering now prefers explicit `gallery.school_year_id` and only falls back to date-range inference for legacy records without a school-year id.
- School-year buttons are sorted by parsed start year, so `SY 2026-2027` becomes the default newest year regardless of database insertion order.
- Backend quality passed via `cd back && bun run quality:check`.
- Frontend quality passed via `cd front && bun run quality:check` with the existing 230 warnings and 6 unit tests passing.
- Applied the local backend migration with `php artisan migrate --force`.
- Browser regression used mocked API data with both `SY 2025-2026` and `SY 2026-2027`: selected year stayed `SY 2026-2027`, visible galleries excluded the old SY, and the intercepted create request included `school_year_id: 11`, `start: 2026-06-01 00:00:00`, and `end: 2026-06-01 23:59:59`.

## Login Refresh Persistence Bug
- [x] Reproduce login becoming unauthenticated after refresh.
- [x] Inspect frontend Sanctum identity initialization and cookie/session handling.
- [x] Inspect backend Sanctum/session/CORS config.
- [x] Fix auth persistence after refresh.
- [x] Run mandatory backend/frontend quality checks for touched areas.
- [x] Browser-verify login remains authenticated after refresh.
### Review
- Root cause: Nuxt Sanctum was configured with `initialRequest: false`, so a hard refresh did not reliably rehydrate the authenticated user. Authenticated UI derived from `useSanctumAuth().user` could reset to guest state even though the browser still had the session cookie.
- Fix: enabled Sanctum's initial identity request, added a client boot init plugin, and made the navbar explicitly call `refreshIdentity()` on mount when the local auth state is empty. Guest refresh failures are swallowed so normal unauthenticated page loads still work.
- Verification: `cd front && bun run quality:check` passed with the existing warnings and 6 tests passing.
- Browser verification: `lean-ctx -c "node tasks/auth-refresh-check.cjs http://localhost:4013"` returned `AUTH_REFRESH_CHECK before=true after=true mock=true userRequests=2 errors=0`.
- Note: browser verification used `http://localhost:4013` because the existing `localhost:4000` process could not be stopped on Windows due access denial. Auth endpoints were mocked because the local login credentials returned `/login` 422, isolating the frontend refresh-persistence behavior.

## Announcement Banner Placeholder Size
- [x] Locate the homepage announcement banner image rendering path.
- [x] Make the fallback/no-image placeholder fill the same frame as real images.
- [x] Run mandatory frontend quality check.
- [x] Browser-verify the homepage announcement banner at the reported viewport.
### Review
- Root cause: the announcement card reserved a tall fixed frame, but the PrimeVue `Image` root and fallback SVG could still render from the placeholder image's square intrinsic sizing.
- Fix: scoped the banner image wrapper and forced the PrimeVue `.p-image` root plus inner `img` to fill the same height and width as the announcement frame.
- Verification: `cd front && bun run quality:check` passed with the existing 230 lint warnings and 6 unit tests passing.
- Browser verification: `lean-ctx -c "node tasks/announcement-placeholder-check.cjs http://localhost:4013"` returned `ANNOUNCEMENT_PLACEHOLDER_CHECK frame=330x460 root=330x460 image=330x460`.

## Announcement Banner Mobile Image Ratio
- [x] Inspect the responsive announcement banner image frame.
- [x] Make the mobile/tablet image use a tall poster ratio when stacked under the hero text.
- [x] Update the browser regression check for mobile/tablet sizing.
- [x] Run mandatory frontend quality check.
- [x] Browser-verify the homepage announcement banner at mobile/tablet widths.
### Review
- Root cause: the stacked mobile/tablet banner used `h-64 sm:h-72 w-full`, which made announcement images render as a wide shallow rectangle below the hero text.
- Fix: changed the announcement image frame to a centered tall poster ratio, capped at `330px` wide, and kept the existing `330x460` desktop sizing.
- Verification: `cd front && bun run quality:check` passed with the existing 230 lint warnings and 6 unit tests passing.
- Browser verification: `lean-ctx -c "node tasks/announcement-placeholder-check.cjs http://localhost:4013"` returned `ANNOUNCEMENT_PLACEHOLDER_CHECK mobile=328x457 tablet=330x460 desktop=330x460`.
