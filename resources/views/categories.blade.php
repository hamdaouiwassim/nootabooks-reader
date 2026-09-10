@extends('layouts.app')

@section('title', 'التصنيفات - نوته بوك')
@section('meta_description', 'تصفح تصنيفات الكتب والروايات العربية على نوته بوك، من الأدب والتاريخ إلى التنمية الذاتية والخيال العلمي.')

@push('styles')
<link rel="stylesheet" href="{{ asset_min('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/categories.css') }}">
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
    @foreach ($topCategories as $category)
      <a href="{{ route('category-details', $category->slug) }}" class="strip-chip">{{ $category->name }}</a>
    @endforeach
  </div>
</section>

<!-- ===================== CATEGORIES GRID ===================== -->
<section class="section">
  <div class="section-head">
    <h2 class="section-title">جميع التصنيفات</h2>
    <span class="results-count"><span id="resultsCount">{{ $categories->total() }}</span> تصنيف</span>
  </div>

  <div class="categories-full-grid" id="categoriesGrid">

    @foreach ($categories as $category)
      <a href="{{ route('category-details', $category->slug) }}" class="category-full-card" data-name="{{ $category->name }}" data-count="{{ $category->books_count }}">
        <span class="cat-icon-circle {{ $category->color ?? 'cat-navy' }}"><i class="fa-solid {{ $category->icon ?? 'fa-book' }}"></i></span>
        <h3>{{ $category->name }}</h3>
        <p>{{ number_format($category->books_count) }} كتاب</p>
      </a>
    @endforeach

  </div>

  <p class="no-results" id="noResults" hidden>لا يوجد تصنيفات مطابقة لبحثك.</p>

  {{ $categories->links() }}
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/categories.js') }}"></script>
@endpush
