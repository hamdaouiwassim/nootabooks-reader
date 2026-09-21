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
@include('partials.seo-meta', ['defaultRobots' => 'noindex, follow'])
<link rel="preload" href="{{ asset('assets/fonts/tajawal/Iura6YBj_oCad4k1nzSBC45I.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{{ asset('assets/fonts/tajawal/Iurf6YBj_oCad4k1l5anHrRpiYlJ.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="{{ asset_min('assets/css/fonts.css') }}">
<link rel="preload" href="{{ asset_min('assets/css/vendor/fontawesome/fontawesome.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/vendor/fontawesome/fontawesome.css') }}"></noscript>
<link rel="preload" href="{{ asset_min('assets/css/style.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/style.css') }}"></noscript>
<link rel="preload" href="{{ asset_min('assets/css/read.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/read.css') }}"></noscript>
@stack('styles')
</head>
<body class="reader-body">

@include('partials.loader')

@yield('content')

<script src="{{ asset_min('assets/js/read.js') }}" defer></script>
@stack('scripts')
</body>
</html>
