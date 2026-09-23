@extends('layouts.app')

@section('title', 'اكتشف الكتب والروايات العربية والمترجمة | نوته بوك')
@section('meta_description', 'اكتشف الكتب والروايات العربية والمترجمة في مختلف المجالات. ابحث حسب العنوان أو المؤلف والتصنيف واللغة واستكشف الكتب المتاحة على نوته بوك.')
@section('robots', $isFiltered ? 'noindex, follow' : 'index, follow')

@push('styles')
<link rel="preload" href="{{ asset_min('assets/css/writers.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/writers.css') }}"></noscript>
<link rel="preload" href="{{ asset_min('assets/css/discover.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/discover.css') }}"></noscript>
@endpush

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'الرئيسية', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'استكشاف', 'item' => route('discover')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@if ($books->isNotEmpty())
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'itemListElement' => $books->getCollection()->values()->map(fn ($book, $index) => [
        '@type' => 'ListItem',
        'position' => ($books->currentPage() - 1) * $books->perPage() + $index + 1,
        'url' => route('book-details', $book->slug),
        'name' => $book->title,
    ])->all(),
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
    <span>استكشاف</span>
  </nav>
</div>

<!-- ===================== PAGE HEADING ===================== -->
<section class="section writers-hero">
  <h1>اكتشف الكتب والروايات</h1>
  <p>اكتشف مجموعة متنوعة من الكتب والروايات العربية والمترجمة في مختلف المجالات. ابحث عن كتابك المفضل أو استكشف الكتب حسب التصنيف واللغة.</p>

  <div class="writers-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <label for="discoverSearch" class="sr-only">ابحث بعنوان الكتاب أو اسم المؤلف</label>
    <input type="text" id="discoverSearch" name="q" form="discoverFilters" value="{{ $search }}" placeholder="ابحث بعنوان الكتاب أو اسم المؤلف ...">
    <button type="submit" form="discoverFilters" class="btn btn-gold small">بحث</button>
  </div>
</section>

<!-- ===================== DISCOVER LAYOUT ===================== -->
<section class="section discover-layout">

  <!-- ---- Filters Sidebar ---- -->
  <button type="button" class="filters-toggle-btn" id="filtersToggleBtn" aria-expanded="false" aria-controls="discoverFilters">
    <span><i class="fa-solid fa-sliders"></i> الفلاتر</span>
    <i class="fa-solid fa-chevron-down toggle-chevron"></i>
  </button>

  <form class="filters-sidebar" id="discoverFilters" method="GET" action="{{ route('discover') }}">
    <div class="filters-head">
      <h2>الفلاتر</h2>
      <button type="button" class="clear-filters-btn" id="clearFilters" data-clear-url="{{ route('discover') }}">مسح الكل</button>
    </div>

    @include('partials.ad-slot', ['zone' => 'discover_sidebar'])

    <div class="filter-group">
      <h3>التصنيف</h3>
      @foreach ($categories as $category)
        <label class="checkbox-row">
          <input type="checkbox" name="category[]" value="{{ $category->slug }}" onchange="this.form.submit()" @checked(in_array($category->slug, $selectedCategorySlugs))>
          <span>{{ $category->name }}</span>
        </label>
      @endforeach
    </div>

    <div class="filter-group">
      <h3>اللغة</h3>
      <label class="checkbox-row"><input type="radio" name="lang" value="all" onchange="this.form.submit()" @checked($selectedLanguage === 'all')> <span>الكل</span></label>
      <label class="checkbox-row"><input type="radio" name="lang" value="عربي" onchange="this.form.submit()" @checked($selectedLanguage === 'عربي')> <span>العربية</span></label>
      <label class="checkbox-row"><input type="radio" name="lang" value="إنجليزي" onchange="this.form.submit()" @checked($selectedLanguage === 'إنجليزي')> <span>الإنجليزية</span></label>
      <label class="checkbox-row"><input type="radio" name="lang" value="مترجم" onchange="this.form.submit()" @checked($selectedLanguage === 'مترجم')> <span>مترجم</span></label>
    </div>

    <div class="filter-group">
      <h3>التقييم</h3>
      <label class="checkbox-row rating-filter" data-min="4.5"><input type="checkbox" name="rating[]" value="4.5" onchange="this.form.submit()" @checked(in_array(4.5, $selectedRatings))> <span class="stars sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></span> فأعلى</label>
      <label class="checkbox-row rating-filter" data-min="4"><input type="checkbox" name="rating[]" value="4" onchange="this.form.submit()" @checked(in_array(4.0, $selectedRatings))> <span class="stars sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></span> فأعلى</label>
      <label class="checkbox-row rating-filter" data-min="3"><input type="checkbox" name="rating[]" value="3" onchange="this.form.submit()" @checked(in_array(3.0, $selectedRatings))> <span class="stars sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i></span> فأعلى</label>
    </div>

  </form>

  <!-- ---- Results ---- -->
  <div class="discover-results">
    <div class="results-toolbar">
      <h2 class="results-count">الكتب المتاحة (<strong id="resultsCount">{{ $books->total() }}</strong>)</h2>
      <div class="sort-wrap">
        <label for="sortSelect">ترتيب حسب</label>
        <select id="sortSelect" name="sort" form="discoverFilters" onchange="this.form.submit()">
          <option value="popular" @selected($sort === 'popular')>الأكثر شعبية</option>
          <option value="newest" @selected($sort === 'newest')>الأحدث</option>
          <option value="rating" @selected($sort === 'rating')>الأعلى تقييمًا</option>
          <option value="az" @selected($sort === 'az')>أبجديًا</option>
        </select>
      </div>
    </div>

    <div class="discover-grid" id="discoverGrid" @if ($books->isEmpty()) hidden @endif>

      @foreach ($books as $book)
        <article class="book-card" data-title="{{ $book->title }}" data-author="{{ $book->writer?->name }}" data-rating="{{ $book->rating_average }}" data-year="{{ $book->published_year }}">
          @if ($book->cover_image)
            <div class="cover-wrap">
              <img class="book-cover cover-photo" src="{{ $book->cover_image_sm_url }}"
                width="300" height="450" loading="lazy" decoding="async" alt="{{ $book->cover_alt }}">
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
          <h3><a href="{{ route('book-details', $book->slug) }}" class="stretched-link">{{ $book->title }}</a></h3>
          @if ($book->writer)
            <p class="author"><a href="{{ route('writer-details', $book->writer->slug) }}">{{ $book->writer->name }}</a></p>
          @endif
          @if ($book->rating_count > 0)
            <p class="rating"><i class="fa-solid fa-star"></i> {{ number_format($book->rating_average, 1) }}</p>
          @else
            <p class="rating no-rating">لا توجد تقييمات بعد</p>
          @endif
        </article>
      @endforeach

    </div>

    <p class="no-results" id="noResults" @unless ($books->isEmpty()) hidden @endunless>لا توجد كتب مطابقة لهذا البحث أو الفلاتر المحددة.</p>

    {{ $books->onEachSide(1)->links() }}
  </div>

</section>

@if ($categories->isNotEmpty())
<!-- ===================== CATEGORY DISCOVERY ===================== -->
<section class="section discover-categories">
  <h2>استكشف الكتب حسب التصنيف</h2>
  <div class="category-links-row">
    @foreach ($categories as $category)
      <a href="{{ route('category-details', $category->slug) }}">{{ $category->name }}</a>
    @endforeach
  </div>
</section>
@endif

<!-- ===================== SEO NOTE ===================== -->
<section class="section discover-seo-note">
  <h2>اكتشف الكتب في مختلف المجالات</h2>
  <p>
    يتيح لك نوته بوك استكشاف مجموعة متنوعة من الكتب والروايات العربية والمترجمة
    @if ($categories->isNotEmpty())
      في مجالات متعددة مثل {{ $categories->take(6)->pluck('name')->implode('، ') }}.
    @else
      في مختلف المجالات.
    @endif
    استخدم البحث والتصنيفات للوصول إلى الكتب التي تناسب اهتماماتك.
  </p>
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/discover.js') }}" defer></script>
<script src="{{ asset_min('assets/js/search-autocomplete.js') }}" defer></script>
@endpush
