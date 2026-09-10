@extends('layouts.app')

@section('title', $currentCategory->name.' - نوته بوك')
@section('meta_description', 'تصفح أفضل كتب وروايات '.$currentCategory->name.' على نوته بوك، مع تقييمات القراء وإمكانية القراءة والتحميل.')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/book-details.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/writer-details.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/categories.css') }}">
@endpush

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'الرئيسية', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'التصنيفات', 'item' => route('categories')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $currentCategory->name, 'item' => route('category-details', $currentCategory->slug)],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <a href="{{ route('categories') }}">التصنيفات</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>{{ $currentCategory->name }}</span>
  </nav>
</div>

<!-- ===================== CATEGORY HERO ===================== -->
<section class="section writer-hero">
  <span class="cat-icon-circle {{ $currentCategory->color ?? 'cat-navy' }}" style="width:90px;height:90px;font-size:36px;justify-self:center;">
    <i class="fa-solid {{ $currentCategory->icon ?? 'fa-book' }}"></i>
  </span>

  <div class="writer-hero-info">
    <h1 class="writer-hero-name">{{ $currentCategory->name }}</h1>
    <p class="writer-hero-desc">اكتشف أفضل الكتب والروايات في تصنيف {{ $currentCategory->name }}</p>

    <div class="writer-hero-meta" style="grid-template-columns: max-content;">
      <div class="meta-item"><i class="fa-solid fa-book"></i><span>عدد الكتب</span><strong>{{ number_format($currentCategory->books_count) }}</strong></div>
    </div>
  </div>
</section>

<!-- ===================== CATEGORY BOOKS ===================== -->
<section class="section">
  <div class="section-head">
    <div class="section-title-wrap">
      <h2 class="section-title">كتب {{ $currentCategory->name }}</h2>
      <p class="section-sub">جميع الكتب المتوفرة في هذا التصنيف</p>
    </div>
    <span class="results-count"><span id="booksCount">{{ $categoryBooks->total() }}</span> كتاب</span>
  </div>

  @if ($categoryBooks->isNotEmpty())
    <div class="writer-books-grid" id="categoryBooksGrid">
      @foreach ($categoryBooks as $book)
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
          <p class="author">{{ $book->writer?->name }}</p>
          <p class="rating"><i class="fa-solid fa-star"></i> {{ number_format($book->rating_average, 1) }}</p>
          <a href="{{ route('book-details', $book->slug) }}" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
        </article>
      @endforeach
    </div>

    {{ $categoryBooks->links() }}
  @else
    <p class="no-results">لا توجد كتب في هذا التصنيف بعد.</p>
  @endif
</section>

<!-- ===================== SIMILAR CATEGORIES ===================== -->
<section class="section">
  <div class="section-head">
    <h2 class="section-title">تصنيفات أخرى</h2>
    <a href="{{ route('categories') }}" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="categories-full-grid">
    @foreach ($similarCategories as $category)
      <a href="{{ route('category-details', $category->slug) }}" class="category-full-card">
        <span class="cat-icon-circle {{ $category->color ?? 'cat-navy' }}"><i class="fa-solid {{ $category->icon ?? 'fa-book' }}"></i></span>
        <h3>{{ $category->name }}</h3>
        <p>{{ number_format($category->books_count) }} كتاب</p>
      </a>
    @endforeach
  </div>
</section>

</main>
@endsection
