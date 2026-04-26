#!/usr/bin/env bun
import { spawnSync } from "node:child_process";
import { dirname } from "node:path";
import { fileURLToPath } from "node:url";

const repoRoot = dirname(fileURLToPath(import.meta.url));

run("bun", ["run", "--cwd", "front", "generate"]);
await import("./ops/create-deployment.mjs");

function run(command, args) {
  const result = spawnSync(command, args, {
    cwd: repoRoot,
    shell: process.platform === "win32",
    stdio: "inherit",
  });

  if (result.status !== 0) {
    process.exit(result.status ?? 1);
  }
}
