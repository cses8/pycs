---
name: fullstack-developer
description: Developer-facing guide for extending this repository's full-stack architecture and conventions. Use when adding or modifying API routes, controllers, models, schemas, services, frontend pages, Vue components, Pinia stores, shared CRUD surfaces, validation flow, alias-based imports, or verification steps inside this codebase.
---

# Full-Stack Developer

Developer workflow guide for extending this repository with its established Nuxt, Vue, Bun, Fastify, Mercurius, Mongoose, TypeBox, shared UI primitives, backend aliases, and verification flow.

## Repo Map

- `front/`: Nuxt 4 app on Bun with Vue, Pinia, PrimeVue, and Tailwind using the `tw-` prefix.
- `front/app/components/App/*`: shared frontend primitives such as `AppDatatable`, `AppFormV2`, `AppDialog`, and `AppFilter`.
- `front/app/pages/**`: thin page shells and route entrypoints.
- `front/app/components/**`: feature and shared Vue components.
- `back/`: Fastify + Mercurius + Mongoose + TypeBox backend on Bun.
- `back/src/routes/**`: API entrypoints. Prefer matching nearby route shapes.
- `back/src/controllers/**`: business logic and orchestration.
- `back/src/models/**`, `back/src/schemas/**`, `back/src/services/**`, `back/src/utils/**`, `back/src/policies/**`: domain layers and shared backend behavior.
- `back/.output/**`: generated sibling indexes and aliases consumed by the backend.

## Core Rules

- Inspect neighboring files before adding new files. Match the local pattern first.
- Extend existing controllers, services, policies, stores, and shared UI primitives before introducing parallel structures.
- Prefer backend aliases such as `#controllers`, `#models`, `#utils`, `#schemas`, `#policies`, `#services`, `#routes`, and other generated siblings from `.output`.
- Preserve generated sibling patterns. Do not bypass them with raw source-only imports when an alias or generated index already exists.
- Keep examples and implementations neutral. Focus on `Entity`, `Record`, `Resource`, `List endpoint`, and `Mutation endpoint`.

## Backend Workflow

When adding backend behavior, follow this order:

1. Inspect the nearest existing route pair under `back/src/routes/api/v2/**`.
2. Decide whether the change belongs in an existing controller/service/policy or needs a new one.
3. Add or extend TypeBox request schemas at the route layer.
4. Keep route handlers thin. Push business rules into controllers or existing shared utilities.
5. Reuse the repo's response and validation patterns already used by nearby routes.

### Route Patterns

- For list endpoints, inspect `paginate.ts` first.
- For create/update/delete mutations, inspect `resource.ts` first.
- Use Fastify auth hooks and schema registration the same way nearby routes do.
- Prefer `graphqlProcessor` for paginated reads when the surrounding area already uses GraphQL-backed pagination.
- Prefer `dbQuery` or established controller/model flows for data access instead of inventing a new DAL.

### Models and Schemas

- Follow existing model folder and registration conventions.
- Keep Mongoose model registration hot-reload safe with the repo's established `models["Name"] ?? model("Name", schema)` pattern.
- Match existing TypeBox conventions for request validation.
- Keep validation responsibility at the proper layer: route schema first, handler/controller guards only where the route schema cannot express the full rule.

### Controllers and Services

- Add logic to an existing controller when the behavior belongs to the same responsibility boundary.
- Add a new controller or service only when the behavior would otherwise create a mixed concern.
- Reuse shared utilities before adding another helper implementation in a route folder.
- Avoid placing reusable helpers inside `back/src/routes/**`.

## Frontend Workflow

When adding a frontend surface, follow this order:

1. Inspect the nearest existing page, component, and store shape.
2. Keep route pages thin when surrounding code uses host components or config-driven composition.
3. Prefer existing shared primitives before building custom CRUD UI from scratch.
4. Reuse the current app shell, component contracts, and visual language.

### Preferred UI Primitives

- Use `AppDatatable` for remote list views, pagination, filters, and row actions.
- Use `AppFormV2` for schema-driven create/update/delete flows.
- Use `AppDialog` when the surrounding area already wraps CRUD or detail views in a dialog.
- Use `AppFilter` and its wrappers for filter controls instead of ad hoc filter logic.

### Component and Store Shape

- Match nearby `index.vue`, `store.default.ts`, `state.ts`, `getters.ts`, `actions.ts`, and `types.ts` patterns when that structure already exists.
- Keep config-driven views in getters or store config when nearby code already does that.
- Let shared components own common behavior such as filter registration, payload shaping, and CRUD dialog wiring.
- Do not introduce a parallel UI system or a one-off layout language for routine internal surfaces.

## Verification

Do not stop at code edits. Prove the change fits the repo.

### Frontend Checks

- After any frontend code change under `front/`, always run `cd front && bun run quality:check`.
- Do not use standalone `bun run typecheck` as the primary frontend gate; `quality:check` includes lint and type checking.

### Backend Checks

- After any backend code change under `back/`, always run `cd back && bun run quality:check`.
- Do not use standalone `bun run typecheck` as the primary backend gate; `quality:check` includes lint and type checking.
- Run targeted tests when relevant, especially under `src/tests/api`

### Runtime and Generated State

- If backend source changes rely on generated indexes or watched runtime artifacts, confirm the generated output is current.
- If the running environment depends on `.output` or timestamp-driven watchers, verify the refreshed runtime state before trusting behavior.
- Compare the implemented route/controller/model flow against a nearby working example when validating.

## Example Tasks

- Add a new paginated API.
- Add a new resource mutation endpoint.
- Add a new controller-backed operation.
- Add a new model-backed entity.
- Add a new schema for route validation.
- Expose a new CRUD screen with `AppDatatable` and `AppFormV2`.

## References

Read these only when needed:

- `front/app/components/App/Datatable/README.md`
- `front/app/components/App/FormV2/README.md`
- `front/app/components/App/Dialog/README.md`
- `front/app/components/App/Filter/README.md`
- `back/src/utils/dbQuery/README.md`
- `back/src/utils/graphqlProcessor/README.md`
- `front/nuxt.config.ts`
- `back/tsconfig.json`
