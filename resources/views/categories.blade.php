@extends('layouts.app')

@section('title', 'تصنيفات الكتب والروايات العربية والمترجمة | نوته بوك')
@section('meta_description', 'تصفح تصنيفات الكتب والروايات العربية والمترجمة على نوته بوك، من الروايات والأدب إلى التاريخ والتكنولوجيا والتنمية الذاتية وغيرها من المجالات.')

@push('styles')
<link rel="stylesheet" href="{{ asset_min('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/categories.css') }}">
@endpush

@php
  $indexableCategories = $categories->filter(fn ($category) => $category->books_count > 0)->values();
@endphp

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'الرئيسية', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'التصنيفات', 'item' => route('categories')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@if ($indexableCategories->isNotEmpty())
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => 'تصنيفات الكتب',
    'numberOfItems' => $indexableCategories->count(),
    'itemListElement' => $indexableCategories->map(fn ($category, $index) => [
        '@type' => 'ListItem',
        'position' => $index + 1,
        'name' => $category->name,
        'url' => route('category-details', $category->slug),
    ])->values()->all(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endif
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
  <h1>تصنيفات الكتب والروايات</h1>
  <p>اكتشف الكتب والروايات حسب المجال والتصنيف، واختر ما يناسب اهتماماتك.</p>

  <div class="writers-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <label for="categorySearch" class="sr-only">ابحث عن تصنيف</label>
    <input type="text" id="categorySearch" placeholder="ابحث عن تصنيف ...">
  </div>

  <div class="filter-tabs" role="group" aria-label="ترتيب التصنيفات">
    <button class="filter-tab active" data-sort="all" aria-pressed="true">الكل</button>
    <button class="filter-tab" data-sort="popular" aria-pressed="false">الأكثر كتبًا</button>
    <button class="filter-tab" data-sort="az" aria-pressed="false">أبجديًا</button>
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
<section class="section" aria-labelledby="categories-heading">
  <div class="section-head">
    <h2 class="section-title" id="categories-heading">جميع التصنيفات</h2>
    <span class="results-count"><span id="resultsCount">{{ $categories->count() }}</span> تصنيف</span>
  </div>

  <div class="categories-full-grid" id="categoriesGrid">

    @foreach ($categories as $category)
      <a href="{{ route('category-details', $category->slug) }}" class="category-full-card @if ($category->books_count === 0) is-empty @endif" data-name="{{ $category->name }}" data-count="{{ $category->books_count }}">
        <span class="cat-icon-circle {{ $category->color ?? 'cat-navy' }}"><i class="fa-solid {{ $category->icon ?? 'fa-book' }}"></i></span>
        <h3>{{ $category->name }}</h3>
        <p>{{ $category->books_count > 0 ? number_format($category->books_count).' كتاب' : 'لا توجد كتب بعد' }}</p>
      </a>
    @endforeach

  </div>

  <p class="no-results" id="noResults" hidden>لا يوجد تصنيفات مطابقة لبحثك.</p>
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/categories.js') }}"></script>
@endpush
