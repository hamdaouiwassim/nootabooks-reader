@extends('layouts.app')

@section('title', 'الملف الشخصي - نوته بوك')
@section('robots', 'noindex, nofollow')

@push('styles')
<link rel="preload" href="{{ asset_min('assets/css/writers.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/writers.css') }}"></noscript>
<link rel="preload" href="{{ asset_min('assets/css/book-details.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/book-details.css') }}"></noscript>
<link rel="preload" href="{{ asset_min('assets/css/community.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/community.css') }}"></noscript>
<link rel="preload" href="{{ asset_min('assets/css/profile.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/profile.css') }}"></noscript>
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>الملف الشخصي</span>
  </nav>
</div>

<!-- ===================== PROFILE HEADER ===================== -->
<section class="section">
  <div class="profile-card">
    <div class="profile-cover"></div>
    <div class="profile-header">
      <div class="profile-avatar-wrap">
        @if ($profileUser->avatar)
          <img src="{{ asset($profileUser->avatar) }}" width="120" height="120" alt="{{ $profileUser->name }}">
        @else
          <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
        @endif
        <button class="avatar-edit-btn" aria-label="edit avatar" data-href="{{ route('settings') }}"><i class="fa-solid fa-camera"></i></button>
      </div>
      <div class="profile-info">
        <h1>{{ $profileUser->name }}</h1>
        @if ($profileUser->bio)
          <p class="profile-bio">{{ $profileUser->bio }}</p>
        @endif
        <div class="profile-meta-row">
          <span><i class="fa-solid fa-calendar-days"></i> انضم في {{ $profileUser->created_at->translatedFormat('F Y') }}</span>
          @if ($profileUser->location)
            <span><i class="fa-solid fa-location-dot"></i> {{ $profileUser->location }}</span>
          @endif
        </div>
      </div>
      <div class="profile-actions">
        <a href="{{ route('settings') }}" class="btn btn-outline"><i class="fa-solid fa-pen"></i> تعديل الملف الشخصي</a>
      </div>
    </div>

    <div class="profile-stats">
      <div class="profile-stat"><strong>{{ number_format($downloadsCount) }}</strong><span>كتاب محمّل</span></div>
      <div class="profile-stat"><strong>{{ number_format($reviewsCount) }}</strong><span>تقييم</span></div>
      <div class="profile-stat"><strong>{{ number_format($bookmarksCount) }}</strong><span>مفضلة</span></div>
      <div class="profile-stat"><strong>{{ number_format($followedWritersCount) }}</strong><span>مؤلف متابَع</span></div>
      <div class="profile-stat"><strong>{{ number_format($profileUser->points) }}</strong><span>نقطة</span></div>
    </div>
  </div>
</section>

<!-- ===================== TABS ===================== -->
<section class="section tabs-section">
  <h2 class="sr-only">نشاط الملف الشخصي</h2>
  <div class="tabs-nav">
    <button class="tab-btn active" data-tab="activity">النشاط</button>
    <button class="tab-btn" data-tab="favorites">المفضلة</button>
    <button class="tab-btn" data-tab="reviews">التقييمات</button>
    <button class="tab-btn" data-tab="achievements">الإنجازات</button>
  </div>

  <!-- ---- Activity ---- -->
  <div class="tab-panel active" id="tab-activity">
    @if (count($activity))
      <div class="activity-list">
        @foreach ($activity as $item)
          <div class="activity-item">
            <span class="activity-icon {{ $item['type'] }}"><i class="fa-solid fa-{{ $item['icon'] }}"></i></span>
            <p>{!! $item['text'] !!} <span class="activity-time">{{ $item['time']->diffForHumans() }}</span></p>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty-state">
        <i class="fa-regular fa-folder-open"></i>
        <p>لا يوجد نشاط بعد</p>
        <a href="{{ route('discover') }}" class="btn btn-gold">استكشف الكتب</a>
      </div>
    @endif
  </div>

  <!-- ---- Favorites ---- -->
  <div class="tab-panel" id="tab-favorites">
    @if ($bookmarks->isNotEmpty())
      <div class="favorites-grid">
        @foreach ($bookmarks as $book)
          <div class="book-card">
            <form method="POST" action="{{ route('books.bookmark', $book->slug) }}" class="favorite-remove-form">
              @csrf
              <button type="submit" class="favorite-remove-btn" aria-label="إزالة من المفضلة" title="إزالة من المفضلة"><i class="fa-solid fa-heart"></i></button>
            </form>
            @if ($book->cover_image)
              <a href="{{ route('book-details', $book->slug) }}">
                <img class="book-cover cover-photo" src="{{ $book->cover_image_sm_url }}" width="300" height="450" loading="lazy" decoding="async" alt="غلاف {{ $book->title }}">
              </a>
            @else
              <a href="{{ route('book-details', $book->slug) }}" class="book-cover cover-{{ ($book->id % 5) + 1 }}">
                <span class="cover-badge">B</span>
                <span class="cover-title">{{ $book->title }}</span>
              </a>
            @endif
            <h3><a href="{{ route('book-details', $book->slug) }}">{{ $book->title }}</a></h3>
            @if ($book->writer)
              <p class="author">{{ $book->writer->name }}</p>
            @endif
            @if ($book->rating_count > 0)
              <p class="rating"><i class="fa-solid fa-star"></i> {{ number_format($book->rating_average, 1) }}</p>
            @else
              <p class="rating no-rating">لا توجد تقييمات بعد</p>
            @endif
          </div>
        @endforeach
      </div>
    @else
      <div class="empty-state">
        <i class="fa-regular fa-heart"></i>
        <p>لم تُضِف أي كتاب إلى المفضلة بعد</p>
        <a href="{{ route('discover') }}" class="btn btn-gold">استكشف الكتب</a>
      </div>
    @endif
  </div>

  <!-- ---- Reviews ---- -->
  <div class="tab-panel" id="tab-reviews">
    @if ($reviews->isNotEmpty())
      <div class="my-reviews-list">
        @foreach ($reviews as $review)
          @continue(! $review->book)
          <article class="my-review-card">
            @if ($review->book->cover_image)
              <img class="book-cover cover-photo" src="{{ $review->book->cover_image_sm_url }}" width="70" height="100" loading="lazy" decoding="async" alt="غلاف {{ $review->book->title }}">
            @else
              <span class="book-cover mini cover-{{ ($review->book->id % 5) + 1 }}"><span class="cover-title">{{ $review->book->title }}</span></span>
            @endif
            <div class="my-review-body">
              <h4><a href="{{ route('book-details', $review->book->slug) }}">{{ $review->book->title }}</a></h4>
              <p class="my-review-author">{{ $review->book->writer?->name }}</p>
              <span class="stars">
                @for ($i = 1; $i <= 5; $i++)
                  <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                @endfor
              </span>
              @if ($review->comment)
                <p class="my-review-text">{{ $review->comment }}</p>
              @endif
              <span class="my-review-date">{{ $review->created_at->diffForHumans() }}</span>
            </div>
          </article>
        @endforeach
      </div>
    @else
      <div class="empty-state">
        <i class="fa-regular fa-star"></i>
        <p>لم تكتب أي تقييم بعد</p>
        <a href="{{ route('discover') }}" class="btn btn-gold">استكشف الكتب</a>
      </div>
    @endif
  </div>

  <!-- ---- Achievements ---- -->
  <div class="tab-panel" id="tab-achievements">
    <div class="achievements-grid">
      @foreach ($achievements as $achievement)
        @php $unlocked = $achievement['progress'] >= $achievement['goal']; @endphp
        <div class="achievement-card @if ($unlocked) unlocked @endif">
          <span class="achievement-icon"><i class="fa-solid fa-{{ $achievement['icon'] }}"></i></span>
          <strong>{{ $achievement['title'] }}</strong>
          <p>{{ $achievement['description'] }} @unless ($unlocked) ({{ min($achievement['progress'], $achievement['goal']) }}/{{ $achievement['goal'] }}) @endunless</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/profile.js') }}" defer></script>
@endpush
