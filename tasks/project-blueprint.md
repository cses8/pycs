# PYCS Project Blueprint

## Purpose

This repository contains the Philippine Yuh Chiau School website and lightweight admin surface.

The system serves public school content, galleries, announcements, and school calendar information. Authenticated users can manage galleries and announcements directly from the Nuxt frontend through Laravel API endpoints.

## System Overview

The project is split into two applications:

- `back/`: Laravel API/backend application.
- `front/`: Nuxt/Vue frontend application.

Primary runtime stack:

- Backend: Laravel 12, PHP 8.2, Fortify, Sanctum, Pest, MariaDB-oriented configuration.
- Frontend: Nuxt 3.17.5, Vue 3.5, TypeScript, Pinia, PrimeVue 4, Tailwind CSS, nuxt-auth-sanctum, v-calendar.

The backend exposes public read endpoints and authenticated write/upload endpoints. The frontend renders public pages and conditionally shows admin controls when a Sanctum-authenticated user is present.

## Repository Layout

Root files:

- `AGENTS.md`: project workflow and verification instructions for agents.
- `LEAN-CTX.md`: redirect to LeanCTX usage rules.
- `DESIGN.md`: visual contract for frontend work.
- `tasks/todo.md`: this technical blueprint.
- `tasks/lessons.md`: concise agent correction and workflow lessons.

Backend layout:

- `back/app/Http/Controllers`: API controllers for galleries, announcements, school calendars, school years, and auth.
- `back/app/Http/Requests`: Laravel FormRequest validation classes.
- `back/app/Models`: Eloquent models.
- `back/app/Policies`: scaffolded resource policies.
- `back/app/Actions/Fortify`: Fortify account actions.
- `back/app/Console/Commands`: gallery folder sync command.
- `back/routes/api.php`: public and authenticated API routes.
- `back/routes/web.php`: Laravel web root route.
- `back/database/migrations`: database schema.
- `back/database/seeders`: initial seed data.
- `back/tests`: Pest tests.

Frontend layout:

- `front/app.vue`: global shell, toast, loader, login modal, layout/page outlet.
- `front/nuxt.config.ts`: Nuxt modules, runtime config, proxy, build options.
- `front/pages`: route pages.
- `front/components`: page, app, form, gallery, calendar, and UI components.
- `front/composables`: API wrappers, Sanctum mutation helper, theme/image helpers.
- `front/stores/app`: Pinia stores for loader, notifications, and selected school year.
- `front/types`: global TypeScript declarations.
- `front/utils`: URL, token, formatting, object, task, random, and storage utilities.
- `front/public/images`: public static assets.
- `front/assets`: fonts, styles, and theme CSS.

## Backend Blueprint

### Framework And Runtime

Backend dependencies:

- `laravel/framework:^12.0`
- `laravel/fortify:^1.25`
- `laravel/sanctum:^4.0`
- `opcodesio/log-viewer:^3.15`
- `pestphp/pest:^3.8`
- `pestphp/pest-plugin-laravel:^3.2`

Important backend behavior:

- `bootstrap/app.php` enables `statefulApi()` for Sanctum SPA authentication.
- `config/cors.php` permits one configured frontend origin and supports credentials.
- `config/fortify.php` registers Fortify auth routes with web middleware and enables registration, password reset, profile updates, password updates, and two-factor auth.
- File uploads are stored on Laravel's `public` disk and exposed through `public/storage`.

### Public API Routes

Defined in `back/routes/api.php`:

- `GET /api/school-years`
- `GET /api/galleries`
- `GET /api/announcements/active`
- `GET /api/announcements`
- `GET /api/school-calendars`

### Authenticated API Routes

Protected by `auth:sanctum`:

- `GET /api/user`
- `POST /api/galleries`
- `PUT /api/galleries/{gallery}`
- `PATCH /api/galleries/{gallery}`
- `DELETE /api/galleries/{gallery}`
- `POST /api/upload/gallery/{gallery}`
- `DELETE /api/upload/gallery/{gallery}/{filename}`
- `POST /api/announcements`
- `PUT /api/announcements/{gallery}`
- `PATCH /api/announcements/{gallery}`
- `DELETE /api/announcements/{gallery}`
- `POST /api/upload/announcements/{announcement}`

Note: announcement route placeholders use `{gallery}` for update/delete even though they operate on announcements. This should be renamed for clarity.

### Backend Resources

Gallery:

- Controller: `GalleryController`.
- Model: `Gallery`.
- Public listing: sorted by descending `start`.
- Create/update: validates title, description, start, and end.
- Delete: removes database record and storage directory.
- Upload: validates `files[]`, converts images to WebP using GD, stores sequential files under `galleries/{id}`, and updates image count.
- Delete image: removes one numbered WebP file, renumbers remaining files, and updates image count.

Announcement:

- Controller: `AnnouncementController`.
- Model: `Announcement`.
- Public listing: sorted by descending `start`.
- Active listing: current time between `start` and `end`.
- Create/update: validates title, description, start, and end.
- Delete: removes database record and associated storage directory.
- Upload: validates `files[]`, converts image to WebP, and stores it as `announcements/{id}/{id}.webp`.

School Calendar:

- Controller: `SchoolCalendarController`.
- Model: `SchoolCalendar`.
- Current implementation is read/filter only.
- Filters by `schoolYearId`, optional date range, and optional `upcoming=true`.
- Create/update/delete methods are still stubs.

School Year:

- Controller: `SchoolYearController`.
- Model: `SchoolYear`.
- Current implementation returns all school years.
- Create/update/delete methods are still stubs.

User/Auth:

- Model: `User`.
- Uses Fortify two-factor support and Laravel hashed password casting.
- A custom `API\AuthController` exists for token-style login/logout, but it is not currently routed in `api.php`.
- The frontend currently uses `nuxt-auth-sanctum`, which aligns more with Fortify/Sanctum SPA auth.

## Data Model Blueprint

Core tables:

- `users`: name, email, password, remember token, timestamps.
- `personal_access_tokens`: Sanctum token table.
- `school_years`: description.
- `galleries`: title, description, start, end, image_count.
- `announcements`: title, description, start datetime, end datetime.
- `school_calendars`: school_year_id, start date, end date, image, title, description.
- Laravel infrastructure tables: sessions, cache, jobs, failed jobs, password reset tokens.

Important schema notes:

- `galleries.start` and `galleries.end` are strings, while announcements use `dateTime`.
- `galleries.image_count` is stored as a string but used like a number in frontend types.
- `school_calendars.school_year_id` is indexed but not declared as a foreign key.
- `SchoolCalendar` and `SchoolYear` currently lack fillable/casts because writes are not implemented.

## Frontend Blueprint

### Framework And Runtime

Frontend dependencies include:

- Nuxt 3 and Vue 3.
- Pinia for app state.
- PrimeVue for UI components and theme.
- Tailwind CSS for utility styling.
- `nuxt-auth-sanctum` for auth composables.
- `v-calendar` for calendar UI.
- Dayjs for date formatting.
- VueUse for reactive utilities.

Nuxt config highlights:

- `runtimeConfig.public.backendBase` reads `NUXT_PUBLIC_BACKEND_BASE_URL`.
- Nitro dev proxy maps `/api` and `/storage` to the backend.
- Dev server is configured for `staging.pycs.localdev:3000`.
- Production build uses Terser and drops console logs.
- PrimeVue uses the custom theme in `front/themes/my-theme.js`.

### Global Shell

`front/app.vue` mounts global application services:

- `Toast`
- `DevBreakpoints`
- `AppLoader`
- `AppLogin`
- `NuxtLayout`
- `NuxtPage`

The frontend uses layouts for page framing:

- `welcome.vue`: main public site layout.
- `themed.vue`: centered themed layout with theme switcher.

### State Stores

`useLoaderStore`:

- Drives multi-step loading flows.
- Supports login, logout, gallery, gallery upload, announcement, announcement upload, test, and none steps.

`useNotificationStore`:

- Centralizes success, error, and warning toast state.

`useSchoolYearStore`:

- Holds the selected school year used by gallery/calendar components.

### API Composables

`useGetFetch`:

- Resolves endpoint against `runtimeConfig.public.backendBase`.
- Sends GET requests.
- Includes bearer token header from local storage.
- Handles 401 and 500 responses.

`usePostFetch` and `usePutFetch`:

- Generic mutation helpers with notification handling.
- Current implementation should be reviewed before relying on them for protected calls.

`useSanctumPost`:

- Wraps `useSanctumFetch`.
- Maps operation names to HTTP methods:
  - `create`: POST
  - `update`: PUT with `/{id}`
  - `delete`: DELETE with `/{id}`
  - `upload`: POST with `/{id}` and FormData

`apiUrl`:

- Resolves public storage/API paths against `backendBase`.

### Public Pages And Flows

Home:

- Public landing page using the `welcome` layout.
- Displays school-oriented hero/content sections.

About, Academics, School Operation:

- Static or mostly static informational route groups under `front/pages`.
- Navigation links are defined in `App/NavBar.vue`.

Galleries:

- Page: `front/pages/galleries.vue`.
- Fetches `api/galleries`.
- Shows public gallery cards.
- Authenticated users see add/edit/delete controls.
- Full gallery dialog displays images and supports authenticated image actions.

Announcements:

- Public active announcement component uses backend active announcements.
- Admin table is opened from profile menu.
- Admin table supports create, edit, delete, and image upload.

School Calendar:

- Page: `front/pages/school-calendar/index.vue`.
- Uses `SchoolYear`, `SchoolCalendarAll`, `SchoolCalendarUpcoming`, and `SchoolCalendarVCalendar`.
- Filters calendar events by selected school year and date range.

Developer School Calendar:

- Page: `front/pages/developer/school-calendar.vue`.
- Currently mirrors the public school calendar page.

### Admin UX

Authentication UI:

- Login is a modal component in `App/Login.vue`.
- It uses `useSanctumAuth().login()`.
- Form validation uses Zod through PrimeVue Forms.

Authenticated controls:

- NavBar shows sign-in button when unauthenticated.
- Profile menu appears when authenticated.
- Gallery and announcement management actions are conditionally rendered with `isAuthenticated`.

Upload UX:

- Gallery and announcement upload dialogs use PrimeVue file upload UI.
- Uploads are converted server-side to WebP.
- Loader steps communicate operation progress.

## Environment Blueprint

Backend `.env.example` expects:

- `DB_CONNECTION=mariadb`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=back`
- database-backed session/cache/queue.

Frontend local env:

- `NUXT_PUBLIC_BACKEND_BASE_URL=http://api.pycs.localdev`
- `NUXT_SITE_URL=http://staging.pycs.localdev`
- `NUXT_SITE_NAME=Philippine Yuh Chiau School`

Frontend production env:

- `NUXT_PUBLIC_BACKEND_BASE_URL=https://api.pycs.school`
- `NUXT_SITE_URL=https://staging.pycs.school`
- `NUXT_SITE_NAME=Philippine Yuh Chiau School`
- `NUXT_SITE_ENV=staging`

Deployment notes:

- Laravel storage requires a public storage symlink.
- Hostinger shared hosting may need manual symlink creation and root `.htaccess` rewrite into Laravel's `public` directory.

## Verification Blueprint

Known available commands:

- Backend tests: `cd back && composer test`
- Backend route audit: `cd back && php artisan route:list --path=api`
- Frontend production build: `cd front && bun run build`
- Frontend dev server: `cd front && bun run dev`

Current verification results from this study:

- Backend `composer test`: passed, 2 tests / 2 assertions.
- Backend API route listing: succeeded.
- Frontend `bun run build`: succeeded.
- Frontend build warning: repeated CSS/PostCSS lexical warning, `Lexical error on line 1: Unrecognized text`.

Workflow mismatch:

- Root instructions require `bun run quality:check` after backend/frontend code changes.
- Current `back/package.json` and `front/package.json` do not define `quality:check`.
- Until scripts are added, available verification is Composer test, Laravel route listing, and Nuxt build.

## Known Technical Gaps

Security and auth:

- Seeder contains plaintext account credentials.
- Authenticated write access lacks role/permission checks.
- FormRequest authorization returns `true` for gallery and announcement writes.
- Policy classes exist but are not meaningfully enforced in controllers.
- Custom token `AuthController` exists but is not routed; choose either Fortify/Sanctum SPA auth or token auth as the clear source of truth.

API contract:

- List endpoints return raw arrays.
- Mutation endpoints return resource-specific objects such as `{ message, gallery }`.
- Frontend success types expect `{ status, message, data, httpCode }`.
- Error response shapes are not normalized.

Type and schema alignment:

- Frontend treats `Gallery.image_count` as number; database stores it as string.
- `ErrorResponseType` references `T` without declaring a generic.
- `SchoolYear.id` is typed as string in frontend but backend IDs are numeric by default.

Correctness:

- Announcement update finds by request body `id` instead of route model binding.
- Announcement update/delete need null handling.
- Announcement route placeholders should be renamed from `{gallery}` to `{announcement}`.
- School calendar overlap filtering should include events that fully cover the requested range.
- Rich HTML descriptions are rendered with `v-html`; sanitization policy should be explicit.

Frontend quality:

- `usePostFetch` appears to invert bearer token header logic.
- `nuxt.config.ts` lists `nuxt-delay-hydration` twice.
- Dev server docs/config differ across README, Nuxt config, and root workflow instructions.
- Frontend build has CSS/PostCSS lexical warnings.
- One client chunk is large enough to justify later bundle analysis.

Testing:

- Backend tests are only default example tests.
- No meaningful endpoint, upload, auth, or calendar filter tests are present.
- No frontend component or browser/e2e tests are present.

## Recommended Build-Out Order

1. Normalize API response and error contracts.
2. Align frontend TypeScript types with backend schema.
3. Harden authenticated writes with policies/roles.
4. Remove or route the unused auth controller intentionally.
5. Fix announcement route names and route model binding.
6. Correct calendar overlap filtering.
7. Add real backend feature tests for public reads, protected writes, uploads, and calendar filters.
8. Add or document the required `quality:check` scripts.
9. Trace and remove frontend CSS build warnings.
10. Align dev server and browser verification URLs.

## Source Of Truth Rules

- Use `DESIGN.md` as the visual contract for frontend work.
- Use Laravel FormRequests for validation.
- Use Sanctum/Fortify consistently for auth.
- Keep public read endpoints safe and unauthenticated.
- Keep admin mutation/upload endpoints protected and policy-checked.
- Keep storage paths predictable:
  - galleries: `storage/app/public/galleries/{galleryId}`
  - announcements: `storage/app/public/announcements/{announcementId}/{announcementId}.webp`
- Run the strongest available verification command after any backend or frontend change.
