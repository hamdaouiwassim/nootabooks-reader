<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>التحقق من الرمز - لوحة تحكم مكتبتي</title>
<link rel="stylesheet" href="{{ asset_min('assets/css/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/vendor/fontawesome/all.min.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/admin.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/auth.css') }}">
</head>
<body>

<div class="auth-wrapper">
  <div class="auth-visual">
    <div class="admin-sidebar-head" style="border:none; padding:0;">
      <i class="fa-solid fa-book-bookmark logo-icon"></i>
      <div class="logo-text">
        <span class="logo-title">مكتبتي</span>
        <span class="logo-tagline">لوحة التحكم</span>
      </div>
    </div>

    <div class="auth-illustration">
      <span class="hero-plant"><i class="fa-solid fa-shield-halved"></i></span>
      <div class="hero-arch"></div>
      <span class="hero-open-book"><i class="fa-solid fa-envelope-open-text"></i></span>
    </div>

    <p class="auth-quote">"للحفاظ على أمان لوحة التحكم، أرسلنا رمز تحقق إلى بريدك الإلكتروني."</p>
  </div>

  <div class="auth-form-panel">
    <div class="auth-form-box">
      <h1>التحقق من الرمز</h1>
      <p class="auth-subtitle">أدخل الرمز المكوّن من 6 أرقام المرسل إلى {{ $maskedEmail }}</p>

      @if (session('status'))
        <div class="admin-alert success"><i class="fa-solid fa-circle-check"></i> {{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ route('admin.login.verify.store') }}" novalidate>
        @csrf

        <div class="form-field">
          <label for="code">رمز التحقق</label>
          <div class="input-with-icon">
            <i class="fa-solid fa-key"></i>
            <input type="text" id="code" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" placeholder="000000" required autofocus style="letter-spacing:6px; text-align:center; font-weight:800; font-size:18px;">
          </div>
          <span class="field-error">{{ $errors->first('code') }}</span>
        </div>

        <button type="submit" class="btn btn-gold full"><i class="fa-solid fa-shield-halved"></i> تأكيد الدخول</button>
      </form>

      <form method="POST" action="{{ route('admin.login.verify.resend') }}" style="margin-top:14px;">
        @csrf
        <button type="submit" class="btn btn-outline full">إعادة إرسال الرمز</button>
      </form>

      <p class="auth-switch">
        <a href="{{ route('admin.login') }}">العودة لتسجيل الدخول</a>
      </p>
    </div>
  </div>
</div>

</body>
</html>
