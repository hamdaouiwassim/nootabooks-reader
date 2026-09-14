@extends('layouts.app')

@section('title', 'كتّاب ومؤلفو الكتب والروايات العربية والمترجمة | نوته بوك')
@section('meta_description', 'اكتشف كتّاب ومؤلفي الكتب والروايات العربية والمترجمة على نوته بوك، وتصفح أعمالهم وكتبهم حسب المجال والتصنيف.')

@push('styles')
<link rel="stylesheet" href="{{ asset_min('assets/css/writers.css') }}">
@endpush

@php
  $indexableWriters = $writers->getCollection()->filter(fn ($writer) => $writer->books_count > 0 || filled($writer->bio))->values();
@endphp

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'الرئيسية', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'المؤلفون', 'item' => route('writers')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@if ($indexableWriters->isNotEmpty())
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => 'كتّاب ومؤلفو الكتب والروايات',
    'itemListElement' => $indexableWriters->map(fn ($writer, $index) => [
        '@type' => 'ListItem',
        'position' => (($writers->currentPage() - 1) * $writers->perPage()) + $index + 1,
        'name' => $writer->name,
        'url' => route('writer-details', $writer->slug),
    ])->values()->all(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endif
@endpush

@section('content')
<main>

@php
  $followedSlugs = auth()->user()?->followedWriters()->pluck('slug')->toArray() ?? [];
@endphp

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb" aria-label="مسار التنقل">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span aria-current="page">المؤلفون</span>
  </nav>
</div>

<!-- ===================== PAGE HEADING ===================== -->
<section class="section writers-hero">
  <h1>كتّاب ومؤلفو الكتب والروايات</h1>
  <p>اكتشف كتّاب ومؤلفي الكتب والروايات العربية والمترجمة، وتصفح أعمالهم وكتبهم على نوته بوك.</p>

  <div class="writers-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <label for="writerSearch" class="sr-only">ابحث عن مؤلف بالاسم</label>
    <input type="text" id="writerSearch" placeholder="ابحث عن مؤلف بالاسم ...">
    <button type="button" id="writerSearchBtn" class="btn btn-gold small">بحث</button>
  </div>

  <div class="filter-tabs" role="group" aria-label="فرز المؤلفين">
    <button class="filter-tab active" data-filter="all" aria-pressed="true">الكل</button>
    <button class="filter-tab" data-filter="popular" aria-pressed="false">الأكثر متابعة</button>
    <button class="filter-tab" data-filter="arabic" aria-pressed="false">أدب عربي</button>
    <button class="filter-tab" data-filter="world" aria-pressed="false">أدب عالمي</button>
    <button class="filter-tab" data-filter="classic" aria-pressed="false">كلاسيكيات</button>
  </div>
</section>

@if ($featuredWriter)
<!-- ===================== FEATURED AUTHOR ===================== -->
<section class="section">
  <div class="featured-author">
    @if ($featuredWriter->photo)
      <img src="{{ $featuredWriter->photo_sm_url }}" width="300" height="300" fetchpriority="high" decoding="async" alt="صورة المؤلف {{ $featuredWriter->name }}" class="featured-photo">
    @else
      <span class="featured-photo avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
    @endif
    <div class="featured-info">
      <span class="featured-chip"><i class="fa-solid fa-star"></i> مؤلف الأسبوع</span>
      <h2>{{ $featuredWriter->name }}</h2>
      <p>{{ $featuredWriter->bio }}</p>
      <div class="featured-stats">
        <span><i class="fa-solid fa-book"></i> {{ number_format($featuredWriter->books_count) }} كتاب</span>
        <span><i class="fa-solid fa-users"></i> {{ number_format($featuredWriter->followers_count) }} متابع</span>
        <span><i class="fa-solid fa-star"></i> {{ number_format($featuredWriter->rating_average, 1) }} تقييم</span>
      </div>
      <div class="featured-actions">
        <a href="{{ route('writer-details', $featuredWriter->slug) }}" class="btn btn-gold">عرض الأعمال</a>
        @auth
          <form method="POST" action="{{ route('writers.follow', $featuredWriter->slug) }}">
            @csrf
            <button type="submit" class="btn btn-gold follow-btn @if (in_array($featuredWriter->slug, $followedSlugs)) following @endif">
              {{ in_array($featuredWriter->slug, $followedSlugs) ? 'تتم المتابعة' : 'متابعة' }}
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn btn-gold follow-btn">متابعة</a>
        @endauth
      </div>
    </div>
  </div>
</section>
@endif

<!-- ===================== WRITERS GRID ===================== -->
<section class="section">
  <div class="section-head">
    <h2 class="section-title">جميع المؤلفين</h2>
    <span class="results-count"><span id="resultsCount">{{ $writers->total() }}</span> مؤلف</span>
  </div>

  <div class="writers-grid" id="writersGrid">

    @foreach ($writers as $writer)
      @php
        $writerTags = [str_contains($writer->genre_tag ?? '', 'عالمي') ? 'world' : 'arabic'];
        if ($writer->joined_year && $writer->joined_year < 1970) $writerTags[] = 'classic';
        if ($writer->followers_count >= 150000) $writerTags[] = 'popular';
      @endphp
      <article class="writer-card" data-tags="{{ implode(' ', $writerTags) }}">
        <a href="{{ route('writer-details', $writer->slug) }}" class="writer-card-link">
          @if ($writer->photo)
            <img src="{{ $writer->photo_sm_url }}" width="300" height="300" loading="lazy" decoding="async" alt="صورة المؤلف {{ $writer->name }}">
          @else
            <span class="avatar-placeholder" aria-hidden="true"><i class="fa-solid fa-feather"></i></span>
          @endif
          <h3>{{ $writer->name }}</h3>
        </a>
        @if ($writer->genre_tag)
          <span class="writer-tag">{{ $writer->genre_tag }}</span>
        @endif
        @if ($writer->bio)
          <p>{{ \Illuminate\Support\Str::limit($writer->bio, 100) }}</p>
        @endif
        <div class="writer-stats">
          <span><i class="fa-solid fa-book"></i> {{ number_format($writer->books_count) }} كتاب</span>
          <span><i class="fa-solid fa-users"></i> {{ number_format($writer->followers_count) }} متابع</span>
        </div>
        @auth
          <form method="POST" action="{{ route('writers.follow', $writer->slug) }}">
            @csrf
            <button type="submit" class="btn btn-outline follow-btn @if (in_array($writer->slug, $followedSlugs)) following @endif">
              {{ in_array($writer->slug, $followedSlugs) ? 'تتم المتابعة' : 'متابعة' }}
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn btn-outline follow-btn">متابعة</a>
        @endauth
      </article>
    @endforeach

  </div>

  <p class="no-results" id="noResults" hidden>لا يوجد مؤلفون مطابقون لبحثك.</p>

  {{ $writers->links() }}
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/writers.js') }}" defer></script>
@endpush
