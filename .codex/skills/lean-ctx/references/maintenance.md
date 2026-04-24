# LeanCTX Maintenance

## Source Of Truth

- This skill is the operational LeanCTX policy source.
- `AGENTS.md` stays policy-only.
- `LEAN-CTX.md` stays a short redirect.
- Tracked upstream baseline: `3.3.5`.

## Release Sync

1. Confirm local version with `lean-ctx --version`.
2. Read official GitHub release and changelog for the target tag.
3. Identify behavior that changes routing, fallback, config, shell hooks, MCP tools, or workspace access.
4. Update this skill and references first.
5. Mirror the skill tree into every workspace and shared install that uses LeanCTX.
6. Verify with LeanCTX reads and targeted text checks.

## Review On Each Upstream Change

- New or renamed MCP tools, read modes, shell wrappers, and CLI commands.
- PathJail, `allow_paths`, project-root detection, dashboard `--project`, and multi-root behavior.
- Codex hooks, PreToolUse reroutes, `LEAN_CTX_COMPRESS`, `LEAN_CTX_HEADLESS`, and setup/update hooks.
- Windows shell quoting, PowerShell pipe guards, Docker `BASH_ENV`, and non-TTY setup.
- Search loop detection, search fidelity, raw/tee/filter, archive/`ctx_expand`, terse mode, compaction recovery.
- Auth, cloud, dashboard, watch, graph, heatmap, route, symbol, and architecture surfaces.
- Config cleanup, uninstall/reinstall behavior, editor-specific MCP config paths.

## Verification Checklist

- `lean-ctx --version` matches the tracked baseline or documented target.
- `AGENTS.md` contains only the concise LeanCTX mandate and redirect.
- `SKILL.md` is compact, forceful, and valid frontmatter.
- Every referenced file under `references/` exists.
- `LEAN-CTX.md` redirects instead of duplicating the skill.
- Sibling workspaces are in `allow_paths` when LeanCTX must read them.
