@extends('layouts.app')

@section('title', 'مجتمع القراء والكتب والروايات | نوته بوك')
@section('meta_description', 'انضم إلى مجتمع القراء على نوته بوك، ناقش الكتب والروايات، شارك آراءك وتوصياتك وتواصل مع قراء يشاركونك شغف القراءة.')
@section('robots', $isIndexable ? 'index, follow' : 'noindex, follow')

@push('styles')
<link rel="preload" href="{{ asset_min('assets/css/writers.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/writers.css') }}"></noscript>
<link rel="preload" href="{{ asset_min('assets/css/my-library.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/my-library.css') }}"></noscript>
<link rel="preload" href="{{ asset_min('assets/css/community.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/community.css') }}"></noscript>
@endpush

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'الرئيسية', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'المجتمع', 'item' => route('community')],
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@if ($isIndexable && $discussions->isNotEmpty())
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => 'مناقشات مجتمع القراء',
    'itemListElement' => $discussions->getCollection()->values()
        ->filter(fn ($discussion) => $discussion->isIndexable())
        ->map(fn ($discussion, $index) => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'url' => route('discussion-details', $discussion->id),
            'name' => \Illuminate\Support\Str::limit($discussion->body, 80),
        ])->values()->all(),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endif
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb" aria-label="مسار التنقل">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span aria-current="page">المجتمع</span>
  </nav>
</div>

<!-- ===================== PAGE HEADING ===================== -->
<section class="section writers-hero">
  <h1>مجتمع القراء</h1>
  <p>انضم إلى مجتمع القراء، ناقش الكتب التي تحبها، شارك آراءك وتوصياتك، وتواصل مع قراء يشاركونك شغف القراءة.</p>
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
          <img src="{{ auth()->user()->avatar }}" width="64" height="64" loading="lazy" decoding="async" alt="{{ auth()->user()->name }}">
        @else
          <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
        @endif
        <label for="newDiscussionBody" class="sr-only">شارك رأيك أو ابدأ نقاشًا جديدًا</label>
        <input type="text" id="newDiscussionBody" name="body" maxlength="2000" required
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
        <div class="no-results">
          @if ($chatBook)
            <p>لا توجد مناقشات حول "{{ $chatBook->title }}" بعد.</p>
          @else
            <p>لا توجد مناقشات بعد.</p>
          @endif
          <p>كن أول من يبدأ النقاش — شارك رأيك حول كتاب قرأته وابدأ أول حوار في مجتمع نوته بوك.</p>
        </div>
      @endforelse

    </div>

    {{ $discussions->onEachSide(1)->links() }}
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
        <div class="no-results">
          <p>لا توجد نوادي قراءة بعد.</p>
          <p>كن أول من ينشئ نادي قراءة وشارك الآخرين رحلة قراءة كتابك المفضل.</p>
        </div>
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
        <div class="no-results">
          <p>لا يوجد مساهمون بعد.</p>
          <p>شارك في أول نقاش أو أنشئ نادي قراءة لتصبح من أوائل المساهمين في مجتمع نوته بوك.</p>
        </div>
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
<script src="{{ asset_min('assets/js/community.js') }}" defer></script>
@endpush
