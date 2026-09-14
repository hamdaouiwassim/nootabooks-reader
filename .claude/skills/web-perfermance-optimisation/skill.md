---
name: web-performance-optimization
description: Guidelines and instructions for eliminating render-blocking requests (CSS/JS) and optimizing First Contentful Paint (FCP) / Largest Contentful Paint (LCP) for mobile web applications.
---

# Web Performance Optimization Skill: Render-Blocking Request Resolution

## Overview
This skill provides structured guidelines for identifying and eliminating render-blocking CSS and JavaScript resources in web applications, with a primary focus on mobile PageSpeed Insights / Core Web Vitals optimizations.

---

## Technical Directives

### 1. JavaScript Optimization Strategy

#### A. Defer Non-Critical Scripts
Ensure scripts that are not strictly required during initial HTML parsing carry the `defer` attribute. This allows the browser to download scripts in parallel without interrupting DOM tree construction.

```html
<!-- BEFORE (Render-Blocking) -->
<script src="/js/script.min.js"></script>

<!-- AFTER (Non-Blocking) -->
<script src="/js/script.min.js" defer></script>
```

#### B. Asynchronous Loading for Independent Scripts
Use `async` for completely independent scripts (e.g., third-party analytics, tracking pixels) where execution order does not matter:

```html
<script src="/js/analytics.js" async></script>
```

---

## 2. CSS Optimization Strategy

### A. Critical CSS Inlining
1. Extract minimum CSS required for rendering above-the-fold content (header, primary navigation, initial hero section).
2. Place the extracted critical CSS directly inside a `<style>` tag in the HTML `<head>`.

```html
<head>
  <style>
    /* Critical Above-the-Fold Styles */
    body { margin: 0; font-family: system-ui, -apple-system, sans-serif; }
    .hero { min-height: 100vh; background-color: #f8f9fa; }
  </style>
</head>
```

### B. Asynchronous Non-Critical CSS Loading
Convert standard stylesheet links for non-critical assets (e.g., icon fonts like FontAwesome, full stylesheet bundles) into preloaded links with fallbacks:

```html
<!-- Preload & Swap Pattern -->
<link rel="preload" href="/fontawesome/fontawesome.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="/fontawesome/fontawesome.min.css"></noscript>

<link rel="preload" href="/css/style.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="/css/style.min.css"></noscript>
```

---

## 3. Font Loading Optimization

1. **Preconnect to Font Domains:** Establish early TCP/TLS handshakes.
2. **Preload Key Web Fonts:** Preload primary fonts used in hero text.
3. **Use `font-display: swap;`:** Prevent Flash of Invisible Text (FOIT) by ensuring system fonts render immediately while custom fonts load.

```html
<!-- Early Connection -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Preload Key Font File -->
<link rel="preload" href="/fonts/main-font.woff2" as="font" type="font/woff2" crossorigin>
```

```css
/* Font Face Rule */
@font-face {
  font-family: 'CustomFont';
  src: url('/fonts/main-font.woff2') format('woff2');
  font-display: swap;
}
```

---

## 4. Framework Implementations

### Laravel (Blade Templates)
Utilize Laravel Mix or Vite directives for non-blocking asset loading:

```html
{{-- Vite CSS/JS with defer/preload strategy --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- Custom Blade Stack for deferred scripts --}}
@push('scripts')
    <script src="{{ asset('js/script.min.js') }}" defer></script>
@endpush
```

### Native HTML / Static Stacks
Apply inline critical CSS + deferred JS + preloaded stylesheet patterns directly within template layouts.

---

## Verification & Audit Checklist

- [ ] Execute Lighthouse / PageSpeed Insights in **Mobile Mode**.
- [ ] Verify **Render-blocking requests** is cleared or reduced to near 0 ms under audit insights.
- [ ] Check First Contentful Paint (FCP) and Largest Contentful Paint (LCP) metrics for improvements.
- [ ] Confirm layout stability (Cumulative Layout Shift / CLS) is unaffected during asset swap.
