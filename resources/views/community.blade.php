@extends('layouts.app')

@section('title', 'المجتمع - نوته بوك')
@section('robots', 'noindex, follow')

@push('styles')
<link rel="stylesheet" href="{{ asset_min('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/my-library.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/community.css') }}">
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>المجتمع</span>
  </nav>
</div>

<!-- ===================== PAGE HEADING ===================== -->
<section class="section writers-hero">
  <h1>مجتمع القراء</h1>
  <p>شارك آراءك، ناقش كتبك المفضلة، وتواصل مع قراء يشاركونك الشغف</p>
</section>

<!-- ===================== COMMUNITY STATS ===================== -->
<section class="section">
  <div class="library-stats">
    <div class="lib-stat-card">
      <i class="fa-solid fa-users"></i>
      <div><strong>{{ number_format($membersCount) }}</strong><span>عضو</span></div>
    </div>
    <div class="lib-stat-card">
      <i class="fa-solid fa-comments"></i>
      <div><strong>{{ number_format($discussionsCount) }}</strong><span>مناقشة</span></div>
    </div>
    <div class="lib-stat-card">
      <i class="fa-solid fa-people-group"></i>
      <div><strong>{{ number_format($clubsCount) }}</strong><span>نادي قراءة</span></div>
    </div>
    <div class="lib-stat-card">
      <i class="fa-solid fa-fire"></i>
      <div><strong>{{ number_format($postsTodayCount) }}</strong><span>منشور اليوم</span></div>
    </div>
  </div>
</section>

<!-- ===================== COMMUNITY LAYOUT ===================== -->
<section class="section community-layout">

  <!-- ---- Main Feed ---- -->
  <div class="community-main">

    <div class="filter-tabs">
      <button class="filter-tab active" data-filter="latest">الأحدث</button>
      <button class="filter-tab" data-filter="popular">الأكثر تفاعلاً</button>
      <button class="filter-tab" data-filter="following">متابعينك</button>
    </div>

    @if ($chatBook)
      <div class="new-post-box" style="margin-bottom:12px;">
        <span><i class="fa-solid fa-book"></i> تعرض الآن المناقشات المتعلقة بكتاب <strong>{{ $chatBook->title }}</strong></span>
        <a href="{{ route('community') }}" class="btn btn-outline small">إلغاء الفلتر</a>
      </div>
    @elseif ($tag)
      <div class="new-post-box" style="margin-bottom:12px;">
        <span><i class="fa-solid fa-hashtag"></i> تعرض الآن المنشورات الموسومة بـ <strong>#{{ $tag }}</strong></span>
        <a href="{{ route('community') }}" class="btn btn-outline small">إلغاء الفلتر</a>
      </div>
    @endif

    @auth
      <form method="POST" action="{{ route('discussions.store') }}" class="new-post-box">
        @csrf
        @if ($chatBook)
          <input type="hidden" name="book" value="{{ $chatBook->slug }}">
        @endif
        @if (auth()->user()->avatar)
          <img src="{{ auth()->user()->avatar }}" width="64" height="64" decoding="async" alt="{{ auth()->user()->name }}">
        @else
          <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
        @endif
        <input type="text" name="body" maxlength="2000" required
          placeholder="{{ $chatBook ? 'شارك رأيك حول "'.$chatBook->title.'" ...' : 'شارك رأيك أو ابدأ نقاشًا جديدًا ...' }}">
        <button type="submit" class="btn btn-gold small">نشر</button>
      </form>
    @else
      <div class="new-post-box">
        <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
        <span>سجل الدخول للمشاركة في النقاش</span>
        <a href="{{ route('login') }}" class="btn btn-gold small">تسجيل الدخول</a>
      </div>
    @endauth

    <div class="discussion-feed" id="discussionFeed">

      @forelse ($discussions as $discussion)
        <article class="discussion-card">
          <div class="discussion-head">
            @if ($discussion->user->avatar)
              <img src="{{ $discussion->user->avatar }}" width="64" height="64" loading="lazy" decoding="async" alt="{{ $discussion->user->name }}">
            @else
              <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
            @endif
            <div>
              <strong>{{ $discussion->user->name }}</strong>
              <span class="discussion-time">{{ $discussion->created_at->diffForHumans() }}</span>
            </div>
            @if ($discussion->book)
              <a href="{{ route('book-details', $discussion->book->slug) }}" class="book-tag"><i class="fa-solid fa-book"></i> {{ $discussion->book->title }}</a>
            @endif
          </div>
          <p class="discussion-text">{{ $discussion->body }}</p>
          <div class="discussion-footer">
            @auth
              <form method="POST" action="{{ route('discussions.like', $discussion) }}">
                @csrf
                <button type="submit" class="engage-btn like-btn @if (in_array($discussion->id, $likedDiscussionIds)) liked @endif">
                  <i class="fa-{{ in_array($discussion->id, $likedDiscussionIds) ? 'solid' : 'regular' }} fa-thumbs-up"></i>
                  <span class="count">{{ $discussion->liked_by_count }}</span>
                </button>
              </form>
            @else
              <span class="engage-btn"><i class="fa-regular fa-thumbs-up"></i> <span class="count">{{ $discussion->liked_by_count }}</span></span>
            @endauth
            <a href="{{ route('discussion-details', $discussion->id) }}" class="engage-btn"><i class="fa-regular fa-comment"></i> <span class="count">{{ $discussion->comments_count }}</span></a>
          </div>
        </article>
      @empty
        <p class="no-results">
          @if ($chatBook)
            لا توجد مناقشات حول "{{ $chatBook->title }}" بعد، كن أول من يبدأ النقاش!
          @else
            لا توجد مناقشات بعد، كن أول من يشارك رأيه!
          @endif
        </p>
      @endforelse

    </div>

    {{ $discussions->links() }}
  </div>

  <!-- ---- Sidebar ---- -->
  <aside class="community-sidebar">

    <div class="sidebar-card">
      <h2>نوادي القراءة النشطة</h2>

      @forelse ($topClubs as $topClub)
        @php $topClubBook = $topClub->currentBook(); @endphp
        <div class="club-item">
          <a href="{{ route('club-details', $topClub->slug) }}" class="club-item-link">
            <span class="club-icon"><i class="fa-solid fa-people-group"></i></span>
            <div class="club-info">
              <strong>{{ $topClub->name }}</strong>
              @if ($topClubBook)
                <p>يقرأون الآن: {{ $topClubBook->title }}</p>
              @endif
              <span class="club-members">{{ number_format($topClub->members_count) }} عضو</span>
            </div>
          </a>
        </div>
      @empty
        <p class="no-results">لا توجد نوادي بعد</p>
      @endforelse

      <a href="{{ route('reading-clubs') }}" class="view-all-link">عرض جميع النوادي <i class="fa-solid fa-arrow-left"></i></a>
    </div>

    <div class="sidebar-card">
      <h2>أفضل المساهمين</h2>

      @forelse ($topContributors as $index => $contributor)
        <div class="contributor-item">
          <span class="rank @if ($index === 0) gold @elseif ($index === 1) silver @elseif ($index === 2) bronze @endif">{{ $index + 1 }}</span>
          @if ($contributor->avatar)
            <img src="{{ $contributor->avatar }}" width="64" height="64" loading="lazy" decoding="async" alt="{{ $contributor->name }}">
          @else
            <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
          @endif
          <div class="contributor-info"><strong>{{ $contributor->name }}</strong><span>{{ number_format($contributor->points) }} نقطة</span></div>
        </div>
      @empty
        <p class="no-results">لا يوجد مساهمون بعد</p>
      @endforelse
    </div>

    @if ($trendingTags)
      <div class="sidebar-card">
        <h2>مواضيع رائجة</h2>
        <div class="trend-tags">
          @foreach ($trendingTags as $trendingTag)
            <a href="{{ route('community', ['tag' => $trendingTag]) }}">#{{ $trendingTag }}</a>
          @endforeach
        </div>
      </div>
    @endif

  </aside>

</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/community.js') }}"></script>
@endpush
