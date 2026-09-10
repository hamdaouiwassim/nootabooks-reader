@extends('layouts.app')

@section('title', $currentWriter->name.' - نوته بوك')
@section('meta_description', $currentWriter->bio ? \Illuminate\Support\Str::limit($currentWriter->bio, 160) : 'تعرّف على '.$currentWriter->name.' وتصفح جميع أعماله على نوته بوك.')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/book-details.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/writer-details.css') }}">
@endpush

@push('schema')
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type' => 'Person',
    'name' => $currentWriter->name,
    'description' => $currentWriter->bio,
    'image' => $currentWriter->photo_url,
    'url' => route('writer-details', $currentWriter->slug),
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<main>

@php
  $isFollowingWriter = auth()->user()?->followedWriters()->where('slug', $currentWriter->slug)->exists() ?? false;
@endphp

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <a href="{{ route('writers') }}">المؤلفون</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>{{ $currentWriter->name }}</span>
  </nav>
</div>

<!-- ===================== AUTHOR HERO ===================== -->
<section class="section writer-hero">
  <img src="{{ $currentWriter->photo_url ?? 'https://i.pravatar.cc/240?img=' . (($currentWriter->id % 70) + 1) }}" alt="{{ $currentWriter->name }}" class="writer-hero-photo">

  <div class="writer-hero-info">
    @if ($currentWriter->genre_tag)
      <span class="genre-chip">{{ $currentWriter->genre_tag }}</span>
    @endif
    <h1 class="writer-hero-name">{{ $currentWriter->name }}</h1>
    @if ($currentWriter->bio)
      <p class="writer-hero-desc">{{ $currentWriter->bio }}</p>
    @endif

    <div class="writer-hero-meta">
      <div class="meta-item"><i class="fa-solid fa-book"></i><span>عدد الكتب</span><strong>{{ number_format($currentWriter->books_count) }}</strong></div>
      <div class="meta-item"><i class="fa-solid fa-users"></i><span>المتابعون</span><strong>{{ number_format($currentWriter->followers_count) }}</strong></div>
      <div class="meta-item"><i class="fa-solid fa-star"></i><span>متوسط التقييم</span><strong>{{ number_format($currentWriter->rating_average, 1) }} / 5</strong></div>
      <div class="meta-item"><i class="fa-solid fa-calendar-days"></i><span>انضم منذ</span><strong>{{ $currentWriter->joined_year ?? '—' }}</strong></div>
    </div>

    <div class="writer-hero-actions">
      @auth
        <form method="POST" action="{{ route('writers.follow', $currentWriter->slug) }}">
          @csrf
          <button type="submit" class="btn btn-teal follow-btn @if ($isFollowingWriter) following @endif">
            <i class="fa-solid {{ $isFollowingWriter ? 'fa-user-check' : 'fa-user-plus' }}"></i>
            {{ $isFollowingWriter ? 'تتم المتابعة' : 'متابعة' }}
          </button>
        </form>
      @else
        <a href="{{ route('login') }}" class="btn btn-teal follow-btn"><i class="fa-solid fa-user-plus"></i> متابعة</a>
      @endauth
      <button class="icon-btn-outline" aria-label="share"><i class="fa-solid fa-share-nodes"></i></button>
      <div class="writer-socials">
        <a href="#"><i class="fa-brands fa-twitter"></i></a>
        <a href="#"><i class="fa-brands fa-instagram"></i></a>
        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
      </div>
    </div>
  </div>
</section>

<!-- ===================== AUTHOR BOOKS ===================== -->
<section class="section">
  <div class="section-head">
    <div class="section-title-wrap">
      <h2 class="section-title">كتب {{ $currentWriter->name }}</h2>
      <p class="section-sub">جميع الأعمال المنشورة للمؤلف</p>
    </div>
    <span class="results-count"><span id="booksCount">{{ $writerBooks->total() }}</span> كتاب</span>
  </div>

  @if ($writerBooks->isNotEmpty())
    <div class="filter-tabs">
      <button class="filter-tab active" data-sort="all">الكل</button>
      <button class="filter-tab" data-sort="popular">الأكثر تحميلاً</button>
      <button class="filter-tab" data-sort="newest">الأحدث</button>
    </div>

    <div class="writer-books-grid" id="writerBooksGrid">
      @foreach ($writerBooks as $book)
        <article class="book-card" data-year="{{ $book->published_year }}" data-downloads="{{ $book->downloads_count }}">
          @if ($book->cover_image)
            <img class="book-cover cover-photo" src="{{ $book->cover_image_url }}" alt="{{ $book->title }}">
          @else
            <a href="{{ route('book-details', $book->slug) }}" class="book-cover cover-{{ ($book->id % 5) + 1 }}">
              <span class="cover-badge">B</span>
              <span class="cover-title">{{ $book->title }}</span>
            </a>
          @endif
          <h3><a href="{{ route('book-details', $book->slug) }}">{{ $book->title }}</a></h3>
          <p class="author">{{ $book->published_year }}</p>
          <p class="rating"><i class="fa-solid fa-star"></i> {{ number_format($book->rating_average, 1) }}</p>
          <a href="{{ route('book-details', $book->slug) }}" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
        </article>
      @endforeach
    </div>

    {{ $writerBooks->links() }}
  @else
    <p class="no-results">لا توجد كتب منشورة لهذا المؤلف بعد.</p>
  @endif
</section>

<!-- ===================== SIMILAR AUTHORS ===================== -->
<section class="section">
  <div class="section-head">
    <h2 class="section-title">مؤلفون مشابهون</h2>
    <a href="{{ route('writers') }}" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="authors-row wide">
    @foreach ($similarWriters as $similarWriter)
      <a href="{{ route('writer-details', $similarWriter->slug) }}" class="author-card">
        <img src="{{ $similarWriter->photo_url ?? 'https://i.pravatar.cc/120?img=' . (($similarWriter->id % 70) + 1) }}" alt="{{ $similarWriter->name }}">
        <p>{{ $similarWriter->name }}</p>
      </a>
    @endforeach
  </div>
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/writer-details.js') }}"></script>
@endpush
