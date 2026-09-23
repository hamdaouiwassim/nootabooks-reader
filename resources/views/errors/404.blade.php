<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>الصفحة غير موجودة - نوتابوكس</title>
@include('partials.favicons')
<link rel="preload" href="{{ asset('assets/fonts/tajawal/Iura6YBj_oCad4k1nzSBC45I.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{{ asset('assets/fonts/tajawal/Iurf6YBj_oCad4k1l5anHrRpiYlJ.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="{{ asset_min('assets/css/fonts.css') }}">
<link rel="preload" href="{{ asset_min('assets/css/vendor/fontawesome/fontawesome.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/vendor/fontawesome/fontawesome.css') }}"></noscript>
<link rel="preload" href="{{ asset_min('assets/css/style.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/style.css') }}"></noscript>
<link rel="preload" href="{{ asset_min('assets/css/errors.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/errors.css') }}"></noscript>
</head>
<body>

@include('partials.loader')

@include('partials.header')

<div class="error-page">
  <div class="error-badge tone-gold"><i class="fa-solid fa-compass"></i></div>
  <div class="error-code">404</div>
  <h1 class="error-title">الصفحة غير موجودة</h1>
  <p class="error-desc">عذرًا، الصفحة التي تبحث عنها غير موجودة أو تم نقلها أو حذفها. جرب البحث عن ما تريد أو عد إلى الرئيسية.</p>

  <div class="error-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="ابحث عن كتاب، مؤلف، او موضوع ...">
  </div>

  <div class="error-actions">
    <a href="{{ route('home') }}" class="btn btn-gold"><i class="fa-solid fa-house"></i> العودة للرئيسية</a>
    <a href="{{ route('discover') }}" class="btn btn-outline"><i class="fa-solid fa-compass"></i> استكشاف الكتب</a>
  </div>

  <p class="error-footer-note">ما زلت بحاجة لمساعدة؟ <a href="{{ route('contact') }}">تواصل مع الدعم</a></p>
</div>

<script src="{{ asset_min('assets/js/script.js') }}" defer></script>
</body>
</html>
