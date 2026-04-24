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

## School Calendar Redesign

- [x] Inspect current school calendar route and shared calendar components.
- [x] Redesign `/school-calendar` for desktop, tablet, and mobile.
- [x] Run frontend verification.
- [x] Verify the route in a real browser.
- [x] Record review/results.

## Review / Results

- Redesigned `/school-calendar` as a responsive school planning dashboard.
- Updated the academic year selector, upcoming event card, and all-events list to match the new layout.
- Verified typecheck, production build, and browser rendering on desktop, tablet, and mobile.
- `bun run quality:check` could not run because `front/package.json` has no `quality:check` script.
- Browser console still shows the existing unauthenticated `http://localhost:8000/api/user` 401.

## Galleries Redesign

- [x] Inspect current galleries route and shared gallery components.
- [x] Redesign `/galleries` for desktop, tablet, and mobile.
- [x] Run frontend verification.
- [x] Verify the route in a real browser.
- [x] Record review/results.

## Review / Results

- Redesigned `/galleries` with a new responsive hero, collection stats, and gallery card grid.
- Preserved authenticated add, edit, and delete gallery actions.
- Added cover-image fallback handling for galleries without a loadable cover image.
- Verified typecheck, production build, and browser rendering on desktop, tablet, and mobile.
- `bun run quality:check` could not run because `front/package.json` has no `quality:check` script.
- Browser console still shows the existing unauthenticated `http://localhost:8000/api/user` 401.

## Galleries Year Tabs And Dialog

- [x] Inspect gallery modal and school-year data flow.
- [x] Add school-year controls to `/galleries`.
- [x] Redesign the View Gallery dialog.
- [x] Run frontend verification.
- [x] Verify in browser across desktop, tablet, and mobile.
- [x] Record review/results.

## Review / Results

- Added visible academic-year tabs/buttons to `/galleries`.
- Filtered galleries by the selected school-year date range; the gallery records do not currently have a `school_year_id`.
- Redesigned the View Gallery dialog with a dark header, integrated close control, responsive photo grid, and no-image fallback for failed gallery photos.
- Verified Nuxt typecheck and production build.
- `bun run quality:check` could not run because `front/package.json` has no `quality:check` script.
- Verified `/galleries` in browser on desktop, tablet, and mobile; the existing unauthenticated `http://localhost:8000/api/user` 401 remains.
