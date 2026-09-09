<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>خطأ في الخادم - نوته بوك</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/errors.css') }}">
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

<script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
