# LeanCTX Platform And Shell

## Defaults

- Use `ctx_shell` for ordinary CLI execution.
- Use `lean-ctx shell` only for real interactive sessions after checking OS and shell support.
- Use `lean-ctx -t` for full streamed human output; use `lean-ctx -c` for compressed command output.
- Do not bypass LeanCTX because a client uses framed stdio, redirected output, or a different shell.

## Windows

- Prefer Git Bash for shell execution when available; fall back to PowerShell only when Git Bash cannot run the command.
- 3.3.5 embeds the PowerShell pipe guard in the profile line and fixes multi-line `where` path resolution.
- Use `LEAN_CTX_SHELL` only when detection is wrong.
- After cargo/npm/brew installs, run `lean-ctx setup`; `lean-ctx update` refreshes hooks automatically.

## Docker And Non-TTY

- `lean-ctx init --global` writes `~/.lean-ctx/env.sh`.
- In containers, set `BASH_ENV="/root/.lean-ctx/env.sh"` for non-interactive shells.
- Non-TTY setup should install hooks without hanging.

## Live Logs

- Long-running dev servers need live output. Keep Bun/Nuxt dev servers out of compressed wrappers unless explicitly debugging compressed behavior.
- Short Bun commands may use `lean-ctx -c`; dev servers should run direct or track mode so logs stream.

## Fallback

- If LeanCTX shell support fails, explain the narrow native fallback.
- If a valid sibling workspace is blocked, add `allow_paths` or `LEAN_CTX_ALLOW_PATH` before falling back.
