@extends('layouts.app')

@section('title', 'استكشاف - نوته بوك')
@section('meta_description', 'تصفح واستكشف مجموعة واسعة من الكتب والروايات العربية حسب التصنيف والتقييم وعدد التحميلات على نوته بوك.')

@push('styles')
<link rel="stylesheet" href="{{ asset_min('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/discover.css') }}">
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
  <h1>استكشف عالم الكتب</h1>
  <p>ابحث عن كتابك القادم من بين آلاف العناوين في مختلف المجالات</p>

  <div class="writers-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" id="discoverSearch" name="q" form="discoverFilters" value="{{ $search }}" placeholder="ابحث بعنوان الكتاب أو اسم المؤلف ...">
  </div>
</section>

<!-- ===================== DISCOVER LAYOUT ===================== -->
<section class="section discover-layout">

  <!-- ---- Filters Sidebar ---- -->
  <form class="filters-sidebar" id="discoverFilters" method="GET" action="{{ route('discover') }}">
    <div class="filters-head">
      <h3>الفلاتر</h3>
      <button type="button" class="clear-filters-btn" id="clearFilters" data-clear-url="{{ route('discover') }}">مسح الكل</button>
    </div>

    <div class="filter-group">
      <h4>التصنيف</h4>
      @foreach ($categories as $category)
        <label class="checkbox-row">
          <input type="checkbox" name="category[]" value="{{ $category->slug }}" onchange="this.form.submit()" @checked(in_array($category->slug, $selectedCategorySlugs))>
          <span>{{ $category->name }}</span>
        </label>
      @endforeach
    </div>

    <div class="filter-group">
      <h4>اللغة</h4>
      <label class="checkbox-row"><input type="radio" name="lang" value="all" onchange="this.form.submit()" @checked($selectedLanguage === 'all')> <span>الكل</span></label>
      <label class="checkbox-row"><input type="radio" name="lang" value="عربي" onchange="this.form.submit()" @checked($selectedLanguage === 'عربي')> <span>العربية</span></label>
      <label class="checkbox-row"><input type="radio" name="lang" value="أجنبي" onchange="this.form.submit()" @checked($selectedLanguage === 'أجنبي')> <span>مترجم</span></label>
    </div>

    <div class="filter-group">
      <h4>التقييم</h4>
      <label class="checkbox-row rating-filter" data-min="4.5"><input type="checkbox" name="rating[]" value="4.5" onchange="this.form.submit()" @checked(in_array(4.5, $selectedRatings))> <span class="stars sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></span> فأعلى</label>
      <label class="checkbox-row rating-filter" data-min="4"><input type="checkbox" name="rating[]" value="4" onchange="this.form.submit()" @checked(in_array(4.0, $selectedRatings))> <span class="stars sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></span> فأعلى</label>
      <label class="checkbox-row rating-filter" data-min="3"><input type="checkbox" name="rating[]" value="3" onchange="this.form.submit()" @checked(in_array(3.0, $selectedRatings))> <span class="stars sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i></span> فأعلى</label>
    </div>

    <div class="filter-group">
      <h4>الصيغة</h4>
      <label class="checkbox-row"><input type="checkbox" name="format[]" value="PDF" onchange="this.form.submit()" @checked(in_array('PDF', $selectedFormats))> <span>PDF</span></label>
      <label class="checkbox-row"><input type="checkbox" name="format[]" value="EPUB" onchange="this.form.submit()" @checked(in_array('EPUB', $selectedFormats))> <span>EPUB</span></label>
      <label class="checkbox-row"><input type="checkbox" name="format[]" value="MOBI" onchange="this.form.submit()" @checked(in_array('MOBI', $selectedFormats))> <span>MOBI</span></label>
    </div>
  </form>

  <!-- ---- Results ---- -->
  <div class="discover-results">
    <div class="results-toolbar">
      <span class="results-count"><strong id="resultsCount">{{ $books->total() }}</strong> كتاب متاح</span>
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
            <img class="book-cover cover-photo" src="{{ $book->cover_image_sm_url }}" alt="{{ $book->title }}">
          @else
            <a href="{{ route('book-details', $book->slug) }}" class="book-cover cover-{{ ($book->id % 5) + 1 }}">
              <span class="cover-badge">B</span>
              <span class="cover-title">{{ $book->title }}</span>
            </a>
          @endif
          <h3><a href="{{ route('book-details', $book->slug) }}">{{ $book->title }}</a></h3>
          <p class="author">{{ $book->writer?->name }}</p>
          <p class="rating"><i class="fa-solid fa-star"></i> {{ number_format($book->rating_average, 1) }}</p>
          <a href="{{ route('book-details', $book->slug) }}" class="btn btn-outline w-full"><i class="fa-solid fa-eye"></i> شاهد</a>
        </article>
      @endforeach

    </div>

    <p class="no-results" id="noResults" @unless ($books->isEmpty()) hidden @endunless>لا توجد كتب مطابقة لهذا البحث أو الفلاتر المحددة.</p>

    {{ $books->links() }}
  </div>

</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/discover.js') }}"></script>
@endpush
