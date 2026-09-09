@extends('layouts.reader')

@section('title', 'قراءة: '.$currentBook->title.' - نوته بوك')

@section('content')
<div class="reader-page">

  <!-- ===================== READER TOPBAR ===================== -->
  <header class="reader-topbar">
    <a href="{{ route('book-details', $currentBook->slug) }}" class="reader-back">
      <i class="fa-solid fa-arrow-right"></i>
      <span>رجوع</span>
    </a>

    <div class="reader-book-info">
      @if ($currentBook->cover_image)
        <img src="{{ $currentBook->cover_image_url }}" alt="{{ $currentBook->title }}" class="reader-mini-cover">
      @endif
      <div class="reader-book-info-text">
        <strong>{{ $currentBook->title }}</strong>
        <span>{{ $currentBook->writer?->name ?? 'بدون مؤلف' }}</span>
      </div>
    </div>

    <div class="reader-actions">
      @if ($currentBook->file_path)
        <a href="{{ route('books.download', $currentBook) }}" class="reader-action-btn" title="تحميل الكتاب" aria-label="download">
          <i class="fa-solid fa-download"></i>
        </a>
      @endif
      <button class="reader-action-btn" id="readerFullscreenBtn" title="ملء الشاشة" aria-label="fullscreen">
        <i class="fa-solid fa-expand"></i>
      </button>
    </div>
  </header>

  <!-- ===================== PDF VIEWER ===================== -->
  <main class="reader-frame-wrap" id="readerFrameWrap">
    @if ($currentBook->file_path)
      <iframe src="{{ $currentBook->file_url }}" class="reader-frame" title="{{ $currentBook->title }}"></iframe>
    @else
      <div class="reader-empty">
        <i class="fa-solid fa-file-circle-exclamation"></i>
        <p>لا يتوفر ملف لهذا الكتاب حاليًا</p>
      </div>
    @endif
  </main>

</div>
@endsection
