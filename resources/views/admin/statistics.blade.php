@extends('admin.layouts.admin')

@section('title', 'الإحصائيات - مكتبتي')

@php
  $pageTitle = 'الإحصائيات';
  $breadcrumb = [['label' => 'الإحصائيات', 'url' => null]];
  $maxSignups = max(array_column($monthlySignups, 'count')) ?: 1;
  $maxCategoryBooks = optional($topCategories->first())->books_count ?: 1;
  $maxWriterFollowers = optional($topWriters->first())->followers_count ?: 1;
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>الإحصائيات</h1>
    <p>نظرة تحليلية على نشاط المنصة ومحتواها</p>
  </div>
</div>

<div class="admin-stats-grid">
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-gold"><i class="fa-solid fa-star"></i></span>
    <div>
      <strong>{{ number_format($averageRating, 1) }}</strong>
      <span>متوسط التقييم العام</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-teal"><i class="fa-solid fa-comment-dots"></i></span>
    <div>
      <strong>{{ number_format($reviewsCount) }}</strong>
      <span>إجمالي التقييمات</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-navy"><i class="fa-solid fa-user-plus"></i></span>
    <div>
      <strong>{{ number_format($followsCount) }}</strong>
      <span>إجمالي متابعات المؤلفين</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-rose"><i class="fa-solid fa-users"></i></span>
    <div>
      <strong>{{ number_format($newUsersThisMonth) }}</strong>
      <span>مستخدم جديد هذا الشهر</span>
    </div>
  </div>
</div>

<div class="admin-panel" style="margin-bottom: 22px;">
  <div class="admin-panel-head">
    <h3>المستخدمون الجدد شهريًا</h3>
    <span class="admin-breadcrumb">آخر 6 أشهر</span>
  </div>

  <div class="admin-trend-chart">
    @foreach ($monthlySignups as $month)
      <div class="admin-trend-bar-wrap">
        <span class="admin-trend-value">{{ $month['count'] }}</span>
        <div class="admin-trend-bar" style="height: {{ ($month['count'] / $maxSignups) * 100 }}%" title="{{ $month['label'] }}: {{ $month['count'] }}"></div>
        <span class="admin-trend-label">{{ $month['label'] }}</span>
      </div>
    @endforeach
  </div>
</div>

<div class="admin-stats-grid" style="grid-template-columns: repeat(2, 1fr);">
  <div class="admin-panel">
    <div class="admin-panel-head">
      <h3>الأكثر تصنيفًا بالكتب</h3>
      <a href="{{ route('admin.categories.index') }}" class="admin-breadcrumb">كل التصنيفات</a>
    </div>

    @if ($topCategories->isEmpty())
      <p class="admin-bar-empty">لا توجد تصنيفات بعد</p>
    @else
      <div class="admin-bar-list">
        @foreach ($topCategories as $category)
          <div class="admin-bar-row">
            <span class="admin-bar-label">{{ $category->name }}</span>
            <div class="admin-bar-track">
              <div class="fill" style="width: {{ ($category->books_count / $maxCategoryBooks) * 100 }}%" title="{{ $category->books_count }} كتاب"></div>
            </div>
            <span class="admin-bar-value">{{ number_format($category->books_count) }}</span>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <div class="admin-panel">
    <div class="admin-panel-head">
      <h3>الأكثر متابعة من المؤلفين</h3>
      <a href="{{ route('admin.writers.index') }}" class="admin-breadcrumb">كل المؤلفين</a>
    </div>

    @if ($topWriters->isEmpty())
      <p class="admin-bar-empty">لا يوجد مؤلفون بعد</p>
    @else
      <div class="admin-bar-list">
        @foreach ($topWriters as $writer)
          <div class="admin-bar-row">
            <span class="admin-bar-label">{{ $writer->name }}</span>
            <div class="admin-bar-track">
              <div class="fill" style="width: {{ ($writer->followers_count / $maxWriterFollowers) * 100 }}%" title="{{ number_format($writer->followers_count) }} متابع"></div>
            </div>
            <span class="admin-bar-value">{{ number_format($writer->followers_count) }}</span>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</div>

<div class="admin-panel" style="margin-top: 22px;">
  <div class="admin-panel-head">
    <h3>الأكثر تحميلاً من الكتب</h3>
    <a href="{{ route('admin.books.index') }}" class="admin-breadcrumb">كل الكتب</a>
  </div>

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>الكتاب</th>
          <th>التصنيف</th>
          <th>التحميلات</th>
          <th>التقييم</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($topBooks as $book)
          <tr>
            <td>
              <div class="admin-book-cell">
                @if ($book->cover_image)
                  <img class="admin-book-cover" src="{{ $book->cover_image_sm_url }}" alt="{{ $book->title }}">
                @else
                  <span class="admin-book-cover placeholder"><i class="fa-solid fa-book"></i></span>
                @endif
                <div><strong>{{ $book->title }}</strong><span>{{ $book->writer?->name ?? 'بدون مؤلف' }}</span></div>
              </div>
            </td>
            <td>{{ $book->category?->name ?? '—' }}</td>
            <td>{{ number_format($book->downloads_count) }}</td>
            <td><i class="fa-solid fa-star" style="color:var(--star)"></i> {{ number_format($book->rating_average, 1) }}</td>
          </tr>
        @empty
          <tr class="admin-empty-row">
            <td colspan="4">لا توجد كتب بعد</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
