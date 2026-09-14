---
name: optimize-laravel-blade-assets
description: Comprehensive rule set for optimizing render-blocking CSS, preloading web fonts, and enforcing critical performance standards in Laravel Blade templates.
---

# Laravel Blade Asset & Font Optimization Rules

## Purpose
Enforce strict performance optimization standards across all Laravel Blade template files to eliminate render-blocking CSS, minimize First Contentful Paint (FCP) delay on mobile networks, and ensure optimal web font loading behavior.

---

## Core Optimization Rules

### Rule 1: Eliminate Render-Blocking CSS
* **Requirement:** Never use standard `<link rel="stylesheet" href="...">` tags for non-critical external or compiled CSS in the `<head>` section.
* **Implementation:** Convert all CSS requests to non-blocking `<link rel="preload" ...>` elements paired with an `onload` listener that converts the link to a stylesheet upon fetch.
* **Fallbacks:** Every preloaded CSS asset **must** have a corresponding entry inside a single `<noscript>` block to preserve rendering for non-JavaScript clients.

#### Template Pattern:
```blade
{{-- Non-Blocking CSS Preloading --}}
<link rel="preload" href="{{ asset('path/to/file.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">

{{-- No-JS Fallback --}}
<noscript>
    <link rel="stylesheet" href="{{ asset('path/to/file.css') }}">
</noscript>
```

---

### Rule 2: Prioritize Critical Web Fonts
* **Requirement:** Preload local `.woff2` font files to initiate fetch requests before CSS parsing completes.
* **Implementation:** Place all critical font preloads at the top of the `<head>` tag, above any CSS link declarations.
* **Attributes:** Font preloads **must** include `as="font"`, `type="font/woff2"`, and `crossorigin` (even for self-hosted domain assets).

#### Template Pattern:
```blade
{{-- Critical Font Preloads (Must appear first) --}}
<link rel="preload" href="https://nootabooks.com/assets/fonts/tajawal/Iura6YBj_oCad4k1nzSBC45I.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="https://nootabooks.com/assets/fonts/tajawal/Iurf6YBj_oCad4k1l5anHrRpiYlJ.woff2" as="font" type="font/woff2" crossorigin>
```

---

### Rule 3: Enforce `font-display: swap`
* **Requirement:** All `@font-face` CSS declarations must set `font-display: swap;` or `font-display: optional;` to prevent Invisible Text during Font Loading (FOIT).
* **Implementation:** Audit inline `<style>` tags and referenced stylesheets (`fonts.min.css`, `fontawesome.min.css`) to verify compliant rules.

#### CSS Pattern:
```css
@font-face {
  font-family: 'Tajawal';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url('/assets/fonts/tajawal/Iura6YBj_oCad4k1nzSBC45I.woff2') format('woff2');
}
```

---

### Rule 4: Prevent Flash of Unstyled Content (FOUC)
* **Requirement:** If deferring primary layout stylesheets (`style.min.css`) causes visible layout shift on slow mobile networks, extract critical above-the-fold layout styles (header, navigation, core container structure) and inline them directly inside the Blade `<head>`.

#### Template Pattern:
```blade
<head>
    <!-- 1. Font Preloads -->
    <link rel="preload" href="..." as="font" type="font/woff2" crossorigin>

    <!-- 2. Critical Above-the-Fold Inline Styles -->
    <style>
        /* Essential structure for immediate paint */
        body { margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Tajawal', sans-serif; }
        .header-container { display: flex; height: 60px; background: #fff; }
    </style>

    <!-- 3. Asynchronous CSS Files -->
    <link rel="preload" href="{{ asset('assets/css/style.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    
    <!-- 4. Noscript Fallback -->
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets/css/style.min.css') }}">
    </noscript>
</head>
```

---

## Refactoring Standard for Existing Layout Files

When processing any standard Blade layout file (e.g., `layouts/app.blade.php`, `partials/head.blade.php`), transform the asset structure to conform to this reference implementation:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>

    {{-- 1. Preload Fonts --}}
    @stack('font-preloads')
    <link rel="preload" href="https://nootabooks.com/assets/fonts/tajawal/Iura6YBj_oCad4k1nzSBC45I.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://nootabooks.com/assets/fonts/tajawal/Iurf6YBj_oCad4k1l5anHrRpiYlJ.woff2" as="font" type="font/woff2" crossorigin>

    {{-- 2. Asynchronous Render-Blocking Stylesheets --}}
    <link rel="preload" href="{{ asset('assets/css/fonts.min.css?v=' . config('app.version')) }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('assets/css/vendor/fontawesome/fontawesome.min.css?v=' . config('app.version')) }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('assets/css/style.min.css?v=' . config('app.version')) }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preload" href="{{ asset('assets/css/writers.min.css?v=' . config('app.version')) }}" as="style" onload="this.onload=null;this.rel='stylesheet'">

    {{-- 3. Consolidated Noscript Fallback --}}
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets/css/fonts.min.css?v=' . config('app.version')) }}">
        <link rel="stylesheet" href="{{ asset('assets/css/vendor/fontawesome/fontawesome.min.css?v=' . config('app.version')) }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.min.css?v=' . config('app.version')) }}">
        <link rel="stylesheet" href="{{ asset('assets/css/writers.min.css?v=' . config('app.version')) }}">
    </noscript>

    @stack('styles')
</head>
```

---

## Verification & Audit Checklist

1. **Google PageSpeed Insights (Mobile):**
   * Confirm **Render-blocking requests** savings target drops to 0 ms.
   * Confirm **Font display** audit passes with zero warnings.
2. **Chrome DevTools (Network Tab):**
   * Filter by `CSS`. Verify Priority column displays `Low` or `Lowest` during initial document load.
   * Filter by `Font`. Verify `.woff2` network calls initiate immediately after the main HTML document request.
