@extends('admin.layouts.admin')

@section('title', 'لوحة التحكم - مكتبتي')

@php
  $pageTitle = 'لوحة التحكم';
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>مرحبًا 👋</h1>
    <p>هذه نظرة سريعة على أداء منصة مكتبتي اليوم</p>
  </div>
</div>

<div class="admin-stats-grid">
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-gold"><i class="fa-solid fa-book"></i></span>
    <div>
      <strong>{{ number_format($booksCount) }}</strong>
      <span>كتاب في المنصة</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-teal"><i class="fa-solid fa-users"></i></span>
    <div>
      <strong>{{ number_format($usersCount) }}</strong>
      <span>مستخدم مسجل</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-navy"><i class="fa-solid fa-cloud-arrow-down"></i></span>
    <div>
      <strong>{{ number_format($downloadsSum) }}</strong>
      <span>إجمالي التحميلات</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-rose"><i class="fa-solid fa-layer-group"></i></span>
    <div>
      <strong>{{ number_format($categoriesCount) }}</strong>
      <span>تصنيف</span>
    </div>
  </div>
</div>

<div class="admin-panel">
  <div class="admin-panel-head">
    <h3>أحدث الكتب المضافة</h3>
    <a href="{{ route('admin.books.index') }}" class="admin-breadcrumb">عرض كل الكتب <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>الكتاب</th>
          <th>التصنيف</th>
          <th>التحميلات</th>
          <th>تاريخ الإضافة</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($latestBooks as $book)
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
            <td>{{ $book->category->name }}</td>
            <td>{{ number_format($book->downloads_count) }}</td>
            <td>{{ $book->created_at->diffForHumans() }}</td>
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

<div class="admin-panel">
  <div class="admin-panel-head">
    <h3>أحدث المناقشات</h3>
    <a href="{{ route('admin.discussions.index') }}" class="admin-breadcrumb">عرض كل المناقشات <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>المنشور</th>
          <th>الكاتب</th>
          <th>مرتبط بـ</th>
          <th>التعليقات</th>
          <th>تاريخ النشر</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($latestDiscussions as $discussion)
          <tr>
            <td style="max-width:280px;">{{ \Illuminate\Support\Str::limit($discussion->body, 70) }}</td>
            <td>{{ $discussion->user->name }}</td>
            <td>
              @if ($discussion->book)
                {{ $discussion->book->title }}
              @elseif ($discussion->club)
                {{ $discussion->club->name }}
              @else
                —
              @endif
            </td>
            <td>{{ number_format($discussion->comments_count) }}</td>
            <td>{{ $discussion->created_at->diffForHumans() }}</td>
          </tr>
        @empty
          <tr class="admin-empty-row">
            <td colspan="5">لا توجد مناقشات بعد</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
