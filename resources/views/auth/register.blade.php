@extends('layouts.auth')

@section('title', 'إنشاء حساب - نوته بوك')

@section('content')
<div class="auth-wrapper">

  <!-- ===================== VISUAL PANEL ===================== -->
  <div class="auth-visual register-visual">
    <a href="{{ route('home') }}" class="logo">
      <i class="fa-solid fa-book-bookmark logo-icon"></i>
      <div class="logo-text">
        <span class="logo-title">نوته بوك</span>
        <span class="logo-tagline">عالم من الكتب بين يديك</span>
      </div>
    </a>

    <h1 class="auth-visual-heading">إنشاء حساب جديد</h1>

    <div class="auth-illustration"></div>

    <blockquote class="auth-quote">"إقرأ. اكتشف. حمّل. عالم من الكتب بين يديك"</blockquote>
  </div>

  <!-- ===================== FORM PANEL ===================== -->
  <div class="auth-form-panel">
    <div class="auth-form-box">
      <h1 class="auth-form-heading">إنشاء حساب جديد</h1>
      <p class="auth-subtitle">انضم إلى مجتمع القراء وابدأ رحلتك مع آلاف الكتب</p>

      <div class="social-auth-buttons">
        <button type="button" class="btn btn-outline social-btn"><i class="fa-brands fa-google"></i> التسجيل عبر جوجل</button>
        <button type="button" class="btn btn-outline social-btn"><i class="fa-brands fa-facebook-f"></i> التسجيل عبر فيسبوك</button>
      </div>

      <div class="auth-divider"><span>أو عبر البريد الإلكتروني</span></div>

      <form id="registerForm" method="POST" action="{{ route('register.submit') }}" novalidate>
        @csrf
        <div class="form-field">
          <label for="registerName">الاسم الكامل</label>
          <div class="input-with-icon">
            <i class="fa-regular fa-user"></i>
            <input type="text" id="registerName" name="name" value="{{ old('name') }}" placeholder="ادخل اسمك الكامل" required>
          </div>
          <span class="field-error" id="registerNameError">@error('name'){{ $message }}@enderror</span>
        </div>

        <div class="form-field">
          <label for="registerEmail">البريد الإلكتروني</label>
          <div class="input-with-icon">
            <i class="fa-regular fa-envelope"></i>
            <input type="email" id="registerEmail" name="email" value="{{ old('email') }}" placeholder="ادخل بريدك الإلكتروني" required>
          </div>
          <span class="field-error" id="registerEmailError">@error('email'){{ $message }}@enderror</span>
        </div>

        <div class="form-field">
          <label for="registerPassword">كلمة المرور</label>
          <div class="input-with-icon">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="registerPassword" name="password" placeholder="8 أحرف على الأقل" required minlength="8">
            <button type="button" class="toggle-password" aria-label="show password"><i class="fa-regular fa-eye"></i></button>
          </div>
          <div class="password-strength" id="passwordStrength">
            <div class="strength-bar"><div class="strength-fill"></div></div>
            <span class="strength-label"></span>
          </div>
          <span class="field-error" id="registerPasswordError">@error('password'){{ $message }}@enderror</span>
        </div>

        <div class="form-field">
          <label for="registerConfirm">تأكيد كلمة المرور</label>
          <div class="input-with-icon">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="registerConfirm" name="password_confirmation" placeholder="أعد كتابة كلمة المرور" required>
            <button type="button" class="toggle-password" aria-label="show password"><i class="fa-regular fa-eye"></i></button>
          </div>
          <span class="field-error" id="registerConfirmError"></span>
        </div>

        <label class="checkbox-row terms-row">
          <input type="checkbox" id="registerTerms" name="terms" required>
          <span>أوافق على <a href="{{ route('terms') }}">الشروط والأحكام</a> و<a href="{{ route('privacy') }}">سياسة الخصوصية</a></span>
        </label>
        <span class="field-error" id="registerTermsError">@error('terms'){{ $message }}@enderror</span>

        <button type="submit" class="btn btn-teal full">إنشاء حساب</button>
      </form>
      <p class="form-success" id="registerSuccess" hidden><i class="fa-solid fa-circle-check"></i> تم إنشاء حسابك بنجاح، جارِ التحويل ...</p>

      <p class="auth-switch">لديك حساب بالفعل؟ <a href="{{ route('login') }}">تسجيل الدخول</a></p>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/register.js') }}"></script>
@endpush
