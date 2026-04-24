# LeanCTX Tool Hierarchy

## Order

1. LeanCTX MCP or CLI.
2. LeanCTX retry with better path, mode, raw/tee/filter, `fresh=true`, or config.
3. Native fallback only for unavailable, failed, timed-out, blocked, or incomplete LeanCTX paths.

## Forced Mappings

- Read: `ctx_read`, `ctx_smart_read`, `ctx_multi_read`; never `cat`, `Get-Content`, `type`, or `more` first.
- Search: `ctx_search`, `ctx_semantic_search`; never raw `rg`, `grep`, `find`, `fd`, `ag`, or `ack` first.
- Tree: `ctx_tree`; never raw `ls`, `dir`, or filesystem globbing first.
- Shell: `ctx_shell`; use `lean-ctx -c` for compressed CLI and `lean-ctx -t` for full human output when MCP shell is unavailable.
- Compute: `ctx_execute` for small isolated Python/TS/etc. snippets; do not shell-wrap trivial scripts.
- Navigation: `ctx_graph`, `ctx_symbol`, `ctx_outline`, `ctx_callers`, `ctx_callees`, `ctx_routes`, `ctx_graph_diagram`.
- Context/memory: `ctx_preload`, `ctx_context`, `ctx_delta`, `ctx_fill`, `ctx_dedup`, `ctx_compress`, `ctx_compress_memory`, `ctx_session`, `ctx_knowledge`.
- Coordination/analysis: `ctx_share`, `ctx_task`, `ctx_cost`, `ctx_heatmap`, `ctx_impact`, `ctx_architecture`, `ctx_metrics`, `ctx_benchmark`.
- Edit deadlocks: `ctx_edit` for exact search/replace when native read/edit loops are blocked.

## Fallback Boundary

- Compression, summarization, cache hits, or archive hints are not fallback reasons; use `fresh=true`, line modes, raw output, tee, or `ctx_expand` when available.
- PathJail is not an immediate fallback reason; add `allow_paths` or `LEAN_CTX_ALLOW_PATH` when the workspace is legitimate.
- Search loop warnings mean narrow scope with `path`, `ctx_tree`, or `ctx_read(mode="map")`; do not cycle search tools.
- Native fallback must be minimal and explained once.
