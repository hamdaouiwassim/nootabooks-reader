@extends('layouts.app')

@section('title', 'الإبلاغ عن حقوق النشر - '.$currentBook->title.' - نوتابوكس')
@section('meta_description', 'الإبلاغ عن انتهاك حقوق النشر لكتاب '.$currentBook->title.' على نوتابوكس.')
@section('robots', 'noindex, follow')

@push('styles')
<link rel="preload" href="{{ asset_min('assets/css/auth.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/auth.css') }}"></noscript>
<link rel="preload" href="{{ asset_min('assets/css/contact.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/contact.css') }}"></noscript>
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <a href="{{ route('book-details', $currentBook->slug) }}">{{ $currentBook->title }}</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>الإبلاغ عن حقوق النشر</span>
  </nav>
</div>

<!-- ===================== REPORT PAGE ===================== -->
<section class="section contact-section">

  <div class="contact-head">
    <h1>الإبلاغ عن حقوق النشر</h1>
    <p>
      أنت على وشك الإبلاغ عن كتاب "<strong>{{ $currentBook->title }}</strong>" بسبب انتهاك محتمل لحقوق النشر.
      يراجع فريقنا كل بلاغ ويتخذ الإجراء المناسب فور التأكد من صحته — اطّلع على
      <a href="{{ route('copyright') }}">سياسة حقوق النشر</a> لمزيد من التفاصيل.
    </p>
  </div>

  <div class="contact-layout contact-layout-solo">

    <form class="contact-form" id="bookReportForm" method="POST" action="{{ route('books.report.store', $currentBook->slug) }}" novalidate>
      @csrf
      <div class="form-row">
        <div class="form-field">
          <label for="reportName">الاسم الكامل</label>
          <div class="input-with-icon">
            <i class="fa-regular fa-user"></i>
            <input type="text" id="reportName" name="reporter_name" value="{{ old('reporter_name', auth()->user()?->name) }}" placeholder="اسمك الكامل" required>
          </div>
          <span class="field-error">@error('reporter_name'){{ $message }}@enderror</span>
        </div>
        <div class="form-field">
          <label for="reportEmail">البريد الإلكتروني</label>
          <div class="input-with-icon">
            <i class="fa-regular fa-envelope"></i>
            <input type="email" id="reportEmail" name="reporter_email" value="{{ old('reporter_email', auth()->user()?->email) }}" placeholder="بريدك الإلكتروني" required>
          </div>
          <span class="field-error">@error('reporter_email'){{ $message }}@enderror</span>
        </div>
      </div>

      <div class="form-field">
        <label for="reportMessage">تفاصيل البلاغ</label>
        <textarea id="reportMessage" name="message" rows="6" placeholder="اشرح طبيعة الانتهاك، وإثبات ملكيتك لحقوق النشر إن أمكن (رابط، مستند، إلخ) ..." required>{{ old('message') }}</textarea>
        <span class="field-error">@error('message'){{ $message }}@enderror</span>
      </div>

      <button type="submit" class="btn btn-teal full"><i class="fa-solid fa-flag"></i> إرسال البلاغ</button>
    </form>

  </div>

</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/book-report.js') }}" defer></script>
@endpush
