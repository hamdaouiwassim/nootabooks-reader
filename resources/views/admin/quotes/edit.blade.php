@extends('admin.layouts.admin')

@section('title', 'تعديل الاقتباس - مكتبتي')

@php
  $pageTitle = 'تعديل الاقتباس';
  $breadcrumb = [
    ['label' => 'إدارة الاقتباسات', 'url' => route('admin.quotes.index')],
    ['label' => 'تعديل', 'url' => null],
  ];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>تعديل الاقتباس</h1>
    <p>حدّث نص الاقتباس أو اسم الكاتب ثم احفظ التعديلات</p>
  </div>
  <a href="{{ route('admin.quotes.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<form method="POST" action="{{ route('admin.quotes.update', $quote) }}" novalidate>
  @csrf
  @method('PUT')
  <div class="admin-form-section">
    <div class="admin-form-grid">
      <div class="admin-form-field full">
        <label for="quoteText">نص الاقتباس</label>
        <textarea id="quoteText" name="text" class="admin-textarea" rows="3" required maxlength="400">{{ old('text', $quote->text) }}</textarea>
        @error('text')
          <span class="admin-field-error">{{ $message }}</span>
        @enderror
      </div>
      <div class="admin-form-field full">
        <label for="quoteAuthor">اسم الكاتب (اختياري)</label>
        <input type="text" id="quoteAuthor" name="author" class="admin-input" value="{{ old('author', $quote->author) }}" placeholder="مثال: نجيب محفوظ">
        @error('author')
          <span class="admin-field-error">{{ $message }}</span>
        @enderror
      </div>
    </div>
  </div>

  <div class="admin-form-actions">
    <a href="{{ route('admin.quotes.index') }}" class="btn btn-outline">إلغاء</a>
    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-floppy-disk"></i> حفظ التعديلات</button>
  </div>
</form>

<div class="admin-form-section" style="border-color:#f3c7c1; background:#fdecea; margin-top:22px;">
  <h3 style="color:#c0392b;"><i class="fa-solid fa-triangle-exclamation"></i> منطقة الخطر</h3>
  <p style="font-size:13px; color:#7a3129; line-height:1.8; margin-bottom:16px;">حذف هذا الاقتباس نهائي ولا يمكن التراجع عنه.</p>
  <form method="POST" action="{{ route('admin.quotes.destroy', $quote) }}" data-confirm-delete data-item-title="{{ $quote->text }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
      <i class="fa-solid fa-trash"></i> حذف هذا الاقتباس نهائيًا
    </button>
  </form>
</div>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا الاقتباس؟</h3>
    <p>سيتم حذف الاقتباس نهائيًا من المنصة.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush
