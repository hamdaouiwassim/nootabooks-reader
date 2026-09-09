@extends('layouts.app')

@section('title', 'التصنيفات - نوته بوك')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/categories.css') }}">
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>التصنيفات</span>
  </nav>
</div>

<!-- ===================== PAGE HEADING ===================== -->
<section class="section writers-hero">
  <h1>تصفح حسب التصنيف</h1>
  <p>اكتشف آلاف الكتب مرتبة حسب اهتماماتك المفضلة</p>

  <div class="writers-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" id="categorySearch" placeholder="ابحث عن تصنيف ...">
  </div>

  <div class="filter-tabs">
    <button class="filter-tab active" data-sort="all">الكل</button>
    <button class="filter-tab" data-sort="popular">الأكثر كتبًا</button>
    <button class="filter-tab" data-sort="az">أبجديًا</button>
  </div>
</section>

<!-- ===================== TOP CATEGORIES STRIP ===================== -->
<section class="section">
  <div class="top-categories-strip">
    <span class="strip-label"><i class="fa-solid fa-fire"></i> الأكثر رواجًا</span>
    <a href="#" class="strip-chip">روايات</a>
    <a href="#" class="strip-chip">أدب عربي</a>
    <a href="#" class="strip-chip">تنمية ذاتية</a>
    <a href="#" class="strip-chip">أدب عالمي</a>
    <a href="#" class="strip-chip">تاريخ</a>
  </div>
</section>

<!-- ===================== CATEGORIES GRID ===================== -->
<section class="section">
  <div class="section-head">
    <h2 class="section-title">جميع التصنيفات</h2>
    <span class="results-count"><span id="resultsCount">16</span> تصنيف</span>
  </div>

  <div class="categories-full-grid" id="categoriesGrid">

    @php
      $categories = [
        ['name' => 'روايات', 'count' => 1560, 'color' => 'cat-purple', 'icon' => 'fa-bookmark'],
        ['name' => 'أدب عربي', 'count' => 1240, 'color' => 'cat-navy', 'icon' => 'fa-book'],
        ['name' => 'أدب عالمي', 'count' => 980, 'color' => 'cat-teal', 'icon' => 'fa-earth-africa'],
        ['name' => 'تنمية ذاتية', 'count' => 720, 'color' => 'cat-brown', 'icon' => 'fa-bell'],
        ['name' => 'تاريخ', 'count' => 610, 'color' => 'cat-navy', 'icon' => 'fa-landmark'],
        ['name' => 'علوم', 'count' => 540, 'color' => 'cat-green', 'icon' => 'fa-atom'],
        ['name' => 'أطفال', 'count' => 450, 'color' => 'cat-rose', 'icon' => 'fa-child'],
        ['name' => 'دين وروحانيات', 'count' => 480, 'color' => 'cat-gold', 'icon' => 'fa-mosque'],
        ['name' => 'شعر', 'count' => 390, 'color' => 'cat-teal', 'icon' => 'fa-feather'],
        ['name' => 'تكنولوجيا', 'count' => 340, 'color' => 'cat-teal', 'icon' => 'fa-microchip'],
        ['name' => 'فلسفة', 'count' => 320, 'color' => 'cat-green', 'icon' => 'fa-leaf'],
        ['name' => 'تنمية ذهنية', 'count' => 310, 'color' => 'cat-green', 'icon' => 'fa-brain'],
        ['name' => 'قصص قصيرة', 'count' => 275, 'color' => 'cat-navy', 'icon' => 'fa-file-lines'],
        ['name' => 'اقتصاد وأعمال', 'count' => 260, 'color' => 'cat-navy', 'icon' => 'fa-chart-line'],
        ['name' => 'سياسة', 'count' => 210, 'color' => 'cat-navy', 'icon' => 'fa-landmark-dome'],
        ['name' => 'فنون وتصميم', 'count' => 190, 'color' => 'cat-rose', 'icon' => 'fa-palette'],
      ];
    @endphp

    @foreach ($categories as $category)
      <a href="#" class="category-full-card" data-name="{{ $category['name'] }}" data-count="{{ $category['count'] }}">
        <span class="cat-icon-circle {{ $category['color'] }}"><i class="fa-solid {{ $category['icon'] }}"></i></span>
        <h3>{{ $category['name'] }}</h3>
        <p>{{ number_format($category['count']) }} كتاب</p>
      </a>
    @endforeach

  </div>

  <p class="no-results" id="noResults" hidden>لا يوجد تصنيفات مطابقة لبحثك.</p>
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/categories.js') }}"></script>
@endpush
