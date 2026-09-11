@extends('layouts.app')

@section('title', 'مناقشة - نوته بوك')
@section('robots', 'noindex, follow')

@push('styles')
<link rel="stylesheet" href="{{ asset_min('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/book-details.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/community.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/discussion-details.css') }}">
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
    <span>مناقشة</span>
  </nav>
</div>

<!-- ===================== SUBJECT LAYOUT ===================== -->
<section class="section subject-layout">

  <!-- ---- Main Thread ---- -->
  <div class="subject-main">

    <article class="subject-post">
      <div class="discussion-head">
        @if ($discussion->user->avatar)
          <img src="{{ $discussion->user->avatar }}" width="64" height="64" decoding="async" alt="{{ $discussion->user->name }}">
        @else
          <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
        @endif
        <div>
          <strong>{{ $discussion->user->name }}</strong>
          <span class="discussion-time">{{ $discussion->created_at->diffForHumans() }} · في <a href="{{ route('community') }}">مجتمع القراء</a></span>
        </div>
        @if ($discussion->book)
          <a href="{{ route('book-details', $discussion->book->slug) }}" class="book-tag"><i class="fa-solid fa-book"></i> {{ $discussion->book->title }}</a>
        @elseif ($discussion->club)
          <a href="{{ route('club-details', $discussion->club->slug) }}" class="book-tag"><i class="fa-solid fa-people-group"></i> {{ $discussion->club->name }}</a>
        @endif
      </div>

      <p class="discussion-text">{{ $discussion->body }}</p>

      <div class="subject-post-footer">
        @auth
          <form method="POST" action="{{ route('discussions.like', $discussion) }}">
            @csrf
            <button type="submit" class="engage-btn like-btn @if (in_array($discussion->id, $likedDiscussionIds)) liked @endif">
              <i class="fa-{{ in_array($discussion->id, $likedDiscussionIds) ? 'solid' : 'regular' }} fa-thumbs-up"></i>
              <span class="count">{{ $discussion->liked_by_count }}</span> إعجاب
            </button>
          </form>
        @else
          <span class="engage-btn"><i class="fa-regular fa-thumbs-up"></i> <span class="count">{{ $discussion->liked_by_count }}</span> إعجاب</span>
        @endauth
        <span class="engage-btn"><i class="fa-regular fa-comment"></i> <span class="count">{{ $comments->count() + $comments->sum(fn ($c) => $c->replies->count()) }}</span> تعليق</span>
      </div>
    </article>

    <div class="comments-section" id="comments">
      <div class="comments-head">
        <h2>{{ $comments->count() + $comments->sum(fn ($c) => $c->replies->count()) }} تعليق</h2>
      </div>

      @auth
        <form method="POST" action="{{ route('discussions.comments.store', $discussion) }}" class="comment-form">
          @csrf
          @if (auth()->user()->avatar)
            <img src="{{ auth()->user()->avatar }}" width="64" height="64" decoding="async" alt="{{ auth()->user()->name }}">
          @else
            <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
          @endif
          <input type="text" name="body" maxlength="2000" required placeholder="أضف تعليقك ...">
          <button type="submit" class="btn btn-gold small">إرسال</button>
        </form>
      @else
        <p class="no-results"><a href="{{ route('login') }}">سجل الدخول</a> للمشاركة في النقاش</p>
      @endauth

      <div class="comment-list" id="commentList">
        @forelse ($comments as $comment)
          <article class="comment-item">
            @if ($comment->user->avatar)
              <img src="{{ $comment->user->avatar }}" width="64" height="64" loading="lazy" decoding="async" alt="{{ $comment->user->name }}">
            @else
              <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
            @endif
            <div class="comment-body">
              <div class="comment-bubble">
                <strong>{{ $comment->user->name }}</strong>
                <p>{{ $comment->body }}</p>
              </div>
              <div class="comment-actions">
                <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                @auth
                  <form method="POST" action="{{ route('comments.like', $comment) }}">
                    @csrf
                    <button type="submit" class="mini-like-btn @if (in_array($comment->id, $likedCommentIds)) liked @endif">
                      <i class="fa-{{ in_array($comment->id, $likedCommentIds) ? 'solid' : 'regular' }} fa-thumbs-up"></i>
                      <span class="count">{{ $comment->liked_by_count }}</span>
                    </button>
                  </form>
                  <button type="button" class="reply-btn" data-reply-target="reply-form-{{ $comment->id }}">رد</button>
                @else
                  <span class="mini-like-btn"><i class="fa-regular fa-thumbs-up"></i> <span class="count">{{ $comment->liked_by_count }}</span></span>
                @endauth
              </div>

              @auth
                <form method="POST" action="{{ route('discussions.comments.store', $discussion) }}" class="inline-reply-form comment-form" id="reply-form-{{ $comment->id }}" hidden>
                  @csrf
                  <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                  @if (auth()->user()->avatar)
                    <img src="{{ auth()->user()->avatar }}" width="64" height="64" loading="lazy" decoding="async" alt="{{ auth()->user()->name }}">
                  @else
                    <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
                  @endif
                  <input type="text" name="body" maxlength="2000" required placeholder="اكتب ردًا ...">
                  <button type="submit" class="btn btn-gold small">رد</button>
                </form>
              @endauth

              @foreach ($comment->replies as $reply)
                <div class="comment-item reply">
                  @if ($reply->user->avatar)
                    <img src="{{ $reply->user->avatar }}" width="64" height="64" loading="lazy" decoding="async" alt="{{ $reply->user->name }}">
                  @else
                    <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
                  @endif
                  <div class="comment-body">
                    <div class="comment-bubble">
                      <strong>{{ $reply->user->name }}</strong>
                      <p>{{ $reply->body }}</p>
                    </div>
                    <div class="comment-actions">
                      <span class="comment-time">{{ $reply->created_at->diffForHumans() }}</span>
                      @auth
                        <form method="POST" action="{{ route('comments.like', $reply) }}">
                          @csrf
                          <button type="submit" class="mini-like-btn @if (in_array($reply->id, $likedCommentIds)) liked @endif">
                            <i class="fa-{{ in_array($reply->id, $likedCommentIds) ? 'solid' : 'regular' }} fa-thumbs-up"></i>
                            <span class="count">{{ $reply->liked_by_count }}</span>
                          </button>
                        </form>
                      @else
                        <span class="mini-like-btn"><i class="fa-regular fa-thumbs-up"></i> <span class="count">{{ $reply->liked_by_count }}</span></span>
                      @endauth
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </article>
        @empty
          <p class="no-results">لا توجد تعليقات بعد، كن أول من يعلّق!</p>
        @endforelse
      </div>
    </div>
  </div>

  <!-- ---- Sidebar ---- -->
  <aside class="subject-sidebar">

    @if ($discussion->book)
      <div class="sidebar-card book-mini-card">
        <h3>الكتاب المُناقَش</h3>
        <a href="{{ route('book-details', $discussion->book->slug) }}" class="book-mini-link">
          @if ($discussion->book->cover_image)
            <img src="{{ $discussion->book->cover_image_sm_url }}" width="300" height="450" loading="lazy" decoding="async" alt="{{ $discussion->book->title }}" class="book-cover mini" style="padding:0;width:70px;height:90px;object-fit:cover;">
          @else
            <span class="book-cover cover-{{ ($discussion->book->id % 5) + 1 }} mini"><span class="cover-title">{{ $discussion->book->title }}</span></span>
          @endif
          <div>
            <strong>{{ $discussion->book->title }}</strong>
            <p>{{ $discussion->book->writer?->name }}</p>
            <span class="rating"><i class="fa-solid fa-star"></i> {{ number_format($discussion->book->rating_average, 1) }}</span>
          </div>
        </a>
        <a href="{{ route('book-details', $discussion->book->slug) }}" class="btn btn-outline">عرض صفحة الكتاب</a>
      </div>
    @endif

    <div class="sidebar-card">
      <h3>كاتب المناقشة</h3>
      <div class="contributor-item">
        @if ($discussion->user->avatar)
          <img src="{{ $discussion->user->avatar }}" width="64" height="64" loading="lazy" decoding="async" alt="{{ $discussion->user->name }}">
        @else
          <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
        @endif
        <div class="contributor-info"><strong>{{ $discussion->user->name }}</strong><span>{{ number_format($discussion->user->discussions()->count()) }} منشور · {{ number_format($discussion->user->points) }} نقطة</span></div>
      </div>
    </div>

    @if ($relatedDiscussions->isNotEmpty())
      <div class="sidebar-card">
        <h3>مناقشات مشابهة</h3>
        @foreach ($relatedDiscussions as $related)
          <a href="{{ route('discussion-details', $related->id) }}" class="related-topic">
            <p>{{ \Illuminate\Support\Str::limit($related->body, 70) }}</p>
            <span><i class="fa-regular fa-comment"></i> {{ number_format($related->comments_count) }} تعليق</span>
          </a>
        @endforeach
      </div>
    @endif

  </aside>

</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/discussion-details.js') }}"></script>
@endpush
