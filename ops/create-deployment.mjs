#!/usr/bin/env bun
import { spawnSync } from "node:child_process";
import {
  copyFile,
  mkdir,
  readdir,
  rm,
  stat,
} from "node:fs/promises";
import { dirname, isAbsolute, join, relative, resolve } from "node:path";
import { fileURLToPath } from "node:url";

const scriptDir = dirname(fileURLToPath(import.meta.url));
const repoRoot = resolve(scriptDir, "..");
const backDir = join(repoRoot, "back");
const frontPublicDir = join(repoRoot, "front", ".output", "public");

const args = process.argv.slice(2);
const outputArg = readOption(args, "--out") ?? readOption(args, "-o") ?? "deployment";
const outputDir = isAbsolute(outputArg)
  ? resolve(outputArg)
  : resolve(repoRoot, outputArg);
const zipFile = `${outputDir}.zip`;

const deploymentBackDir = join(outputDir, "back");
const deploymentFrontDir = join(outputDir, "front");

await assertDirectory(backDir, "Backend source directory is missing.");
await assertDirectory(
  frontPublicDir,
  "Frontend SSG output is missing. Run `cd front && bun run generate` first.",
);
assertSafeOutputPath(outputDir);

await rm(outputDir, { recursive: true, force: true });
await mkdir(deploymentBackDir, { recursive: true });
await mkdir(deploymentFrontDir, { recursive: true });

const backendFiles = listBackendFiles();
for (const file of backendFiles) {
  await copyProjectFile(file, deploymentBackDir);
}

await copyDirectoryContents(frontPublicDir, deploymentFrontDir);
await createZipFile(outputDir, zipFile);

console.log(`Created deployment bundle: ${relative(repoRoot, outputDir) || outputDir}`);
console.log(`- back: ${backendFiles.length} git-ignored-safe source files`);
console.log("- front: contents copied from front/.output/public");
console.log(`Created deployment zip: ${relative(repoRoot, zipFile) || zipFile}`);

function readOption(sourceArgs, longName) {
  const index = sourceArgs.indexOf(longName);
  if (index === -1) {
    return undefined;
  }

  const value = sourceArgs[index + 1];
  if (!value || value.startsWith("-")) {
    fail(`Missing value for ${longName}.`);
  }

  return value;
}

async function assertDirectory(path, message) {
  try {
    const info = await stat(path);
    if (info.isDirectory()) {
      return;
    }
  } catch {
    // Fall through to the normalized error below.
  }

  fail(message);
}

function assertSafeOutputPath(path) {
  const protectedTrees = [
    backDir,
    resolve(repoRoot, "front"),
    resolve(repoRoot, "ops"),
    resolve(repoRoot, "tasks"),
  ];

  if (path === repoRoot) {
    fail(`Refusing to replace protected path: ${path}`);
  }

  for (const protectedPath of protectedTrees) {
    const relativePath = relative(protectedPath, path);
    const isProtectedPath = relativePath === "";
    const isInsideProtectedPath =
      relativePath && !relativePath.startsWith("..") && !isAbsolute(relativePath);

    if (isProtectedPath || isInsideProtectedPath) {
      fail(`Refusing to replace protected path: ${path}`);
    }
  }
}

function listBackendFiles() {
  const output = runGit(["ls-files", "-co", "--exclude-standard", "--", "back"]);
  const files = output
    .split(/\r?\n/)
    .map((line) => line.trim())
    .filter(Boolean)
    .filter((file) => !file.startsWith("back/.git/"));

  return removeIgnoredFiles(files);
}

function removeIgnoredFiles(files) {
  if (files.length === 0) {
    return files;
  }

  const result = spawnSync("git", ["check-ignore", "--no-index", "--stdin"], {
    cwd: repoRoot,
    encoding: "utf8",
    input: `${files.join("\n")}\n`,
    stdio: ["pipe", "pipe", "pipe"],
  });

  if (result.status !== 0 && result.status !== 1) {
    fail(result.stderr.trim() || "Failed to apply Git ignore rules.");
  }

  const ignoredFiles = new Set(
    result.stdout
      .split(/\r?\n/)
      .map((line) => line.trim())
      .filter(Boolean),
  );

  return files.filter((file) => !ignoredFiles.has(file));
}

function runGit(args) {
  const result = spawnSync("git", args, {
    cwd: repoRoot,
    encoding: "utf8",
    stdio: ["ignore", "pipe", "pipe"],
  });

  if (result.status !== 0) {
    fail(result.stderr.trim() || `Git command failed: git ${args.join(" ")}`);
  }

  return result.stdout;
}

async function copyProjectFile(repoRelativePath, destinationRoot) {
  const source = join(repoRoot, repoRelativePath);
  const target = join(destinationRoot, relative("back", repoRelativePath));

  await mkdir(dirname(target), { recursive: true });
  await copyFile(source, target);
}

async function copyDirectoryContents(sourceDir, destinationDir) {
  const entries = await readdir(sourceDir, { withFileTypes: true });

  for (const entry of entries) {
    const source = join(sourceDir, entry.name);
    const destination = join(destinationDir, entry.name);

    if (entry.isDirectory()) {
      await mkdir(destination, { recursive: true });
      await copyDirectoryContents(source, destination);
      continue;
    }

    if (entry.isFile()) {
      await mkdir(dirname(destination), { recursive: true });
      await copyFile(source, destination);
    }
  }
}

async function createZipFile(sourceDir, destinationZip) {
  await rm(destinationZip, { force: true });

  if (process.platform === "win32") {
    const command = [
      `$paths = @('${toPowerShellLiteral(join(sourceDir, "back"))}', '${toPowerShellLiteral(join(sourceDir, "front"))}')`,
      `Compress-Archive -LiteralPath $paths -DestinationPath '${toPowerShellLiteral(destinationZip)}' -Force`,
    ].join("; ");
    const result = spawnSync(
      "powershell.exe",
      [
        "-NoProfile",
        "-ExecutionPolicy",
        "Bypass",
        "-Command",
        command,
      ],
      {
        cwd: repoRoot,
        encoding: "utf8",
        stdio: ["ignore", "pipe", "pipe"],
      },
    );

    if (result.status !== 0) {
      fail(result.stderr.trim() || "Failed to create deployment zip.");
    }

    return;
  }

  const result = spawnSync("zip", ["-qr", destinationZip, "back", "front"], {
    cwd: sourceDir,
    encoding: "utf8",
    stdio: ["ignore", "pipe", "pipe"],
  });

  if (result.status !== 0) {
    fail(result.stderr.trim() || "Failed to create deployment zip.");
  }
}

function toPowerShellLiteral(value) {
  return value.replaceAll("'", "''");
}

function fail(message) {
  console.error(message);
  process.exit(1);
}
