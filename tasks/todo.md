# Todo

## Native localhost front/back dev wiring

- [x] Find where the frontend dev server/API host is set to `staging.pycs.localdev`.
- [x] Change the default local setup to use native localhost ports without a vhost.
- [x] Verify backend quality gate after backend-affecting changes, if any.
- [x] Verify frontend quality gate and browser behavior after frontend changes.
- [x] Record review/results.

## Review

- Updated local frontend defaults from `*.pycs.localdev` to `localhost`.
- Removed Nuxt's forced dev vhost so `bun run dev` can use the native localhost dev server.
- Updated Laravel local/example env, CORS, and Sanctum stateful domains for localhost frontend/backend ports.
- Required `bun run quality:check` scripts are missing in both `front/` and `back/`; ran fallback checks instead.
- Verified `composer test`, PHP config syntax, Nuxt typecheck, `http://localhost:3000/`, and localhost backend CORS responses.

Long-lived project documentation belongs in:

- `tasks/project-blueprint.md`
