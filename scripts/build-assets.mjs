#!/usr/bin/env node
/**
 * Builds the prebuilt design-system assets this package ships (#42, D-014).
 *
 * Copies the `@artisanpack-ui/css` entry points (default and headless), the
 * `@artisanpack-ui/behavior` Alpine adapter (`alpine.global.js` and its map)
 * and the `@artisanpack-ui/tokens` PHP token map into the package, then writes
 * `dist/manifest.json` with a content hash per file for cache-busting.
 *
 * The Tailwind preset (`@artisanpack-ui/css/tailwind`) is left out on purpose:
 * it is `@theme` input for the Tailwind compiler, not a stylesheet a browser
 * can load, so it only makes sense on the npm path.
 *
 * Sources, in order:
 *   1. `--from <path>`: a design-system checkout (its packages must be built).
 *   2. The `DESIGN_SYSTEM_PATH` environment variable: the same, as an env var.
 *   3. `node_modules/@artisanpack-ui/*`, once the packages are published (#81).
 *
 * Usage:
 *   node scripts/build-assets.mjs --from ../design-system
 *   node scripts/build-assets.mjs --check   # fail if the committed files are stale
 *
 * The output is deterministic: same sources, same bytes. No timestamps or
 * absolute paths go into the manifest.
 *
 * @since 3.0.0
 */

import { createHash } from 'node:crypto';
import { existsSync, mkdirSync, readFileSync, readdirSync, rmSync, writeFileSync } from 'node:fs';
import { dirname, join, relative, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const packageRoot = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const distDir = join(packageRoot, 'dist');
const fixturesDir = join(packageRoot, 'tests', 'Fixtures', 'design-system', 'generator');

/** Length of the hex sha256 prefix used as the cache-busting id. */
const HASH_LENGTH = 16;

/** The CSS entry points that ship as stylesheets. `tailwind` is npm-only. */
const STYLE_ENTRIES = ['default', 'headless'];

/**
 * Parses the command line.
 *
 * @param {string[]} argv
 * @returns {{ from: string | null, check: boolean }}
 */
function parseArgs(argv) {
  const options = { from: null, check: false };

  for (let index = 0; index < argv.length; index++) {
    const arg = argv[index];

    if ('--check' === arg) {
      options.check = true;
    } else if ('--from' === arg) {
      options.from = argv[++index] ?? null;
      if (!options.from) fail('--from needs a path to a design-system checkout.');
    } else if (arg.startsWith('--from=')) {
      options.from = arg.slice('--from='.length);
    } else {
      fail(`Unknown option: ${arg}`);
    }
  }

  return options;
}

/**
 * Prints an error and exits.
 *
 * @param {string} message
 * @returns {never}
 */
function fail(message) {
  console.error(`build-assets: ${message}`);
  process.exit(1);
}

/**
 * Resolves the directory of each design-system package.
 *
 * @param {string | null} from
 * @returns {{ origin: string, checkout: string | null, css: string, behavior: string, tokens: string }}
 */
function resolveSources(from) {
  const checkout = from ?? process.env.DESIGN_SYSTEM_PATH ?? null;

  if (checkout) {
    const root = resolve(process.cwd(), checkout);
    if (!existsSync(join(root, 'packages'))) {
      fail(`${root} is not a design-system checkout (no packages/ directory).`);
    }

    return {
      origin: 'checkout',
      checkout: root,
      css: join(root, 'packages', 'css'),
      behavior: join(root, 'packages', 'behavior'),
      tokens: join(root, 'packages', 'tokens'),
    };
  }

  const modules = join(packageRoot, 'node_modules', '@artisanpack-ui');
  if (!existsSync(join(modules, 'css')) || !existsSync(join(modules, 'behavior'))) {
    fail(
      'No design-system source. Pass --from <path> or set DESIGN_SYSTEM_PATH to a design-system checkout, '
        + 'or install @artisanpack-ui/css, @artisanpack-ui/behavior and @artisanpack-ui/tokens.',
    );
  }

  return {
    origin: 'node_modules',
    checkout: null,
    css: join(modules, 'css'),
    behavior: join(modules, 'behavior'),
    tokens: join(modules, 'tokens'),
  };
}

/**
 * Reads a file that must exist, with a hint to build the design system.
 *
 * @param {string} path
 * @returns {Buffer}
 */
function readRequired(path) {
  if (!existsSync(path)) {
    fail(`Missing ${path}. Build the design system first (npm run build in its checkout).`);
  }

  return readFileSync(path);
}

/**
 * Reads the CSS entry points from `@artisanpack-ui/css/dist/assets.php` (D-012).
 *
 * @param {string} cssDir
 * @returns {Record<string, string>} Entry name => file name.
 */
function readCssEntries(cssDir) {
  const source = readRequired(join(cssDir, 'dist', 'assets.php')).toString('utf8');
  const block = source.match(/'entries'\s*=>\s*\[([\s\S]*?)\]/);
  if (!block) fail('Could not find the entries in @artisanpack-ui/css/dist/assets.php.');

  const entries = {};
  for (const [, name, file] of block[1].matchAll(/'([a-z-]+)'\s*=>\s*'([A-Za-z0-9._-]+\.css)'/g)) {
    entries[name] = file;
  }

  for (const name of STYLE_ENTRIES) {
    if (!entries[name]) fail(`@artisanpack-ui/css/dist/assets.php has no '${name}' entry.`);
  }

  return entries;
}

/**
 * Reads a package's version from its package.json.
 *
 * @param {string} dir
 * @returns {string}
 */
function packageVersion(dir) {
  const path = join(dir, 'package.json');

  return existsSync(path) ? JSON.parse(readFileSync(path, 'utf8')).version : 'unknown';
}

/**
 * The short content hash used as the cache-busting id.
 *
 * @param {Buffer} contents
 * @returns {string}
 */
function hash(contents) {
  return createHash('sha256').update(contents).digest('hex').slice(0, HASH_LENGTH);
}

/**
 * Sorts an object's keys, for stable JSON.
 *
 * @param {Record<string, unknown>} object
 * @returns {Record<string, unknown>}
 */
function sorted(object) {
  return Object.fromEntries(Object.entries(object).sort(([a], [b]) => a.localeCompare(b)));
}

/**
 * Collects every output file as path (relative to the package root) => contents.
 *
 * @param {ReturnType<typeof resolveSources>} sources
 * @returns {Map<string, Buffer>}
 */
function collect(sources) {
  const outputs = new Map();
  const cssEntries = readCssEntries(sources.css);
  const styles = {};
  const files = {};

  for (const name of STYLE_ENTRIES) {
    const file = `css/${cssEntries[name]}`;
    const contents = readRequired(join(sources.css, 'dist', cssEntries[name]));
    outputs.set(`dist/${file}`, contents);
    styles[name] = file;
    files[file] = hash(contents);
  }

  for (const name of ['alpine.global.js', 'alpine.global.js.map']) {
    const file = `js/${name}`;
    const contents = readRequired(join(sources.behavior, 'dist', name));
    outputs.set(`dist/${file}`, contents);
    files[file] = hash(contents);
  }

  const manifest = {
    packages: {
      '@artisanpack-ui/behavior': packageVersion(sources.behavior),
      '@artisanpack-ui/css': packageVersion(sources.css),
      '@artisanpack-ui/tokens': packageVersion(sources.tokens),
    },
    styles: sorted(styles),
    scripts: { alpine: 'js/alpine.global.js' },
    files: sorted(files),
  };
  outputs.set('dist/manifest.json', Buffer.from(`${JSON.stringify(manifest, null, 2)}\n`));

  // The token map Theming\DesignTokens reads, so PHP needs no Node.
  outputs.set('resources/design-system/tokens.php', readRequired(join(sources.tokens, 'generated', 'tokens.php')));

  // The generator parity fixtures aren't in the npm package, only in a checkout.
  if (sources.checkout) {
    const fixtures = join(sources.tokens, 'test', 'fixtures', 'generator');
    if (!existsSync(fixtures)) {
      console.error(`build-assets: ${fixtures} is missing. Is ${sources.origin} a design-system checkout?`);
      process.exit(1);
    }
    for (const name of readdirSync(fixtures).filter((file) => file.endsWith('.json')).sort()) {
      outputs.set(`tests/Fixtures/design-system/generator/${name}`, readFileSync(join(fixtures, name)));
    }
  }

  return outputs;
}

/**
 * The generator fixtures currently committed, relative to the package root.
 *
 * @returns {string[]}
 */
function existingFixtureFiles() {
  if (!existsSync(fixturesDir)) return [];

  return readdirSync(fixturesDir)
    .filter((name) => name.endsWith('.json'))
    .map((name) => `tests/Fixtures/design-system/generator/${name}`);
}

/**
 * Lists the files currently in dist/, relative to the package root.
 *
 * @returns {string[]}
 */
function existingDistFiles() {
  if (!existsSync(distDir)) return [];

  return readdirSync(distDir, { recursive: true, withFileTypes: true })
    .filter((entry) => entry.isFile())
    .map((entry) => relative(packageRoot, join(entry.parentPath ?? entry.path, entry.name)).split('\\').join('/'));
}

const options = parseArgs(process.argv.slice(2));
const sources = resolveSources(options.from);
const outputs = collect(sources);

if (options.check) {
  const stale = [];
  for (const [path, contents] of outputs) {
    const target = join(packageRoot, path);
    if (!existsSync(target) || !readFileSync(target).equals(contents)) stale.push(path);
  }
  const managed = [...existingDistFiles(), ...(sources.checkout ? existingFixtureFiles() : [])];
  for (const path of managed) {
    if (!outputs.has(path)) stale.push(`${path} (not in the build)`);
  }

  if (stale.length) {
    console.error('build-assets: these files are out of date:');
    for (const path of stale) console.error(`  ${path}`);
    process.exit(1);
  }

  console.log(`build-assets: up to date with the ${sources.origin} sources.`);
  process.exit(0);
}

rmSync(distDir, { recursive: true, force: true });
// Fixtures removed upstream go too; they only come from a checkout.
if (sources.checkout) {
  for (const path of existingFixtureFiles()) {
    if (!outputs.has(path)) rmSync(join(packageRoot, path));
  }
}

for (const [path, contents] of outputs) {
  const target = join(packageRoot, path);
  mkdirSync(dirname(target), { recursive: true });
  writeFileSync(target, contents);
  console.log(`  ${path}`);
}

console.log(`build-assets: wrote ${outputs.size} files from the ${sources.origin} sources.`);
