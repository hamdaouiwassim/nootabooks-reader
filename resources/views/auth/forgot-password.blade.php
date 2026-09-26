@extends('layouts.auth')

@section('title', 'استعادة كلمة المرور - نوتابوكس')

@section('content')
<div class="auth-wrapper">

  <!-- ===================== VISUAL PANEL ===================== -->
  <div class="auth-visual login-visual">
    <a href="{{ route('home') }}" class="logo">
      <img src="{{ asset('assets/logos/dark-logo-nootabooks.webp') }}" alt="نوتابوكس" class="logo-icon">
    </a>

    <h1 class="auth-visual-heading">استعادة كلمة المرور</h1>

    <div class="auth-illustration"></div>

    <blockquote class="auth-quote">"لا تقلق، سنساعدك على العودة إلى قراءتك"</blockquote>
  </div>

  <!-- ===================== FORM PANEL ===================== -->
  <div class="auth-form-panel">
    <div class="auth-form-box">
      <h1 class="auth-form-heading">نسيت كلمة المرور؟</h1>
      <p class="auth-subtitle">أدخل بريدك الإلكتروني وسنرسل لك رمزًا لإعادة تعيين كلمة المرور</p>

      <form id="forgotPasswordForm" method="POST" action="{{ route('forgot-password.submit') }}" data-recaptcha-action="forgot-password" novalidate>
        @csrf
        <div class="form-field">
          <label for="forgotPasswordEmail">البريد الإلكتروني</label>
          <div class="input-with-icon">
            <i class="fa-regular fa-envelope"></i>
            <input type="email" id="forgotPasswordEmail" name="email" value="{{ old('email') }}" placeholder="ادخل بريدك الإلكتروني" required>
          </div>
          <span class="field-error" id="forgotPasswordEmailError">@error('email'){{ $message }}@enderror</span>
        </div>

        @include('partials.recaptcha')

        <button type="submit" class="btn btn-teal full">إرسال رمز إعادة التعيين</button>
      </form>

      <p class="auth-switch"><a href="{{ route('login') }}">العودة لتسجيل الدخول</a></p>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/forgot-password.js') }}" defer></script>
@endpush
