@extends('admin.layouts.admin')

@section('title', 'عرض المناقشة - مكتبتي')

@php
  $pageTitle = 'عرض المناقشة';
  $breadcrumb = [
    ['label' => 'المناقشات', 'url' => route('admin.discussions.index')],
    ['label' => 'عرض', 'url' => null],
  ];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>عرض المناقشة</h1>
    <p>مراجعة المنشور وتعليقاته</p>
  </div>
  <a href="{{ route('admin.discussions.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<div class="admin-form-section">
  <h3>المنشور</h3>
  <p style="font-size:13px; color:var(--text-gray); margin-bottom:10px;">
    بواسطة <strong style="color:var(--navy);">{{ $discussion->user->name }}</strong> · {{ $discussion->created_at->diffForHumans() }}
    @if ($discussion->book)
      · مرتبط بكتاب <a href="{{ route('book-details', $discussion->book->slug) }}" target="_blank">{{ $discussion->book->title }}</a>
    @elseif ($discussion->club)
      · في نادي <a href="{{ route('club-details', $discussion->club->slug) }}" target="_blank">{{ $discussion->club->name }}</a>
    @endif
  </p>
  <p style="font-size:15px; line-height:1.9; color:var(--text-dark); margin-bottom:16px;">{{ $discussion->body }}</p>
  <a href="{{ route('discussion-details', $discussion->id) }}" target="_blank" class="btn btn-outline small">عرض على الموقع</a>

  <form method="POST" action="{{ route('admin.discussions.destroy', $discussion) }}" data-confirm-delete data-item-title="المناقشة" style="display:inline-block; margin-inline-start:8px;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger small"><i class="fa-solid fa-trash"></i> حذف المناقشة</button>
  </form>
</div>

<div class="admin-form-section" style="margin-top:20px;">
  <h3>التعليقات ({{ $comments->count() + $comments->sum(fn ($c) => $c->replies->count()) }})</h3>

  @forelse ($comments as $comment)
    <div style="border-bottom:1px solid var(--border-light); padding:14px 0;">
      <div class="admin-row-actions" style="justify-content:space-between;">
        <p style="font-size:14px;">
          <strong style="color:var(--navy);">{{ $comment->user->name }}</strong>
          <span style="color:var(--text-gray); font-size:12px;"> · {{ $comment->created_at->diffForHumans() }}</span><br>
          {{ $comment->body }}
        </p>
        <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}?from=discussion" data-confirm-delete data-item-title="التعليق">
          @csrf
          @method('DELETE')
          <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
        </form>
      </div>

      @foreach ($comment->replies as $reply)
        <div style="margin-inline-start:30px; margin-top:10px; padding-inline-start:14px; border-inline-start:2px solid var(--border-light);">
          <div class="admin-row-actions" style="justify-content:space-between;">
            <p style="font-size:13px;">
              <strong style="color:var(--navy);">{{ $reply->user->name }}</strong>
              <span style="color:var(--text-gray); font-size:12px;"> · {{ $reply->created_at->diffForHumans() }}</span><br>
              {{ $reply->body }}
            </p>
            <form method="POST" action="{{ route('admin.comments.destroy', $reply) }}?from=discussion" data-confirm-delete data-item-title="الرد">
              @csrf
              @method('DELETE')
              <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
            </form>
          </div>
        </div>
      @endforeach
    </div>
  @empty
    <p style="font-size:13px; color:var(--text-gray);">لا توجد تعليقات على هذه المناقشة.</p>
  @endforelse
</div>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">تأكيد الحذف</h3>
    <p>لن تتمكن من التراجع عن هذا الإجراء.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush
