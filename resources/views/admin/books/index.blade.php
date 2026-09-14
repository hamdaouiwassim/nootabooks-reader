@extends('admin.layouts.admin')

@section('title', 'إدارة الكتب - مكتبتي')

@php
  $pageTitle = 'إدارة الكتب';
  $breadcrumb = [['label' => 'إدارة الكتب', 'url' => null]];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>إدارة الكتب</h1>
    <p>عرض وتعديل وإضافة الكتب المتوفرة على المنصة ({{ $totalBooks }} كتاب)</p>
  </div>
  <a href="{{ route('admin.books.create') }}" class="btn btn-gold"><i class="fa-solid fa-plus"></i> إضافة كتاب جديد</a>
</div>

<form class="admin-toolbar" method="GET" action="{{ route('admin.books.index') }}">
  <div class="admin-search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث بعنوان الكتاب أو اسم المؤلف ...">
  </div>
  <select class="admin-filter-select" name="category" onchange="this.form.submit()">
    <option value="">كل التصنيفات</option>
    @foreach ($categories as $category)
      <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
    @endforeach
  </select>
  <select class="admin-filter-select" name="status" onchange="this.form.submit()">
    <option value="">كل الحالات</option>
    <option value="published" @selected(request('status') === 'published')>منشور</option>
    <option value="draft" @selected(request('status') === 'draft')>مخفي</option>
  </select>
  <button type="submit" class="btn btn-outline">بحث</button>
</form>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>الكتاب</th>
        <th>التصنيف</th>
        <th>الصيغ</th>
        <th>التقييم</th>
        <th>التحميلات</th>
        <th>تاريخ الإضافة</th>
        <th></th>
      </tr>
    </thead>
    <tbody id="booksTableBody">
      @forelse ($books as $book)
        <tr>
          <td>
            <div class="admin-book-cell">
              @if ($book->cover_image)
                <img class="admin-book-cover" src="{{ $book->cover_image_sm_url }}" width="300" height="450" loading="lazy" alt="{{ $book->title }}">
              @else
                <span class="admin-book-cover placeholder"><i class="fa-solid fa-book"></i></span>
              @endif
              <div>
                <strong>{{ $book->title }}</strong>
                <span>{{ $book->writer?->name ?? 'بدون مؤلف' }}</span>
                @if ($book->status === 'draft')
                  <span class="status-badge draft">مخفي</span>
                @endif
                @if ($book->is_coming_soon)
                  <span class="status-badge coming-soon">قريبًا</span>
                @endif
              </div>
            </div>
          </td>
          <td>{{ $book->category->name }}</td>
          <td>{{ $book->formats ? implode('، ', $book->formats) : '—' }}</td>
          <td><i class="fa-solid fa-star" style="color:var(--star)"></i> {{ number_format($book->rating_average, 1) }}</td>
          <td>{{ number_format($book->downloads_count) }}</td>
          <td>{{ $book->created_at->diffForHumans() }}</td>
          <td>
            <div class="admin-row-actions">
              <a href="{{ route('read', $book->slug) }}" class="admin-icon-btn" title="عرض" aria-label="view"><i class="fa-regular fa-eye"></i></a>
              <a href="{{ route('admin.books.edit', $book) }}" class="admin-icon-btn" title="تعديل" aria-label="edit"><i class="fa-solid fa-pen"></i></a>
              <form method="POST" action="{{ route('admin.books.destroy', $book) }}" data-confirm-delete data-item-title="{{ $book->title }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr class="admin-empty-row">
          <td colspan="7">لا توجد كتب مطابقة لبحثك</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="admin-pagination">
  <span class="admin-pagination-info">{{ $books->total() ? "عرض {$books->firstItem()}-{$books->lastItem()} من {$books->total()} كتاب" : 'لا توجد نتائج' }}</span>
  <div class="admin-pagination-controls">
    {{ $books->onEachSide(1)->links('admin.pagination.admin') }}
  </div>
</div>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا الكتاب؟</h3>
    <p>سيتم حذف الكتاب بشكل نهائي من المنصة، ولن تتمكن من التراجع عن هذا الإجراء.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush
