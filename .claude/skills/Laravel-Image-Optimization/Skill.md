# Laravel Uploaded Image Optimization Skill

## Purpose

This skill defines how to handle uploaded images in a Laravel application to improve:

- Page load performance
- Core Web Vitals
- Largest Contentful Paint (LCP)
- SEO
- Bandwidth consumption
- Storage usage
- Mobile performance
- Image delivery efficiency

Every uploaded raster image should be automatically optimized and converted to **WebP** when appropriate.

The implementation must prioritize:

1. Visual quality
2. Small file size
3. Fast processing
4. Secure image handling
5. Correct dimensions
6. SEO-friendly delivery
7. Maintainable Laravel architecture

---

# 1. Core Requirements

When an image is uploaded:

```text
User uploads image
        ↓
Validate file
        ↓
Verify actual MIME type
        ↓
Read image dimensions
        ↓
Fix EXIF orientation
        ↓
Resize if necessary
        ↓
Compress
        ↓
Convert to WebP
        ↓
Store optimized image
        ↓
Return optimized path
```

Do not simply store the original uploaded image.

Avoid serving large JPEG/PNG images directly when an optimized WebP version can be generated.

---

# 2. Recommended Laravel Architecture

Image optimization should be implemented as a reusable service rather than duplicated inside controllers.

Recommended structure:

```text
app/
├── Services/
│   └── Image/
│       ├── ImageOptimizer.php
│       ├── ImageOptimizationResult.php
│       └── ImageOptimizationException.php
│
├── Http/
│   ├── Controllers/
│   └── Requests/
│
└── Jobs/
    └── OptimizeImageJob.php
```

For simple applications, optimization can happen synchronously during upload.

For large images or high traffic applications, use a queued job.

---

# 3. Image Processing Library

Prefer a maintained PHP image-processing library.

Recommended:

```bash
composer require intervention/image
```

Use the current Intervention Image API compatible with the project's Laravel/PHP versions.

The implementation should use a real image processor rather than manipulating images manually with string/file operations.

---

# 4. Supported Input Formats

Recommended input formats:

```text
JPEG
JPG
PNG
WEBP
GIF
AVIF
```

For normal photographic and content images:

```text
JPEG → WebP
PNG  → WebP
WEBP → optimized WebP
```

Do not blindly convert every image.

For images requiring transparency:

```text
PNG with transparency → WebP with transparency
```

Animated GIFs should not automatically be converted into a static WebP.

For animated images, either preserve the original format or implement a dedicated animation-processing strategy.

SVG should normally remain SVG rather than being converted to WebP.

---

# 5. Security Validation

Never trust the uploaded file extension.

Validate both:

- Extension
- Actual MIME type

Example:

```php
$request->validate([
    'image' => [
        'required',
        'file',
        'mimes:jpg,jpeg,png,webp,gif',
        'max:10240',
    ],
]);
```

For stronger validation, inspect the actual image using the image-processing library.

Reject:

- Corrupted images
- Unsupported formats
- Files pretending to be images
- Images exceeding configured dimensions
- Suspicious files

Never execute uploaded files.

---

# 6. Maximum Image Dimensions

Do not store unnecessarily huge images.

Example configuration:

```env
IMAGE_MAX_WIDTH=2560
IMAGE_MAX_HEIGHT=2560
```

The exact value depends on the application.

For most websites:

```text
1920px – 2560px
```

is sufficient for large content images.

A 10,000 × 10,000 image should not normally be stored simply because the user uploaded it.

Resize proportionally.

Never upscale a smaller image.

Example:

```text
Original: 6000 × 4000
Maximum: 2560 × 2560

Result:
2560 × 1707
```

Preserve the original aspect ratio.

---

# 7. WebP Quality

Do not use extremely aggressive compression.

A good starting point is:

```text
WebP quality: 80–85
```

Recommended defaults:

```env
IMAGE_WEBP_QUALITY=82
```

For photographic images:

```text
80–85
```

For images containing text, UI screenshots, illustrations, or sharp graphics:

```text
85–90
```

Avoid automatically using quality `100`.

Quality 100 often produces a significantly larger file with little visible benefit.

The goal is:

```text
Visual quality ≈ original
File size ↓↓↓
```

rather than:

```text
Quality = 100
File size = huge
```

---

# 8. Visual Quality Principle

"Without losing quality" should be interpreted as:

> No noticeable visual degradation under normal website viewing conditions.

Compression should be evaluated visually and by file size.

Example:

```text
Original JPEG:
2.8 MB

Optimized WebP:
420 KB

Visual difference:
Barely noticeable / not noticeable
```

This is preferable to preserving a 2.8 MB original image.

Do not blindly optimize toward the smallest possible file.

---

# 9. Strip Unnecessary Metadata

Remove unnecessary metadata from public images where possible.

Metadata can include:

- EXIF
- Camera information
- GPS coordinates
- Software metadata
- Other unnecessary metadata

For public website images, EXIF GPS information should generally not be exposed.

However, preserve metadata when the application explicitly requires it.

---

# 10. EXIF Orientation

Some JPEG images contain orientation information in EXIF metadata.

Before saving the optimized image, correctly orient the image.

Otherwise an uploaded photograph can appear rotated after optimization.

The optimization pipeline should therefore handle:

```text
Upload
 ↓
Read EXIF orientation
 ↓
Orient image correctly
 ↓
Resize
 ↓
Compress
 ↓
Convert WebP
```

---

# 11. File Naming

Do not use the original filename directly.

Bad:

```text
my holiday photo.jpg
```

Better:

```text
images/2026/09/01/a8f31c9d.webp
```

Or:

```text
books/covers/{uuid}.webp
```

Generate safe unique filenames.

Recommended:

```php
$filename = (string) Str::uuid() . '.webp';
```

Never trust user-provided filenames for filesystem paths.

---

# 12. Storage

Use Laravel's filesystem abstraction.

Example:

```php
Storage::disk('public')->put(
    $path,
    $encodedImage
);
```

Do not hardcode:

```text
/var/www/html/project/public/uploads
```

inside application logic.

The storage disk should be configurable.

For example:

```env
FILESYSTEM_DISK=public
```

This makes it possible to move later to:

- S3
- Cloudflare R2
- DigitalOcean Spaces
- OVH Object Storage
- another CDN-backed storage system

without rewriting the image service.

---

# 13. Recommended Service API

The application should expose a simple service interface.

Example:

```php
$result = $imageOptimizer->optimize(
    file: $request->file('image'),
    directory: 'images'
);
```

The service should return information such as:

```php
[
    'path' => 'images/2026/09/example.webp',
    'url' => '...',
    'width' => 1600,
    'height' => 1067,
    'mime_type' => 'image/webp',
    'size' => 184320,
    'original_size' => 1245184,
]
```

This makes the service reusable throughout the application.

---

# 14. Example Service Concept

The service should conceptually perform:

```php
public function optimize(UploadedFile $file, string $directory): array
{
    // 1. Validate image

    // 2. Load image

    // 3. Correct orientation

    // 4. Resize if larger than maximum dimensions

    // 5. Encode as WebP

    // 6. Store optimized file

    // 7. Return metadata
}
```

The exact implementation should follow the installed version of Intervention Image.

Do not copy an old Intervention Image API from an outdated tutorial.

Always verify the API against the version installed in the project.

---

# 15. Responsive Images

Converting an image to WebP is only part of image optimization.

Do not serve a 2560px image to a device that only displays it at 400px.

For important images, generate multiple sizes.

Example:

```text
image-400.webp
image-800.webp
image-1200.webp
image-1600.webp
image-2560.webp
```

Recommended responsive widths:

```text
400
800
1200
1600
2560
```

Only generate the sizes required by the application's UI.

---

# 16. HTML Responsive Images

Use `srcset`:

```html
<img
    src="/storage/images/book-800.webp"
    srcset="
        /storage/images/book-400.webp 400w,
        /storage/images/book-800.webp 800w,
        /storage/images/book-1200.webp 1200w,
        /storage/images/book-1600.webp 1600w
    "
    sizes="(max-width: 768px) 100vw, 800px"
    width="800"
    height="1200"
    alt="Book cover"
>
```

This allows the browser to choose an appropriate image.

---

# 17. Always Define Width and Height

Images should have explicit dimensions whenever possible.

Bad:

```html
<img src="/images/book.webp" alt="Book">
```

Better:

```html
<img
    src="/images/book.webp"
    width="800"
    height="1200"
    alt="Book"
>
```

This helps the browser reserve the correct space before the image loads and reduces **Cumulative Layout Shift (CLS)**.

---

# 18. Lazy Loading

Images below the initial viewport should generally use:

```html
loading="lazy"
```

Example:

```html
<img
    src="/storage/images/book.webp"
    width="800"
    height="1200"
    loading="lazy"
    decoding="async"
    alt="Book cover"
>
```

Do NOT blindly apply lazy loading to the main LCP image.

The hero/LCP image should usually be loaded immediately.

---

# 19. LCP Images

For an important above-the-fold image:

```html
<img
    src="/storage/images/hero-1200.webp"
    width="1200"
    height="630"
    fetchpriority="high"
    decoding="async"
    alt="..."
>
```

Avoid:

```html
loading="lazy"
```

on the primary LCP image unless there is a specific reason.

---

# 20. SEO Alt Text

Image optimization does not replace proper SEO metadata.

Every meaningful content image should have descriptive alt text.

Bad:

```html
alt="image"
```

Bad:

```html
alt="IMG_23891"
```

Better:

```html
alt="Arabic novel book cover"
```

Decorative images can use:

```html
alt=""
```

Do not stuff keywords into alt text.

---

# 21. Cache Headers

Optimized images are generally excellent candidates for long browser caching.

For immutable/versioned image filenames:

```text
Cache-Control: public, max-age=31536000, immutable
```

If the same filename can be overwritten, use a shorter cache duration or version the filename.

Prefer:

```text
book-cover-v2.webp
```

or UUID-based filenames over replacing:

```text
book-cover.webp
```

---

# 22. CDN

For applications with significant traffic, serve optimized images through a CDN.

Recommended architecture:

```text
Laravel
   ↓
Object Storage
   ↓
CDN
   ↓
User
```

Examples:

```text
Cloudflare
Cloudflare R2
AWS S3 + CloudFront
DigitalOcean Spaces + CDN
OVH Object Storage
```

Do not introduce a CDN prematurely for a small application.

First solve:

```text
Image dimensions
+
Compression
+
WebP
+
Caching
```

Then introduce CDN delivery when traffic justifies it.

---

# 23. Original Image Strategy

There are two valid strategies.

## Strategy A — Delete original

```text
Upload
 ↓
Optimize
 ↓
Store WebP
 ↓
Delete original
```

Advantages:

- Less storage
- Simpler public filesystem
- No accidental serving of huge originals

Use this when the original is not needed.

## Strategy B — Keep original privately

```text
Private:
original.jpg

Public:
optimized.webp
```

This is preferable when the application needs:

- Future reprocessing
- Multiple output formats
- Image editing
- Original downloads
- Image restoration

The original should not be publicly exposed unless required.

---

# 24. Recommended Production Strategy

For most Laravel content platforms:

```text
Private original
       ↓
Image optimization service
       ↓
WebP variants
       ↓
Public storage
       ↓
CDN/browser cache
```

Example:

```text
private/originals/
public/images/
```

---

# 25. Database Design

Do not store binary image data in the database.

Store only metadata/path.

Example:

```text
images
------
id
disk
path
original_name
mime_type
width
height
size
original_size
created_at
updated_at
```

Example:

```php
$table->string('disk')->default('public');
$table->string('path');
$table->string('mime_type', 100);
$table->unsignedInteger('width')->nullable();
$table->unsignedInteger('height')->nullable();
$table->unsignedBigInteger('size')->nullable();
$table->unsignedBigInteger('original_size')->nullable();
```

For responsive images, either create related image variants or store their paths in a dedicated structure.

---

# 26. Reusable Image Model

For applications with many image types, create a reusable model:

```text
Image
```

Possible relationships:

```text
Book → image
Author → avatar
Article → featuredImage
Writing → cover
User → avatar
```

This prevents each model from implementing its own image-processing logic.

---

# 27. Queue Processing

For small images:

```text
Upload → Optimize immediately
```

For large images:

```text
Upload
 ↓
Store temporary/original
 ↓
Dispatch OptimizeImageJob
 ↓
Process WebP
 ↓
Generate variants
 ↓
Update database
```

Example:

```php
OptimizeImageJob::dispatch($image->id);
```

Use queues when image processing starts noticeably slowing HTTP requests.

---

# 28. Do Not Block the User Unnecessarily

If an image can take several seconds to process, do not make the user wait for all variants.

Prefer:

```text
Upload completed
      ↓
Image queued
      ↓
Background processing
      ↓
Variants generated
```

The UI can show:

```text
Processing image...
```

until the optimized version is ready.

---

# 29. Image Size Limits

Configure both:

```text
Maximum upload file size
Maximum pixel dimensions
```

Example:

```env
IMAGE_MAX_UPLOAD_SIZE=10240
IMAGE_MAX_WIDTH=2560
IMAGE_MAX_HEIGHT=2560
```

Do not assume a 2 MB file is safe.

A highly compressed image can still contain enormous dimensions.

For example:

```text
10 MB image
10000 × 10000 pixels
```

can consume substantial memory during processing.

---

# 30. Memory Safety

Image processing can consume significant PHP memory.

Avoid loading unnecessarily huge images.

Consider:

```text
Upload limits
Dimension limits
Queue workers
PHP memory_limit
```

Do not solve memory problems by simply increasing:

```ini
memory_limit=2G
```

without controlling image dimensions.

---

# 31. WebP Is Not Always the Final Answer

WebP is an excellent default, but modern image delivery can also use:

```text
AVIF
WebP
JPEG
PNG
```

A future-proof architecture can generate:

```text
AVIF
WebP
```

and provide fallback behavior.

However, do not introduce AVIF complexity unless there is a measurable benefit for the application.

WebP alone is already a major improvement over unoptimized JPEG/PNG in many applications.

---

# 32. Do Not Convert SVG

SVG is already a vector format.

Do not do:

```text
SVG → WebP
```

for normal icons/logos.

Keep:

```text
SVG → SVG
```

provided the SVG is sanitized and trusted.

---

# 33. Do Not Convert Every PNG Blindly

PNG is useful for:

- Transparency
- Screenshots
- Certain graphics
- Lossless assets

For photographs stored as PNG, conversion to WebP can significantly reduce size.

But test visual quality for:

```text
Screenshots
Text-heavy graphics
Logos
Transparent assets
```

---

# 34. Automatic Optimization Policy

A recommended policy:

```text
JPEG/JPG
    → WebP
    → resize
    → quality 82

PNG
    → WebP
    → preserve transparency
    → quality 85–90

WEBP
    → optimize if necessary

GIF
    → preserve unless dedicated animation processing exists

SVG
    → preserve
```

---

# 35. Avoid Double Compression

Do not repeatedly process an already optimized image.

Bad:

```text
original.jpg
 ↓
WebP
 ↓
WebP
 ↓
WebP
```

Every unnecessary processing cycle can degrade quality.

Track whether an image has already been optimized.

---

# 36. Blade Image Component

For Laravel Blade applications, create a reusable component.

Example:

```text
resources/views/components/image.blade.php
```

Usage:

```blade
<x-image
    :src="$book->cover"
    :alt="$book->title"
    width="800"
    height="1200"
/>
```

The component can centralize:

- `src`
- `srcset`
- `sizes`
- `loading`
- `decoding`
- `fetchpriority`
- `width`
- `height`
- `alt`

This prevents inconsistent image markup across the application.

---

# 37. Recommended Blade Output

Example:

```blade
<img
    src="{{ $src }}"
    srcset="{{ $srcset }}"
    sizes="{{ $sizes }}"
    width="{{ $width }}"
    height="{{ $height }}"
    loading="{{ $loading }}"
    decoding="async"
    alt="{{ $alt }}"
>
```

For LCP:

```blade
fetchpriority="high"
```

For normal content:

```blade
loading="lazy"
```

---

# 38. SEO Performance Checklist

Every production image implementation should verify:

- [ ] WebP generated
- [ ] Image dimensions limited
- [ ] Image resized appropriately
- [ ] Compression enabled
- [ ] EXIF orientation handled
- [ ] Unnecessary metadata removed
- [ ] Width specified
- [ ] Height specified
- [ ] Correct alt text
- [ ] Lazy loading for below-the-fold images
- [ ] LCP image not lazy loaded
- [ ] `fetchpriority="high"` for important LCP image
- [ ] Browser caching enabled
- [ ] CDN considered when appropriate
- [ ] Responsive `srcset` used for important images
- [ ] Original images not publicly exposed unnecessarily
- [ ] Upload MIME type validated
- [ ] Maximum file size enforced
- [ ] Maximum pixel dimensions enforced

---

# 39. Performance Target

Do not optimize images based only on a fixed file-size number.

Use practical targets.

For typical web content:

```text
Thumbnail:
< 100 KB

Card image:
~ 50–200 KB

Content image:
~ 100–400 KB

Large hero:
~ 200–600 KB
```

These are guidelines, not absolute rules.

A visually complex image may legitimately be larger.

The important goal is:

```text
Smallest file size
+
Acceptable visual quality
+
Correct dimensions
```

---

# 40. Lighthouse / PageSpeed Validation

After implementing optimization, test the application using:

- Lighthouse
- PageSpeed Insights
- Chrome DevTools
- WebPageTest

Look specifically for:

```text
Properly size images
Serve images in next-gen formats
Efficiently encode images
Largest Contentful Paint
Cumulative Layout Shift
Total Blocking Time
```

Do not assume that converting everything to WebP automatically solves image performance.

An oversized WebP can still be a performance problem.

Example:

```text
Bad:
4000px image displayed at 400px

Better:
800px image displayed at 400px
```

---

# 41. Golden Rule

The image pipeline should optimize for the **actual display size**, not simply convert the original file.

The preferred architecture is:

```text
UPLOAD
  ↓
VALIDATE
  ↓
SECURITY CHECK
  ↓
ORIENTATION
  ↓
RESIZE
  ↓
COMPRESS
  ↓
WEBP
  ↓
GENERATE RESPONSIVE VARIANTS
  ↓
STORE
  ↓
CACHE/CDN
  ↓
RESPONSIVE HTML
  ↓
FAST PAGE
```

The objective is not merely:

> "Convert images to WebP."

The objective is:

> **Deliver the smallest appropriate image to each device while preserving excellent visual quality.**