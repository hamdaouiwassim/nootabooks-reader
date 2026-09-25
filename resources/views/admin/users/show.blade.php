@extends('admin.layouts.admin')

@section('title', 'عرض المستخدم - مكتبتي')

@php
  $pageTitle = 'عرض المستخدم';
  $breadcrumb = [
    ['label' => 'إدارة المستخدمين', 'url' => route('admin.users.index')],
    ['label' => $user->name, 'url' => null],
  ];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>{{ $user->name }}</h1>
    <p>تفاصيل حساب المستخدم ونشاطه على المنصة</p>
  </div>
  <a href="{{ route('admin.users.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<div class="admin-form-section">
  <div class="admin-book-cell" style="gap:18px; margin-bottom:20px;">
    @if ($user->avatar)
      <img style="border-radius:50%; width:72px; height:72px; object-fit:cover;" src="{{ asset($user->avatar) }}" width="72" height="72" loading="lazy" decoding="async" alt="{{ $user->name }}">
    @else
      <span class="admin-book-cover placeholder" style="border-radius:50%; width:72px; height:72px; font-size:24px;"><i class="fa-solid fa-feather"></i></span>
    @endif
    <div>
      <strong style="font-size:17px;">{{ $user->name }}</strong>
      <span>{{ $user->email }}</span>
    </div>
  </div>

  <div class="admin-form-grid">
    <div class="admin-form-field">
      <label>الحالة</label>
      <p style="font-size:14px; color:var(--text-dark);">
        <span class="status-badge {{ $user->is_public ? 'published' : 'draft' }}">{{ $user->is_public ? 'عام' : 'خاص' }}</span>
      </p>
    </div>
    <div class="admin-form-field">
      <label>تفعيل التحقق بخطوتين</label>
      <p style="font-size:14px; color:var(--text-dark);">
        <span class="status-badge {{ $user->two_factor_enabled ? 'published' : 'draft' }}">{{ $user->two_factor_enabled ? 'مفعّل' : 'غير مفعّل' }}</span>
      </p>
    </div>
    <div class="admin-form-field">
      <label>تأكيد البريد الإلكتروني</label>
      <p style="font-size:14px; color:var(--text-dark);">
        <span class="status-badge {{ $user->email_verified_at ? 'published' : 'coming-soon' }}">{{ $user->email_verified_at ? 'مؤكّد' : 'غير مؤكّد' }}</span>
      </p>
    </div>
    <div class="admin-form-field">
      <label>النقاط</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ number_format($user->points) }}</p>
    </div>
    <div class="admin-form-field">
      <label>الموقع</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ $user->location ?: 'غير محدد' }}</p>
    </div>
    <div class="admin-form-field">
      <label>تاريخ التسجيل</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ $user->created_at->format('Y-m-d H:i') }} ({{ $user->created_at->diffForHumans() }})</p>
    </div>
    <div class="admin-form-field">
      <label>آخر تسجيل دخول</label>
      <p style="font-size:14px; color:var(--text-dark);">
        @if ($user->last_login_at)
          {{ $user->last_login_at->format('Y-m-d H:i') }} ({{ $user->last_login_at->diffForHumans() }})
        @else
          لم يسجل الدخول بعد
        @endif
      </p>
    </div>
    @if ($user->bio)
      <div class="admin-form-field full">
        <label>نبذة تعريفية</label>
        <p style="font-size:14px; color:var(--text-dark); line-height:1.9; white-space:pre-wrap;">{{ $user->bio }}</p>
      </div>
    @endif
  </div>
</div>

<div class="admin-stats-grid" style="margin-top:20px;">
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-navy"><i class="fa-solid fa-cloud-arrow-down"></i></span>
    <div>
      <strong>{{ number_format($downloadsCount) }}</strong>
      <span>تحميل</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-gold"><i class="fa-solid fa-star"></i></span>
    <div>
      <strong>{{ number_format($reviewsCount) }}</strong>
      <span>تقييم</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-teal"><i class="fa-solid fa-comments"></i></span>
    <div>
      <strong>{{ number_format($discussionsCount) }}</strong>
      <span>مناقشة</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-rose"><i class="fa-solid fa-comment-dots"></i></span>
    <div>
      <strong>{{ number_format($commentsCount) }}</strong>
      <span>تعليق</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-navy"><i class="fa-solid fa-people-group"></i></span>
    <div>
      <strong>{{ number_format($clubs->count()) }}</strong>
      <span>نادي قراءة</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-gold"><i class="fa-solid fa-feather"></i></span>
    <div>
      <strong>{{ number_format($followedWritersCount) }}</strong>
      <span>مؤلف متابَع</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-rose"><i class="fa-solid fa-flag"></i></span>
    <div>
      <strong>{{ number_format($bookReportsCount) }}</strong>
      <span>بلاغ حقوق نشر</span>
    </div>
  </div>
</div>

<div class="admin-form-section" style="margin-top:20px;">
  <h3>آخر التحميلات</h3>
  @forelse ($recentDownloads as $download)
    <div class="admin-toggle-row">
      <div>
        <strong>{{ $download->book->title ?? 'كتاب محذوف' }}</strong>
        <p>{{ $download->created_at->diffForHumans() }}</p>
      </div>
      @if ($download->book)
        <a href="{{ route('admin.books.edit', $download->book) }}" class="btn btn-outline small">عرض الكتاب</a>
      @endif
    </div>
  @empty
    <p style="color:var(--text-gray); font-size:14px;">لا يوجد تحميلات بعد.</p>
  @endforelse
</div>

<div class="admin-form-section" style="margin-top:20px;">
  <h3>آخر التقييمات</h3>
  @forelse ($recentReviews as $review)
    <div class="admin-toggle-row">
      <div>
        <strong>{{ $review->book->title ?? 'كتاب محذوف' }}</strong>
        <p>
          @for ($i = 1; $i <= 5; $i++)
            <i class="fa-solid fa-star" style="color: {{ $i <= $review->rating ? 'var(--star)' : 'var(--border-light)' }}; font-size:12px;"></i>
          @endfor
          @if ($review->comment)
            — {{ \Illuminate\Support\Str::limit($review->comment, 80) }}
          @endif
        </p>
      </div>
      <span style="font-size:12px; color:var(--text-gray); white-space:nowrap;">{{ $review->created_at->diffForHumans() }}</span>
    </div>
  @empty
    <p style="color:var(--text-gray); font-size:14px;">لا يوجد تقييمات بعد.</p>
  @endforelse
</div>

<div class="admin-form-section" style="margin-top:20px;">
  <h3>آخر المناقشات</h3>
  @forelse ($recentDiscussions as $discussion)
    <div class="admin-toggle-row">
      <div>
        <strong>{{ \Illuminate\Support\Str::limit($discussion->body, 90) }}</strong>
        <p>
          @if ($discussion->book) في كتاب: {{ $discussion->book->title }} @endif
          @if ($discussion->club) — نادي: {{ $discussion->club->name }} @endif
        </p>
      </div>
      <span style="font-size:12px; color:var(--text-gray); white-space:nowrap;">{{ $discussion->created_at->diffForHumans() }}</span>
    </div>
  @empty
    <p style="color:var(--text-gray); font-size:14px;">لا يوجد مناقشات بعد.</p>
  @endforelse
</div>

<div class="admin-form-section" style="margin-top:20px;">
  <h3>نوادي القراءة</h3>
  @forelse ($clubs as $club)
    <div class="admin-toggle-row">
      <div>
        <strong>{{ $club->name }}</strong>
        <p>{{ $club->pivot->role === 'admin' ? 'مشرف' : 'عضو' }}</p>
      </div>
      <a href="{{ route('admin.clubs.edit', $club) }}" class="btn btn-outline small">عرض النادي</a>
    </div>
  @empty
    <p style="color:var(--text-gray); font-size:14px;">لم ينضم إلى أي نادي قراءة بعد.</p>
  @endforelse
</div>

<div class="admin-form-section" style="margin-top:20px;">
  <h3>إجراءات</h3>
  <div class="admin-toggle-row">
    <div>
      <strong>حذف الحساب</strong>
      <p>سيتم حذف حساب المستخدم بشكل نهائي، ولن تتمكن من التراجع عن هذا الإجراء.</p>
    </div>
    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" data-confirm-delete data-item-title="{{ $user->name }}">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-danger small"><i class="fa-solid fa-trash"></i> حذف المستخدم</button>
    </form>
  </div>
</div>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا المستخدم؟</h3>
    <p>سيتم حذف حساب المستخدم بشكل نهائي، ولن تتمكن من التراجع عن هذا الإجراء.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush
