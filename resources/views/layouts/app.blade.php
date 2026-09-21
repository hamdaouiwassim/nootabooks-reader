<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
@include('partials.theme-init')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'نوته بوك - عالم من الكتب بين يديك')</title>
@include('partials.favicons')
@include('partials.google-analytics')
@include('partials.google-adsense')
@include('partials.seo-meta')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'نوته بوك',
    'url' => url('/'),
    'description' => 'منصة عربية لاكتشاف الكتب والروايات وقراءتها أونلاين.',
    'inLanguage' => 'ar',
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => [
            '@type' => 'EntryPoint',
            'urlTemplate' => route('discover').'?q={search_term_string}',
        ],
        'query-input' => 'required name=search_term_string',
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'نوته بوك',
    'url' => url('/'),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@stack('schema')
<link rel="preload" href="{{ asset('assets/fonts/tajawal/Iura6YBj_oCad4k1nzSBC45I.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{{ asset('assets/fonts/tajawal/Iurf6YBj_oCad4k1l5anHrRpiYlJ.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="{{ asset_min('assets/css/fonts.css') }}">
<link rel="preload" href="{{ asset_min('assets/css/vendor/fontawesome/fontawesome.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/vendor/fontawesome/fontawesome.css') }}"></noscript>
<link rel="preload" href="{{ asset_min('assets/css/style.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/style.css') }}"></noscript>
@stack('styles')
</head>
<body>

@include('partials.loader')

@include('partials.header')

@include('partials.alert')

@yield('content')

@include('partials.footer')

<script src="{{ asset_min('assets/js/script.js') }}" defer></script>
@stack('scripts')
</body>
</html>
