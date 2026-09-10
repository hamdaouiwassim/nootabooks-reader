@extends('layouts.app')

@section('title', 'نوته بوك - عالم من الكتب بين يديك')
@section('meta_description', 'اكتشف أفضل الروايات والكتب العربية على نوته بوك، اقرأ وحمّل مجانًا، وتابع مؤلفيك المفضلين واطّلع على مراجعات القراء.')

@section('content')

<!-- ===================== HERO ===================== -->
<section class="hero">
  <div class="hero-content">
    @if ($heroQuotes->isNotEmpty())
      <div class="hero-quote-stack @if ($heroQuotes->count() < 2) static @endif">
        @foreach ($heroQuotes as $quote)
          <blockquote class="hero-quote-card" style="animation-delay: {{ ($loop->index / $heroQuotes->count()) * 15 }}s">
            <i class="fa-solid fa-quote-right"></i>
            <p>"{{ $quote->text }}"</p>
            @if ($quote->author)
              <cite>— {{ $quote->author }}</cite>
            @endif
          </blockquote>
        @endforeach
      </div>
    @endif
    <h1 class="hero-title">إقرأ . اكتشف . حمّل</h1>
    <p class="hero-subtitle">عالم من الكتب بين يديك</p>
    <form class="hero-search" method="GET" action="{{ route('discover') }}">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث عن كتاب، مؤلف، او موضوع ...">
      <button type="submit" class="btn btn-gold">ابحث</button>
    </form>
  </div>
</section>

<!-- ===================== STATS BAR ===================== -->
<section class="stats-bar">
  <div class="stat-item">
    <div class="stat-text"><strong>تحميل مجاني</strong><span>بسهولة وأمان</span></div>
    <i class="fa-solid fa-cloud-arrow-down stat-icon"></i>
  </div>
  <div class="stat-item">
    <div class="stat-text"><strong>قراءة أونلاين</strong><span>في اي وقت</span></div>
    <i class="fa-solid fa-book stat-icon"></i>
  </div>
  <div class="stat-item">
    <div class="stat-text"><strong>{{ number_format($categoriesCount) }}</strong><span>تصنيف متنوع</span></div>
    <i class="fa-solid fa-layer-group stat-icon"></i>
  </div>
  <div class="stat-item">
    <div class="stat-text"><strong>{{ number_format($writersCount) }}</strong><span>مؤلف</span></div>
    <i class="fa-solid fa-book-open stat-icon"></i>
  </div>
  <div class="stat-item">
    <div class="stat-text"><strong>{{ number_format($booksCount) }}</strong><span>كتاب متوفر</span></div>
    <i class="fa-solid fa-gift stat-icon"></i>
  </div>
</section>

<main>

<!-- ===================== TRENDING BOOKS ===================== -->
<section class="section trending-section">
  <div class="section-head">
    <div class="section-title-wrap">
      <h2 class="section-title">الأكثر قراءة هذا الأسبوع <i class="fa-solid fa-fire fire-icon"></i></h2>
      <p class="section-sub">اكتشف اكثر الكتب قراءة من قبل مجتمعنا</p>
    </div>
    <a href="{{ route('discover') }}" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="carousel-wrap">
    <button class="carousel-btn prev" aria-label="previous"><i class="fa-solid fa-chevron-right"></i></button>

    <div class="book-carousel">
      @foreach ($trendingBooks as $book)
        <article class="book-card">
          @if ($book->cover_image)
            <img class="book-cover cover-photo" src="{{ $book->cover_image_sm_url }}" alt="{{ $book->title }}">
          @else
            <div class="book-cover cover-{{ ($book->id % 5) + 1 }}">
              <span class="cover-badge">B</span>
              <span class="cover-title">{{ $book->title }}</span>
            </div>
          @endif
          <h3><a href="{{ route('book-details', $book->slug) }}">{{ $book->title }}</a></h3>
          <p class="author">{{ $book->writer?->name }}</p>
          <p class="rating"><i class="fa-solid fa-star"></i> {{ number_format($book->rating_average, 1) }}</p>
          <a href="{{ route('book-details', $book->slug) }}" class="btn btn-outline w-full"><i class="fa-solid fa-eye"></i> شاهد</a>
        </article>
      @endforeach
    </div>

    <button class="carousel-btn next" aria-label="next"><i class="fa-solid fa-chevron-left"></i></button>
  </div>
</section>

@if ($booksCount > 10)
<!-- ===================== RECENT BOOKS ===================== -->
<section class="section trending-section">
  <div class="section-head">
    <div class="section-title-wrap">
      <h2 class="section-title">أحدث الكتب <i class="fa-solid fa-clock-rotate-left fire-icon"></i></h2>
      <p class="section-sub">آخر الكتب المضافة إلى المنصة</p>
    </div>
    <a href="{{ route('discover', ['sort' => 'newest']) }}" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="carousel-wrap">
    <button class="carousel-btn prev" aria-label="previous"><i class="fa-solid fa-chevron-right"></i></button>

    <div class="book-carousel">
      @foreach ($recentBooks as $book)
        <article class="book-card">
          @if ($book->cover_image)
            <img class="book-cover cover-photo" src="{{ $book->cover_image_sm_url }}" alt="{{ $book->title }}">
          @else
            <div class="book-cover cover-{{ ($book->id % 5) + 1 }}">
              <span class="cover-badge">B</span>
              <span class="cover-title">{{ $book->title }}</span>
            </div>
          @endif
          <h3><a href="{{ route('book-details', $book->slug) }}">{{ $book->title }}</a></h3>
          <p class="author">{{ $book->writer?->name }}</p>
          <p class="rating"><i class="fa-solid fa-star"></i> {{ number_format($book->rating_average, 1) }}</p>
          <a href="{{ route('book-details', $book->slug) }}" class="btn btn-outline w-full"><i class="fa-solid fa-eye"></i> شاهد</a>
        </article>
      @endforeach
    </div>

    <button class="carousel-btn next" aria-label="next"><i class="fa-solid fa-chevron-left"></i></button>
  </div>
</section>
@endif

<!-- ===================== DISCOVER BANNER ===================== -->
<section class="section">
  <div class="discover-banner">
    <div class="discover-tags">
      <div class="tag-item">
        <span class="tag-icon tag-purple"><i class="fa-solid fa-bookmark"></i></span>
        <span>روايات</span>
      </div>
      <div class="tag-item">
        <span class="tag-icon tag-green"><i class="fa-solid fa-mosque"></i></span>
        <span>تنمية ذهنية</span>
      </div>
      <div class="tag-item">
        <span class="tag-icon tag-brown"><i class="fa-solid fa-bell"></i></span>
        <span>تنمية ذاتية</span>
      </div>
      <div class="tag-item">
        <span class="tag-icon tag-blue"><i class="fa-solid fa-table-cells"></i></span>
        <span>غير ذلك</span>
      </div>
    </div>
    <div class="discover-left">
      <div class="discover-text">
        <h3>اكتشف عوالم جديدة</h3>
        <p>ألاف الكتب في انتظارك ...</p>
        <button class="btn btn-gold small"><i class="fa-solid fa-arrow-left"></i> <span class="btn-label">استكشف</span></button>
      </div>
    </div>
  </div>
</section>

<!-- ===================== AUTHORS + SIMILAR BOOKS ===================== -->
<section class="section two-col">
  <div class="col">
    <div class="section-head">
      <h2 class="section-title">كتب مشابهة لك</h2>
      <a href="{{ route('discover') }}" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="similar-row">
      @foreach ($similarBooks as $book)
        <a href="{{ route('book-details', $book->slug) }}" class="mini-book">
          <div class="mini-cover mc-{{ ($book->id % 4) + 1 }}"><span class="cover-badge sm">B</span></div>
          <h4>{{ $book->title }}</h4>
          <p>{{ $book->writer?->name }}</p>
        </a>
      @endforeach
    </div>
  </div>

  <div class="col">
    <div class="section-head">
      <h2 class="section-title">مؤلفون مميزون</h2>
      <a href="{{ route('writers') }}" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="authors-row">
      @forelse ($popularWriters as $writer)
        <a href="{{ route('writer-details', $writer->slug) }}" class="author-card">
          <img src="{{ $writer->photo_sm_url ?? 'https://i.pravatar.cc/120?img=' . (($writer->id % 70) + 1) }}" alt="{{ $writer->name }}">
          <p>{{ $writer->name }}</p>
        </a>
      @empty
        <p class="no-results">لا يوجد مؤلفون بعد</p>
      @endforelse
    </div>
  </div>
</section>

<!-- ===================== CATEGORIES ===================== -->
<section class="section">
  <div class="section-head">
    <h2 class="section-title">تصفح حسب التصنيف</h2>
    <a href="{{ route('categories') }}" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="categories-grid">
    @foreach ($categories as $category)
      <a href="{{ route('category-details', $category->slug) }}" class="category-card"><i class="fa-solid {{ $category->icon }}"></i><span>{{ $category->name }}</span></a>
    @endforeach
  </div>
</section>

<!-- ===================== NEWSLETTER ===================== -->
<section class="section">
  <div class="newsletter-banner">
    <div class="newsletter-text">
      <h3>كن دائمًا على اطلاع</h3>
      <p>اشترك في نشرتنا البريدية للحصول على أحدث الكتب والمقالات</p>
    </div>
    <form class="newsletter-form" id="newsletterForm">
      <input type="email" placeholder="أدخل بريدك الاكتروني" required>
      <button type="submit" class="btn btn-teal"><i class="fa-solid fa-paper-plane"></i> <span class="btn-label">اشترك الآن</span></button>
    </form>
  </div>
</section>

</main>
@endsection
