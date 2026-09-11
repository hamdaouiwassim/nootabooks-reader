@extends('admin.layouts.admin')

@section('title', 'إدارة المستخدمين - مكتبتي')

@php
  $pageTitle = 'إدارة المستخدمين';
  $breadcrumb = [['label' => 'إدارة المستخدمين', 'url' => null]];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>إدارة المستخدمين</h1>
    <p>عرض وإدارة حسابات المستخدمين المسجّلين على المنصة ({{ $totalUsers }} مستخدم)</p>
  </div>
</div>

<form class="admin-toolbar" method="GET" action="{{ route('admin.users.index') }}">
  <div class="admin-search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث بالاسم أو البريد الإلكتروني ...">
  </div>
  <button type="submit" class="btn btn-outline">بحث</button>
</form>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>المستخدم</th>
        <th>البريد الإلكتروني</th>
        <th>النقاط</th>
        <th>الملف الشخصي</th>
        <th>تاريخ التسجيل</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse ($users as $user)
        <tr>
          <td>
            <div class="admin-book-cell">
              @if ($user->avatar)
                <img class="admin-book-cover" style="border-radius:50%; width:44px; height:44px;" src="{{ asset($user->avatar) }}" width="44" height="44" loading="lazy" alt="{{ $user->name }}">
              @else
                <span class="admin-book-cover placeholder" style="border-radius:50%; width:44px; height:44px;"><i class="fa-solid fa-user"></i></span>
              @endif
              <div><strong>{{ $user->name }}</strong></div>
            </div>
          </td>
          <td>{{ $user->email }}</td>
          <td>{{ number_format($user->points) }}</td>
          <td><span class="status-badge {{ $user->is_public ? 'published' : 'draft' }}">{{ $user->is_public ? 'عام' : 'خاص' }}</span></td>
          <td>{{ $user->created_at->diffForHumans() }}</td>
          <td>
            <div class="admin-row-actions">
              <form method="POST" action="{{ route('admin.users.destroy', $user) }}" data-confirm-delete data-item-title="{{ $user->name }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr class="admin-empty-row">
          <td colspan="6">لا يوجد مستخدمون مطابقون لبحثك</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="admin-pagination">
  <span class="admin-pagination-info">{{ $users->total() ? "عرض {$users->firstItem()}-{$users->lastItem()} من {$users->total()} مستخدم" : 'لا توجد نتائج' }}</span>
  <div class="admin-pagination-controls">
    {{ $users->onEachSide(1)->links('admin.pagination.admin') }}
  </div>
</div>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا المستخدم؟</h3>
    <p>سيتم حذف حساب المستخدم بشكل نهائي، ولن تتمكن من التراجع عن هذا الإجراء.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush
