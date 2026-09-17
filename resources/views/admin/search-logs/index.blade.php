@extends('admin.layouts.admin')

@section('title', 'سجل بحث المستخدمين - مكتبتي')

@php
  $pageTitle = 'سجل بحث المستخدمين';
  $breadcrumb = [['label' => 'سجل بحث المستخدمين', 'url' => null]];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>سجل بحث المستخدمين</h1>
    <p>كل عملية بحث عن كتاب تمت من صفحة الاستكشاف أو صفحات التصنيفات ({{ number_format($totalSearches) }} عملية بحث)</p>
  </div>
  @if ($totalSearches > 0)
    <form method="POST" action="{{ route('admin.search-logs.clear') }}" data-confirm-delete data-item-title="سجل البحث بالكامل">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-outline"><i class="fa-solid fa-broom"></i> مسح السجل بالكامل</button>
    </form>
  @endif
</div>

@if ($topQueries->isNotEmpty())
  <div class="admin-form-section" style="margin-bottom:20px;">
    <h3>الكلمات الأكثر بحثًا (آخر 7 أيام)</h3>
    <div class="admin-row-actions" style="justify-content:flex-start; flex-wrap:wrap; gap:10px;">
      @foreach ($topQueries as $item)
        <span class="status-badge free">{{ $item->normalized_query }} <strong style="margin-right:4px;">×{{ $item->total }}</strong></span>
      @endforeach
    </div>
  </div>
@endif

<form class="admin-toolbar" method="GET" action="{{ route('admin.search-logs.index') }}">
  <div class="admin-search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث في كلمات البحث المسجّلة ...">
  </div>
  <button type="submit" class="btn btn-outline">بحث</button>
</form>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>كلمة البحث</th>
        <th>المستخدم</th>
        <th>عدد النتائج</th>
        <th>التاريخ</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse ($logs as $log)
        <tr>
          <td><strong>{{ $log->query }}</strong></td>
          <td>{{ $log->user?->name ?? 'زائر' }}</td>
          <td>
            @if ($log->results_count > 0)
              <span class="status-badge published">{{ number_format($log->results_count) }}</span>
            @else
              <span class="status-badge draft">بدون نتائج</span>
            @endif
          </td>
          <td>{{ $log->created_at->diffForHumans() }}</td>
          <td>
            <div class="admin-row-actions">
              <form method="POST" action="{{ route('admin.search-logs.destroy', $log) }}" data-confirm-delete data-item-title="هذا السجل">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr class="admin-empty-row">
          <td colspan="5">لا يوجد سجل بحث مطابق</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="admin-pagination">
  <span class="admin-pagination-info">{{ $logs->total() ? "عرض {$logs->firstItem()}-{$logs->lastItem()} من {$logs->total()} عملية بحث" : 'لا توجد نتائج' }}</span>
  <div class="admin-pagination-controls">
    {{ $logs->onEachSide(1)->links('admin.pagination.admin') }}
  </div>
</div>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا العنصر؟</h3>
    <p>لن تتمكن من التراجع عن هذا الإجراء.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush
