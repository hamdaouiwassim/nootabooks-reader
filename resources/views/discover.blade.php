@extends('layouts.app')

@section('title', 'استكشاف - نوته بوك')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/discover.css') }}">
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
    <input type="text" id="discoverSearch" placeholder="ابحث بعنوان الكتاب أو اسم المؤلف ...">
  </div>
</section>

<!-- ===================== DISCOVER LAYOUT ===================== -->
<section class="section discover-layout">

  <!-- ---- Filters Sidebar ---- -->
  <aside class="filters-sidebar">
    <div class="filters-head">
      <h3>الفلاتر</h3>
      <button class="clear-filters-btn" id="clearFilters">مسح الكل</button>
    </div>

    <div class="filter-group">
      <h4>التصنيف</h4>
      <label class="checkbox-row"><input type="checkbox" value="روايات"> <span>روايات</span></label>
      <label class="checkbox-row"><input type="checkbox" value="أدب عربي"> <span>أدب عربي</span></label>
      <label class="checkbox-row"><input type="checkbox" value="أدب عالمي"> <span>أدب عالمي</span></label>
      <label class="checkbox-row"><input type="checkbox" value="تنمية ذاتية"> <span>تنمية ذاتية</span></label>
      <label class="checkbox-row"><input type="checkbox" value="تاريخ"> <span>تاريخ</span></label>
    </div>

    <div class="filter-group">
      <h4>اللغة</h4>
      <label class="checkbox-row"><input type="radio" name="lang" value="all" checked> <span>الكل</span></label>
      <label class="checkbox-row"><input type="radio" name="lang" value="عربي"> <span>العربية</span></label>
      <label class="checkbox-row"><input type="radio" name="lang" value="أجنبي"> <span>مترجم</span></label>
    </div>

    <div class="filter-group">
      <h4>التقييم</h4>
      <label class="checkbox-row rating-filter" data-min="4.5"><input type="checkbox" value="4.5"> <span class="stars sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></span> فأعلى</label>
      <label class="checkbox-row rating-filter" data-min="4"><input type="checkbox" value="4"> <span class="stars sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></span> فأعلى</label>
      <label class="checkbox-row rating-filter" data-min="3"><input type="checkbox" value="3"> <span class="stars sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i></span> فأعلى</label>
    </div>

    <div class="filter-group">
      <h4>الصيغة</h4>
      <label class="checkbox-row"><input type="checkbox" value="PDF"> <span>PDF</span></label>
      <label class="checkbox-row"><input type="checkbox" value="EPUB"> <span>EPUB</span></label>
      <label class="checkbox-row"><input type="checkbox" value="صوتي"> <span>كتاب صوتي</span></label>
    </div>
  </aside>

  <!-- ---- Results ---- -->
  <div class="discover-results">
    <div class="results-toolbar">
      <span class="results-count"><strong id="resultsCount">{{ $books->total() }}</strong> كتاب متاح</span>
      <div class="sort-wrap">
        <label for="sortSelect">ترتيب حسب</label>
        <select id="sortSelect">
          <option value="popular">الأكثر شعبية</option>
          <option value="newest">الأحدث</option>
          <option value="rating">الأعلى تقييمًا</option>
          <option value="az">أبجديًا</option>
        </select>
      </div>
    </div>

    <div class="discover-grid" id="discoverGrid">

      @foreach ($books as $book)
        <article class="book-card" data-title="{{ $book->title }}" data-author="{{ $book->writer?->name }}" data-rating="{{ $book->rating_average }}" data-year="{{ $book->published_year }}" data-category="{{ $book->category?->name }}">
          @if ($book->cover_image)
            <img class="book-cover cover-photo" src="{{ $book->cover_image_url }}" alt="{{ $book->title }}">
          @else
            <a href="{{ route('book-details', $book->slug) }}" class="book-cover cover-{{ ($book->id % 5) + 1 }}">
              <span class="cover-badge">B</span>
              <span class="cover-title">{{ $book->title }}</span>
            </a>
          @endif
          <h3><a href="{{ route('book-details', $book->slug) }}">{{ $book->title }}</a></h3>
          <p class="author">{{ $book->writer?->name }}</p>
          <p class="rating"><i class="fa-solid fa-star"></i> {{ number_format($book->rating_average, 1) }}</p>
          <a href="{{ route('book-details', $book->slug) }}" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
        </article>
      @endforeach

    </div>

    <p class="no-results" id="noResults" hidden>لا توجد كتب مطابقة لهذا البحث أو الفلاتر المحددة.</p>

    {{ $books->links() }}
  </div>

</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/discover.js') }}"></script>
@endpush
