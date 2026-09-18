@extends('admin.layouts.admin')

@section('title', 'عرض البلاغ - مكتبتي')

@php
  $pageTitle = 'عرض البلاغ';
  $breadcrumb = [
    ['label' => 'بلاغات حقوق النشر', 'url' => route('admin.book-reports.index')],
    ['label' => 'عرض', 'url' => null],
  ];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>عرض البلاغ</h1>
    <p>تفاصيل بلاغ حقوق النشر</p>
  </div>
  <a href="{{ route('admin.book-reports.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<div class="admin-form-section">
  <h3>الكتاب المُبلَّغ عنه</h3>

  @if ($report->book)
    <div class="admin-book-cell" style="margin-bottom:16px;">
      @if ($report->book->cover_image)
        <img class="admin-book-cover" src="{{ $report->book->cover_image_sm_url }}" width="300" height="450" loading="lazy" decoding="async" alt="{{ $report->book->title }}">
      @else
        <span class="admin-book-cover placeholder"><i class="fa-solid fa-book"></i></span>
      @endif
      <div>
        <strong>{{ $report->book->title }}</strong>
        <span>{{ $report->book->writer?->name ?? 'بدون مؤلف' }}</span>
      </div>
    </div>
    <div class="admin-row-actions" style="justify-content:flex-start; gap:10px;">
      <a href="{{ route('book-details', $report->book->slug) }}" target="_blank" rel="noopener" class="btn btn-outline small">عرض على الموقع</a>
      <a href="{{ route('admin.books.edit', $report->book) }}" class="btn btn-outline small">تعديل الكتاب</a>
      <form method="POST" action="{{ route('admin.books.copyright-block', $report->book) }}">
        @csrf
        @method('PUT')
        <button type="submit" class="btn small" style="background:#fdecea; color:#a53125;">
          <i class="fa-solid fa-scale-balanced"></i>
          {{ $report->book->copyright_blocked ? 'إلغاء الحظر' : 'حظر الكتاب بسبب حقوق النشر' }}
        </button>
      </form>
    </div>
  @else
    <p style="color:var(--text-gray);">تم حذف هذا الكتاب من المنصة.</p>
  @endif
</div>

<div class="admin-form-section" style="margin-top:20px;">
  <h3>بيانات المُبلِّغ</h3>
  <div class="admin-form-grid">
    <div class="admin-form-field">
      <label>الاسم</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ $report->reporter_name }}</p>
    </div>
    <div class="admin-form-field">
      <label>البريد الإلكتروني</label>
      <p style="font-size:14px; color:var(--text-dark);"><a href="mailto:{{ $report->reporter_email }}">{{ $report->reporter_email }}</a></p>
    </div>
    @if ($report->user)
      <div class="admin-form-field">
        <label>حساب مسجّل</label>
        <p style="font-size:14px; color:var(--text-dark);">{{ $report->user->name }} ({{ $report->user->email }})</p>
      </div>
    @endif
    <div class="admin-form-field">
      <label>تاريخ البلاغ</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ $report->created_at->format('Y-m-d H:i') }} ({{ $report->created_at->diffForHumans() }})</p>
    </div>
  </div>
</div>

<div class="admin-form-section" style="margin-top:20px;">
  <h3>تفاصيل البلاغ</h3>
  <p style="font-size:15px; line-height:1.9; color:var(--text-dark); white-space:pre-wrap;">{{ $report->message }}</p>
</div>

<div class="admin-form-section" style="margin-top:20px;">
  <h3>الحالة</h3>
  <div class="admin-toggle-row">
    <div>
      @if ($report->status === 'reviewed')
        <span class="status-badge published">تمت المراجعة</span>
      @else
        <span class="status-badge coming-soon">قيد الانتظار</span>
      @endif
    </div>
    <div class="admin-row-actions" style="gap:10px;">
      <form method="POST" action="{{ route('admin.book-reports.mark-reviewed', $report) }}">
        @csrf
        @method('PUT')
        <button type="submit" class="btn btn-teal small">
          <i class="fa-solid {{ $report->status === 'pending' ? 'fa-check' : 'fa-rotate-left' }}"></i>
          {{ $report->status === 'pending' ? 'وضع علامة تمت المراجعة' : 'إعادة لقيد الانتظار' }}
        </button>
      </form>
      <form method="POST" action="{{ route('admin.book-reports.destroy', $report) }}" data-confirm-delete data-item-title="هذا البلاغ">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger small"><i class="fa-solid fa-trash"></i> حذف البلاغ</button>
      </form>
    </div>
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
