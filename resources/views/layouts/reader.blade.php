<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'نوته بوك - عالم من الكتب بين يديك')</title>
@include('partials.google-analytics')
@include('partials.seo-meta', ['defaultRobots' => 'noindex, nofollow'])
<link rel="stylesheet" href="{{ asset_min('assets/css/fonts.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/vendor/fontawesome/fontawesome.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/read.css') }}">
@stack('styles')
</head>
<body class="reader-body">

@include('partials.loader')

@yield('content')

<script src="{{ asset_min('assets/js/read.js') }}"></script>
@stack('scripts')
</body>
</html>
