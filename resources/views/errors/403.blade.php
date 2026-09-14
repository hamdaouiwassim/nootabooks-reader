<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>غير مصرح بالوصول - نوته بوك</title>
<link rel="preload" href="{{ asset('assets/fonts/tajawal/Iura6YBj_oCad4k1nzSBC45I.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{{ asset('assets/fonts/tajawal/Iurf6YBj_oCad4k1l5anHrRpiYlJ.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{{ asset_min('assets/css/fonts.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/fonts.css') }}"></noscript>
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
  <div class="error-badge tone-navy"><i class="fa-solid fa-lock"></i></div>
  <div class="error-code">403</div>
  <h1 class="error-title">غير مصرح لك بالوصول</h1>
  <p class="error-desc">عذرًا، ليس لديك الصلاحية اللازمة لعرض هذه الصفحة. قد تحتاج لتسجيل الدخول بحساب مختلف أو طلب صلاحية الوصول.</p>

  <div class="error-actions">
    <a href="{{ route('home') }}" class="btn btn-gold"><i class="fa-solid fa-house"></i> العودة للرئيسية</a>
    <a href="{{ route('login') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right-to-bracket"></i> تسجيل الدخول</a>
  </div>

  <p class="error-footer-note">تعتقد أن هذا خطأ؟ <a href="{{ route('contact') }}">تواصل مع الدعم</a></p>
</div>

<script src="{{ asset_min('assets/js/script.js') }}" defer></script>
</body>
</html>
