@extends('admin.layouts.admin')

@section('title', 'إدارة الاقتباسات - مكتبتي')

@php
  $pageTitle = 'إدارة الاقتباسات';
  $breadcrumb = [['label' => 'إدارة الاقتباسات', 'url' => null]];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>إدارة الاقتباسات</h1>
    <p>الاقتباسات التي تظهر في البطاقات المتحركة بالصفحة الرئيسية ({{ $quotes->count() }} اقتباس، تُعرض آخر 3 منها)</p>
  </div>
  <a href="{{ route('admin.quotes.create') }}" class="btn btn-gold"><i class="fa-solid fa-plus"></i> إضافة اقتباس جديد</a>
</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>الاقتباس</th>
        <th>الكاتب</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse ($quotes as $quote)
        <tr>
          <td>{{ $quote->text }}</td>
          <td>{{ $quote->author ?? '—' }}</td>
          <td>
            <div class="admin-row-actions">
              <a href="{{ route('admin.quotes.edit', $quote) }}" class="admin-icon-btn" title="تعديل" aria-label="edit"><i class="fa-solid fa-pen"></i></a>
              <form method="POST" action="{{ route('admin.quotes.destroy', $quote) }}" data-confirm-delete data-item-title="{{ $quote->text }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr class="admin-empty-row">
          <td colspan="3">لا توجد اقتباسات بعد</td>
        </tr>
      @endforelse
    </tbody>
  </table>
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
