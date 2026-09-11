@extends('admin.layouts.admin')

@section('title', 'إدارة المناقشات - مكتبتي')

@php
  $pageTitle = 'إدارة المناقشات';
  $breadcrumb = [['label' => 'المناقشات', 'url' => null]];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>إدارة المناقشات</h1>
    <p>عرض ومراقبة المنشورات في مجتمع القراء ({{ $totalDiscussions }} مناقشة)</p>
  </div>
</div>

<form class="admin-toolbar" method="GET" action="{{ route('admin.discussions.index') }}">
  <div class="admin-search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث بنص المنشور أو اسم الكاتب ...">
  </div>
  <button type="submit" class="btn btn-outline">بحث</button>
</form>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>المنشور</th>
        <th>الكاتب</th>
        <th>مرتبط بـ</th>
        <th>الإعجابات</th>
        <th>التعليقات</th>
        <th>تاريخ النشر</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse ($discussions as $discussion)
        <tr>
          <td style="max-width:280px;">{{ \Illuminate\Support\Str::limit($discussion->body, 90) }}</td>
          <td>{{ $discussion->user->name }}</td>
          <td>
            @if ($discussion->book)
              <a href="{{ route('book-details', $discussion->book->slug) }}" target="_blank">{{ $discussion->book->title }}</a>
            @elseif ($discussion->club)
              <a href="{{ route('club-details', $discussion->club->slug) }}" target="_blank">{{ $discussion->club->name }}</a>
            @else
              —
            @endif
          </td>
          <td>{{ number_format($discussion->liked_by_count) }}</td>
          <td>{{ number_format($discussion->comments_count) }}</td>
          <td>{{ $discussion->created_at->diffForHumans() }}</td>
          <td>
            <div class="admin-row-actions">
              <a href="{{ route('admin.discussions.show', $discussion) }}" class="admin-icon-btn" title="عرض" aria-label="view"><i class="fa-regular fa-eye"></i></a>
              <form method="POST" action="{{ route('admin.discussions.destroy', $discussion) }}" data-confirm-delete data-item-title="المناقشة">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr class="admin-empty-row">
          <td colspan="7">لا توجد مناقشات مطابقة لبحثك</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="admin-pagination">
  <span class="admin-pagination-info">{{ $discussions->total() ? "عرض {$discussions->firstItem()}-{$discussions->lastItem()} من {$discussions->total()} مناقشة" : 'لا توجد نتائج' }}</span>
  <div class="admin-pagination-controls">
    {{ $discussions->onEachSide(1)->links('admin.pagination.admin') }}
  </div>
</div>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذه المناقشة؟</h3>
    <p>سيتم حذف المنشور وكل تعليقاته بشكل نهائي، ولن تتمكن من التراجع عن هذا الإجراء.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush
