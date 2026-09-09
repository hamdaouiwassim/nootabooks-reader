@extends('layouts.reader')

@section('title', 'قراءة: أرض زيكولا - نوته بوك')

@section('content')
<div class="reader-page">

  <!-- ===================== READER TOPBAR ===================== -->
  <header class="reader-topbar">
    <a href="{{ route('home') }}" class="reader-back">
      <i class="fa-solid fa-arrow-right"></i>
      <span>رجوع</span>
    </a>

    <div class="reader-book-info">
      <img src="{{ asset('assets/books/zikola-land.jpeg') }}" alt="أرض زيكولا" class="reader-mini-cover">
      <div class="reader-book-info-text">
        <strong>أرض زيكولا</strong>
        <span>عمرو عبد الحميد</span>
      </div>
    </div>

    <div class="reader-actions">
      <a href="{{ asset('assets/pdfs/ارض زيكولا.pdf') }}" download class="reader-action-btn" title="تحميل الكتاب" aria-label="download">
        <i class="fa-solid fa-download"></i>
      </a>
      <button class="reader-action-btn" id="readerFullscreenBtn" title="ملء الشاشة" aria-label="fullscreen">
        <i class="fa-solid fa-expand"></i>
      </button>
    </div>
  </header>

  <!-- ===================== PDF VIEWER ===================== -->
  <main class="reader-frame-wrap" id="readerFrameWrap">
    <iframe src="{{ asset('assets/pdfs/ارض زيكولا.pdf') }}" class="reader-frame" title="أرض زيكولا"></iframe>
  </main>

</div>
@endsection
