@extends('admin.layouts.admin')

@section('title', 'إدارة المؤلفين - مكتبتي')

@php
  $pageTitle = 'إدارة المؤلفين';
  $breadcrumb = [['label' => 'إدارة المؤلفين', 'url' => null]];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>إدارة المؤلفين</h1>
    <p>عرض وتعديل وإضافة المؤلفين المتوفرين على المنصة ({{ $totalWriters }} مؤلف)</p>
  </div>
  <a href="{{ route('admin.writers.create') }}" class="btn btn-gold"><i class="fa-solid fa-plus"></i> إضافة مؤلف جديد</a>
</div>

<form class="admin-toolbar" method="GET" action="{{ route('admin.writers.index') }}">
  <div class="admin-search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث باسم المؤلف ...">
  </div>
  <button type="submit" class="btn btn-outline">بحث</button>
</form>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>المؤلف</th>
        <th>التصنيف الأدبي</th>
        <th>عدد الكتب</th>
        <th>المتابعون</th>
        <th>التقييم</th>
        <th></th>
      </tr>
    </thead>
    <tbody id="writersTableBody">
      @forelse ($writers as $writer)
        <tr>
          <td>
            <div class="admin-book-cell">
              @if ($writer->photo)
                <img class="admin-book-cover" style="border-radius:50%; width:44px; height:44px;" src="{{ $writer->photo_sm_url }}" alt="{{ $writer->name }}">
              @else
                <span class="admin-book-cover placeholder" style="border-radius:50%; width:44px; height:44px;"><i class="fa-solid fa-feather"></i></span>
              @endif
              <div>
                <strong>{{ $writer->name }}</strong>
                @if ($writer->is_featured)
                  <span class="status-badge published">مميز</span>
                @endif
              </div>
            </div>
          </td>
          <td>{{ $writer->genre_tag ?? '—' }}</td>
          <td>{{ $writer->books_count }}</td>
          <td>{{ number_format($writer->followers_count) }}</td>
          <td><i class="fa-solid fa-star" style="color:var(--star)"></i> {{ number_format($writer->rating_average, 1) }}</td>
          <td>
            <div class="admin-row-actions">
              <a href="{{ route('writer-details', $writer->slug) }}" class="admin-icon-btn" title="عرض" aria-label="view"><i class="fa-regular fa-eye"></i></a>
              <a href="{{ route('admin.writers.edit', $writer) }}" class="admin-icon-btn" title="تعديل" aria-label="edit"><i class="fa-solid fa-pen"></i></a>
              <form method="POST" action="{{ route('admin.writers.destroy', $writer) }}" data-confirm-delete data-item-title="{{ $writer->name }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr class="admin-empty-row">
          <td colspan="6">لا يوجد مؤلفون مطابقون لبحثك</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="admin-pagination">
  <span class="admin-pagination-info">{{ $writers->total() ? "عرض {$writers->firstItem()}-{$writers->lastItem()} من {$writers->total()} مؤلف" : 'لا توجد نتائج' }}</span>
  <div class="admin-pagination-controls">
    {{ $writers->onEachSide(1)->links('admin.pagination.admin') }}
  </div>
</div>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا المؤلف؟</h3>
    <p>سيتم حذف المؤلف نهائيًا. تبقى كتبه على المنصة لكنها تصبح بدون مؤلف محدد.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush
