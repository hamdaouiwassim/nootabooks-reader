# Custom Artisan Commands

This document describes the custom Artisan commands added to this project, defined in [`app/Console/Commands`](app/Console/Commands), plus two standalone scripts (one Node, one PHP). They're all one-off/maintenance commands you run manually — none of them are scheduled.

| Command | Purpose |
|---|---|
| [`images:optimize`](#images-optimize) | Compress already-uploaded book covers & writer photos to WebP, with a small card-thumbnail variant |
| [`images:optimize-assets`](#images-optimize-assets) | Compress the static hero/banner images in `public/assets/images`, with a small mobile variant of each |
| [`covers:stamp-ribbon`](#coversstamp-ribbon) | One-time backfill: bake the "nootabooks.com" brand ribbon into every already-uploaded book cover |
| [`assets:minify`](#assets-minify) | Generate `.min.css` / `.min.js` files for production |
| [`urls:fix-domain`](#urlsfix-domain) | Rewrite the domain baked into already-stored cover/photo/file URLs after a domain change |
| [`build:fontawesome`](#build-fontawesome) | Regenerate the self-hosted, subsetted Font Awesome build (only the icons this app actually uses) |
| [`vendor:chartjs`](#vendorchartjs) | Copy the Chart.js UMD build into `public/assets/js/vendor/chartjs/` so the admin panel's charts self-host it |
| [`scripts/optimize-cover.php`](#scriptsoptimize-coverphp) | Generate the 3 book-cover sizes (large/medium/small) locally from one source image, ready to upload into the admin's 3 cover fields |
| [`scripts/optimize-cover-server.php`](#scriptsoptimize-cover-serverphp) | Same as above, but with a browser file-picker/drag-and-drop UI instead of typing a CLI path |

---

## `images:optimize`

```bash
php artisan images:optimize
```

**Purpose:** retroactively optimizes every already-uploaded `Book.cover_image` and `Writer.photo` — for anything uploaded before the [`ImageOptimizer`](app/Services/Image/ImageOptimizer.php) pipeline existed, or before it started generating responsive variants.

**Book covers and writer photos now work differently** (books moved to independently-uploadable size columns — see `Admin\BookController::storeCoverVariant()` — while writer photos still use the older shared-basename `-sm` suffix convention), so this command handles them separately:

- **Book covers** — for every non-null `cover_image` missing its WebP conversion and/or its `cover_image_md` / `cover_image_sm` column:
  1. Skips rows that aren't real local uploads (external URLs, or seeded demo assets) or whose file is missing on disk.
  2. Converts a non-WebP `cover_image` to WebP (resized to `800×1200`) if needed.
  3. Backfills `cover_image_md` (`600×900`) and/or `cover_image_sm` (`300×450`) by resizing from the current `cover_image` — only for whichever of the two columns is still empty, so it never overwrites a size the admin already uploaded manually.
- **Writer photos** — skips rows already `.webp` with both a `-sm` and `-xs` sibling generated, otherwise re-encodes to WebP and generates `600×600` full + `300×300` small (`-sm`, for the ~160-200px featured/hero photo) + `200×200` extra-small (`-xs`, for the ~40-90px avatar cards/grids) sharing one filename.
- Saving a row fires the model's normal `save()` event — which busts the home-page/sitemap cache (see [`FlushesAppCache`](app/Models/Concerns/FlushesAppCache.php)) — so you don't need to clear the cache separately afterward.
- Safe to re-run anytime: every check above is "only touch what's actually missing/outdated."

**When to run it:**
- Once, right after deploying the responsive-image feature, to bring existing content up to date.
- Anytime you suspect some covers/photos are still in their original (large, non-WebP) form.

**Requires:** `intervention/image` (`composer update`).

---

## `covers:stamp-ribbon`

```bash
php artisan covers:stamp-ribbon
php artisan covers:stamp-ribbon --force   # re-stamp every cover, even ones already marked done
```

**Purpose:** book covers now get the "nootabooks.com" brand ribbon baked directly into the image pixels at upload time (see [`CoverRibbonStamper`](app/Services/Image/CoverRibbonStamper.php), wired into `Admin\BookController::storeCoverVariant()`) instead of the old CSS `.brand-ribbon` overlay — this command backfills that ribbon onto every cover uploaded *before* that pipeline existed.

**What it does:** for every book with a non-null `cover_image` and `cover_ribbon_stamped = false`, re-reads the cover from disk, re-runs it through `CoverRibbonStamper` (same 300×450/quality-80 WebP pipeline as `images:optimize`, plus the ribbon composite), replaces the stored file, and sets `cover_ribbon_stamped = true`. Skips rows with an external cover URL or a missing local file (with a warning), so it's safe to re-run — anything already stamped or unreachable is left alone.

**When to run it:** once, right after deploying this feature, to bring existing covers up to date. New uploads and edits are stamped automatically going forward and never need this command. Use `--force` only if the ribbon design itself changes and every cover needs re-stamping with the new artwork.

**Requires:** `intervention/image` (`composer update`) and the `public/assets/images/cover-ribbon-overlay.png` asset present.

---

## `images:optimize-assets`

```bash
php artisan images:optimize-assets
```

**Purpose:** compresses the hand-picked static banner/hero JPGs that ship in `public/assets/images` — these aren't database-tracked, so they need their own command rather than going through `images:optimize`. Also generates a small, mobile-sized variant of each (the same large+small pattern `images:optimize` uses for covers/photos, applied to CSS backgrounds this time), since a background sized for a full desktop hero is oversized for a phone screen.

**What it does**, per file, two variants each:
- **Converts to WebP** (large + small, original deleted): `discover-section.jpg`, `subscribe-section.jpg`, `login-hero.jpg`, `register-hero.jpg`, `admin-login-hero.jpg` → `{name}.webp` (desktop) + `{name}-sm.webp` (mobile). Every CSS reference already points at the right filename for its breakpoint (`style.css`, `auth.css` — the large one at the base/desktop rule, the small one inside the relevant mobile `@media` block), so this only needs running once per image.
- **`hero-section.jpg`**: the large variant is recompressed **in place, same JPG filename** — deliberately *not* renamed or converted, because it also doubles as the site-wide Open Graph fallback image (see `partials/seo-meta.blade.php`), and some link-preview crawlers still handle WebP inconsistently. A small `hero-section-sm.webp` is generated alongside it purely for the mobile CSS background — that one's safe to be WebP since it's never used as the OG image.

**When to run it:** once — it's a one-time cleanup of a fixed, hand-picked list of files (see the `RESPONSIVE_WEBP` / `RESPONSIVE_JPEG_BASE` constants at the top of the command if you ever add a new static image and want it included).

**Requires:** `intervention/image` (`composer update`).

⚠️ **Not idempotent** — running it a second time will fail with "file missing" warnings for the five renamed-to-webp images, since the `.jpg` source is gone. That's expected; it just means there's nothing left to do for those. `hero-section.jpg` alone can be safely re-run (its large variant recompresses in place; its small variant regenerates fresh each time).

---

## `assets:minify`

```bash
php artisan assets:minify
```

**Purpose:** generates the `.min.css` / `.min.js` companion files that [`asset_min()`](app/helpers.php) serves in production, without ever touching the original files — so local development keeps seeing live edits with zero build step (see [`asset_min()`](app/helpers.php) for how that split works).

**What it does:**
- Walks every `*.css` in `public/assets/css` and every `*.js` in `public/assets/js` (recursively — this includes the self-hosted `fonts.css` and the subsetted `vendor/fontawesome/fontawesome.css`, skipping only files whose name already ends in `.min.css`/`.min.js`).
- Minifies each with `matthiasmullie/minify` and writes it next to the original as `{name}.min.{css,js}`.
- Prints a before/after KB size and % reduction per file.
- Safe to re-run anytime — it always overwrites the `.min.` file fresh from the current source.

**When to run it:**
- Before/as part of every production deploy.
- Anytime after editing any `.css`/`.js` file, if you want the production build to reflect that change (local dev doesn't need this — it always serves the live, unminified file).

**Requires:** `matthiasmullie/minify` (`composer update`).

---

## `urls:fix-domain`

```bash
php artisan urls:fix-domain nootabooks.nootapedia.com nootabooks.com
```

**Purpose:** `Book.cover_image` (+ `cover_image_md` / `cover_image_sm`), `Book.file_path`, and `Writer.photo` are stored as **full absolute URLs baked in at upload time** (see `BookController::prepareData()` / `WriterController::prepareData()`, both using `force_https_url(rtrim(config('app.url'), '/')).'/storage/'.$path`) — not relative paths resolved dynamically. Changing `APP_URL` in `.env` only affects *new* uploads going forward; anything uploaded before the domain change keeps the old domain baked into its DB row forever, which is why covers/photos can keep loading from an old domain even after `APP_URL` is updated and the app is otherwise fully served on the new one.

**What it does:**
- For every `cover_image` / `cover_image_md` / `cover_image_sm` / `file_path` / `photo` value containing the old domain, does a plain string replace with the new one and saves the row.
- Saving each row fires the model's normal `save()` event, which busts the home-page/sitemap cache — no separate cache-clear needed afterward.
- Doesn't touch any files on disk — this is a pure database URL rewrite.

**When to run it:** once, right after updating `APP_URL` in production to point at a new domain — run it immediately after so existing content's URLs match.

**Requires:** nothing extra — no new Composer packages.

---

## `build:fontawesome`

```bash
npm install   # once — installs fontawesome-subset + @fortawesome/fontawesome-free as devDependencies
node scripts/build-fontawesome-subset.js
# or: npm run build:fontawesome
```

**Purpose:** this app self-hosts Font Awesome, but instead of shipping the full free set (~2000 icons, ~100KB CSS + ~300KB of woff2 across the solid/regular/brands styles), [`scripts/build-fontawesome-subset.js`](scripts/build-fontawesome-subset.js) generates a hand-subsetted build containing **only** the icon glyphs this app actually references — currently ~19KB total (CSS + woff2 combined) instead of ~400KB.

**What it does:**
1. Scans every `resources/views/**/*.blade.php` and `public/assets/js/**/*.js` file for `fa-{style} fa-{name}` class pairs, plus every admin `<option value="fa-{name}">` icon-picker choice (so an icon an admin *could* select, even if unused by any current row, still gets included).
2. Filters out Font Awesome's non-glyph utility classes (`fa-spin`, `fa-rotate-180`, `fa-fw`, etc.) so they aren't mistaken for icon names.
3. Feeds the resulting list to `fontawesome-subset` (backed by the pinned `@fortawesome/fontawesome-free@6.5.1` package for glyph metadata — kept in lockstep with the actual Font Awesome version this app was built against), which extracts only those glyphs into new `fa-solid-900` / `fa-regular-400` / `fa-brands-400` woff2 + ttf files under `public/assets/css/vendor/webfonts/`.
4. Writes a matching minimal CSS file to `public/assets/css/vendor/fontawesome/fontawesome.css` (just the shared icon-base rule, the three `@font-face` declarations, and one `.fa-{name}:before { content: ... }` rule per icon actually used) — served via `asset_min()` like any other stylesheet, so run `assets:minify` afterward to refresh the production `.min.css`.

**When to run it:** any time a **new** Font Awesome icon class is introduced anywhere in the app — a new `<i class="fa-solid fa-whatever">`, a JS file that sets an icon class dynamically, or a new `<option value="fa-...">` in an icon picker. Forgetting to re-run it doesn't error — the new icon just silently renders as an empty/missing-glyph box, since its glyph isn't in the subset font.

**Requires:** Node.js + npm (this is the one piece of tooling in this project that isn't PHP/Composer-based — there's no comparable font-subsetting library in the PHP ecosystem). Not required for normal app usage, only for regenerating this build.

---

## `vendor:chartjs`

```bash
npm install   # once — installs chart.js as a devDependency
node scripts/vendor-chartjs.js
# or: npm run vendor:chartjs
```

**Purpose:** the admin panel's charts (statistics page, per-book stats page) are rendered with [Chart.js](https://www.chartjs.org/) (MIT license) instead of hand-rolled CSS bars. Like Font Awesome, it's self-hosted rather than pulled from a CDN — [`scripts/vendor-chartjs.js`](scripts/vendor-chartjs.js) just copies the single-file UMD minified build (`node_modules/chart.js/dist/chart.umd.min.js`, no bundler needed) to `public/assets/js/vendor/chartjs/chart.umd.min.js`, loaded via `asset_min()` like any other script (so `?v=` cache-busting still applies even though the file itself is already minified).

**What it does:** copies one file. That's it — there's no subsetting step like Font Awesome, since Chart.js doesn't have a per-feature build for this app's simple bar-chart usage.

**When to run it:** after bumping the pinned `chart.js` version in `package.json`'s devDependencies (currently `4.5.1`).

**Requires:** Node.js + npm. Not required for normal app usage, only for re-vendoring after a version bump.

---

## `scripts/optimize-cover.php`

```bash
php scripts/optimize-cover.php ~/covers/blue-elephant.jpg
# or, batch mode over every image directly inside a folder:
php scripts/optimize-cover.php ~/covers ~/covers/ready
```

**Purpose:** the admin book create/edit form has 3 independent cover upload fields — "الغلاف الكبير/المتوسط/الصغير" (large/medium/small) — each stored as-is (see `Admin\BookController::storeCoverVariant()`), on the assumption you'll sometimes pre-size covers yourself locally before uploading. This script generates those exact 3 sizes from one original source image, so you can prepare all 3 files locally and then upload each into its matching field. It's a plain CLI script, not an Artisan command — it reuses this project's own `vendor/autoload.php` (same `intervention/image` library the app itself uses) but never touches the database or runs inside the app.

**What it does:**
- Reads one source image (or, in batch mode, every `.jpg`/`.jpeg`/`.png`/`.webp` file directly inside a source folder).
- For each, generates 3 WebP files at the same bounds/quality the app's own pipeline used to auto-generate: `{name}-lg.webp` (max `800×1200`), `{name}-md.webp` (max `600×900`), `{name}-sm.webp` (max `300×450`), all quality `80`.
- Writes output into an `optimized/` subfolder next to the source by default, or a folder you pass as the 2nd argument.

**When to run it:** anytime before adding/editing a book in the admin, whenever you want to pre-size a cover locally rather than letting the admin's safety-cap resize (`optimize()`, 2000×3000) do it for you.

**Requires:** PHP + this project's `vendor/` already installed (`composer install`) — no separate setup, no Node.

---

## `scripts/optimize-cover-server.php`

```bash
php -S localhost:8000 scripts/optimize-cover-server.php
# then open http://localhost:8000 in your browser
```

**Purpose:** the same tool as [`scripts/optimize-cover.php`](#scriptsoptimize-coverphp), just with a browser UI (file picker + drag-and-drop) instead of typing a source path on the command line. Shares the exact same resize logic — both scripts require [`scripts/lib/cover-variants.php`](scripts/lib/cover-variants.php), so they can never drift out of sync on sizes/quality.

**What it does:**
- `php -S` starts PHP's built-in dev server using this file as the router — it's the whole "app", no separate web server or config needed.
- The page lets you choose (or drop) one image; on submit it generates the same 3 WebP sizes (`800×1200` / `600×900` / `300×450`, quality `80`) into a temp folder and shows a download link for each, labeled بالعربية (الغلاف الكبير/المتوسط/الصغير) with their actual pixel dimensions.
- Uploads/outputs live under your OS temp directory (`nootabook-cover-optimizer/`), never inside the project or `storage/` — closing the server and deleting that temp folder leaves no trace.

**When to run it:** same situations as `optimize-cover.php`, whenever a file picker is more convenient than typing a path — e.g. picking straight from Downloads/Desktop.

**Requires:** same as `optimize-cover.php` (PHP + `vendor/`) — nothing extra. Stop it with Ctrl+C when done; it only serves on `localhost`, not your network.

---

## General notes

- None of these commands are registered on the scheduler (`routes/console.php`) — there's no automatic recurring optimization. Run them manually when relevant, or wire them into a deploy script.
- None of them need a queue worker — they run synchronously and print progress as they go.
- All five Artisan commands are safe to run against production data; the image/asset commands are destructive only in that they delete the specific old file they just replaced, `urls:fix-domain` never touches files at all (only DB values), and `build:fontawesome` only ever overwrites its own generated webfont/CSS output.
- `scripts/optimize-cover.php` and `scripts/optimize-cover-server.php` don't touch the app or database at all — they only read a source image and write new files into an output folder (or your OS temp directory, for the server UI), entirely outside `storage/`/`public/`. Nothing to run against production; run them on your own machine before uploading.

---

## Server caching (Nginx)

The app itself sets no `Cache-Control`/`Expires` headers on static assets (images, fonts, CSS, JS) — there's no `.htaccess` (this isn't Apache) and no middleware for it. Since production runs on **Nginx**, add a `location` block like this to the site's server config (outside this repo — ask your host, or add it to whatever `nginx.conf`/site conf manages this domain) to get long-lived caching on the assets that don't change once written:

```nginx
location ~* \.(?:jpg|jpeg|png|webp|gif|svg|ico|woff2?|ttf)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
    access_log off;
}

location ~* \.(?:css|js)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
    access_log off;
}
```

This is safe because:
- Book covers / writer photos are stored under UUID filenames (see `ImageOptimizer`) — a new upload always gets a new filename, so caching the old one forever is harmless.
- CSS/JS already carry a `?v=<filemtime>` cache-busting query string via the `asset_min()` helper (see [`app/helpers.php`](app/helpers.php)), so `immutable` caching is safe there too — a deploy that changes the file changes the URL.
- Static hero/banner images in `public/assets/images` (`images:optimize-assets`) are only ever replaced by re-running that command, which is a deliberate manual step — bump the filename (or purge the CDN/browser cache) if you ever need to force a refresh after re-running it.
