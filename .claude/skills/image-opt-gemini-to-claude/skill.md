---
name: image-performance-optimization
description: Guidelines and instructions for resolving responsive image sizing, WebP compression, and layout shift issues for mobile web applications.
---

# Image Performance Optimization Skill: Responsive Sizing & Compression

## Overview
This skill provides operational directives to eliminate image-related Google Lighthouse / PageSpeed Insights warnings (such as "Properly size images" and "Efficiently encode images") by serving scaled responsive variants, applying optimal WebP compression, and preventing Cumulative Layout Shift (CLS).

---

## Technical Directives

### 1. Responsive Image Sizing (`srcset` & `sizes`)

#### A. Avoid Static Oversized Dimensions
Do not hardcode large fixed `width` and `height` attributes (e.g., `300x300`) on UI elements that render at smaller sizes on mobile (e.g., `80x80` avatars).

```html
<!-- BEFORE (Oversized payload for mobile) -->
<img src="/storage/writers/author.webp" width="300" height="300" alt="Author">

<!-- AFTER (Matched intrinsic dimensions) -->
<img src="/storage/writers/author-80x80.webp" width="80" height="80" loading="lazy" decoding="async" alt="Author">
```

#### B. Implement Multi-Resolution `srcset`
For dynamic content such as book covers or product banners, supply multiple dimension variants in `srcset` alongside an accurate `sizes` media query attribute:

```html
<img 
  src="/storage/covers/book-md.webp" 
  srcset="
    /storage/covers/book-sm.webp 200w,
    /storage/covers/book-md.webp 400w,
    /storage/covers/book-lg.webp 800w
  "
  sizes="(max-width: 640px) 174px, 300px"
  width="174" 
  height="250" 
  loading="lazy" 
  decoding="async" 
  alt="Book Cover"
/>
```

---

## 2. Image Compression Standards

To pass the "Efficiently encode images" audit, ensure WebP/AVIF generation outputs at optimal compression factors without visible degradation.

* **Target WebP Quality:** Set compression level between **75% and 80%** (default 90%+ creates unnecessary file weight).
* **Target AVIF Quality:** Set compression level between **65% and 70%**.

### Backend Implementation Examples

#### PHP / Laravel (Intervention Image)
```php
use Intervention\Image\Facades\Image;

// Generate avatar thumbnail
Image::make($uploadedFile)
    ->fit(160, 160)
    ->encode('webp', 75)
    ->save(storage_path('app/public/writers/' . $filename . '-sm.webp'));

// Generate responsive cover
Image::make($uploadedFile)
    ->resize(400, null, function ($constraint) {
        $constraint->aspectRatio();
    })
    ->encode('webp', 75)
    ->save(storage_path('app/public/covers/' . $filename . '-md.webp'));
```

#### Node.js (Sharp)
```javascript
const sharp = require('sharp');

// Generate compressed responsive WebP
await sharp(inputBuffer)
  .resize(200, 300, { fit: 'cover' })
  .webp({ quality: 75 })
  .toFile('./output/book-sm.webp');
```

---

## 3. Mandatory Image Attributes

To ensure zero layout shifts (CLS) and non-blocking page loads, every `<img>` element must include:

1. **`width` & `height`**: Explicit aspect ratio declaration matching target proportions.
2. **`loading="lazy"`**: Defer off-screen images until scrolled near the viewport (exclude above-the-fold hero images).
3. **`decoding="async"`**: Offload image decoding operations from the main browser thread.

---

## Verification & Audit Checklist

- [ ] Run Lighthouse in **Mobile Mode**.
- [ ] Confirm **Properly size images** audit is clear (no requested images larger than displayed area).
- [ ] Confirm **Efficiently encode images** audit passes with optimized WebP/AVIF payloads.
- [ ] Ensure `loading="lazy"` is omitted ONLY for the primary LCP image, and present on all off-screen items.
- [ ] Check CLS score to ensure zero layout shift during image load.
