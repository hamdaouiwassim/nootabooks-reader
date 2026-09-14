# Nootabooks Arabic Book SEO Skill

## Purpose

This skill defines the SEO standards and implementation rules for the Nootabooks Laravel Blade application.

Nootabooks is an Arabic-language book platform where users can discover books, read books online, and download books when legally available.

The primary SEO goal is to make each **book detail page** a high-quality, indexable landing page targeting:

* Book title
* Author
* Book type
* Reading intent
* Download intent
* PDF intent where applicable
* Related books
* Author discovery
* Category discovery

The goal is **not keyword stuffing**. Every SEO implementation must prioritize useful, natural Arabic content and a strong user experience.

---

# 1. Technology Context

This project uses:

* Laravel
* Laravel Blade
* PHP
* MySQL
* Alpine.js where needed
* Server-side rendered HTML
* Nootabooks Arabic UI
* RTL layout

Do not introduce React, Next.js, Vue, or another frontend framework for SEO-related implementation unless explicitly requested.

SEO content must be present in the server-rendered Blade HTML.

---

# 2. Primary SEO URL Architecture

Use clean, permanent URLs.

## Book

```text
/books/{slug}
```

Example:

```text
/books/ard-zykola
```

This is the **canonical SEO page** for the book.

## Author

```text
/authors/{slug}
```

## Category

```text
/categories/{slug}
```

## Online reader

```text
/books/{slug}/read
```

## Download

```text
/books/{slug}/download
```

The main book page must remain the primary SEO landing page.

The reader and download endpoints must not compete with the main book page in search results.

---

# 3. Book Page SEO Objective

Every book page should provide useful, indexable content including:

1. Breadcrumb
2. H1
3. Book cover
4. Book title
5. Author
6. Category
7. Language
8. Page count when available
9. Publication information when available
10. Book description
11. Online reading option
12. Download option when legally available
13. Author information
14. Related books
15. Internal links
16. Structured data

Do not create thin pages containing only:

```text
Book cover
Download button
Book title
```

A book page should be a complete informational landing page.

---

# 4. Primary Keyword Strategy

Do not create a fixed keyword list for the entire website.

Generate keyword intent from the actual book.

For a normal book:

```text
تحميل كتاب {title} PDF
قراءة كتاب {title}
قراءة {title} أونلاين
كتاب {title} PDF
{title} PDF
```

For a novel:

```text
تحميل رواية {title} PDF
قراءة رواية {title}
رواية {title} PDF
قراءة {title} أونلاين
```

Author-related queries:

```text
كتب {author}
روايات {author}
كتب {author} PDF
```

Use these phrases naturally.

Never repeat the same keyword unnaturally throughout the page.

---

# 5. Keyword Stuffing Rules

Never generate content such as:

```text
تحميل كتاب X PDF
تحميل كتاب X مجانا
تحميل كتاب X PDF مجانا
تحميل كتاب X
كتاب X PDF
كتاب X تحميل
تحميل X
```

as repetitive paragraphs.

Do not create SEO text whose only purpose is keyword repetition.

Instead, write useful Arabic prose.

Bad:

```text
تحميل رواية X PDF. يمكنك تحميل رواية X PDF.
رواية X PDF متوفرة للتحميل. تحميل رواية X PDF.
```

Good:

```text
رواية X للكاتب Y هي رواية تدور أحداثها حول...
يمكنك التعرف على معلومات الكتاب ومؤلفه وقراءته أونلاين
من خلال نوتابوكس، كما تتوفر صيغة PDF عندما تكون متاحة بشكل قانوني.
```

---

# 6. Page Title

The default title for a normal book should be:

```text
تحميل كتاب {title} PDF وقراءته أونلاين | نوتابوكس
```

For a novel:

```text
تحميل رواية {title} PDF وقراءتها أونلاين | نوتابوكس
```

Use the actual book type.

Do not automatically call every work a "رواية".

Examples:

```text
تحميل كتاب {title} PDF وقراءته أونلاين | نوتابوكس

تحميل رواية {title} PDF وقراءتها أونلاين | نوتابوكس

قراءة {title} أونلاين | نوتابوكس
```

Allow a manually configured `seo_title` field to override the generated title.

Example:

```php
$seoTitle = $book->seo_title
    ?: "تحميل {$book->title} PDF وقراءته أونلاين | نوتابوكس";
```

---

# 7. Meta Description

The default description should be natural Arabic.

Example:

```text
تحميل {title} PDF وقراءته أونلاين. تعرّف على الكتاب ومؤلفه وتصنيفه واقرأه مباشرة على نوتابوكس.
```

For novels:

```text
تحميل رواية {title} PDF وقراءتها أونلاين. تعرّف على الرواية ومؤلفها وتصنيفها واقرأها مباشرة على نوتابوكس.
```

Do not keyword-stuff the description.

Do not always use the word:

```text
مجانا
```

unless it accurately describes the actual offering.

Allow:

```text
seo_description
```

to override the generated description.

---

# 8. Canonical URL

Every indexable book page must have exactly one canonical URL.

Use:

```blade
<link
    rel="canonical"
    href="{{ route('books.show', $book->slug) }}"
>
```

The canonical URL must point to:

```text
/books/{slug}
```

Never canonicalize the main book page to:

```text
/books/{slug}/read
```

or:

```text
/books/{slug}/download
```

Avoid query-string canonical URLs.

For example:

```text
/books/book-name?utm_source=facebook
```

must canonicalize to:

```text
/books/book-name
```

---

# 9. Robots Rules

## Main book page

The default should be:

```html
<meta name="robots" content="index,follow">
```

## Reader page

The reader UI should normally be:

```html
<meta name="robots" content="noindex,follow">
```

unless there is a deliberate SEO reason to index it.

## Download endpoint

The download endpoint should not become an SEO landing page.

SEO value should remain on:

```text
/books/{slug}
```

Do not create separate indexable pages for every download request.

---

# 10. H1

Each book page must have exactly one primary H1.

Recommended:

```blade
<h1>
    تحميل {{ $book->title }} PDF وقراءته أونلاين
</h1>
```

For a novel:

```blade
<h1>
    تحميل رواية {{ $book->title }} PDF وقراءتها أونلاين
</h1>
```

The H1 must remain readable.

Do not make an H1 such as:

```text
تحميل رواية X PDF مجانا تحميل رواية X كاملة قراءة رواية X أونلاين
```

---

# 11. Book Description

The book description is one of the most important SEO content sections.

Use:

```blade
<section aria-labelledby="book-description">

    <h2 id="book-description">
        نبذة عن {{ $book->title }}
    </h2>

    <div>
        {!! nl2br(e($book->description)) !!}
    </div>

</section>
```

The description should provide real information about:

* Subject
* Story
* Themes
* Author
* Book context
* Genre
* Audience where appropriate

Never generate empty SEO paragraphs simply to increase word count.

---

# 12. Book Information

Include factual information in semantic HTML.

Recommended fields:

```text
اسم الكتاب
المؤلف
التصنيف
النوع
اللغة
عدد الصفحات
الناشر
تاريخ النشر
ISBN
```

Only show fields that actually exist.

Never invent:

* ISBN
* Publisher
* Publication date
* Page count
* Author information
* Ratings
* Reviews

---

# 13. Online Reading Section

Nootabooks should target both reading and download intent.

Use an explicit section:

```blade
<section aria-labelledby="online-reading">

    <h2 id="online-reading">
        قراءة {{ $book->title }} أونلاين
    </h2>

    <p>
        يمكنك قراءة {{ $book->title }}
        أونلاين مباشرة من خلال قارئ الكتب في نوتابوكس.
    </p>

    <a href="{{ route('books.read', $book->slug) }}">
        قراءة الكتاب أونلاين
    </a>

</section>
```

Use the actual book type where appropriate:

```text
قراءة الرواية أونلاين
قراءة الكتاب أونلاين
```

---

# 14. Download Section

If a legal download is available:

```blade
<section aria-labelledby="download-book">

    <h2 id="download-book">
        تحميل {{ $book->title }} PDF
    </h2>

    <p>
        يمكنك تحميل {{ $book->title }}
        بصيغة PDF وقراءته على الهاتف أو الكمبيوتر.
    </p>

    <a href="{{ route('books.download', $book->slug) }}">
        تحميل الكتاب PDF
    </a>

</section>
```

Do not claim that a PDF exists if it does not.

Do not claim "مجانا" unless the download is actually free.

Do not claim a book is legally downloadable unless the application has reliable information supporting that claim.

---

# 15. Copyright and Legal Content

Nootabooks must not use SEO techniques to promote unauthorized copyrighted downloads.

If a book cannot legally be distributed:

* Do not create misleading "تحميل PDF" content.
* Do not claim that an unauthorized file is available.
* Prefer legal reading, author information, publisher information, or availability information.
* Do not create SEO text specifically designed to rank for pirated copies.

SEO optimization must remain compatible with copyright requirements.

---

# 16. Author Section

Every book with an author should link to the author page.

Recommended:

```blade
<section aria-labelledby="author">

    <h2 id="author">
        عن مؤلف {{ $book->title }}
    </h2>

    <h3>
        {{ $book->author->name }}
    </h3>

    @if($book->author->bio)
        <p>
            {{ $book->author->bio }}
        </p>
    @endif

    <a href="{{ route('authors.show', $book->author->slug) }}">
        جميع كتب {{ $book->author->name }}
    </a>

</section>
```

This creates an internal linking relationship:

```text
Author
   ↓
Books
   ↓
Book
```

---

# 17. Related Books

Every book page should provide relevant internal links when enough related books exist.

Recommended:

```text
كتب مشابهة
روايات مشابهة
كتب من نفس التصنيف
كتب أخرى للمؤلف
```

Do not randomly link unrelated books.

Prioritize:

1. Same author
2. Same category
3. Same type
4. Similar topics
5. Popular relevant books

---

# 18. Image SEO

The book cover must have meaningful alt text.

Use:

```blade
<img
    src="{{ $book->cover_url }}"
    alt="غلاف {{ $book->title }}"
    width="300"
    height="450"
>
```

Do not use:

```text
alt="book"
alt="image"
alt="تحميل كتاب PDF"
alt="تحميل مجاني"
```

The alt text should describe the image.

Avoid stuffing keywords into image alt attributes.

Use explicit width and height to reduce layout shift.

The primary book cover may use:

```html
loading="eager"
fetchpriority="high"
```

Related book covers should normally use:

```html
loading="lazy"
```

---

# 19. Open Graph

Every book page should provide:

```html
<meta property="og:type" content="book">
<meta property="og:title" content="...">
<meta property="og:description" content="...">
<meta property="og:url" content="...">
<meta property="og:image" content="...">
<meta property="og:site_name" content="نوتابوكس">
<meta property="og:locale" content="ar_AR">
```

Use the book cover as the Open Graph image where appropriate.

---

# 20. Twitter Metadata

Use:

```html
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="...">
<meta name="twitter:description" content="...">
<meta name="twitter:image" content="...">
```

Do not create Twitter metadata with different misleading content.

---

# 21. Book Structured Data

Add `Book` JSON-LD when the information is available.

Example:

```blade
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Book",
    "@id": "{{ route('books.show', $book->slug) }}#book",
    "name": @json($book->title),
    "url": @json(route('books.show', $book->slug)),
    "image": [
        @json($book->cover_url)
    ],
    "description": @json(strip_tags($book->description)),
    "inLanguage": @json($book->language ?? 'ar')

    @if($book->author)
    ,
    "author": {
        "@type": "Person",
        "name": @json($book->author->name),
        "url": @json(route('authors.show', $book->author->slug))
    }
    @endif

    @if($book->isbn)
    ,
    "isbn": @json($book->isbn)
    @endif

    @if($book->publisher)
    ,
    "publisher": {
        "@type": "Organization",
        "name": @json($book->publisher)
    }
    @endif

    @if($book->published_at)
    ,
    "datePublished": "{{ $book->published_at->toIso8601String() }}"
    @endif
}
</script>
```

Only output properties when the underlying database value exists.

Never fabricate structured-data values.

---

# 22. Breadcrumb Structured Data

Add:

```json
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "الرئيسية",
            "item": "https://nootabooks.com"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "الكتب",
            "item": "https://nootabooks.com/books"
        },
        {
            "@type": "ListItem",
            "position": 3,
            "name": "{Book title}",
            "item": "{Book URL}"
        }
    ]
}
```

Use actual Laravel route URLs rather than hardcoded URLs whenever possible.

---

# 23. FAQ

FAQ content can be added when it genuinely helps users.

Possible questions:

```text
هل يمكن قراءة {title} أونلاين؟
هل يتوفر {title} بصيغة PDF؟
من مؤلف {title}؟
ما تصنيف {title}؟
```

Only create FAQs when the answers are useful and factually correct.

Do not generate dozens of fake questions to manipulate search rankings.

Do not assume that FAQ structured data will generate special Google search results.

---

# 24. Meta Keywords

Do NOT implement:

```html
<meta name="keywords">
```

Google does not use the meta keywords tag as a normal ranking mechanism.

SEO keywords should appear naturally in:

* Title
* Meta description
* H1
* H2
* Body content
* Internal links
* Image alt text where appropriate
* Structured data
* URL slug

---

# 25. Internal Linking Strategy

Book pages should be part of a strong internal-link architecture.

Recommended structure:

```text
Homepage
   ↓
Categories
   ↓
Books
   ↓
Book
   ↓
Author
   ↓
Other books
```

And:

```text
Book
 ├── Author
 ├── Category
 ├── Related books
 └── Other books by author
```

Use descriptive Arabic anchor text.

Good:

```text
كتب عمرو عبد الحميد
روايات الخيال
قراءة الكتاب أونلاين
```

Avoid:

```text
اضغط هنا
المزيد
انقر هنا
```

when a more descriptive anchor is possible.

---

# 26. Slug Rules

Slugs must be:

* Stable
* Unique
* Short
* Human-readable
* Permanent

Example:

```text
/books/ard-zykola
```

Do not include SEO keyword stuffing in the slug.

Bad:

```text
/books/download-ard-zykola-pdf-free-full-book
```

If the application already has stable slugs, do not change them without implementing proper redirects.

---

# 27. Duplicate Content

Avoid creating multiple URLs for the same book.

For example:

```text
/books/ard-zykola
/books/ard-zykola/
/book/ard-zykola
/books?id=123
```

The application should have one preferred canonical URL.

If old URLs exist, use 301 redirects where appropriate.

---

# 28. Pagination and Filtering

Do not allow arbitrary filters to create thousands of indexable duplicate URLs.

Examples:

```text
/books?sort=latest
/books?sort=popular
/books?category=novels
/books?author=123
```

Determine which listing pages are valuable for SEO.

Use canonical URLs and/or `noindex` where appropriate.

Do not automatically index every parameter combination.

---

# 29. Arabic SEO Writing Rules

Arabic SEO content must be written naturally.

Prefer:

```text
تحميل رواية...
قراءة الرواية أونلاين
معلومات عن الكتاب
نبذة عن المؤلف
```

Avoid unnatural machine-generated repetition.

Do not mix Arabic and English keywords unnecessarily.

Use the language users naturally search for.

If the book has an established English title, it may be included where genuinely useful.

---

# 30. SEO Fields in the Database

Prefer supporting manual SEO overrides.

Recommended fields:

```text
seo_title
seo_description
canonical_url
noindex
og_image
```

Example model structure:

```text
books
├── id
├── title
├── slug
├── description
├── excerpt
├── type
├── language
├── pages
├── isbn
├── publisher
├── published_at
├── cover
├── author_id
├── category_id
├── seo_title
├── seo_description
├── canonical_url
├── noindex
└── og_image
```

SEO fields should override generated defaults.

---

# 31. Blade SEO Implementation

Prefer reusable Blade sections.

Example:

```blade
@section('title')
    {{ $book->seo_title
        ?: "تحميل {$book->title} PDF وقراءته أونلاين | نوتابوكس" }}
@endsection

@section('meta_description')
    {{ $book->seo_description
        ?: "تحميل {$book->title} PDF وقراءته أونلاين. تعرّف على الكتاب ومؤلفه وتصنيفه واقرأه مباشرة على نوتابوكس." }}
@endsection

@section('canonical')
    {{ $book->canonical_url ?: route('books.show', $book->slug) }}
@endsection
```

If the book is a novel, adapt the generated wording based on the actual type.

---

# 32. Performance Requirements

SEO implementation must not unnecessarily hurt performance.

For book covers:

* Use WebP or AVIF where supported
* Use responsive images
* Provide width and height
* Lazy-load non-critical images
* Prioritize the primary cover
* Avoid unnecessarily large source images

Use:

```html
srcset
sizes
```

where responsive image variants are available.

Do not load large images when the displayed image is small.

Avoid unnecessary third-party fonts and scripts.

---

# 33. Accessibility and SEO

SEO HTML must also be accessible.

Use:

* Semantic headings
* `<nav>`
* `<article>`
* `<section>`
* `<header>`
* `<dl>` for book metadata where appropriate
* Meaningful link text
* Meaningful image alt text
* Proper RTL direction

Example:

```html
<html lang="ar" dir="rtl">
```

Do not sacrifice accessibility for keyword placement.

---

# 34. SEO Checklist for Every Book Page

Before considering a book page complete, verify:

### Metadata

* [ ] Unique `<title>`
* [ ] Unique meta description
* [ ] Canonical URL
* [ ] Correct robots directive
* [ ] Open Graph title
* [ ] Open Graph description
* [ ] Open Graph image
* [ ] Twitter card

### Content

* [ ] Exactly one H1
* [ ] Book title visible in HTML
* [ ] Author visible in HTML
* [ ] Real book description
* [ ] Book metadata
* [ ] Reading section
* [ ] Download section when applicable
* [ ] Author section
* [ ] Related books

### Images

* [ ] Meaningful alt text
* [ ] Width/height attributes
* [ ] Responsive images where available
* [ ] Proper loading strategy

### Structured data

* [ ] Book JSON-LD
* [ ] Breadcrumb JSON-LD
* [ ] Only factual properties
* [ ] No fabricated values

### Internal linking

* [ ] Breadcrumb links
* [ ] Author link
* [ ] Category link
* [ ] Related books
* [ ] Other books by author where relevant

### Technical

* [ ] Server-rendered HTML contains SEO content
* [ ] No duplicate canonical URLs
* [ ] No unnecessary query-indexing
* [ ] Reader page does not compete with book page
* [ ] Download endpoint does not compete with book page
* [ ] No broken internal links
* [ ] No unnecessary JavaScript dependency for essential SEO content

---

# 35. Claude Code Implementation Rules

When modifying Nootabooks SEO:

1. Inspect the existing Laravel project before changing anything.
2. Reuse existing Blade layouts and components.
3. Do not create duplicate SEO systems.
4. Check existing routes before creating new routes.
5. Check existing models and database columns before adding fields.
6. Prefer reusable Blade components for metadata.
7. Preserve existing design and functionality.
8. Do not introduce React, Vue, Next.js, or another frontend framework.
9. Keep SEO content server-rendered.
10. Never invent book metadata.
11. Never create fake reviews, ratings, authors, ISBNs, or publishers.
12. Never add keyword stuffing.
13. Never automatically use "مجانا" unless factually correct.
14. Never create misleading SEO content for unauthorized copyrighted downloads.
15. Use Laravel route helpers instead of hardcoded URLs.
16. Preserve existing canonical URLs when they are already correct.
17. Validate JSON-LD after implementation.
18. Check generated HTML, not only Blade source code.
19. Test pages with missing optional fields.
20. Ensure the implementation works correctly for Arabic RTL content.

---

# 36. Recommended Final Book Page

The final page should conceptually follow:

```text
Breadcrumb

H1:
تحميل {Book Type} {Title} PDF وقراءته أونلاين

Book information
├── Cover
├── Title
├── Author
├── Category
├── Language
└── Pages

Primary actions
├── قراءة أونلاين
└── تحميل PDF

H2:
نبذة عن {Title}

Real book description

H2:
معلومات عن {Title}

Book metadata

H2:
قراءة {Title} أونلاين

Reader CTA

H2:
تحميل {Title} PDF

Download information / CTA

H2:
عن مؤلف {Title}

Author information

H2:
كتب أخرى للمؤلف

Author books

H2:
كتب مشابهة

Related books

Footer
```

This structure should be used as the default SEO architecture for Nootabooks book pages.

---

# 37. Core SEO Philosophy

The most important rule:

**Build the page for the reader first and search engines second.**

A strong Nootabooks book page should answer:

* What is this book?
* Who wrote it?
* What is it about?
* What type of book is it?
* Can I read it online?
* Can I legally download it?
* What other books might I like?
* What other books has this author written?

If the page answers these questions clearly in natural Arabic, the page will have substantially stronger SEO foundations than a page built primarily around repeating "تحميل PDF".
