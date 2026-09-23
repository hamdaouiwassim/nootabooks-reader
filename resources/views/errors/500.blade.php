<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>خطأ في الخادم - نوتابوكس</title>
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
  <div class="error-badge tone-rose"><i class="fa-solid fa-server"></i></div>
  <div class="error-code">500</div>
  <h1 class="error-title">حدث خطأ في الخادم</h1>
  <p class="error-desc">عذرًا، حدث خطأ غير متوقع من جانبنا. فريقنا التقني يعمل على إصلاح المشكلة، الرجاء المحاولة مرة أخرى بعد قليل.</p>

  <div class="error-actions">
    <button type="button" class="btn btn-gold" onclick="location.reload()"><i class="fa-solid fa-arrow-rotate-right"></i> إعادة تحميل الصفحة</button>
    <a href="{{ route('home') }}" class="btn btn-outline"><i class="fa-solid fa-house"></i> العودة للرئيسية</a>
  </div>

  <p class="error-footer-note">استمرت المشكلة؟ <a href="{{ route('contact') }}">تواصل مع الدعم</a></p>
</div>

<script src="{{ asset_min('assets/js/script.js') }}" defer></script>
</body>
</html>
