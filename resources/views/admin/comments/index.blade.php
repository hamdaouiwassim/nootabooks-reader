@extends('admin.layouts.admin')

@section('title', 'إدارة التعليقات - مكتبتي')

@php
  $pageTitle = 'إدارة التعليقات';
  $breadcrumb = [['label' => 'التعليقات', 'url' => null]];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>إدارة التعليقات</h1>
    <p>عرض ومراقبة التعليقات على مناقشات المجتمع ({{ $totalComments }} تعليق)</p>
  </div>
</div>

<form class="admin-toolbar" method="GET" action="{{ route('admin.comments.index') }}">
  <div class="admin-search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث بنص التعليق أو اسم الكاتب ...">
  </div>
  <button type="submit" class="btn btn-outline">بحث</button>
</form>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>التعليق</th>
        <th>الكاتب</th>
        <th>الإعجابات</th>
        <th>تاريخ النشر</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse ($comments as $comment)
        <tr>
          <td style="max-width:320px;">{{ \Illuminate\Support\Str::limit($comment->body, 100) }}</td>
          <td>{{ $comment->user->name }}</td>
          <td>{{ number_format($comment->liked_by_count) }}</td>
          <td>{{ $comment->created_at->diffForHumans() }}</td>
          <td>
            <div class="admin-row-actions">
              <a href="{{ route('admin.discussions.show', $comment->discussion_id) }}" class="admin-icon-btn" title="عرض المناقشة" aria-label="view"><i class="fa-regular fa-eye"></i></a>
              <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" data-confirm-delete data-item-title="التعليق">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr class="admin-empty-row">
          <td colspan="5">لا توجد تعليقات مطابقة لبحثك</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="admin-pagination">
  <span class="admin-pagination-info">{{ $comments->total() ? "عرض {$comments->firstItem()}-{$comments->lastItem()} من {$comments->total()} تعليق" : 'لا توجد نتائج' }}</span>
  <div class="admin-pagination-controls">
    {{ $comments->onEachSide(1)->links('admin.pagination.admin') }}
  </div>
</div>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا التعليق؟</h3>
    <p>سيتم حذف التعليق بشكل نهائي، ولن تتمكن من التراجع عن هذا الإجراء.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush
