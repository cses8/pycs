# Full-Stack Audit Report

Date: 2026-04-25

## Executive Summary

The stack is Nuxt 4.4.2 / Nitro 2.13.3 on the frontend and Laravel Framework 13.6.0 on the backend. Existing local quality gates pass, and the Nuxt production build succeeds, but the repository does not currently prove the requested Lighthouse, coverage, security, or 100k-concurrency targets.

Backend hardening performed during this audit:

- Disabled Fortify public registration to prevent arbitrary account creation from reaching Sanctum-protected mutation routes.
- Fixed announcement update/delete route model binding from `{gallery}` to `{announcement}`.
- Removed request-body `id` control from announcement updates.
- Added regression tests for disabled registration, announcement route-bound updates, and missing announcement routes.

## Verified Baseline

- `cd back && bun run quality:check`: passed.
- `cd front && bun run quality:check`: passed, but it lints only a small named file set before `vue-tsc`.
- `cd front && bun run build`: passed on Nuxt 4.4.2 / Nitro 2.13.3.
- `cd back && composer audit --no-ansi`: no advisories found.
- `cd front && bun audit`: failed with high and moderate advisories.

## Critical And High Findings

1. Public registration plus broad authenticated mutations was a privilege escalation path. Public registration is now disabled, but a real role/permission model is still needed for content administration.
2. Frontend dependency audit currently fails with high advisories in transitive packages including `tar`, `h3`, `defu`, `flatted`, `lodash`, `node-forge`, `picomatch`, and related packages.
3. Backend announcement update/delete routing was incorrect. This is fixed and covered by tests.
4. Backend image uploads load full images into GD after accepting 10 MB files without dimension limits. Add pixel dimension validation, lower limits, streaming-safe processing, and upload throttles.
5. Frontend renders backend rich content through `v-html`. Add server-side sanitization, sanitize stored legacy content, and enforce a restrictive CSP.

## Frontend Architecture Review

Positive signals:

- Nuxt 4 is installed and configured with `srcDir: 'app/'`.
- Auto-imports are enabled.
- Components, composables, stores, pages, layouts, assets, public, and server folders exist.
- `RecentActivity` uses `useAsyncData` and cached payload/static data.

Gaps:

- `front/.nuxtrc` sets `ssr=false`, so the app is effectively SPA-rendered despite SSR-oriented modules.
- Nitro usage is minimal: `front/server` only contains `tsconfig.json`; Nuxt config mainly uses Nitro dev proxying.
- No `routeRules` or explicit hybrid rendering strategy exists.
- `nuxt-delay-hydration` is listed twice.
- No `@nuxt/image` or `NuxtImg` image pipeline is in use; key images are raw `<img>` tags.
- No Vitest, Playwright, Storybook, Lighthouse CI, or frontend coverage setup is present.
- The production build contains a very large client chunk around 877 kB raw and emits repeated PostCSS lexical warnings.
- Navigation has mouse/click-only submenu behavior that needs keyboard and ARIA support.

## Backend Architecture Review

Positive signals:

- Laravel 13.6.0 is installed.
- Controllers, FormRequests, models, policies, migrations, seeders, and Pest tests exist.
- School update migrations include useful indexes on slug, type, category, status, and published date.
- Existing tests cover school calendars and school updates.

Gaps:

- Most business logic lives in controllers; repositories, DTOs, resource classes, domain services, jobs, and events are mostly absent.
- Policies exist for some models but are not consistently enforced on API mutations.
- Some public reads are unpaginated or sorted in memory.
- Database constraints do not fully match validation rules, including missing unique and foreign-key constraints.
- Log Viewer is installed; production access must be explicitly gated or disabled.
- Cache/session defaults are database-backed, not Redis-backed.

## Security Review

Implemented:

- Closed Fortify public registration.
- Added tests for the registration route being unavailable.
- Added CI scaffolding with Composer audit, Bun audit, and CodeQL.

Remaining:

- Add role/permission authorization and `can:` middleware or request policy checks for every mutation.
- Add strict security headers: HSTS, CSP, X-Content-Type-Options, X-Frame-Options, Referrer-Policy, and Permissions-Policy.
- Restrict CORS origins, methods, and headers in production.
- Add write/upload throttles per IP and per user.
- Move password hashing explicitly to Argon2id if that is a production requirement.
- Add DAST, dependency update automation, and deployment blocks for high advisories.

## Caching And Scalability Review

Current proof is insufficient for the requested 100,000 concurrent users with p99 below 200 ms. Required next evidence:

- Production-like load testing with staged ramps and realistic cache warmup.
- Redis for cache/session/queues.
- HTTP cache headers and CDN rules for static assets.
- Laravel route/config/cache optimization in deploy.
- Query result caching with explicit invalidation.
- Database indexing and query plans for high-read endpoints.
- Blue-green deployment runbook with health checks and rollback.

## Accessibility And UX Review

The app has some good alt text and ARIA labels, but WCAG 2.2 AA compliance is not proven. Required next evidence:

- Automated axe or Lighthouse accessibility runs for public and authenticated routes.
- Keyboard testing for navigation, menus, dialogs, and forms.
- Color contrast checks against the actual school-site design system.
- Reduced-motion behavior for animated components.
- Screen-reader smoke testing for data tables and rich content.

## Lighthouse And Coverage Status

No checked-in Lighthouse CI artifacts exist, so 100% Lighthouse scores cannot be claimed.

Vitest, Nuxt test utilities, coverage, and Playwright are now configured, but current frontend coverage is still only 0.12% statements from the smoke coverage that exists today. Do not claim 90% frontend coverage until focused component/page tests are added and thresholds are raised.

## Verification Results

- Backend quality: `cd back && bun run quality:check` passed with 19 tests and 70 assertions.
- Frontend quality: `cd front && bun run quality:check` passed with 0 lint errors, 230 lint warnings, vue-tsc success, and Vitest success.
- Browser smoke: `cd front && bun run test:e2e` passed in Chromium against `http://localhost:4000`.
- Production build: `cd front && bun run build` passed, with known PostCSS lexical warnings and one large client chunk warning.
- Production runtime: built Nitro server returned HTTP 200 for `/` and emitted security/cache headers.
- Security audits: `cd back && composer audit --no-ansi` passed; `cd front && bun run audit:security` passed for high-severity advisories.

## Next Priority Work

1. Replace warning-only frontend lint debt with clean code and stricter rules once the current warning set is burned down.
2. Raise frontend coverage toward 90% with page, component, store, and composable tests before enabling threshold enforcement.
3. Add Lighthouse CI and publish measured Performance, Accessibility, Best Practices, and SEO scores.
4. Resolve Nuxt build warnings: Tailwind/PostCSS `100% * <alpha-value>`, stale Browserslist data, and the large client chunk.
5. Run production-like load testing and publish p50/p95/p99 reports before claiming 100k-user or sub-200 ms p99 capacity.
