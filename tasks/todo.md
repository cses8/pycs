# Todo

## Nuxt 4 Directory Structure Migration

- [x] Confirm Nuxt 4 recommended structure from official docs.
- [x] Move app source files into `front/app/`.
- [x] Update config paths for Tailwind, PrimeVue theme, Pinia stores, and Fontaine assets.
- [x] Verify Nuxt typecheck/build.
- [x] Verify frontend in browser across the local dev URL.
- [x] Record review/results.

## Review / Results

- Confirmed Nuxt 4 directory structure against the official Nuxt 4 docs.
- Migrated frontend source into `front/app/` while keeping root-level config, `public/`, and `server/` at the Nuxt project root.
- Verified `bunx nuxi prepare`, `bunx nuxi typecheck --dotenv .env.local`, and `bun run build`.
- Verified `http://localhost:4000/` in browser at desktop, tablet, and mobile viewport sizes.
- `bun run quality:check` could not run because `front/package.json` has no `quality:check` script.
- Browser still reports backend API 500s from `http://localhost:8000/api/user` and announcements endpoints; that is outside the Nuxt directory migration.

Long-lived project documentation belongs in:

- `tasks/project-blueprint.md`

## Announcement Image Fallback

- [x] Locate the active announcement image rendering path.
- [x] Add a default no-image fallback for missing or failed image loads.
- [x] Verify frontend quality gate and browser rendering.
- [x] Record review/results.

## Review / Results

- Added `/images/no-image.svg` as the announcement fallback image.
- Updated the active announcement carousel image renderer to use the fallback when an image URL is missing or fails to load.
- Verified typecheck, production build, and browser rendering at desktop, tablet, and mobile sizes.
- `bun run quality:check` could not run because `front/package.json` has no `quality:check` script.
