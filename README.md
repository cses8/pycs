# Philippine Yuh Chiau School Web Stack

This repository contains a Nuxt 4 frontend in `front/` and a Laravel 13 API in `back/`.

## Local Setup

### Backend

```powershell
cd back
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
bun run quality:check
php artisan serve --host=127.0.0.1 --port=8000
```

### Frontend

```powershell
cd front
bun install
copy .env.local .env.local
bun run quality:check
bun run dev
```

Use `http://localhost:4000` for browser verification. If the app redirects to `/login`, use the local seeded account documented in the project agent instructions.

## Quality Gates

- Backend: `cd back && bun run quality:check`
- Frontend: `cd front && bun run quality:check`
- Frontend production build: `cd front && bun run build`
- Backend dependency audit: `cd back && composer audit --no-ansi`
- Frontend dependency audit: `cd front && bun run audit:security`

The release-blocking dependency gate checks high-severity advisories. Full `bun audit` can still report lower-severity ecosystem advisories, so review those separately before each release.

## Deployment Notes

- Point the backend web server document root directly at `back/public`; avoid rewriting a parent directory into `public/`.
- Run Laravel deploy steps atomically: `composer install --no-dev --optimize-autoloader`, `php artisan migrate --force`, `php artisan config:cache`, `php artisan route:cache`, and `php artisan storage:link`.
- Build the frontend with production environment values and serve the Nuxt Nitro output from `front/.output/server/index.mjs`.
- Keep production `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`, explicit CORS origins, and HTTPS-only cookies.
- Use blue-green or rolling deploys with health checks against Laravel `/up` and a Nuxt route smoke test before traffic promotion.

## Scaling Targets

The codebase does not yet prove 100,000 concurrent users or sub-200 ms p99 latency. Use `ops/load/k6-api-smoke.js` as the starting harness, then run staged tests from production-like infrastructure with Redis, CDN caching, database replicas, queue workers, and full observability.

## Verified State

- Backend: `cd back && bun run quality:check` passes.
- Frontend: `cd front && bun run quality:check` passes, with existing warnings and low coverage reported by the tool.
- Browser: `cd front && bun run test:e2e` passes against Chromium at `http://localhost:4000`.
- Production: `cd front && bun run build` passes; `node .output/server/index.mjs` returns HTTP 200 for `/`.

## Documentation

- Audit report: `docs/audit-report.md`
- OpenAPI starter: `docs/openapi.yaml`
- Load-test harness: `ops/load/k6-api-smoke.js`
