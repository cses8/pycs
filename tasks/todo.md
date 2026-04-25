# School Year Delete Protection

## Plan

- [x] Identify records that bind to school years.
- [x] Block school year deletion when bound records exist.
- [x] Show the backend block reason in the school year form.
- [x] Run backend/frontend quality checks.
- [x] Browser-verify blocked deletion behavior.
- [x] Record review/results.

## Review / Results

- School years with school calendar records now return HTTP 409 instead of being deleted.
- The school year delete dialog shows the backend block reason to the user.
- Added a regression test proving bound school years cannot be deleted.
- Verification passed: `cd back && bun run quality:check`, `cd front && bun run quality:check`, and browser verification against `SY 2025-2026`.

# School Year Maintenance

## Plan

- [x] Map the existing announcement maintenance flow and current school year API gaps.
- [x] Add backend school year create, update, delete support with validation.
- [x] Add frontend school year table/form maintenance components.
- [x] Wire the school year maintenance entry beside Announcement in the profile menu.
- [x] Run required backend/frontend quality checks.
- [x] Browser-verify the frontend at `http://localhost:4000`.
- [x] Record review/results.

## Review / Results

- Added authenticated school year create/update/delete backend support and a profile-menu maintenance dialog for school year encoding.
- `bun run quality:check` is not defined in `back/package.json` or `front/package.json`; ran available fallback gates instead.
- Verification passed: `composer test`, `bun run build`, and browser create/delete flow for `SY 2099-2100` at `http://localhost:4000`.

## School Updates Module
- [x] Add backend SchoolUpdate model, migration, factory, seeder, requests, controller, routes, uploads, and tests.
- [x] Add frontend `/school-updates` page with public listing, filters, search, authenticated CRUD form, media uploads, and refresh behavior.
- [x] Add School Updates navigation and quick-link entries.
- [x] Add/confirm quality check scripts required by repo instructions.
- [x] Run backend quality gate.
- [x] Run frontend quality gate.
- [x] Browser-test `/school-updates` with the provided authenticated account.
- [x] Record review/results.

## Review / Results

- Added the unified School Updates backend, public/published read API, authenticated management API, media uploads, seed data, and Pest coverage.
- Added the `/school-updates` public/CMS page with responsive listing, filters, tags, inline authenticated CRUD, basic rich-text HTML editing, image uploads, notifications, and refresh behavior.
- Added `School Updates` to the main nav and quick links.
- Added required `quality:check` scripts and verified `cd back && bun run quality:check` and `cd front && bun run quality:check`.
- Browser-verified `/school-updates` at desktop and mobile sizes with `moiseskevin.dev@gmail.com`; create, read, and delete worked, and the temporary browser test record was removed.

# Announcement Modal/DataTable UI + Server Pagination

## Plan

- [x] Map the current announcement modal/table flow and backend list endpoint.
- [x] Identify all form modal/dialog surfaces in `front/app/components/Form`.
- [x] Implement server-side lazy pagination for announcement data.
- [x] Redesign announcement table modal with clearer hierarchy, states, and responsive layout.
- [x] Redesign related form dialogs/modals to match `DESIGN.md`.
- [x] Run required backend/frontend quality checks.
- [x] Browser-verify the frontend at `http://localhost:4000`.

## Review / Results

- Backend announcement index now returns paginated data with `page`, `per_page`, and optional `search`, while preserving the active-announcements endpoint.
- Announcement management modal now uses a denser server-lazy PrimeVue table with pagination, search, image preview, schedule/status columns, compact icon actions, empty state, and responsive sizing.
- Follow-up correction: capped the announcement dialog to viewport height, made the table area flex/scroll-aware, tightened row density, fixed broken-image text overlap with a fallback image, and decoded HTML entities in table descriptions.
- Announcement create/update/delete and upload dialogs were redesigned with structured headers, clear action areas, better field spacing, and upload progress states.
- Follow-up correction: replaced the announcement description rich editor with a clean plain text area, removed the editor toolbar, and normalized existing saved HTML into readable text for edit mode.
- Gallery form and upload dialogs were also redesigned so all current `front/app/components/Form` modals share the same visual language.
- Fixed child form dialogs not rendering from inside the parent Dialog custom container by moving them to sibling roots.
- `cd front && bun run quality:check` failed because `front/package.json` has no `quality:check` script.
- `cd back && bun run quality:check` failed because `back/package.json` has no `quality:check` script.
- `cd front && bun run build` passed. Existing warnings remain for stale Browserslist data, Nuxt/fontaine sourcemaps, Tailwind/PostCSS lexical warnings, chunk size, and Node deprecations.
- Browser verification at `http://localhost:4000` passed with admin login, announcement modal open, form modal open, server pagination visible, and no horizontal overflow at 1440px or 390px. Console still shows the existing pre-login `/api/user` 401.
- Follow-up browser verification passed at the reported `1114x857` viewport: 10 rows loaded, 10 rows visible, paginator visible, modal within viewport, and no horizontal overflow.
- Follow-up browser verification passed at a 600x420 screenshot-style viewport: `textarea[name="description"]` exists, `.ql-toolbar` is absent, and there is no horizontal overflow.
- `cd back && php artisan test` currently fails in unrelated `Tests\Feature\SchoolUpdateTest` cases because `App\Models\User::withAccessToken()` is missing in separate school-update worktree changes; the earlier base example tests passed before those failures appeared.
- `git diff --check` passed for the files touched by this task.

## Humble Beginnings Founder Card Bug
- [x] Inspect the founder card markup and current responsive behavior.
- [x] Fix long founder names so card overlays do not break or overflow.
- [x] Run frontend verification.
- [x] Browser-test `/about/humble-beginnings-pycs` at desktop and mobile sizes.
- [x] Record review/results.

### Review / Results

- Founder cards now use a normal-flow image/details layout instead of an absolute text overlay, so long founder names wrap inside the card.
- Cleared frontend upload component type errors that were causing a Nuxt dev overlay during browser verification.
- `bun run quality:check` could not run because `front/package.json` does not define that script.
- `bun run build` passed with existing warnings only.
- Browser-verified `/about/humble-beginnings-pycs` at 1440x1000, 760x807, and 390x844. The page returned 200, rendered 9 founder cards, had no horizontal overflow, and no broken founder cards were detected.

## Home Academic Program Missing List

- [x] Inspect the home page academic program component and expected content.
- [x] Restore the missing left-side program list for Preschool, Primary School, Junior High School, and Senior High School.
- [x] Run frontend verification.
- [x] Browser-test the homepage at desktop and mobile sizes.
- [x] Record review/results.

### Review / Results

- Restored the academic program list as a persistent left-side rail with Preschool, Primary School, Junior High School, and Senior High School.
- Removed the reveal wrapper from the text column so the heading and description render immediately during real browser checks.
- `bun run quality:check` could not run because `front/package.json` does not define that script.
- `bun run build` passed with existing warnings only.
- Browser-verified `/` at 1440x1000, 541x396, and 390x844. All four requested labels were visible, the page returned 200, and no horizontal overflow was detected.
## School Calendar Authenticated CRUD

- [x] Confirm the fields used by `/school-calendar` data fetching.
- [x] Add authenticated backend create, update, and delete support for school calendar records.
- [x] Add validation for school year, date range, image path, title, and description.
- [x] Add authenticated-only management controls on `/school-calendar`.
- [x] Refresh the public calendar views after authenticated CRUD actions.
- [x] Run backend and frontend quality checks.
- [x] Browser-verify `/school-calendar`.

### Notes

- `/school-calendar` currently reads `GET /api/school-calendars`.
- Displayed fields are `id`, `school_year_id`, `start`, `end`, `image`, `title`, and `description`.
- Mutations should be under the existing `auth:sanctum` route group so guests can keep reading but cannot change records.

### Review / Results

- Added authenticated `POST`, `PUT`/`PATCH`, and `DELETE` routes for `/api/school-calendars`; public `GET /api/school-calendars` remains unchanged for visitors.
- Added school calendar validation for school year, start/end date range, image path, title, and description.
- Added an authenticated-only `/school-calendar` manager with create, edit, delete, search, and refresh controls.
- Calendar, upcoming, and complete schedule views remount after authenticated changes so the public display refreshes.
- Added feature tests proving public read access, authenticated CRUD access, unauthenticated mutation rejection, and validation failures.
- `cd back && bun run quality:check` passed.
- `cd front && bun run quality:check` passed.
- Targeted calendar lint passed for the touched frontend files.
- Browser-verified `http://localhost:4000/school-calendar`: hero, month overview, complete schedule, and events rendered; guest view did not show the management panel.

## Rich Editor Form Consistency

- [x] Confirm PrimeVue Editor capabilities and current announcement, gallery, and school update form patterns.
- [x] Add a shared rich editor toolbar and styling layer for content-management forms.
- [x] Apply the shared PrimeVue Editor to announcement, gallery, and school update content fields.
- [x] Redesign the school update form dialog to follow the announcement form DNA.
- [x] Run `cd front && bun run quality:check`.
- [x] Browser-verify the edited dialogs at `http://localhost:4000`.
- [x] Record review/results and lessons.

### Review / Results

- Added a shared `AppRichEditorToolbar` and `.pycs-rich-editor` styling so content-management rich text fields use the same PrimeVue/Quill control set.
- Announcement, gallery, and school update content fields now use PrimeVue `Editor` with heading, font, bold/italic/underline/strike, quote/code, color, highlight, list, indent, alignment, link, image, and clear-format controls.
- School update create/edit now uses the same announcement-style modal DNA: dark header, icon, operation badge, light form body, consistent labels, rounded inputs, and aligned action buttons.
- Added Vite dependency optimization for `quill` and `quill-delta` so PrimeVue Editor initializes correctly in Nuxt/Vite instead of rendering only an empty editor shell.
- `cd front && bun run quality:check` passed.
- Browser verification passed at `http://localhost:4000` for school updates, galleries, and announcements on desktop `1366x900` and mobile `390x844`; each dialog had visible initialized toolbar/editor content and no horizontal overflow.

## App Header Readability

- [x] Inspect the current App/NavBar structure and design constraints.
- [x] Improve top-bar separation for multi-word menu labels without changing routes.
- [x] Keep the mobile menu readable and aligned with the desktop treatment.
- [x] Run `cd front && bun run quality:check`.
- [x] Browser-verify `http://localhost:4000` at desktop and mobile sizes.
- [x] Record review/results.

### Review / Results

- Updated `AppNavBar` so desktop top-level nav items render as compact bordered tabs, making multi-word labels visually separate from neighboring menu names.
- Shifted the full desktop nav breakpoint from `lg` to `xl`, so medium widths use the hamburger instead of a crowded horizontal row.
- Tightened the brand width on desktop and added a clear divider before account/theme controls.
- Verified `cd front && bun run quality:check` passed.
- Browser-verified `http://localhost:4000` at 1440px, 1024px, and 390px. Header/menu had no horizontal overflow; the 390px menu opened as a stacked list. Existing unauthenticated `/api/user` 401 remains.

## School Calendar Event Form Editor

- [x] Inspect the current school calendar event manager dialog.
- [x] Redesign the event form modal to match the announcement form DNA.
- [x] Replace the description textarea with the shared PrimeVue rich editor.
- [x] Update validation so rich editor empty HTML is treated as empty content.
- [x] Run `cd front && bun run quality:check`.
- [x] Browser-verify `/school-calendar` event modal at desktop and mobile sizes.
- [x] Record review/results and lessons.

### Review / Results

- School calendar event create/edit now uses the same announcement-style modal DNA: dark header, icon, operation badge, light form body, rounded inputs, and aligned actions.
- Replaced the event description textarea with the shared PrimeVue `Editor` and `AppRichEditorToolbar`.
- Rich editor validation now strips HTML before checking required description content.
- `cd front && bun run quality:check` passed.
- Targeted `bunx eslint app/components/SchoolCalendarManager.vue` passed.
- Browser verification passed at `http://localhost:4000/school-calendar` on desktop `1366x900` and mobile `390x844`; the editor initialized, textarea count was zero, and the dialog had no horizontal overflow.

## Responsive Form Modal Content

- [x] Inspect current form modal shells and rich editor overflow behavior.
- [x] Add shared viewport-bounded modal shell/body classes.
- [x] Apply scroll-safe modal layout to all form dialogs.
- [x] Constrain rich editor long text, large images, videos, and code blocks inside the editor.
- [x] Run `cd front && bun run quality:check`.
- [x] Browser-verify long editor content in form dialogs on desktop and mobile.
- [x] Record review/results and lessons.

### Review / Results

- Added shared `.pycs-modal-shell`, `.pycs-modal-header`, and `.pycs-modal-body` classes so form dialogs stay within `100dvh` and scroll internally.
- Applied the scroll-safe shell to announcement, gallery, announcement upload, gallery upload, school year, school calendar event, school update, and login forms.
- Constrained rich editor content so long text wraps and oversized pasted images/videos/code blocks stay inside the editor.
- Removed targeted lint errors in touched form files; remaining targeted output is existing style warnings.
- `cd front && bun run quality:check` passed.
- Browser stress verification passed for school update, school calendar, gallery, and announcement forms at `720x820` and `390x844` with long rich text plus a deliberately oversized image; dialogs stayed inside the viewport, modal bodies scrolled, and editor images did not overflow horizontally.

## App Header Cleanup Correction
- [x] Record lesson from boxed-menu feedback.
- [x] Replace boxed desktop menu items with text links and subtle separators.
- [x] Keep mobile menu readable without heavy boxed styling.
- [x] Run `cd front && bun run quality:check`.
- [x] Browser-verify desktop and mobile header.
- [x] Record review/results.

### Review / Results
- Removed the boxed treatment from top-level desktop menu items and the outer nav container.
- Kept separation with whitespace, thin vertical desktop dividers, mobile row dividers, and a small underline/hover color for submenu items.
- `cd front && bun run quality:check` passed.
- Browser-verified `http://localhost:4000` at 1912px, 1440px, and 390px. Header/menu had no horizontal overflow, mobile menu opened cleanly, and desktop dropdown positioning still worked.
- Existing unauthenticated `/api/user` 401 remains in console.

## Home Academic Pathways Expansion

- [x] Remove the homepage stats strip with Established, Learning Pathway, and Focus.
- [x] Expand Academic Pathways items to show full descriptions.
- [x] Run frontend verification.
- [x] Browser-test the homepage at desktop and mobile sizes.
- [x] Record review/results.

### Review / Results

- Removed the homepage stats strip containing `Established`, `Learning Pathway`, and `Focus`.
- Reworked Academic Pathways into a timeline-style list that shows each program's full description.
- Restored the old visible title `Primary School & Middle School`.
- `cd front && bun run quality:check` passed.
- `cd front && bun run build` passed with existing project warnings only.
- Browser-verified `/` at 1440x1000, 760x900, and 390x844. All four pathway titles and description snippets were visible, the removed stats labels were absent, and no horizontal overflow was detected.
