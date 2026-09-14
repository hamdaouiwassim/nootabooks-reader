@extends('layouts.app')

@section('title', 'كتب ومؤلفات '.$currentWriter->name.' | نوته بوك')
@section('meta_description', $currentWriter->bio
    ? 'اكتشف كتب ومؤلفات '.$currentWriter->name.' على نوته بوك. '.\Illuminate\Support\Str::limit($currentWriter->bio, 100)
    : 'اكتشف كتب ومؤلفات '.$currentWriter->name.' على نوته بوك، وتعرف على أعماله وتصفح كتبه المتاحة للقراءة أونلاين.')
@section('robots', $isIndexable ? 'index, follow' : 'noindex, follow')

@push('styles')
<link rel="preload" href="{{ asset_min('assets/css/book-details.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/book-details.css') }}"></noscript>
<link rel="preload" href="{{ asset_min('assets/css/writers.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/writers.css') }}"></noscript>
<link rel="preload" href="{{ asset_min('assets/css/writer-details.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/writer-details.css') }}"></noscript>
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
    'sameAs' => array_values(array_filter([$currentWriter->facebook_url, $currentWriter->twitter_url, $currentWriter->instagram_url])),
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'الرئيسية', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'المؤلفون', 'item' => route('writers')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $currentWriter->name, 'item' => route('writer-details', $currentWriter->slug)],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<main>

@php
  $isFollowingWriter = auth()->user()?->followedWriters()->where('slug', $currentWriter->slug)->exists() ?? false;
@endphp

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb" aria-label="مسار التنقل">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <a href="{{ route('writers') }}">المؤلفون</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span aria-current="page">{{ $currentWriter->name }}</span>
  </nav>
</div>

<!-- ===================== AUTHOR HERO ===================== -->
<section class="section writer-hero">
  @if ($currentWriter->photo)
    <img src="{{ $currentWriter->photo_sm_url }}" width="300" height="300" fetchpriority="high" decoding="async" alt="صورة المؤلف {{ $currentWriter->name }}" class="writer-hero-photo">
  @else
    <span class="writer-hero-photo avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
  @endif

  <div class="writer-hero-info">
    @if ($currentWriter->genre_tag)
      <span class="genre-chip">{{ $currentWriter->genre_tag }}</span>
    @endif
    <h1 class="writer-hero-name">{{ $currentWriter->name }}</h1>
    @if ($currentWriter->bio)
      <section aria-labelledby="author-bio-heading">
        <h2 id="author-bio-heading" class="sr-only">نبذة عن المؤلف</h2>
        <p class="writer-hero-desc">{{ $currentWriter->bio }}</p>
      </section>
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
      <button class="icon-btn-outline share-btn" aria-label="مشاركة"><i class="fa-solid fa-share-nodes"></i> مشاركة</button>
      @if ($currentWriter->twitter_url || $currentWriter->instagram_url || $currentWriter->facebook_url)
        <div class="writer-socials">
          @if ($currentWriter->twitter_url)
            <a href="{{ $currentWriter->twitter_url }}" target="_blank" rel="noopener noreferrer" aria-label="تويتر"><i class="fa-brands fa-twitter"></i></a>
          @endif
          @if ($currentWriter->instagram_url)
            <a href="{{ $currentWriter->instagram_url }}" target="_blank" rel="noopener noreferrer" aria-label="انستغرام"><i class="fa-brands fa-instagram"></i></a>
          @endif
          @if ($currentWriter->facebook_url)
            <a href="{{ $currentWriter->facebook_url }}" target="_blank" rel="noopener noreferrer" aria-label="فيسبوك"><i class="fa-brands fa-facebook-f"></i></a>
          @endif
        </div>
      @endif
    </div>
  </div>
</section>

<!-- ===================== AUTHOR BOOKS ===================== -->
<section class="section">
  <div class="section-head">
    <div class="section-title-wrap">
      <h2 class="section-title">كتب ومؤلفات {{ $currentWriter->name }}</h2>
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
        <a href="{{ route('book-details', $book->slug) }}" class="book-card" data-year="{{ $book->published_year }}" data-downloads="{{ $book->downloads_count }}">
          @if ($book->cover_image)
            <div class="cover-wrap">
              <img class="book-cover cover-photo" src="{{ $book->cover_image_sm_url }}"
                srcset="{{ $book->cover_image_sm_url }} 300w, {{ $book->cover_image_md_url }} 600w"
                sizes="(max-width: 640px) 45vw, 200px" width="300" height="450"
                loading="lazy" decoding="async" alt="غلاف {{ $book->title }}">
              <span class="brand-ribbon">nootabooks.com</span>
              @if ($book->is_coming_soon)
                <span class="coming-soon-badge">قريبًا</span>
              @endif
            </div>
          @else
            <div class="book-cover cover-{{ ($book->id % 5) + 1 }}">
              <span class="cover-badge">B</span>
              <span class="cover-title">{{ $book->title }}</span>
              <span class="brand-ribbon">nootabooks.com</span>
              @if ($book->is_coming_soon)
                <span class="coming-soon-badge">قريبًا</span>
              @endif
            </div>
          @endif
          <h3>{{ $book->title }}</h3>
          <p class="author">{{ $book->published_year }}</p>
          @if ($book->rating_count > 0)
            <p class="rating"><i class="fa-solid fa-star"></i> {{ number_format($book->rating_average, 1) }}</p>
          @else
            <p class="rating no-rating">لا توجد تقييمات بعد</p>
          @endif
        </a>
      @endforeach
    </div>

    {{ $writerBooks->links() }}
  @else
    <p class="no-results">لا توجد كتب منشورة لهذا المؤلف بعد.</p>
  @endif
</section>

@if ($writerCategories->isNotEmpty())
<!-- ===================== AUTHOR CATEGORIES ===================== -->
<section class="section">
  <div class="section-head">
    <h2 class="section-title">تصنيفات الكتب</h2>
  </div>
  <div class="category-links-row">
    @foreach ($writerCategories as $writerCategory)
      <a href="{{ route('category-details', $writerCategory->slug) }}">{{ $writerCategory->name }}</a>
    @endforeach
  </div>
</section>
@endif

<!-- ===================== SIMILAR AUTHORS ===================== -->
<section class="section">
  <div class="section-head">
    <h2 class="section-title">مؤلفون مشابهون</h2>
    <a href="{{ route('writers') }}" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="authors-row wide">
    @foreach ($similarWriters as $similarWriter)
      <a href="{{ route('writer-details', $similarWriter->slug) }}" class="author-card">
        @if ($similarWriter->photo)
          <img src="{{ $similarWriter->photo_xs_url }}" width="200" height="200" loading="lazy" decoding="async" alt="صورة المؤلف {{ $similarWriter->name }}">
        @else
          <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
        @endif
        <p>{{ $similarWriter->name }}</p>
      </a>
    @endforeach
  </div>
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/writer-details.js') }}" defer></script>
@endpush
