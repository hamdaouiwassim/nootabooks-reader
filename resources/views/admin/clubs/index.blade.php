@extends('admin.layouts.admin')

@section('title', 'إدارة نوادي القراءة - مكتبتي')

@php
  $pageTitle = 'إدارة نوادي القراءة';
  $breadcrumb = [['label' => 'نوادي القراءة', 'url' => null]];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>إدارة نوادي القراءة</h1>
    <p>عرض وإدارة نوادي القراءة على المنصة ({{ $totalClubs }} نادٍ)</p>
  </div>
  <a href="{{ route('admin.clubs.create') }}" class="btn btn-gold"><i class="fa-solid fa-plus"></i> إضافة نادٍ جديد</a>
</div>

<form class="admin-toolbar" method="GET" action="{{ route('admin.clubs.index') }}">
  <div class="admin-search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث باسم النادي ...">
  </div>
  <button type="submit" class="btn btn-outline">بحث</button>
</form>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>النادي</th>
        <th>التصنيف</th>
        <th>الأعضاء</th>
        <th>المناقشات</th>
        <th>تاريخ الإنشاء</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse ($clubs as $club)
        <tr>
          <td>
            <div class="admin-book-cell">
              <span class="admin-book-cover placeholder" style="border-radius:50%; width:44px; height:44px;"><i class="fa-solid fa-people-group"></i></span>
              <div><strong>{{ $club->name }}</strong></div>
            </div>
          </td>
          <td>{{ $club->category ?? '—' }}</td>
          <td>{{ number_format($club->members_count) }}</td>
          <td>{{ number_format($club->discussions_count) }}</td>
          <td>{{ $club->created_at->diffForHumans() }}</td>
          <td>
            <div class="admin-row-actions">
              <a href="{{ route('club-details', $club->slug) }}" class="admin-icon-btn" title="عرض" aria-label="view" target="_blank"><i class="fa-regular fa-eye"></i></a>
              <a href="{{ route('admin.clubs.edit', $club) }}" class="admin-icon-btn" title="تعديل" aria-label="edit"><i class="fa-solid fa-pen"></i></a>
              <form method="POST" action="{{ route('admin.clubs.destroy', $club) }}" data-confirm-delete data-item-title="{{ $club->name }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr class="admin-empty-row">
          <td colspan="6">لا توجد نوادٍ مطابقة لبحثك</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="admin-pagination">
  <span class="admin-pagination-info">{{ $clubs->total() ? "عرض {$clubs->firstItem()}-{$clubs->lastItem()} من {$clubs->total()} نادٍ" : 'لا توجد نتائج' }}</span>
  <div class="admin-pagination-controls">
    {{ $clubs->onEachSide(1)->links('admin.pagination.admin') }}
  </div>
</div>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا النادي؟</h3>
    <p>سيتم حذف النادي وجميع مناقشاته بشكل نهائي، ولن تتمكن من التراجع عن هذا الإجراء.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush
