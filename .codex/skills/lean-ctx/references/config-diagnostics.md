# LeanCTX Config And Diagnostics

## Config

- User config: `~/.lean-ctx/config.toml`.
- Repo override: `.lean-ctx.toml` when project-specific behavior is needed.
- MCP compression for Codex: set `LEAN_CTX_COMPRESS = "1"` under `[mcp_servers.lean-ctx.env]`.
- Multi-root access: `allow_paths = ["D:\\www\\repo", "..."]` or `LEAN_CTX_ALLOW_PATH`.
- Exclusions: `excluded_commands` can be edited directly or via `lean-ctx config set excluded_commands "make,go build"`.

## High-Value Fields

- `ultra_compact`, `output_density`, `tee_mode`, `checkpoint_interval`.
- `rules_scope`, `excluded_commands`, `custom_aliases`, `passthrough_urls`, `redirect_exclude`.
- `allow_paths`, `disabled_tools`, `extra_ignore_patterns`.
- `[loop_detection] normal_threshold`, `reduced_threshold`, `blocked_threshold`, `window_secs`, `search_group_limit`.
- `[autonomy] enabled`, `auto_preload`, `auto_dedup`, `auto_related`, `auto_consolidate`, `silent_preload`.
- Archive/terse controls when available: `archive.*`, `terse_agent`; env overrides include `LEAN_CTX_ARCHIVE*` and `LEAN_CTX_TERSE_AGENT`.

## Key Env Vars

- `LEAN_CTX_COMPRESS`, `LEAN_CTX_HEADLESS`, `LEAN_CTX_ALLOW_PATH`, `LEAN_CTX_DISABLED`, `LEAN_CTX_ENABLED`.
- `LEAN_CTX_SHELL`, `LEAN_CTX_DASHBOARD_PROJECT`, `LEAN_CTX_PORT`, `LEAN_CTX_HOST`.
- `LEAN_CTX_CACHE_TTL`, `LEAN_CTX_CACHE_MAX_TOKENS`, `LEAN_CTX_RULES_SCOPE`, `LEAN_CTX_CRP_MODE`.

## Diagnostics Order

1. Retry the LeanCTX call with tighter path, mode, raw output, tee, filter, `fresh=true`, or configured `allow_paths`.
2. Inspect LeanCTX state: `ctx_context`, `ctx_session`, `lean-ctx doctor`, `lean-ctx gain`, `lean-ctx wrapped`.
3. Inspect observability: `lean-ctx watch`, `lean-ctx dashboard`, `~/.lean-ctx/tool-calls.log`, sessions, tee logs, archives.
4. Use `report-issue` for LeanCTX defects.
5. Fall back only for the smallest blocked operation.

## Behavior Notes

- `ctx_search` is regex-oriented and gitignore-aware.
- Pattern search output is less aggressively compressed than generic output; read it before retrying.
- Cache stubs are actionable; rerun with `fresh=true`.
- PathJail hints are actionable; configure `allow_paths`.
- `LEAN_CTX_HEADLESS=1` skips MCP auto-setup for custom launchers while keeping tools active.
- `lean-ctx login` and `register` are separate; login failures should not create accounts.
- `lean-ctx uninstall` should clean `mcp_servers.lean-ctx.*` sections before reinstall.
