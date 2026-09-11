<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'نوته بوك - عالم من الكتب بين يديك')</title>
@include('partials.google-analytics')
@include('partials.seo-meta', ['defaultRobots' => 'noindex, follow'])
<link rel="stylesheet" href="{{ asset_min('assets/css/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/vendor/fontawesome/all.min.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/auth.css') }}">
@stack('styles')
</head>
<body>

@include('partials.loader')

@include('partials.alert')

@yield('content')

<script src="{{ asset_min('assets/js/auth.js') }}"></script>
@stack('scripts')
</body>
</html>
