@extends('admin.layouts.admin')

@section('title', 'بلاغات حقوق النشر - مكتبتي')

@php
  $pageTitle = 'بلاغات حقوق النشر';
  $breadcrumb = [['label' => 'بلاغات حقوق النشر', 'url' => null]];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>بلاغات حقوق النشر</h1>
    <p>مراجعة البلاغات المقدّمة عبر زر "الإبلاغ عن حقوق النشر" في صفحات الكتب ({{ $totalReports }} بلاغ، {{ $pendingReports }} قيد الانتظار)</p>
  </div>
</div>

<form class="admin-toolbar" method="GET" action="{{ route('admin.book-reports.index') }}">
  <div class="admin-search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث باسم المُبلِّغ أو بريده أو عنوان الكتاب ...">
  </div>
  <select class="admin-filter-select" name="status" onchange="this.form.submit()">
    <option value="">كل الحالات</option>
    <option value="pending" @selected(request('status') === 'pending')>قيد الانتظار</option>
    <option value="reviewed" @selected(request('status') === 'reviewed')>تمت المراجعة</option>
  </select>
  <button type="submit" class="btn btn-outline">بحث</button>
</form>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>الكتاب</th>
        <th>المُبلِّغ</th>
        <th>تفاصيل البلاغ</th>
        <th>الحالة</th>
        <th>التاريخ</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse ($reports as $report)
        <tr>
          <td>
            @if ($report->book)
              <div class="admin-book-cell">
                @if ($report->book->cover_image)
                  <img class="admin-book-cover" src="{{ $report->book->cover_image_sm_url }}" width="300" height="450" loading="lazy" decoding="async" alt="{{ $report->book->title }}">
                @else
                  <span class="admin-book-cover placeholder"><i class="fa-solid fa-book"></i></span>
                @endif
                <div><strong>{{ $report->book->title }}</strong></div>
              </div>
            @else
              <span style="color:var(--text-gray);">تم حذف الكتاب</span>
            @endif
          </td>
          <td>
            <strong>{{ $report->reporter_name }}</strong>
            <span style="display:block; font-size:12px; color:var(--text-gray);">{{ $report->reporter_email }}</span>
          </td>
          <td style="max-width:320px;">{{ \Illuminate\Support\Str::limit($report->message, 120) }}</td>
          <td>
            @if ($report->status === 'reviewed')
              <span class="status-badge published">تمت المراجعة</span>
            @else
              <span class="status-badge coming-soon">قيد الانتظار</span>
            @endif
          </td>
          <td>{{ $report->created_at->diffForHumans() }}</td>
          <td>
            <div class="admin-row-actions">
              <a href="{{ route('admin.book-reports.show', $report) }}" class="admin-icon-btn" title="عرض تفاصيل البلاغ" aria-label="view"><i class="fa-regular fa-eye"></i></a>
              <form method="POST" action="{{ route('admin.book-reports.mark-reviewed', $report) }}">
                @csrf
                @method('PUT')
                <button type="submit" class="admin-icon-btn" title="{{ $report->status === 'pending' ? 'وضع علامة تمت المراجعة' : 'إعادة لقيد الانتظار' }}" aria-label="toggle-status">
                  <i class="fa-solid {{ $report->status === 'pending' ? 'fa-check' : 'fa-rotate-left' }}"></i>
                </button>
              </form>
              <form method="POST" action="{{ route('admin.book-reports.destroy', $report) }}" data-confirm-delete data-item-title="هذا البلاغ">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr class="admin-empty-row">
          <td colspan="6">لا توجد بلاغات مطابقة</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="admin-pagination">
  <span class="admin-pagination-info">{{ $reports->total() ? "عرض {$reports->firstItem()}-{$reports->lastItem()} من {$reports->total()} بلاغ" : 'لا توجد نتائج' }}</span>
  <div class="admin-pagination-controls">
    {{ $reports->onEachSide(1)->links('admin.pagination.admin') }}
  </div>
</div>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا البلاغ؟</h3>
    <p>سيتم حذف البلاغ بشكل نهائي، ولن تتمكن من التراجع عن هذا الإجراء.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush
