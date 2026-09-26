@extends('admin.layouts.admin')

@section('title', 'عرض الرسالة - مكتبتي')

@php
  $pageTitle = 'عرض الرسالة';
  $breadcrumb = [
    ['label' => 'رسائل التواصل', 'url' => route('admin.contact-messages.index')],
    ['label' => 'عرض', 'url' => null],
  ];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>عرض الرسالة</h1>
    <p>تفاصيل الرسالة المرسلة عبر نموذج "تواصل معنا"</p>
  </div>
  <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<div class="admin-form-section">
  <h3>بيانات المرسل</h3>
  <div class="admin-form-grid">
    <div class="admin-form-field">
      <label>الاسم</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ $message->name }}</p>
    </div>
    <div class="admin-form-field">
      <label>البريد الإلكتروني</label>
      <p style="font-size:14px; color:var(--text-dark);"><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></p>
    </div>
    <div class="admin-form-field">
      <label>الموضوع</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ $message->subject }}</p>
    </div>
    <div class="admin-form-field">
      <label>تاريخ الإرسال</label>
      <p style="font-size:14px; color:var(--text-dark);">{{ $message->created_at->format('Y-m-d H:i') }} ({{ $message->created_at->diffForHumans() }})</p>
    </div>
  </div>
</div>

<div class="admin-form-section" style="margin-top:20px;">
  <h3>نص الرسالة</h3>
  <p style="font-size:15px; line-height:1.9; color:var(--text-dark); white-space:pre-wrap;">{{ $message->message }}</p>
</div>

<div class="admin-form-section" style="margin-top:20px;">
  <div class="admin-toggle-row">
    <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('رد: '.$message->subject) }}" class="btn btn-teal small">
      <i class="fa-solid fa-reply"></i> الرد عبر البريد الإلكتروني
    </a>
    <form method="POST" action="{{ route('admin.contact-messages.destroy', $message) }}" data-confirm-delete data-item-title="هذه الرسالة">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn btn-danger small"><i class="fa-solid fa-trash"></i> حذف الرسالة</button>
    </form>
  </div>
</div>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذه الرسالة؟</h3>
    <p>سيتم حذف الرسالة بشكل نهائي، ولن تتمكن من التراجع عن هذا الإجراء.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush
