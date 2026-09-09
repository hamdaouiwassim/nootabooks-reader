@extends('layouts.app')

@section('title', 'المؤلفون - نوته بوك')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/writers.css') }}">
@endpush

@section('content')
<main>

@php
  $followedSlugs = auth()->user()?->followedWriters()->pluck('slug')->toArray() ?? [];
@endphp

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>المؤلفون</span>
  </nav>
</div>

<!-- ===================== PAGE HEADING ===================== -->
<section class="section writers-hero">
  <h1>المؤلفون</h1>
  <p>تعرف على أبرز الكتّاب والمؤلفين في مكتبتنا واكتشف أعمالهم</p>

  <div class="writers-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" id="writerSearch" placeholder="ابحث عن مؤلف بالاسم ...">
  </div>

  <div class="filter-tabs">
    <button class="filter-tab active" data-filter="all">الكل</button>
    <button class="filter-tab" data-filter="popular">الأكثر متابعة</button>
    <button class="filter-tab" data-filter="arabic">أدب عربي</button>
    <button class="filter-tab" data-filter="world">أدب عالمي</button>
    <button class="filter-tab" data-filter="classic">كلاسيكيات</button>
  </div>
</section>

@if ($featuredWriter)
<!-- ===================== FEATURED AUTHOR ===================== -->
<section class="section">
  <div class="featured-author">
    <img src="{{ $featuredWriter->photo_url ?? 'https://i.pravatar.cc/240?img=' . (($featuredWriter->id % 70) + 1) }}" alt="{{ $featuredWriter->name }}" class="featured-photo">
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
      <article class="writer-card" data-tags="{{ implode(' ', $writerTags) }}" data-href="{{ route('writer-details', $writer->slug) }}">
        <img src="{{ $writer->photo_url ?? 'https://i.pravatar.cc/140?img=' . (($writer->id % 70) + 1) }}" alt="{{ $writer->name }}">
        <h3>{{ $writer->name }}</h3>
        @if ($writer->genre_tag)
          <span class="writer-tag">{{ $writer->genre_tag }}</span>
        @endif
        @if ($writer->bio)
          <p>{{ $writer->bio }}</p>
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
<script src="{{ asset('assets/js/writers.js') }}"></script>
@endpush
