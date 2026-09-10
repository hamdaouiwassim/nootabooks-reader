<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'نوته بوك - عالم من الكتب بين يديك')</title>
@include('partials.seo-meta')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'نوته بوك',
    'url' => url('/'),
    'inLanguage' => 'ar',
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'نوته بوك',
    'url' => url('/'),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@stack('schema')
<link rel="stylesheet" href="{{ asset_min('assets/css/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/vendor/fontawesome/all.min.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/style.css') }}">
@stack('styles')
</head>
<body>

@include('partials.loader')

@include('partials.header')

@include('partials.alert')

@yield('content')

@include('partials.footer')

<script src="{{ asset_min('assets/js/script.js') }}"></script>
@stack('scripts')
</body>
</html>
