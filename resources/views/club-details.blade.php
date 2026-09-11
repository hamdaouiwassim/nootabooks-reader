@extends('layouts.app')

@section('title', $club->name.' - نوته بوك')
@section('robots', 'noindex, follow')

@push('styles')
<link rel="stylesheet" href="{{ asset_min('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/book-details.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/community.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/club-details.css') }}">
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <a href="{{ route('community') }}">المجتمع</a>
    <i class="fa-solid fa-chevron-left"></i>
    <a href="{{ route('reading-clubs') }}">نوادي القراءة</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>{{ $club->name }}</span>
  </nav>
</div>

<!-- ===================== CLUB HERO ===================== -->
<section class="section club-hero">
  <div class="club-hero-icon"><i class="fa-solid fa-people-group"></i></div>
  <div class="club-hero-info">
    @if ($club->category)
      <span class="genre-chip">{{ $club->category }}</span>
    @endif
    <h1 class="club-hero-name">{{ $club->name }}</h1>
    <p class="club-hero-desc">{{ $club->description }}</p>

    <div class="club-hero-meta">
      <div class="meta-item"><i class="fa-solid fa-users"></i><span>الأعضاء</span><strong>{{ number_format($club->members_count) }}</strong></div>
      <div class="meta-item"><i class="fa-solid fa-comments"></i><span>المناقشات</span><strong>{{ number_format($club->discussions_count) }}</strong></div>
      <div class="meta-item"><i class="fa-solid fa-book"></i><span>كتب أُنجزت</span><strong>{{ number_format($pastBooks->count()) }}</strong></div>
      <div class="meta-item"><i class="fa-solid fa-calendar-days"></i><span>تأسس في</span><strong>{{ $club->created_at->format('Y') }}</strong></div>
    </div>

    <div class="club-hero-actions">
      @auth
        @if ($isOwner)
          <span class="btn btn-outline"><i class="fa-solid fa-crown"></i> أنت منشئ هذا النادي</span>
        @else
          <form method="POST" action="{{ route('clubs.join', $club) }}">
            @csrf
            <button type="submit" class="btn btn-teal join-club-btn @if ($isMember) joined @endif">
              @if ($isMember)
                <i class="fa-solid fa-check"></i> منضم للنادي
              @else
                <i class="fa-solid fa-user-plus"></i> انضمام للنادي
              @endif
            </button>
          </form>
        @endif
      @else
        <a href="{{ route('login') }}" class="btn btn-teal"><i class="fa-solid fa-user-plus"></i> سجل الدخول للانضمام</a>
      @endauth
      <button class="icon-btn-outline" aria-label="share"><i class="fa-solid fa-share-nodes"></i></button>
    </div>
  </div>
</section>

<!-- ===================== TABS ===================== -->
<section class="section tabs-section">
  <div class="tabs-nav">
    <button class="tab-btn active" data-tab="reading">يقرأون الآن</button>
    <button class="tab-btn" data-tab="discussions">المناقشات</button>
    <button class="tab-btn" data-tab="members">الأعضاء</button>
    <button class="tab-btn" data-tab="about">نبذة وقوانين</button>
  </div>

  <!-- ---- Currently Reading ---- -->
  <div class="tab-panel active" id="tab-reading">
    @if ($currentBook)
      <div class="current-book-card">
        <a href="{{ route('book-details', $currentBook->slug) }}" class="book-cover @if ($currentBook->cover_image) cover-photo @else cover-{{ ($currentBook->id % 5) + 1 }} @endif" @if ($currentBook->cover_image) style="padding:0;" @endif>
          @if ($currentBook->cover_image)
            <img src="{{ $currentBook->cover_image_sm_url }}" alt="{{ $currentBook->title }}" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;">
          @else
            <span class="cover-badge">B</span>
            <span class="cover-title">{{ $currentBook->title }}</span>
          @endif
        </a>
        <div class="current-book-info">
          <span class="genre-chip">كتاب النادي الحالي</span>
          <h3>{{ $currentBook->title }}</h3>
          <p class="author">{{ $currentBook->writer?->name }}</p>
          <p class="schedule-text"><i class="fa-solid fa-calendar-check"></i> بدأ النادي قراءته {{ \Carbon\Carbon::parse($currentBook->pivot->started_at)->diffForHumans() }}</p>
          <a href="{{ route('book-details', $currentBook->slug) }}" class="btn btn-outline">عرض الكتاب</a>
        </div>
      </div>
    @else
      <p class="no-results">لا يقرأ النادي أي كتاب حاليًا.</p>
    @endif

    @if ($pastBooks->isNotEmpty())
      <h3 class="mini-heading">الكتب السابقة</h3>
      <div class="past-books-row">
        @foreach ($pastBooks as $pastBook)
          <div class="mini-book">
            <a href="{{ route('book-details', $pastBook->slug) }}" class="book-cover mini @if ($pastBook->cover_image) cover-photo @else cover-{{ ($pastBook->id % 5) + 1 }} @endif" @if ($pastBook->cover_image) style="padding:0;" @endif>
              @if ($pastBook->cover_image)
                <img src="{{ $pastBook->cover_image_sm_url }}" alt="{{ $pastBook->title }}" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;">
              @else
                <span class="cover-title">{{ $pastBook->title }}</span>
              @endif
            </a>
            <p>{{ $pastBook->title }}</p>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <!-- ---- Discussions ---- -->
  <div class="tab-panel" id="tab-discussions">
    @auth
      <form method="POST" action="{{ route('discussions.store') }}" class="new-post-box" style="margin-bottom:20px;">
        @csrf
        <input type="hidden" name="club" value="{{ $club->slug }}">
        <img src="{{ auth()->user()->avatar ?? 'https://i.pravatar.cc/64?img=13' }}" alt="{{ auth()->user()->name }}">
        <input type="text" name="body" maxlength="2000" required placeholder="شارك في نقاش النادي ...">
        <button type="submit" class="btn btn-gold small">نشر</button>
      </form>
    @endauth

    <div class="discussion-feed">
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
            <a href="{{ route('discussion-details', $discussion->id) }}" class="engage-btn"><i class="fa-regular fa-comment"></i> <span class="count">{{ $discussion->comments_count }}</span></a>
          </div>
        </article>
      @empty
        <p class="no-results">لا توجد مناقشات في هذا النادي بعد.</p>
      @endforelse
    </div>
  </div>

  <!-- ---- Members ---- -->
  <div class="tab-panel" id="tab-members">
    <div class="members-grid">
      @foreach ($members as $member)
        <div class="member-card">
          <img src="{{ $member->avatar ?? 'https://i.pravatar.cc/100?img=' . (($member->id % 70) + 1) }}" alt="{{ $member->name }}">
          <strong>{{ $member->name }}</strong>
          <span class="member-role @if ($member->pivot->role === 'owner') admin @endif">{{ $member->pivot->role === 'owner' ? 'مشرف النادي' : 'عضو' }}</span>
        </div>
      @endforeach
    </div>
    @if ($club->members_count > $members->count())
      <p class="members-more">و {{ number_format($club->members_count - $members->count()) }} عضوًا آخر</p>
    @endif
  </div>

  <!-- ---- About ---- -->
  <div class="tab-panel" id="tab-about">
    <h2>عن النادي</h2>
    <p>{{ $club->description }}</p>
    @if ($club->rules)
      <h3 class="mini-heading">قوانين النادي</h3>
      <ul class="club-rules">
        @foreach (explode("\n", $club->rules) as $rule)
          @if (trim($rule) !== '')
            <li><i class="fa-solid fa-check"></i> {{ trim($rule) }}</li>
          @endif
        @endforeach
      </ul>
    @endif
  </div>
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/club-details.js') }}"></script>
@endpush
