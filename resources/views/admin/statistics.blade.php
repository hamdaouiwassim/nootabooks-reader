@extends('admin.layouts.admin')

@section('title', 'الإحصائيات - مكتبتي')

@php
  $pageTitle = 'الإحصائيات';
  $breadcrumb = [['label' => 'الإحصائيات', 'url' => null]];
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

  <div class="admin-chart-wrap">
    <canvas class="admin-chart-canvas"
      data-chart-labels="{{ json_encode(array_column($monthlySignups, 'label')) }}"
      data-chart-values="{{ json_encode(array_column($monthlySignups, 'count')) }}"
      data-chart-color="teal" data-chart-unit="مستخدم"></canvas>
  </div>
</div>

<div class="admin-panel" style="margin-bottom: 22px;">
  <div class="admin-panel-head">
    <h3>التحميلات يوميًا</h3>
    <span class="admin-breadcrumb">آخر 30 يومًا</span>
  </div>

  <div class="admin-trend-scroll">
    <div class="admin-chart-wrap admin-chart-wrap-dense" style="min-width: {{ count($dailyDownloads) * 22 }}px;">
      <canvas class="admin-chart-canvas"
        data-chart-labels="{{ json_encode(array_column($dailyDownloads, 'label')) }}"
        data-chart-values="{{ json_encode(array_column($dailyDownloads, 'count')) }}"
        data-chart-tooltips="{{ json_encode(array_column($dailyDownloads, 'fullLabel')) }}"
        data-chart-color="gold" data-chart-unit="تحميل"></canvas>
    </div>
  </div>
</div>

<div class="admin-panel" style="margin-bottom: 22px;">
  <div class="admin-panel-head">
    <h3>الكتب المضافة يوميًا</h3>
    <span class="admin-breadcrumb">آخر 30 يومًا</span>
  </div>

  <div class="admin-trend-scroll">
    <div class="admin-chart-wrap admin-chart-wrap-dense" style="min-width: {{ count($dailyBooksAdded) * 22 }}px;">
      <canvas class="admin-chart-canvas"
        data-chart-labels="{{ json_encode(array_column($dailyBooksAdded, 'label')) }}"
        data-chart-values="{{ json_encode(array_column($dailyBooksAdded, 'count')) }}"
        data-chart-tooltips="{{ json_encode(array_column($dailyBooksAdded, 'fullLabel')) }}"
        data-chart-color="navy" data-chart-unit="كتاب"></canvas>
    </div>
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
                  <img class="admin-book-cover" src="{{ $book->cover_image_sm_url }}" width="300" height="450" loading="lazy" decoding="async" alt="{{ $book->title }}">
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

<div class="admin-panel" style="margin-top: 22px;">
  <div class="admin-panel-head">
    <h3>الأكثر تحميلاً خلال آخر 48 ساعة</h3>
    <span class="admin-breadcrumb">أفضل 10 كتب</span>
  </div>

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>الكتاب</th>
          <th>التصنيف</th>
          <th>تحميلات آخر 48 ساعة</th>
          <th>التقييم</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($topDownloaded48h as $book)
          <tr>
            <td>
              <div class="admin-book-cell">
                @if ($book->cover_image)
                  <img class="admin-book-cover" src="{{ $book->cover_image_sm_url }}" width="300" height="450" loading="lazy" decoding="async" alt="{{ $book->title }}">
                @else
                  <span class="admin-book-cover placeholder"><i class="fa-solid fa-book"></i></span>
                @endif
                <div><strong>{{ $book->title }}</strong><span>{{ $book->writer?->name ?? 'بدون مؤلف' }}</span></div>
              </div>
            </td>
            <td>{{ $book->category?->name ?? '—' }}</td>
            <td>{{ number_format($book->recent_downloads) }}</td>
            <td><i class="fa-solid fa-star" style="color:var(--star)"></i> {{ number_format($book->rating_average, 1) }}</td>
          </tr>
        @empty
          <tr class="admin-empty-row">
            <td colspan="4">لا توجد تحميلات خلال آخر 48 ساعة</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/vendor/chartjs/chart.umd.min.js') }}" defer></script>
<script src="{{ asset_min('assets/js/admin-charts.js') }}" defer></script>
@endpush
