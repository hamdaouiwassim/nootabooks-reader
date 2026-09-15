@extends('admin.layouts.admin')

@section('title', 'إحصائيات: '.$book->title.' - مكتبتي')

@php
  $pageTitle = 'إحصائيات الكتاب';
  $breadcrumb = [
    ['label' => 'إدارة الكتب', 'url' => route('admin.books.index')],
    ['label' => 'إحصائيات', 'url' => null],
  ];
  $maxDailyDownloads = max(array_column($dailyDownloads, 'count')) ?: 1;
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>إحصائيات: {{ $book->title }}</h1>
    <p>نشاط التحميل والتقييم لهذا الكتاب</p>
  </div>
  <div style="display:flex; gap:10px;">
    <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-outline"><i class="fa-solid fa-pen"></i> تعديل الكتاب</a>
    <a href="{{ route('admin.books.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
  </div>
</div>

<div class="admin-book-cell" style="margin-bottom:22px;">
  @if ($book->cover_image)
    <img class="admin-book-cover" src="{{ $book->cover_image_sm_url }}" width="300" height="450" loading="lazy" decoding="async" alt="{{ $book->title }}">
  @else
    <span class="admin-book-cover placeholder"><i class="fa-solid fa-book"></i></span>
  @endif
  <div>
    <strong>{{ $book->title }}</strong>
    <span>{{ $book->writer?->name ?? 'بدون مؤلف' }}</span>
  </div>
</div>

<div class="admin-stats-grid" style="grid-template-columns: repeat(3, 1fr);">
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-gold"><i class="fa-solid fa-cloud-arrow-down"></i></span>
    <div>
      <strong>{{ number_format($book->downloads_count) }}</strong>
      <span>إجمالي التحميلات</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-teal"><i class="fa-solid fa-star"></i></span>
    <div>
      <strong>{{ number_format($book->rating_average, 1) }}</strong>
      <span>متوسط التقييم</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-navy"><i class="fa-solid fa-comment-dots"></i></span>
    <div>
      <strong>{{ number_format($book->rating_count) }}</strong>
      <span>عدد التقييمات</span>
    </div>
  </div>
</div>

<div class="admin-panel" style="margin-top:22px;">
  <div class="admin-panel-head">
    <h3>التحميلات {{ $period === 'day' ? 'بالساعة' : 'يوميًا' }}</h3>
    <span class="admin-breadcrumb">{{ $periodLabel }}</span>
  </div>

  <form method="GET" action="{{ route('admin.books.stats', $book) }}" class="admin-toolbar" style="margin-bottom:18px;">
    <select name="period" class="admin-filter-select" id="statsPeriodSelect" onchange="document.getElementById('statsCustomFields').hidden = this.value !== 'custom'; if (this.value !== 'custom') this.form.submit();">
      <option value="day" @selected($period === 'day')>اليوم (كل ساعة)</option>
      <option value="7d" @selected($period === '7d')>آخر 7 أيام</option>
      <option value="1m" @selected($period === '1m')>الشهر الماضي</option>
      <option value="3m" @selected($period === '3m')>آخر 3 أشهر</option>
      <option value="custom" @selected($period === 'custom')>فترة مخصصة</option>
    </select>
    <div id="statsCustomFields" style="display:flex; gap:10px; align-items:center;" @if ($period !== 'custom') hidden @endif>
      <input type="date" name="from" class="admin-input" value="{{ $fromInput }}" style="width:auto;">
      <span>إلى</span>
      <input type="date" name="to" class="admin-input" value="{{ $toInput }}" style="width:auto;">
      <button type="submit" class="btn btn-outline">تطبيق</button>
    </div>
  </form>

  <div class="admin-trend-scroll">
    <div class="admin-trend-chart admin-trend-chart-dense">
      @foreach ($dailyDownloads as $day)
        <div class="admin-trend-bar-wrap">
          <div class="admin-trend-bar" style="height: {{ ($day['count'] / $maxDailyDownloads) * 100 }}%" title="{{ $day['fullLabel'] }}: {{ $day['count'] }} تحميل"></div>
          <span class="admin-trend-label">{{ $day['label'] }}</span>
        </div>
      @endforeach
    </div>
  </div>
</div>

@endsection
