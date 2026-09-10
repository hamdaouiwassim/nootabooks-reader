# Custom Artisan Commands

This document describes the custom Artisan commands added to this project, defined in [`app/Console/Commands`](app/Console/Commands). They're all one-off/maintenance commands you run manually — none of them are scheduled.

| Command | Purpose |
|---|---|
| [`images:optimize`](#images-optimize) | Compress already-uploaded book covers & writer photos to WebP, with a small card-thumbnail variant |
| [`images:optimize-assets`](#images-optimize-assets) | Compress the static hero/banner images in `public/assets/images`, with a small mobile variant of each |
| [`assets:minify`](#assets-minify) | Generate `.min.css` / `.min.js` files for production |

---

## `images:optimize`

```bash
php artisan images:optimize
```

**Purpose:** retroactively optimizes every already-uploaded `Book.cover_image` and `Writer.photo` — for anything uploaded before the [`ImageOptimizer`](app/Services/Image/ImageOptimizer.php) pipeline existed, or before it started generating responsive variants.

**What it does**, for every non-null `cover_image` / `photo` row:
1. Skips rows that aren't real uploaded files (external URLs it can't reach locally, or seeded demo assets like `assets/books/*.jpg`).
2. Skips rows that are already `.webp` **and** already have a small `-sm` variant generated — so re-running this command is always safe and won't re-compress the same file twice.
3. For everything else: re-encodes the image as WebP and generates two sized variants sharing one filename —
   - Book covers: full `800×1200` + small `300×450`
   - Writer photos: full `600×600` + small `300×300`
4. Deletes the old file(s) and updates the DB row with the new (full-size) URL.
5. Saving the row fires the model's normal `save()` event — which busts the home-page/sitemap cache (see [`FlushesAppCache`](app/Models/Concerns/FlushesAppCache.php)) — so you don't need to clear the cache separately afterward.

**When to run it:**
- Once, right after deploying the responsive-image feature, to bring existing content up to date.
- Anytime you suspect some covers/photos are still in their original (large, non-WebP) form.

**Requires:** `intervention/image` (`composer update`).

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
- Walks every `*.css` in `public/assets/css` and every `*.js` in `public/assets/js` (recursively — this includes the self-hosted `fonts.css`, but skips the already-minified vendor Font Awesome CSS since its filename already ends in `.min.css`).
- Minifies each with `matthiasmullie/minify` and writes it next to the original as `{name}.min.{css,js}`.
- Prints a before/after KB size and % reduction per file.
- Safe to re-run anytime — it always overwrites the `.min.` file fresh from the current source.

**When to run it:**
- Before/as part of every production deploy.
- Anytime after editing any `.css`/`.js` file, if you want the production build to reflect that change (local dev doesn't need this — it always serves the live, unminified file).

**Requires:** `matthiasmullie/minify` (`composer update`).

---

## General notes

- None of these commands are registered on the scheduler (`routes/console.php`) — there's no automatic recurring optimization. Run them manually when relevant, or wire them into a deploy script.
- None of them need a queue worker — they run synchronously and print progress as they go.
- All three are safe to run against production data; none of them are destructive beyond deleting the specific old file they just replaced.
