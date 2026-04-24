# Todo

## Upgrade Nuxt and Laravel Dependencies

- [x] Confirm current Nuxt/Laravel latest stable versions from primary sources.
- [x] Update frontend package constraints and lockfile.
- [x] Update backend Composer constraints and lockfile.
- [x] Resolve breaking changes or peer dependency conflicts.
- [x] Run required quality gates and fallback framework checks if scripts are missing.
- [x] Verify frontend in a real browser against the backend.
- [x] Record review/results.

## Review

- Upgraded frontend dependencies to stable latest, including Nuxt 4.4.2, Vue 3.5.33, Vite 7.3.2 through Nuxt, PrimeVue 4.5.x, VueUse 14.2.1, and TypeScript 6.0.3.
- Removed `@nuxtjs/web-vitals` because its latest release is not compatible with Nuxt 4.
- Upgraded backend to Laravel 13.6.0, PHP constraint `^8.3`, Tinker 3.0.2, Pest 4.6.3, Pest Laravel plugin 4.1.0, and refreshed the Composer lockfile.
- Fixed Nuxt 4 type errors in local frontend components/composables.
- Required `bun run quality:check` scripts are still missing in both apps; fallback checks passed.
- Verified backend tests, Nuxt typecheck/build, direct API/CORS checks, and browser load at `http://localhost:4000/`.

Long-lived project documentation belongs in:

- `tasks/project-blueprint.md`
