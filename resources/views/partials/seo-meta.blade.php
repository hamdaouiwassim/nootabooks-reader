@php
    $seoTitle = trim($__env->yieldContent('title')) ?: 'نوته بوك - عالم من الكتب بين يديك';
    $seoDescription = trim($__env->yieldContent('meta_description')) ?: 'نوته بوك: منصة عربية لاكتشاف وقراءة وتحميل أفضل الكتب والروايات العربية، مع مراجعات القراء ومتابعة المؤلفين المفضلين لديك.';
    $seoRobots = trim($__env->yieldContent('robots')) ?: ($defaultRobots ?? 'index, follow');
    $seoImage = trim($__env->yieldContent('og_image')) ?: asset('assets/images/hero-section.jpg');
    $seoType = trim($__env->yieldContent('og_type')) ?: 'website';
    // Self-reference the "page" query param on the canonical (so /discover?page=2
    // isn't collapsed into /discover's canonical) while ignoring other query
    // params (search terms, filters) that don't represent genuinely distinct content.
    $canonicalUrl = url()->current();
    if (request()->filled('page') && request()->integer('page') > 1) {
        $canonicalUrl .= '?page='.request()->integer('page');
    }
@endphp
<meta name="description" content="{{ $seoDescription }}">
<link rel="canonical" href="{{ $canonicalUrl }}">
<meta name="robots" content="{{ $seoRobots }}">

<meta property="og:site_name" content="نوته بوك">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:type" content="{{ $seoType }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="{{ $seoImage }}">
<meta property="og:locale" content="ar_AR">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $seoImage }}">
