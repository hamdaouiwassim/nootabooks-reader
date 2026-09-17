@extends('layouts.auth')

@section('title', 'تفعيل الحساب - نوته بوك')

@section('content')
<div class="auth-wrapper">

  <!-- ===================== VISUAL PANEL ===================== -->
  <div class="auth-visual register-visual">
    <a href="{{ route('home') }}" class="logo">
      <img src="{{ asset('assets/images/logo.png') }}" alt="نوته بوك" class="logo-icon">
    </a>

    <h1 class="auth-visual-heading">تفعيل الحساب</h1>

    <div class="auth-illustration"></div>

    <blockquote class="auth-quote">"خطوة أخيرة قبل أن تبدأ رحلتك مع آلاف الكتب"</blockquote>
  </div>

  <!-- ===================== FORM PANEL ===================== -->
  <div class="auth-form-panel">
    <div class="auth-form-box">
      <h1 class="auth-form-heading">تفعيل الحساب</h1>
      <p class="auth-subtitle">أدخل الرمز المكوّن من 6 أرقام المرسل إلى {{ $maskedEmail }}</p>

      <form method="POST" action="{{ route('register.verify.submit') }}" novalidate>
        @csrf

        <div class="form-field">
          <label for="code">رمز التفعيل</label>
          <div class="input-with-icon">
            <i class="fa-solid fa-key"></i>
            <input type="text" id="code" name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" placeholder="000000" required autofocus style="letter-spacing:6px; text-align:center; font-weight:800; font-size:18px;">
          </div>
          <span class="field-error">{{ $errors->first('code') }}</span>
        </div>

        <button type="submit" class="btn btn-teal full"><i class="fa-solid fa-shield-halved"></i> تأكيد التفعيل</button>
      </form>

      <form method="POST" action="{{ route('register.verify.resend') }}" style="margin-top:14px;">
        @csrf
        <button type="submit" class="btn btn-outline full">إعادة إرسال الرمز</button>
      </form>

      <p class="auth-switch">
        <a href="{{ route('register') }}">العودة لإنشاء حساب</a>
      </p>
    </div>
  </div>

</div>
@endsection
