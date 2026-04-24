# LeanCTX Workflows

## Baseline

- Target: `lean-ctx 3.3.5`.
- Default surface: granular `ctx_*` tools.
- Read modes: `full`, `map`, `signatures`, `diff`, `aggressive`, `entropy`, `task`, `lines:N-M`.
- Execution surfaces: MCP tools, shell hooks, `lean-ctx -c`, `lean-ctx -t`, `lean-ctx serve`, `lean-ctx watch`, `lean-ctx dashboard`.
- Human shell aliases default to track mode; use `_lc_compress` or `lean-ctx-mode compress` only when compressed interactive output is intentional.

## Start Flow

1. Frame: `ctx_context`, `ctx_preload(task)`, `ctx_intent`, `ctx_session`.
2. Scope: `ctx_tree`, `ctx_overview`, `ctx_search(path=...)`, `ctx_semantic_search`.
3. Inspect: `ctx_read`, `ctx_smart_read`, `ctx_multi_read`, `ctx_symbol`, `ctx_outline`, graph/route tools.
4. Execute: `ctx_shell` or `ctx_execute`; use CLI wrappers only when MCP shell is absent or unsuitable.
5. Revisit: `ctx_delta`, `ctx_read(mode="diff")`, `ctx_fill`, `ctx_dedup`.

## Read/Search Rules

- Need whole file: `ctx_read(mode="full")`.
- Need shape only: `ctx_read(mode="map")`.
- Need API/signatures: `ctx_read(mode="signatures")`.
- Need task slice: `ctx_read(mode="task")`.
- Need exact range: `ctx_read(mode="lines:N-M")`.
- Need stale body after cache stub: retry with `fresh=true`.
- Search too broad: add `path`, inspect tree, or read maps; do not repeat variant searches.

## Shell Rules

- CLI command: `ctx_shell(command="...")`.
- Noisy output: `lean-ctx -c "..."`.
- Full human output: `lean-ctx -t "..."` or track mode.
- Short script/transform: `ctx_execute`.
- File writes: use the proper write/edit tool, not shell redirects, heredoc redirects, or `tee`.
- Heredocs are allowed unless combined with file redirection; LeanCTX hooks pass heredocs through when quoting would corrupt them.

## Useful CLI/Admin

- Setup/update: `lean-ctx setup`, `lean-ctx update`, `lean-ctx init --global`, `lean-ctx init --agent <tool>`, `lean-ctx doctor`.
- Config: `lean-ctx config init`, `lean-ctx config set`, `LEAN_CTX_COMPRESS`, `LEAN_CTX_HEADLESS`, `LEAN_CTX_ALLOW_PATH`.
- Observability: `lean-ctx gain`, `lean-ctx wrapped`, `lean-ctx sessions`, `lean-ctx watch`, `lean-ctx dashboard`, `lean-ctx heatmap`.
- Server/indexing: `lean-ctx serve`, `lean-ctx graph [build] [path]`.
- Auth/cloud: `lean-ctx login`, `lean-ctx register`, `lean-ctx forgot-password`.

## Multi-Project Workspaces

- 3.3.5 supports `allow_paths` in `~/.lean-ctx/config.toml` for sibling repos and shared skills.
- When a root contains multiple child projects, LeanCTX can auto-allow detected child projects.
- If PathJail blocks a valid workspace, configure `allow_paths` before native fallback.
