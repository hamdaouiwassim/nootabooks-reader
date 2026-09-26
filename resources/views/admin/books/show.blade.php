@extends('admin.layouts.admin')

@section('title', 'عرض الكتاب - مكتبتي')

@php
  $pageTitle = 'عرض الكتاب';
  $breadcrumb = [
    ['label' => 'إدارة الكتب', 'url' => route('admin.books.index')],
    ['label' => $book->title, 'url' => null],
  ];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>{{ $book->title }}</h1>
    <p>تفاصيل الكتاب وإحصائياته</p>
  </div>
  <div class="admin-row-actions" style="gap:10px;">
    <a href="{{ route('admin.books.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
    <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-outline"><i class="fa-solid fa-pen"></i> تعديل</a>
    @if ($book->status === 'published')
      <a href="{{ route('book-details', $book->slug) }}" target="_blank" rel="noopener" class="btn btn-gold"><i class="fa-regular fa-eye"></i> عرض على الموقع</a>
    @endif
  </div>
</div>

<div class="admin-form-section">
  <div class="admin-book-cell" style="gap:18px; margin-bottom:20px; align-items:flex-start;">
    @if ($book->cover_image)
      <img class="admin-book-cover" src="{{ $book->cover_image_sm_url }}" width="90" height="135" style="width:90px; height:135px;" loading="lazy" decoding="async" alt="{{ $book->cover_alt }}">
    @else
      <span class="admin-book-cover placeholder" style="width:90px; height:135px; font-size:22px;"><i class="fa-solid fa-book"></i></span>
    @endif
    <div>
      <strong style="font-size:17px;">{{ $book->title }}</strong>
      <span>{{ $book->writer?->name ?? 'بدون مؤلف' }}</span>
      <div class="admin-row-actions" style="justify-content:flex-start; gap:6px; margin-top:10px; flex-wrap:wrap;">
        @if ($book->status === 'published')
          <span class="status-badge published">منشور</span>
        @else
          <span class="status-badge draft">مخفي</span>
        @endif
        @if ($book->is_coming_soon)
          <span class="status-badge coming-soon">قريبًا</span>
        @endif
        @if ($book->reading_disabled)
          <span class="status-badge draft">القراءة معطّلة</span>
        @endif
        @if ($book->download_disabled)
          <span class="status-badge draft">التحميل معطّل</span>
        @endif
        @if ($book->copyright_blocked)
          <span class="status-badge copyright"><i class="fa-solid fa-scale-balanced"></i> محجوب لحقوق النشر</span>
        @endif
      </div>
    </div>
  </div>

  <div class="admin-form-grid">
    <div class="admin-form-field">
      <label>التصنيف الرئيسي</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ $book->category?->name ?? '—' }}</p>
    </div>
    <div class="admin-form-field">
      <label>كل التصنيفات</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ $book->categories->pluck('name')->implode('، ') ?: '—' }}</p>
    </div>
    <div class="admin-form-field">
      <label>سنة النشر</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ $book->published_year ?? '—' }}</p>
    </div>
    <div class="admin-form-field">
      <label>تاريخ الإضافة للمنصة</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ $book->created_at->format('Y-m-d H:i') }} ({{ $book->created_at->diffForHumans() }})</p>
    </div>
    <div class="admin-form-field">
      <label>اللغة</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ $book->language }}</p>
    </div>
    @if ($book->translator_name)
      <div class="admin-form-field">
        <label>اسم المترجم</label>
        <p style="font-size:14px; color:var(--text-dark);">{{ $book->translator_name }}</p>
      </div>
    @endif
    <div class="admin-form-field">
      <label>عدد الصفحات</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ $book->pages_count ? number_format($book->pages_count) : '—' }}</p>
    </div>
    <div class="admin-form-field">
      <label>حجم الملف</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ format_file_size($book->file_size_mb) ?? '—' }}</p>
    </div>
    @if ($book->series)
      <div class="admin-form-field">
        <label>السلسلة</label>
        <p style="font-size:14px; color:var(--text-dark);">{{ $book->series->name }} @if ($book->series_order)(الجزء {{ $book->series_order }})@endif</p>
      </div>
    @endif
    @if ($book->tags)
      <div class="admin-form-field full">
        <label>الوسوم</label>
        <p style="font-size:14px; color:var(--text-dark);">{{ implode('، ', $book->tags) }}</p>
      </div>
    @endif
    @if ($book->description_short)
      <div class="admin-form-field full">
        <label>نبذة مختصرة</label>
        <p style="font-size:14px; color:var(--text-dark); line-height:1.9;">{{ $book->description_short }}</p>
      </div>
    @endif
    @if ($book->description)
      <div class="admin-form-field full">
        <label>وصف الكتاب</label>
        <p style="font-size:14px; color:var(--text-dark); line-height:1.9; white-space:pre-wrap;">{{ $book->description }}</p>
      </div>
    @endif
  </div>
</div>

<div class="admin-stats-grid" style="margin-top:20px;">
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-navy"><i class="fa-solid fa-cloud-arrow-down"></i></span>
    <div>
      <strong>{{ number_format($book->downloads_count) }}</strong>
      <span>إجمالي التحميلات (كل الأوقات)</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-gold"><i class="fa-solid fa-star"></i></span>
    <div>
      <strong>{{ number_format($book->rating_average, 1) }}</strong>
      <span>متوسط التقييم</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-teal"><i class="fa-solid fa-comment-dots"></i></span>
    <div>
      <strong>{{ number_format($book->rating_count) }}</strong>
      <span>عدد التقييمات</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-rose"><i class="fa-solid fa-circle-question"></i></span>
    <div>
      <strong>{{ number_format($book->faqs->count()) }}</strong>
      <span>سؤال شائع</span>
    </div>
  </div>
</div>

<div class="admin-form-section" style="margin-top:20px;">
  <h3>إعدادات السيو</h3>
  <div class="admin-form-grid">
    <div class="admin-form-field full">
      <label>عنوان السيو</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ $book->seo_title ?: $book->resolved_seo_title }}</p>
    </div>
    <div class="admin-form-field full">
      <label>وصف السيو</label>
      <p style="font-size:14px; color:var(--text-dark); line-height:1.9;">{{ $book->seo_description ?: $book->resolved_seo_description }}</p>
    </div>
  </div>
</div>

<div class="admin-form-section" style="margin-top:20px;">
  <h3>آخر التحميلات</h3>
  @forelse ($recentDownloads as $download)
    <div class="admin-toggle-row">
      <div>
        <strong>{{ $download->user?->name ?? 'زائر' }}</strong>
        <p>{{ $download->created_at->diffForHumans() }}</p>
      </div>
    </div>
  @empty
    <p style="color:var(--text-gray); font-size:14px;">لا يوجد تحميلات بعد.</p>
  @endforelse
  <a href="{{ route('admin.books.stats', $book) }}" class="btn btn-outline" style="margin-top:16px; width:100%; justify-content:center;"><i class="fa-solid fa-chart-line"></i> عرض إحصائيات التحميل اليومية</a>
</div>

@endsection
