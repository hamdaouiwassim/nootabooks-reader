@extends('layouts.app')

@section('title', $currentBook->title.' - نوته بوك')
@section('meta_description', $currentBook->description_short ?: \Illuminate\Support\Str::limit(strip_tags((string) $currentBook->description), 160) ?: 'اقرأ وحمّل كتاب '.$currentBook->title.' على نوته بوك.')
@section('og_type', 'book')
@section('og_image', $currentBook->cover_image_url ?? asset('assets/images/hero-section.jpg'))

@push('styles')
<link rel="stylesheet" href="{{ asset_min('assets/css/book-details.css') }}">
@endpush

@push('schema')
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type' => 'Book',
    'name' => $currentBook->title,
    'description' => $currentBook->description_short ?: strip_tags((string) $currentBook->description),
    'inLanguage' => $currentBook->language,
    'numberOfPages' => $currentBook->pages_count,
    'image' => $currentBook->cover_image_url,
    'author' => $currentBook->writer ? [
        '@type' => 'Person',
        'name' => $currentBook->writer->name,
    ] : null,
    'aggregateRating' => $currentBook->rating_count > 0 ? [
        '@type' => 'AggregateRating',
        'ratingValue' => (string) $currentBook->rating_average,
        'reviewCount' => $currentBook->rating_count,
    ] : null,
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => array_values(array_filter([
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'الرئيسية', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'التصنيفات', 'item' => route('categories')],
        $currentBook->category ? ['@type' => 'ListItem', 'position' => 3, 'name' => $currentBook->category->name, 'item' => route('category-details', $currentBook->category->slug)] : null,
        ['@type' => 'ListItem', 'position' => $currentBook->category ? 4 : 3, 'name' => $currentBook->title, 'item' => route('book-details', $currentBook->slug)],
    ])),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<main>

@php
  $isFollowingBookAuthor = $currentBook->writer && (auth()->user()?->followedWriters()->where('slug', $currentBook->writer->slug)->exists() ?? false);
@endphp

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <a href="{{ route('categories') }}">التصنيفات</a>
    @if ($currentBook->category)
      <i class="fa-solid fa-chevron-left"></i>
      <a href="{{ route('category-details', $currentBook->category->slug) }}">{{ $currentBook->category->name }}</a>
    @endif
    <i class="fa-solid fa-chevron-left"></i>
    <span>{{ $currentBook->title }}</span>
  </nav>
</div>

<!-- ===================== BOOK HERO ===================== -->
<section class="section book-hero">
  <div class="book-hero-cover">
    @if ($currentBook->cover_image)
      <img class="hero-cover-img cover-photo" src="{{ $currentBook->cover_image_url }}" alt="{{ $currentBook->title }}">
    @else
      <div class="hero-cover-img cover-{{ ($currentBook->id % 5) + 1 }}">
        <span class="cover-badge">B</span>
        <span class="cover-title">{{ $currentBook->title }}</span>
      </div>
    @endif
    <button class="wishlist-btn" aria-label="add to wishlist"><i class="fa-regular fa-heart"></i></button>
  </div>

  <div class="book-hero-info">
    @if ($currentBook->category)
      <span class="genre-chip">{{ $currentBook->category->name }}</span>
    @endif
    <h1 class="book-title">{{ $currentBook->title }}</h1>
    @if ($currentBook->writer)
      <p class="book-author">تأليف <a href="{{ route('writer-details', $currentBook->writer->slug) }}">{{ $currentBook->writer->name }}</a></p>
    @endif

    <div class="rating-row">
      <span class="stars">
        @for ($i = 1; $i <= 5; $i++)
          @if ($currentBook->rating_average >= $i)
            <i class="fa-solid fa-star"></i>
          @elseif ($currentBook->rating_average >= $i - 0.5)
            <i class="fa-solid fa-star-half-stroke"></i>
          @else
            <i class="fa-regular fa-star"></i>
          @endif
        @endfor
      </span>
      <strong>{{ number_format($currentBook->rating_average, 1) }}</strong>
      <span class="review-count">({{ number_format($currentBook->rating_count) }} تقييم)</span>
    </div>

    @if ($currentBook->description_short)
      <p class="book-desc-short">{{ $currentBook->description_short }}</p>
    @endif

    <div class="book-meta-grid">
      <div class="meta-item"><i class="fa-solid fa-file-lines"></i><span>عدد الصفحات</span><strong>{{ $currentBook->pages_count ? number_format($currentBook->pages_count).' صفحة' : '—' }}</strong></div>
      <div class="meta-item"><i class="fa-solid fa-language"></i><span>اللغة</span><strong>{{ $currentBook->language }}</strong></div>
      <div class="meta-item"><i class="fa-solid fa-calendar-days"></i><span>تاريخ النشر</span><strong>{{ $currentBook->published_year ?? '—' }}</strong></div>
      <div class="meta-item"><i class="fa-solid fa-file-arrow-down"></i><span>حجم الملف</span><strong>{{ $currentBook->file_size_mb ? $currentBook->file_size_mb.' MB' : '—' }}</strong></div>
      <div class="meta-item"><i class="fa-solid fa-book-open-reader"></i><span>الصيغة</span><strong>{{ $currentBook->formats ? implode(', ', $currentBook->formats) : '—' }}</strong></div>
      <div class="meta-item"><i class="fa-solid fa-cloud-arrow-down"></i><span>مرات التحميل</span><strong>{{ number_format($currentBook->downloads_count) }}</strong></div>
    </div>

    <div class="book-actions">
      <a href="{{ route('read', $currentBook->slug) }}" class="btn btn-teal"><i class="fa-solid fa-headphones"></i> قراءة الآن</a>
      @if ($currentBook->file_path)
        <a href="{{ route('books.download', $currentBook) }}" class="btn btn-gold"><i class="fa-solid fa-download"></i> تحميل الكتاب</a>
      @else
        <button class="btn btn-gold" disabled title="الملف غير متوفر حاليًا"><i class="fa-solid fa-download"></i> تحميل الكتاب</button>
      @endif
      <button class="btn btn-navy" aria-label="مشاركة"><i class="fa-solid fa-share-nodes"></i> مشاركة</button>
    </div>
  </div>
</section>

<!-- ===================== TABS ===================== -->
<section class="section tabs-section">
  <div class="tabs-nav">
    <button class="tab-btn active" data-tab="about">نبذة عن الكتاب</button>
    <button class="tab-btn" data-tab="reviews">التقييمات والمراجعات</button>
    <button class="tab-btn" data-tab="author">عن المؤلف</button>
  </div>

  <div class="tab-panel active" id="tab-about">
    <h2>نبذة عن الكتاب</h2>
    @if ($currentBook->description)
      @foreach (explode("\n", $currentBook->description) as $paragraph)
        @continue(trim($paragraph) === '')
        <p>{{ $paragraph }}</p>
      @endforeach
    @elseif ($currentBook->description_short)
      <p>{{ $currentBook->description_short }}</p>
    @endif

    @if ($currentBook->tags)
      <div class="genre-tags">
        @foreach ($currentBook->tags as $tag)
          <span>#{{ $tag }}</span>
        @endforeach
      </div>
    @endif
  </div>

  <div class="tab-panel" id="tab-reviews">
    @php
      $bookRatingAverage = $currentBook->rating_average ?? 0;
      $bookRatingCount = $currentBook->rating_count ?? 0;
    @endphp
    <div class="reviews-overview">
      <div class="rating-big">
        <span class="big-number">{{ number_format($bookRatingAverage, 1) }}</span>
        <span class="stars">
          @for ($i = 1; $i <= 5; $i++)
            @if ($bookRatingAverage >= $i)
              <i class="fa-solid fa-star"></i>
            @elseif ($bookRatingAverage >= $i - 0.5)
              <i class="fa-solid fa-star-half-stroke"></i>
            @else
              <i class="fa-regular fa-star"></i>
            @endif
          @endfor
        </span>
        <span class="review-count">من {{ number_format($bookRatingCount) }} تقييم</span>
      </div>
      <div class="rating-bars">
        @foreach (($ratingBreakdown ?? []) as $stars => $pct)
          <div class="bar-row"><span>{{ $stars }}</span><div class="bar"><div class="fill" style="width:{{ $pct }}%"></div></div><span class="pct">{{ $pct }}%</span></div>
        @endforeach
      </div>
      @auth
        <button type="button" class="btn btn-outline add-review-btn" id="toggleReviewForm"><i class="fa-solid fa-pen"></i> أضف تقييمك</button>
      @else
        <a href="{{ route('login') }}" class="btn btn-outline add-review-btn"><i class="fa-solid fa-pen"></i> سجل الدخول لإضافة تقييم</a>
      @endauth
    </div>

    @auth
      <form method="POST" action="{{ route('reviews.store', $currentBook->slug) }}" class="add-review-form" id="addReviewForm" hidden>
        @csrf
        <div class="form-field">
          <label for="reviewRating">تقييمك</label>
          <select name="rating" id="reviewRating" required>
            <option value="">اختر تقييمًا</option>
            <option value="5">5 - ممتاز</option>
            <option value="4">4 - جيد جدًا</option>
            <option value="3">3 - جيد</option>
            <option value="2">2 - مقبول</option>
            <option value="1">1 - ضعيف</option>
          </select>
        </div>
        <textarea name="comment" rows="3" placeholder="اكتب رأيك في الكتاب (اختياري) ..."></textarea>
        @include('partials.recaptcha')
        <button type="submit" class="btn btn-teal">إرسال التقييم</button>
      </form>
    @endauth

    <div class="review-list">
      @forelse ($reviews as $review)
        <article class="review-card">
          <img src="{{ $review->user->avatar ?? 'https://i.pravatar.cc/72?img=' . (($review->user_id % 70) + 1) }}" alt="{{ $review->user->name }}">
          <div class="review-body">
            <div class="review-head">
              <strong>{{ $review->user->name }}</strong>
              <span class="stars sm">
                @for ($i = 1; $i <= 5; $i++)
                  <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                @endfor
              </span>
              <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
            </div>
            @if ($review->comment)
              <p>{{ $review->comment }}</p>
            @endif
          </div>
        </article>
      @empty
        <p class="no-results">لا توجد تقييمات بعد. كن أول من يقيّم هذا الكتاب.</p>
      @endforelse
    </div>

    {{ $reviews->links() }}
  </div>

  <div class="tab-panel" id="tab-author">
    @if ($currentBook->writer)
      <div class="author-mini-card">
        <img src="{{ $currentBook->writer->photo ?? 'https://i.pravatar.cc/120?img=' . (($currentBook->writer->id % 70) + 1) }}" alt="{{ $currentBook->writer->name }}">
        <div class="author-mini-info">
          <h3><a href="{{ route('writer-details', $currentBook->writer->slug) }}">{{ $currentBook->writer->name }}</a></h3>
          @if ($currentBook->writer->bio)
            <p>{{ $currentBook->writer->bio }}</p>
          @endif
          <div class="author-mini-stats">
            <span><i class="fa-solid fa-book"></i> {{ number_format($currentBook->writer->books()->count()) }} كتاب</span>
            <span><i class="fa-solid fa-users"></i> {{ number_format($currentBook->writer->followers_count) }} متابع</span>
          </div>
        </div>
        @auth
          <form method="POST" action="{{ route('writers.follow', $currentBook->writer->slug) }}">
            @csrf
            <button type="submit" class="btn btn-outline follow-btn @if ($isFollowingBookAuthor) following @endif">
              {{ $isFollowingBookAuthor ? 'تتم المتابعة' : 'متابعة' }}
            </button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn btn-outline follow-btn">متابعة</a>
        @endauth
      </div>
    @else
      <p class="no-results">لا توجد معلومات عن المؤلف لهذا الكتاب.</p>
    @endif
  </div>
</section>

<!-- ===================== SIMILAR BOOKS ===================== -->
<section class="section trending-section">
  <div class="section-head">
    <div class="section-title-wrap">
      <h2 class="section-title">كتب مشابهة قد تعجبك</h2>
      <p class="section-sub">اختيارات مبنية على قراءة هذا الكتاب</p>
    </div>
    <a href="{{ route('discover') }}" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="carousel-wrap">
    <button class="carousel-btn prev" aria-label="previous"><i class="fa-solid fa-chevron-right"></i></button>

    <div class="book-carousel">
      @forelse ($similarBooks as $similarBook)
        <article class="book-card">
          @if ($similarBook->cover_image)
            <img class="book-cover cover-photo" src="{{ $similarBook->cover_image_sm_url }}" alt="{{ $similarBook->title }}">
          @else
            <div class="book-cover cover-{{ ($similarBook->id % 5) + 1 }}">
              <span class="cover-badge">B</span>
              <span class="cover-title">{{ $similarBook->title }}</span>
            </div>
          @endif
          <h3>{{ $similarBook->title }}</h3>
          <p class="author">{{ $similarBook->writer?->name }}</p>
          <p class="rating"><i class="fa-solid fa-star"></i> {{ number_format($similarBook->rating_average, 1) }}</p>
          <a href="{{ route('book-details', $similarBook->slug) }}" class="btn btn-outline w-full"><i class="fa-solid fa-eye"></i> شاهد</a>
        </article>
      @empty
        <p class="no-results">لا توجد كتب مشابهة في نفس التصنيف حاليًا.</p>
      @endforelse
    </div>

    <button class="carousel-btn next" aria-label="next"><i class="fa-solid fa-chevron-left"></i></button>
  </div>
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/book-details.js') }}"></script>
@endpush
