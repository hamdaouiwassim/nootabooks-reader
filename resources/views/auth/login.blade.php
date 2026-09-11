@extends('layouts.auth')

@section('title', 'تسجيل الدخول - نوته بوك')

@section('content')
<div class="auth-wrapper">

  <!-- ===================== VISUAL PANEL ===================== -->
  <div class="auth-visual login-visual">
    <a href="{{ route('home') }}" class="logo">
      <i class="fa-solid fa-book-bookmark logo-icon"></i>
      <div class="logo-text">
        <span class="logo-title">نوته بوك</span>
        <span class="logo-tagline">عالم من الكتب بين يديك</span>
      </div>
    </a>

    <h1 class="auth-visual-heading">تسجيل الدخول</h1>

    <div class="auth-illustration"></div>

    <blockquote class="auth-quote">"الكتب هي نوافذ نرى من خلالها عوالم أخرى"</blockquote>
  </div>

  <!-- ===================== FORM PANEL ===================== -->
  <div class="auth-form-panel">
    <div class="auth-form-box">
      <h1 class="auth-form-heading">تسجيل الدخول</h1>
      <p class="auth-subtitle">مرحبًا بعودتك! سجل دخولك لمتابعة القراءة</p>

      <div class="social-auth-buttons">
        <button type="button" class="btn btn-outline social-btn"><i class="fa-brands fa-google"></i> المتابعة عبر جوجل</button>
        <button type="button" class="btn btn-outline social-btn"><i class="fa-brands fa-facebook-f"></i> المتابعة عبر فيسبوك</button>
      </div>

      <div class="auth-divider"><span>أو عبر البريد الإلكتروني</span></div>

      <form id="loginForm" method="POST" action="{{ route('login.submit') }}" data-recaptcha-action="login" novalidate>
        @csrf
        <div class="form-field">
          <label for="loginEmail">البريد الإلكتروني</label>
          <div class="input-with-icon">
            <i class="fa-regular fa-envelope"></i>
            <input type="email" id="loginEmail" name="email" value="{{ old('email') }}" placeholder="ادخل بريدك الإلكتروني" required>
          </div>
          <span class="field-error" id="loginEmailError">@error('email'){{ $message }}@enderror</span>
        </div>

        <div class="form-field">
          <label for="loginPassword">كلمة المرور</label>
          <div class="input-with-icon">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="loginPassword" name="password" placeholder="ادخل كلمة المرور" required>
            <button type="button" class="toggle-password" aria-label="show password"><i class="fa-regular fa-eye"></i></button>
          </div>
          <span class="field-error" id="loginPasswordError">@error('password'){{ $message }}@enderror</span>
        </div>

        <div class="form-row-between">
          <label class="checkbox-row"><input type="checkbox" name="remember"> <span>تذكرني</span></label>
          <a href="#" class="forgot-link">نسيت كلمة المرور؟</a>
        </div>

        @include('partials.recaptcha')

        <button type="submit" class="btn btn-teal full">تسجيل الدخول</button>
      </form>
      <p class="form-success" id="loginSuccess" hidden><i class="fa-solid fa-circle-check"></i> تم تسجيل الدخول بنجاح، جارِ التحويل ...</p>

      <p class="auth-switch">ليس لديك حساب؟ <a href="{{ route('register') }}">إنشاء حساب جديد</a></p>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/login.js') }}"></script>
@endpush
