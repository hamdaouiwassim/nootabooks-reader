@extends('layouts.app')

@section('title', 'تواصل معنا - نوته بوك')
@section('meta_description', 'تواصل مع فريق نوته بوك لأي استفسار أو اقتراح أو مشكلة تقنية.')

@push('styles')
<link rel="stylesheet" href="{{ asset_min('assets/css/auth.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/contact.css') }}">
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>تواصل معنا</span>
  </nav>
</div>

<!-- ===================== CONTACT PAGE ===================== -->
<section class="section contact-section">

  <div class="contact-head">
    <h1>تواصل معنا</h1>
    <p>لديك سؤال أو اقتراح أو ملاحظة؟ يسعدنا سماعك، فريقنا يرد خلال 24 ساعة عمل</p>
  </div>

  <div class="contact-layout">

    <form class="contact-form" id="contactForm" method="POST" action="{{ route('contact.submit') }}" novalidate>
      @csrf
      <div class="form-row">
        <div class="form-field">
          <label for="contactName">الاسم الكامل</label>
          <div class="input-with-icon">
            <i class="fa-regular fa-user"></i>
            <input type="text" id="contactName" name="name" value="{{ old('name') }}" placeholder="اسمك الكامل" required>
          </div>
          <span class="field-error" id="contactNameError">@error('name'){{ $message }}@enderror</span>
        </div>
        <div class="form-field">
          <label for="contactEmail">البريد الإلكتروني</label>
          <div class="input-with-icon">
            <i class="fa-regular fa-envelope"></i>
            <input type="email" id="contactEmail" name="email" value="{{ old('email') }}" placeholder="بريدك الإلكتروني" required>
          </div>
          <span class="field-error" id="contactEmailError">@error('email'){{ $message }}@enderror</span>
        </div>
      </div>

      <div class="form-field">
        <label for="contactSubject">الموضوع</label>
        <div class="input-with-icon">
          <i class="fa-regular fa-message"></i>
          <select id="contactSubject" name="subject" required>
            <option value="" disabled {{ old('subject') ? '' : 'selected' }}>اختر موضوع الرسالة</option>
            <option value="support" @selected(old('subject') === 'support')>مساعدة تقنية</option>
            <option value="account" @selected(old('subject') === 'account')>استفسار عن الحساب</option>
            <option value="content" @selected(old('subject') === 'content')>اقتراح كتاب أو محتوى</option>
            <option value="partnership" @selected(old('subject') === 'partnership')>شراكة أو تعاون</option>
            <option value="other" @selected(old('subject') === 'other')>أخرى</option>
          </select>
        </div>
        <span class="field-error" id="contactSubjectError">@error('subject'){{ $message }}@enderror</span>
      </div>

      <div class="form-field">
        <label for="contactMessage">الرسالة</label>
        <textarea id="contactMessage" name="message" rows="6" placeholder="اكتب رسالتك هنا ..." required>{{ old('message') }}</textarea>
        <span class="field-error" id="contactMessageError">@error('message'){{ $message }}@enderror</span>
      </div>

      <button type="submit" class="btn btn-teal full"><i class="fa-solid fa-paper-plane"></i> إرسال الرسالة</button>
      @if (session('contactSuccess'))
        <p class="form-success" id="contactSuccess"><i class="fa-solid fa-circle-check"></i> {{ session('contactSuccess') }}</p>
      @else
        <p class="form-success" id="contactSuccess" hidden><i class="fa-solid fa-circle-check"></i> تم إرسال رسالتك بنجاح، سنتواصل معك قريبًا</p>
      @endif
    </form>

    <aside class="contact-info">
      <div class="contact-info-card">
        <span class="contact-info-icon"><i class="fa-regular fa-envelope"></i></span>
        <div>
          <strong>البريد الإلكتروني</strong>
          <p>support@maktabati.com</p>
        </div>
      </div>
      <div class="contact-info-card">
        <span class="contact-info-icon"><i class="fa-solid fa-phone"></i></span>
        <div>
          <strong>الهاتف</strong>
          <p dir="ltr">+966 11 234 5678</p>
        </div>
      </div>
      <div class="contact-info-card">
        <span class="contact-info-icon"><i class="fa-solid fa-location-dot"></i></span>
        <div>
          <strong>العنوان</strong>
          <p>الرياض، المملكة العربية السعودية</p>
        </div>
      </div>
      <div class="contact-info-card">
        <span class="contact-info-icon"><i class="fa-regular fa-clock"></i></span>
        <div>
          <strong>أوقات العمل</strong>
          <p>الأحد - الخميس، 9ص - 5م</p>
        </div>
      </div>

      <div class="contact-social">
        <span>تابعنا</span>
        <div class="social-icons">
          <a href="#"><i class="fa-brands fa-youtube"></i></a>
          <a href="#"><i class="fa-brands fa-instagram"></i></a>
          <a href="#"><i class="fa-brands fa-twitter"></i></a>
          <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
        </div>
      </div>
    </aside>

  </div>

</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/contact.js') }}"></script>
@endpush
