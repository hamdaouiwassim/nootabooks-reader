@extends('layouts.auth')

@section('title', 'إعادة تعيين كلمة المرور - نوتابوكس')

@section('content')
<div class="auth-wrapper">

  <!-- ===================== VISUAL PANEL ===================== -->
  <div class="auth-visual login-visual">
    <a href="{{ route('home') }}" class="logo">
      <img src="{{ asset('assets/logos/dark-logo-nootabooks.webp') }}" alt="نوتابوكس" class="logo-icon">
    </a>

    <h1 class="auth-visual-heading">إعادة تعيين كلمة المرور</h1>

    <div class="auth-illustration"></div>

    <blockquote class="auth-quote">"خطوة أخيرة قبل أن تعود إلى قراءتك"</blockquote>
  </div>

  <!-- ===================== FORM PANEL ===================== -->
  <div class="auth-form-panel">
    <div class="auth-form-box">
      <h1 class="auth-form-heading">إعادة تعيين كلمة المرور</h1>
      <p class="auth-subtitle">أدخل الرمز المكوّن من 6 أرقام المرسل إلى {{ $maskedEmail }} مع كلمة المرور الجديدة</p>

      <form id="resetPasswordForm" method="POST" action="{{ route('reset-password.submit') }}" novalidate>
        @csrf

        <div class="form-field">
          <label for="code">رمز التحقق</label>
          <div class="input-with-icon">
            <i class="fa-solid fa-key"></i>
            <input type="text" id="code" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" placeholder="000000" required autofocus style="letter-spacing:6px; text-align:center; font-weight:800; font-size:18px;">
          </div>
          <span class="field-error" id="resetCodeError">{{ $errors->first('code') }}</span>
        </div>

        <div class="form-field">
          <label for="resetPassword">كلمة المرور الجديدة</label>
          <div class="input-with-icon">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="resetPassword" name="password" placeholder="8 أحرف على الأقل" required minlength="8">
            <button type="button" class="toggle-password" aria-label="show password"><i class="fa-regular fa-eye"></i></button>
          </div>
          <div class="password-strength" id="passwordStrength">
            <div class="strength-bar"><div class="strength-fill"></div></div>
            <span class="strength-label"></span>
          </div>
          <span class="field-error" id="resetPasswordError">@error('password'){{ $message }}@enderror</span>
        </div>

        <div class="form-field">
          <label for="resetPasswordConfirm">تأكيد كلمة المرور</label>
          <div class="input-with-icon">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="resetPasswordConfirm" name="password_confirmation" placeholder="أعد كتابة كلمة المرور" required>
            <button type="button" class="toggle-password" aria-label="show password"><i class="fa-regular fa-eye"></i></button>
          </div>
          <span class="field-error" id="resetPasswordConfirmError"></span>
        </div>

        <button type="submit" class="btn btn-teal full"><i class="fa-solid fa-shield-halved"></i> تحديث كلمة المرور</button>
      </form>

      <form method="POST" action="{{ route('reset-password.resend') }}" style="margin-top:14px;">
        @csrf
        <button type="submit" class="btn btn-outline full">إعادة إرسال الرمز</button>
      </form>

      <p class="auth-switch">
        <a href="{{ route('forgot-password') }}">العودة</a>
      </p>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/reset-password.js') }}" defer></script>
@endpush
