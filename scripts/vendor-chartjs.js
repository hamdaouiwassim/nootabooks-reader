/**
 * Copies the Chart.js UMD minified build (a self-contained, single-file
 * build meant for a plain <script> tag — no bundler required) from
 * node_modules into public/assets/js/vendor/chartjs/, so the admin panel's
 * charts (statistics page, per-book stats page) self-host it like every
 * other third-party asset in this app instead of pulling it from a CDN.
 *
 * Run this any time the pinned "chart.js" devDependency version changes:
 *   npm install
 *   node scripts/vendor-chartjs.js
 */
const fs = require('fs');
const path = require('path');

const ROOT = path.resolve(__dirname, '..');
const SOURCE = path.join(ROOT, 'node_modules', 'chart.js', 'dist', 'chart.umd.min.js');
const OUT_DIR = path.join(ROOT, 'public', 'assets', 'js', 'vendor', 'chartjs');
const OUT_FILE = path.join(OUT_DIR, 'chart.umd.min.js');

if (!fs.existsSync(SOURCE)) {
  console.error('chart.js is not installed — run "npm install" first.');
  process.exit(1);
}

fs.mkdirSync(OUT_DIR, { recursive: true });
fs.copyFileSync(SOURCE, OUT_FILE);

const { version } = require(path.join(ROOT, 'node_modules', 'chart.js', 'package.json'));
console.log(`Vendored chart.js ${version} -> ${path.relative(ROOT, OUT_FILE)} (${(fs.statSync(OUT_FILE).size / 1024).toFixed(1)} KB)`);
