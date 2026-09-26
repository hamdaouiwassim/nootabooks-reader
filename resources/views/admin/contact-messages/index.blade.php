@extends('admin.layouts.admin')

@section('title', 'رسائل التواصل - مكتبتي')

@php
  $pageTitle = 'رسائل التواصل';
  $breadcrumb = [['label' => 'رسائل التواصل', 'url' => null]];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>رسائل التواصل</h1>
    <p>الرسائل المرسلة عبر نموذج "تواصل معنا" ({{ $totalMessages }} رسالة، {{ $unreadMessages }} غير مقروءة)</p>
  </div>
</div>

<form class="admin-toolbar" method="GET" action="{{ route('admin.contact-messages.index') }}">
  <div class="admin-search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث بالاسم أو البريد أو الموضوع ...">
  </div>
  <select class="admin-filter-select" name="status" onchange="this.form.submit()">
    <option value="">كل الحالات</option>
    <option value="unread" @selected(request('status') === 'unread')>غير مقروءة</option>
    <option value="read" @selected(request('status') === 'read')>مقروءة</option>
  </select>
  <button type="submit" class="btn btn-outline">بحث</button>
</form>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>المرسل</th>
        <th>الموضوع</th>
        <th>الرسالة</th>
        <th>الحالة</th>
        <th>التاريخ</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse ($messages as $contactMessage)
        <tr>
          <td>
            <strong>{{ $contactMessage->name }}</strong>
            <span style="display:block; font-size:12px; color:var(--text-gray);">{{ $contactMessage->email }}</span>
          </td>
          <td>{{ $contactMessage->subject }}</td>
          <td style="max-width:320px;">{{ \Illuminate\Support\Str::limit($contactMessage->message, 100) }}</td>
          <td>
            @if ($contactMessage->status === 'unread')
              <span class="status-badge coming-soon">غير مقروءة</span>
            @else
              <span class="status-badge published">مقروءة</span>
            @endif
          </td>
          <td>{{ $contactMessage->created_at->diffForHumans() }}</td>
          <td>
            <div class="admin-row-actions">
              <a href="{{ route('admin.contact-messages.show', $contactMessage) }}" class="admin-icon-btn" title="عرض الرسالة" aria-label="view"><i class="fa-regular fa-eye"></i></a>
              <form method="POST" action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" data-confirm-delete data-item-title="هذه الرسالة">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr class="admin-empty-row">
          <td colspan="6">لا توجد رسائل مطابقة</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="admin-pagination">
  <span class="admin-pagination-info">{{ $messages->total() ? "عرض {$messages->firstItem()}-{$messages->lastItem()} من {$messages->total()} رسالة" : 'لا توجد نتائج' }}</span>
  <div class="admin-pagination-controls">
    {{ $messages->onEachSide(1)->links('admin.pagination.admin') }}
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
