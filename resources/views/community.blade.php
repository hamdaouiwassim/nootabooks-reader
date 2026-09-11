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
      <div><strong>85</strong><span>نادي قراءة</span></div>
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
    @endif

    @auth
      <form method="POST" action="{{ route('discussions.store') }}" class="new-post-box">
        @csrf
        @if ($chatBook)
          <input type="hidden" name="book" value="{{ $chatBook->slug }}">
        @endif
        <img src="{{ auth()->user()->avatar ?? 'https://i.pravatar.cc/64?img=13' }}" alt="{{ auth()->user()->name }}">
        <input type="text" name="body" maxlength="2000" required
          placeholder="{{ $chatBook ? 'شارك رأيك حول "'.$chatBook->title.'" ...' : 'شارك رأيك أو ابدأ نقاشًا جديدًا ...' }}">
        <button type="submit" class="btn btn-gold small">نشر</button>
      </form>
    @else
      <div class="new-post-box">
        <img src="https://i.pravatar.cc/64?img=13" alt="زائر">
        <span>سجل الدخول للمشاركة في النقاش</span>
        <a href="{{ route('login') }}" class="btn btn-gold small">تسجيل الدخول</a>
      </div>
    @endauth

    <div class="discussion-feed" id="discussionFeed">

      @forelse ($discussions as $discussion)
        <article class="discussion-card">
          <div class="discussion-head">
            <img src="{{ $discussion->user->avatar ?? 'https://i.pravatar.cc/64?img=' . (($discussion->user_id % 70) + 1) }}" alt="{{ $discussion->user->name }}">
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
            <button class="engage-btn"><i class="fa-solid fa-share-nodes"></i> مشاركة</button>
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
      <h3>نوادي القراءة النشطة</h3>

      <div class="club-item">
        <a href="{{ route('club-details', 'arabic-literature') }}" class="club-item-link">
          <span class="club-icon"><i class="fa-solid fa-people-group"></i></span>
          <div class="club-info">
            <strong>أدب عربي معاصر</strong>
            <p>يقرأون الآن: تراب الماس</p>
            <span class="club-members">1,240 عضو</span>
          </div>
        </a>
        <button class="btn btn-outline small join-btn">انضمام</button>
      </div>

      <div class="club-item">
        <a href="{{ route('club-details', 'sci-fi-lovers') }}" class="club-item-link">
          <span class="club-icon"><i class="fa-solid fa-people-group"></i></span>
          <div class="club-info">
            <strong>عشاق الخيال العلمي</strong>
            <p>يقرأون الآن: 1984</p>
            <span class="club-members">890 عضو</span>
          </div>
        </a>
        <button class="btn btn-outline small join-btn">انضمام</button>
      </div>

      <div class="club-item">
        <a href="{{ route('club-details', 'translated-novels') }}" class="club-item-link">
          <span class="club-icon"><i class="fa-solid fa-people-group"></i></span>
          <div class="club-info">
            <strong>روايات مترجمة</strong>
            <p>يقرأون الآن: الخيميائي</p>
            <span class="club-members">670 عضو</span>
          </div>
        </a>
        <button class="btn btn-outline small join-btn">انضمام</button>
      </div>

      <a href="{{ route('reading-clubs') }}" class="view-all-link">عرض جميع النوادي <i class="fa-solid fa-arrow-left"></i></a>
    </div>

    <div class="sidebar-card">
      <h3>أفضل المساهمين</h3>

      <div class="contributor-item">
        <span class="rank gold">1</span>
        <img src="https://i.pravatar.cc/64?img=32" alt="سارة محمود">
        <div class="contributor-info"><strong>سارة محمود</strong><span>3,450 نقطة</span></div>
      </div>
      <div class="contributor-item">
        <span class="rank silver">2</span>
        <img src="https://i.pravatar.cc/64?img=45" alt="محمد العتيبي">
        <div class="contributor-info"><strong>محمد العتيبي</strong><span>2,980 نقطة</span></div>
      </div>
      <div class="contributor-item">
        <span class="rank bronze">3</span>
        <img src="https://i.pravatar.cc/64?img=21" alt="ليلى حسن">
        <div class="contributor-info"><strong>ليلى حسن</strong><span>2,410 نقطة</span></div>
      </div>
      <div class="contributor-item">
        <span class="rank">4</span>
        <img src="https://i.pravatar.cc/64?img=68" alt="عمر خالد">
        <div class="contributor-info"><strong>عمر خالد</strong><span>1,875 نقطة</span></div>
      </div>
    </div>

    <div class="sidebar-card">
      <h3>مواضيع رائجة</h3>
      <div class="trend-tags">
        <a href="#">#الفيل_الأزرق</a>
        <a href="#">#أدب_عربي</a>
        <a href="#">#روايات_2026</a>
        <a href="#">#نادي_القراءة</a>
        <a href="#">#عزازيل</a>
        <a href="#">#1984</a>
      </div>
    </div>

  </aside>

</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/community.js') }}"></script>
@endpush
