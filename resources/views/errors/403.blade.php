<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>غير مصرح بالوصول - نوته بوك</title>
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

<script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
