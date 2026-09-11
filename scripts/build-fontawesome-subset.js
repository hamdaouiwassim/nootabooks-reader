/**
 * Regenerates the self-hosted, subsetted Font Awesome build in
 * public/assets/css/vendor/{fontawesome,webfonts}/ — containing ONLY the
 * icon glyphs this app actually references, instead of the full ~2000-icon
 * free set (which is what ships with a stock Font Awesome download).
 *
 * Run this any time a NEW Font Awesome icon class is introduced anywhere in
 * the app (a blade view, a JS file that sets an icon class dynamically, or
 * an admin <option value="fa-..."> icon picker) — otherwise that icon will
 * silently render as a missing-glyph box, since its glyph won't be in the
 * subset font file.
 *
 * Usage:
 *   npm install   (once, from the project root — installs fontawesome-subset
 *                  and @fortawesome/fontawesome-free as devDependencies)
 *   node scripts/build-fontawesome-subset.js
 *
 * Requires Node.js — this is the one piece of this otherwise PHP/Composer
 * tooling chain that needs it, since there's no comparable PHP font-subsetting
 * library.
 */
const fs = require('fs');
const path = require('path');
const { fontawesomeSubset } = require('fontawesome-subset');
const yaml = require('yaml');

const ROOT = path.resolve(__dirname, '..');
const VIEWS_DIR = path.join(ROOT, 'resources', 'views');
const JS_DIR = path.join(ROOT, 'public', 'assets', 'js');
const CSS_OUT_DIR = path.join(ROOT, 'public', 'assets', 'css', 'vendor', 'fontawesome');
const FONT_OUT_DIR = path.join(ROOT, 'public', 'assets', 'css', 'vendor', 'webfonts');

const STYLE_CLASS_RE = /fa-(solid|regular|brands|light|thin|duotone|sharp)((?:\s+fa-[a-z0-9-]+)+)/g;
const OPTION_ICON_RE = /<option\s+value="fa-([a-z0-9-]+)"/g;
const UTILITY_CLASSES = new Set([
  'fa-spin', 'fa-spin-pulse', 'fa-pulse', 'fa-beat', 'fa-beat-fade', 'fa-bounce', 'fa-fade',
  'fa-flip', 'fa-flip-horizontal', 'fa-flip-vertical', 'fa-flip-both',
  'fa-rotate-90', 'fa-rotate-180', 'fa-rotate-270', 'fa-rotate-by',
  'fa-border', 'fa-fw', 'fa-ul', 'fa-li', 'fa-inverse',
  'fa-pull-left', 'fa-pull-right', 'fa-stack', 'fa-stack-1x', 'fa-stack-2x',
  'fa-xs', 'fa-sm', 'fa-lg', 'fa-xl', 'fa-2xl',
  'fa-sr-only', 'fa-sr-only-focusable', 'fa-swap-opacity',
]);
const STYLE_KEYS = { solid: 'solid', regular: 'regular', brands: 'brands', light: 'light', thin: 'thin', duotone: 'duotone', sharp: 'sharp' };

// Icons only ever referenced dynamically (JS classList.toggle with no
// adjacent style class on the same line) or that don't otherwise appear as a
// literal "fa-style fa-name" pair anywhere — the static scan below can't see
// these, so they're declared by hand. Keep this in sync if such usages change.
const MANUAL_ICONS = {
  solid: ['compress'], // read.js toggles expand/compress on the same <i>; only fa-expand is ever static
};

function walk(dir, extensions) {
  const results = [];
  for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
    const fullPath = path.join(dir, entry.name);
    if (entry.isDirectory()) {
      results.push(...walk(fullPath, extensions));
    } else if (extensions.some((ext) => entry.name.endsWith(ext))) {
      results.push(fullPath);
    }
  }
  return results;
}

function extractIcons() {
  const subset = { solid: new Set(), regular: new Set(), brands: new Set(), light: new Set(), thin: new Set(), duotone: new Set(), sharp: new Set() };
  const files = [
    ...walk(VIEWS_DIR, ['.blade.php']),
    ...walk(JS_DIR, ['.js']),
  ];

  for (const file of files) {
    const content = fs.readFileSync(file, 'utf8');

    for (const match of content.matchAll(STYLE_CLASS_RE)) {
      const styleKey = STYLE_KEYS[match[1]];
      const names = match[2].trim().split(/\s+/);
      for (const token of names) {
        if (UTILITY_CLASSES.has(token)) continue;
        subset[styleKey].add(token.replace(/^fa-/, ''));
      }
    }

    // Admin <option value="fa-x"> icon pickers (e.g. category icon select) —
    // every choice must be renderable even if not the current default.
    for (const match of content.matchAll(OPTION_ICON_RE)) {
      subset.solid.add(match[1]);
    }
  }

  for (const [style, names] of Object.entries(MANUAL_ICONS)) {
    names.forEach((n) => subset[style].add(n));
  }

  for (const key of Object.keys(subset)) {
    subset[key] = Array.from(subset[key]).sort();
    if (subset[key].length === 0) delete subset[key];
  }

  return subset;
}

async function main() {
  const subset = extractIcons();
  console.log('Icon counts:', Object.fromEntries(Object.entries(subset).map(([k, v]) => [k, v.length])));

  const ok = await fontawesomeSubset(subset, FONT_OUT_DIR, { targetFormats: ['woff2', 'sfnt'] });
  if (!ok) {
    console.error('Subsetting reported missing icons — see warnings above. Fix the icon name/style before continuing.');
    process.exit(1);
  }
  console.log('Subset webfonts written to', FONT_OUT_DIR);

  const packageLocation = require.resolve('@fortawesome/fontawesome-free');
  const iconMeta = yaml.parse(fs.readFileSync(path.resolve(packageLocation, '../../metadata/icons.yml'), 'utf8'));

  const styleSelector = { solid: '.fa-solid, .fas', regular: '.fa-regular, .far', brands: '.fa-brands, .fab' };
  const fontFamily = { solid: '"Font Awesome 6 Free"', regular: '"Font Awesome 6 Free"', brands: '"Font Awesome 6 Brands"' };
  const fontWeight = { solid: 900, regular: 400, brands: 400 };
  const fileBase = { solid: 'fa-solid-900', regular: 'fa-regular-400', brands: 'fa-brands-400' };

  let css = `/*!
 * Self-hosted, hand-subsetted Font Awesome 6.5.1 build.
 * Contains ONLY the icon glyphs this app actually references — NOT the full
 * ~2000-icon set. Regenerated by scripts/build-fontawesome-subset.js —
 * re-run that script (see its header comment) after adding a new icon
 * anywhere in the app, or it will silently render as a missing-glyph box.
 */
.fa-solid, .fa-regular, .fa-brands, .fas, .far, .fab {
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  display: var(--fa-display, inline-block);
  font-style: normal;
  font-variant: normal;
  line-height: 1;
  text-rendering: auto;
}
`;

  for (const style of ['solid', 'regular', 'brands']) {
    if (!subset[style]) continue;
    css += `
@font-face {
  font-family: ${fontFamily[style]};
  font-style: normal;
  font-weight: ${fontWeight[style]};
  font-display: block;
  src: url("../webfonts/${fileBase[style]}.woff2") format("woff2"), url("../webfonts/${fileBase[style]}.ttf") format("truetype");
}
${styleSelector[style]} {
  font-family: ${fontFamily[style]};
  font-weight: ${fontWeight[style]};
}
`;
  }

  const missing = [];
  const emitted = new Set();
  for (const style of ['solid', 'regular', 'brands']) {
    if (!subset[style]) continue;
    for (const name of subset[style]) {
      if (emitted.has(name)) continue;
      const icon = iconMeta[name];
      if (!icon) { missing.push(`${style}:${name}`); continue; }
      css += `.fa-${name}:before { content: "\\${icon.unicode}"; }\n`;
      emitted.add(name);
    }
  }

  if (missing.length) {
    console.warn('Icons missing from Font Awesome metadata (not written to CSS) — check the name/style:', missing);
  }

  fs.mkdirSync(CSS_OUT_DIR, { recursive: true });
  const cssPath = path.join(CSS_OUT_DIR, 'fontawesome.css');
  fs.writeFileSync(cssPath, css);
  console.log('CSS written to', cssPath);
  console.log('\nDone. Run `php artisan assets:minify` to refresh the production .min.css.');
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});
