@extends('layouts.app')

@section('title', $currentBook->resolved_seo_title)
@section('meta_description', $currentBook->resolved_seo_description)
@section('og_type', 'book')
@section('og_image', $currentBook->cover_image_url ?? asset('assets/images/hero-section.jpg'))

@push('styles')
<link rel="preload" href="{{ asset_min('assets/css/book-details.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/book-details.css') }}"></noscript>
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
    'datePublished' => $currentBook->published_year ? (string) $currentBook->published_year : null,
    'genre' => $currentBook->categories->pluck('name')->all() ?: null,
    'image' => $currentBook->cover_image_url,
    'author' => $currentBook->writer ? [
        '@type' => 'Person',
        'name' => $currentBook->writer->name,
        'url' => route('writer-details', $currentBook->writer->slug),
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
      <img class="hero-cover-img cover-photo" src="{{ $currentBook->cover_image_url }}"
        width="300" height="450"
        fetchpriority="high" decoding="async" alt="غلاف {{ $currentBook->title }}">
    @else
      <div class="hero-cover-img cover-{{ ($currentBook->id % 5) + 1 }}">
        <span class="cover-badge">B</span>
        <span class="cover-title">{{ $currentBook->title }}</span>
      </div>
    @endif
    <span class="brand-ribbon">nootabooks.com</span>
    @if ($currentBook->is_coming_soon)
      <span class="coming-soon-badge">قريبًا</span>
    @endif
    <button class="wishlist-btn" aria-label="add to wishlist"><i class="fa-regular fa-heart"></i></button>
  </div>

  <div class="book-hero-info">
    @if ($currentBook->categories->isNotEmpty())
      <div class="genre-chip-row">
        @foreach ($currentBook->categories as $bookCategory)
          <a href="{{ route('category-details', $bookCategory->slug) }}" class="genre-chip">{{ $bookCategory->name }}</a>
        @endforeach
      </div>
    @endif
    <h1 class="book-title">{{ $currentBook->title }}</h1>
    @if ($currentBook->writer)
      <p class="book-author">تأليف <a href="{{ route('writer-details', $currentBook->writer->slug) }}">{{ $currentBook->writer->name }}</a></p>
    @endif

    <div class="rating-row">
      @if ($currentBook->rating_count > 0)
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
      @else
        <span class="no-reviews">لا توجد تقييمات بعد</span>
      @endif
    </div>

    @if ($currentBook->description_short)
      <p class="book-desc-short">{{ $currentBook->description_short }}</p>
    @endif

    <div class="book-meta-grid">
      <div class="meta-item"><i class="fa-solid fa-file-lines"></i><span>عدد الصفحات</span><strong>{{ $currentBook->pages_count ? number_format($currentBook->pages_count).' صفحة' : '—' }}</strong></div>
      <div class="meta-item"><i class="fa-solid fa-language"></i><span>اللغة</span><strong>{{ $currentBook->language }}</strong></div>
      <div class="meta-item"><i class="fa-solid fa-calendar-days"></i><span>تاريخ النشر</span><strong>{{ $currentBook->published_year ?? '—' }}</strong></div>
      <div class="meta-item"><i class="fa-solid fa-file-arrow-down"></i><span>حجم الملف</span><strong>{{ $currentBook->file_size_mb ? $currentBook->file_size_mb.' MB' : '—' }}</strong></div>
      <div class="meta-item"><i class="fa-solid fa-cloud-arrow-down"></i><span>مرات التحميل</span><strong>{{ number_format($currentBook->downloads_count) }}</strong></div>
    </div>

    <div class="book-actions">
      @if ($currentBook->is_coming_soon)
        <button class="btn btn-teal" disabled title="هذا الكتاب سيتوفر قريبًا"><i class="fa-solid fa-clock"></i> قريبًا</button>
        <button class="btn btn-gold" disabled title="هذا الكتاب سيتوفر قريبًا"><i class="fa-solid fa-download"></i> تحميل الكتاب</button>
      @else
        @if ($currentBook->reading_disabled)
          <button class="btn btn-teal" disabled title="غير متاح للقراءة"><i class="fa-solid fa-ban"></i> غير متاح للقراءة</button>
        @else
          <a href="{{ route('read', $currentBook->slug) }}" class="btn btn-teal"><i class="fa-solid fa-headphones"></i> قراءة الآن</a>
        @endif
        @if ($currentBook->downloadUrl())
          <a href="{{ $currentBook->downloadUrl() }}" class="btn btn-gold"><i class="fa-solid fa-download"></i> تحميل الكتاب</a>
        @elseif ($currentBook->download_disabled)
          <button class="btn btn-gold" disabled title="غير متاح للتحميل"><i class="fa-solid fa-ban"></i> غير متاح للتحميل</button>
        @else
          <button class="btn btn-gold" disabled title="الملف غير متوفر حاليًا"><i class="fa-solid fa-download"></i> تحميل الكتاب</button>
        @endif
      @endif
      <button class="btn btn-navy" aria-label="مشاركة"><i class="fa-solid fa-share-nodes"></i> مشاركة</button>
      <a href="{{ route('community', ['book' => $currentBook->slug]) }}" class="btn btn-outline"><i class="fa-solid fa-comments"></i> دردش حول الكتاب</a>
      <a href="{{ route('books.report', $currentBook->slug) }}" class="btn btn-alert" title="الإبلاغ عن حقوق النشر"><i class="fa-solid fa-triangle-exclamation"></i> الإبلاغ عن حقوق النشر</a>
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
    <h2 id="book-description">نبذة عن {{ $currentBook->title }}</h2>
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

    <section aria-labelledby="online-reading" style="margin-top:22px;">
      <h2 id="online-reading">قراءة {{ $currentBook->title }} أونلاين</h2>
      @if ($currentBook->is_coming_soon)
        <p>سيتوفر {{ $currentBook->type_label }} {{ $currentBook->title }} للقراءة أونلاين على نوته بوك قريبًا.</p>
      @elseif ($currentBook->reading_disabled)
        <p>القراءة أونلاين غير متاحة حاليًا لـ {{ $currentBook->title }}.</p>
      @else
        <p>يمكنك قراءة {{ $currentBook->title }} أونلاين مباشرة من خلال قارئ الكتب في نوته بوك، دون الحاجة لتحميل أي برنامج إضافي.</p>
        <a href="{{ route('read', $currentBook->slug) }}">قراءة {{ $currentBook->type_label }} أونلاين</a>
      @endif
    </section>

    @if (! $currentBook->is_coming_soon && $currentBook->downloadUrl())
      <section aria-labelledby="download-book" style="margin-top:22px;">
        <h2 id="download-book">تحميل {{ $currentBook->title }} PDF</h2>
        <p>يمكنك تحميل {{ $currentBook->title }} بصيغة PDF وقراءته على الهاتف أو الكمبيوتر في أي وقت، دون الحاجة للاتصال بالإنترنت.</p>
        <a href="{{ $currentBook->downloadUrl() }}">تحميل {{ $currentBook->type_label }} PDF</a>
      </section>
    @endif
  </div>

  <div class="tab-panel" id="tab-reviews">
    @php
      $bookRatingAverage = $currentBook->rating_average ?? 0;
      $bookRatingCount = $currentBook->rating_count ?? 0;
    @endphp
    <div class="reviews-overview">
      <div class="rating-big">
        @if ($bookRatingCount > 0)
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
        @else
          <span class="no-reviews">لا توجد تقييمات بعد</span>
        @endif
      </div>
      @if ($bookRatingCount > 0)
      <div class="rating-bars">
        @foreach (($ratingBreakdown ?? []) as $stars => $pct)
          <div class="bar-row"><span>{{ $stars }}</span><div class="bar"><div class="fill" style="width:{{ $pct }}%"></div></div><span class="pct">{{ $pct }}%</span></div>
        @endforeach
      </div>
      @endif
      @auth
        <button type="button" class="btn btn-outline add-review-btn" id="toggleReviewForm"><i class="fa-solid fa-pen"></i> أضف تقييمك</button>
      @else
        <a href="{{ route('login') }}" class="btn btn-outline add-review-btn"><i class="fa-solid fa-pen"></i> سجل الدخول لإضافة تقييم</a>
      @endauth
    </div>

    @auth
      <form method="POST" action="{{ route('reviews.store', $currentBook->slug) }}" class="add-review-form" id="addReviewForm" data-recaptcha-action="review" hidden>
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
          @if ($review->user->avatar)
            <img src="{{ $review->user->avatar }}" width="72" height="72" loading="lazy" decoding="async" alt="{{ $review->user->name }}">
          @else
            <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
          @endif
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
        @if ($currentBook->writer->photo)
          <img src="{{ $currentBook->writer->photo_xs_url }}" width="200" height="200" loading="lazy" decoding="async" alt="صورة المؤلف {{ $currentBook->writer->name }}">
        @else
          <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
        @endif
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

@if ($seriesBooks->count() > 1)
<!-- ===================== SERIES BOOKS ===================== -->
<section class="section trending-section">
  <div class="section-head">
    <div class="section-title-wrap">
      <h2 class="section-title">أجزاء سلسلة {{ $currentBook->series->name }}</h2>
      <p class="section-sub">جميع أجزاء هذه السلسلة مرتبة حسب الجزء</p>
    </div>
  </div>

  <div class="carousel-wrap">
    <button class="carousel-btn prev" aria-label="previous"><i class="fa-solid fa-chevron-right"></i></button>

    <div class="book-carousel">
      @foreach ($seriesBooks as $seriesBook)
        <a href="{{ route('book-details', $seriesBook->slug) }}" class="book-card @if ($seriesBook->id === $currentBook->id) current @endif">
          @if ($seriesBook->cover_image)
            <div class="cover-wrap">
              <img class="book-cover cover-photo" src="{{ $seriesBook->cover_image_sm_url }}"
                width="300" height="450" loading="lazy" decoding="async" alt="{{ $seriesBook->title }}">
              <span class="brand-ribbon">nootabooks.com</span>
              @if ($seriesBook->series_order)
                <span class="series-part-badge">الجزء {{ $seriesBook->series_order }}</span>
              @endif
              @if ($seriesBook->is_coming_soon)
                <span class="coming-soon-badge">قريبًا</span>
              @endif
            </div>
          @else
            <div class="book-cover cover-{{ ($seriesBook->id % 5) + 1 }}">
              <span class="cover-badge">B</span>
              <span class="cover-title">{{ $seriesBook->title }}</span>
              <span class="brand-ribbon">nootabooks.com</span>
              @if ($seriesBook->series_order)
                <span class="series-part-badge">الجزء {{ $seriesBook->series_order }}</span>
              @endif
              @if ($seriesBook->is_coming_soon)
                <span class="coming-soon-badge">قريبًا</span>
              @endif
            </div>
          @endif
          <h3>{{ $seriesBook->title }}</h3>
          @if ($seriesBook->id === $currentBook->id)
            <p class="rating no-rating">أنت تقرأ هذا الجزء الآن</p>
          @elseif ($seriesBook->rating_count > 0)
            <p class="rating"><i class="fa-solid fa-star"></i> {{ number_format($seriesBook->rating_average, 1) }}</p>
          @else
            <p class="rating no-rating">لا توجد تقييمات بعد</p>
          @endif
        </a>
      @endforeach
    </div>

    <button class="carousel-btn next" aria-label="next"><i class="fa-solid fa-chevron-left"></i></button>
  </div>
</section>
@endif

@if ($writerBooks->isNotEmpty())
<!-- ===================== WRITER BOOKS ===================== -->
<section class="section trending-section">
  <div class="section-head">
    <div class="section-title-wrap">
      <h2 class="section-title">كتب للمؤلف</h2>
      <p class="section-sub">كتب أخرى لـ {{ $currentBook->writer?->name }}</p>
    </div>
    @if ($currentBook->writer)
      <a href="{{ route('writer-details', $currentBook->writer->slug) }}" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
    @endif
  </div>

  <div class="carousel-wrap">
    <button class="carousel-btn prev" aria-label="previous"><i class="fa-solid fa-chevron-right"></i></button>

    <div class="book-carousel">
      @foreach ($writerBooks as $writerBook)
        <a href="{{ route('book-details', $writerBook->slug) }}" class="book-card">
          @if ($writerBook->cover_image)
            <div class="cover-wrap">
              <img class="book-cover cover-photo" src="{{ $writerBook->cover_image_sm_url }}"
                width="300" height="450" loading="lazy" decoding="async" alt="{{ $writerBook->title }}">
              <span class="brand-ribbon">nootabooks.com</span>
              @if ($writerBook->is_coming_soon)
                <span class="coming-soon-badge">قريبًا</span>
              @endif
            </div>
          @else
            <div class="book-cover cover-{{ ($writerBook->id % 5) + 1 }}">
              <span class="cover-badge">B</span>
              <span class="cover-title">{{ $writerBook->title }}</span>
              <span class="brand-ribbon">nootabooks.com</span>
              @if ($writerBook->is_coming_soon)
                <span class="coming-soon-badge">قريبًا</span>
              @endif
            </div>
          @endif
          <h3>{{ $writerBook->title }}</h3>
          <p class="author">{{ $currentBook->writer?->name }}</p>
          @if ($writerBook->rating_count > 0)
            <p class="rating"><i class="fa-solid fa-star"></i> {{ number_format($writerBook->rating_average, 1) }}</p>
          @else
            <p class="rating no-rating">لا توجد تقييمات بعد</p>
          @endif
        </a>
      @endforeach
    </div>

    <button class="carousel-btn next" aria-label="next"><i class="fa-solid fa-chevron-left"></i></button>
  </div>
</section>
@endif

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
        <a href="{{ route('book-details', $similarBook->slug) }}" class="book-card">
          @if ($similarBook->cover_image)
            <div class="cover-wrap">
              <img class="book-cover cover-photo" src="{{ $similarBook->cover_image_sm_url }}"
                width="300" height="450" loading="lazy" decoding="async" alt="{{ $similarBook->title }}">
              <span class="brand-ribbon">nootabooks.com</span>
              @if ($similarBook->is_coming_soon)
                <span class="coming-soon-badge">قريبًا</span>
              @endif
            </div>
          @else
            <div class="book-cover cover-{{ ($similarBook->id % 5) + 1 }}">
              <span class="cover-badge">B</span>
              <span class="cover-title">{{ $similarBook->title }}</span>
              <span class="brand-ribbon">nootabooks.com</span>
              @if ($similarBook->is_coming_soon)
                <span class="coming-soon-badge">قريبًا</span>
              @endif
            </div>
          @endif
          <h3>{{ $similarBook->title }}</h3>
          <p class="author">{{ $similarBook->writer?->name }}</p>
          @if ($similarBook->rating_count > 0)
            <p class="rating"><i class="fa-solid fa-star"></i> {{ number_format($similarBook->rating_average, 1) }}</p>
          @else
            <p class="rating no-rating">لا توجد تقييمات بعد</p>
          @endif
        </a>
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
<script src="{{ asset_min('assets/js/book-details.js') }}" defer></script>
@endpush
