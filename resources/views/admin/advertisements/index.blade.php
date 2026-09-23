@extends('admin.layouts.admin')

@section('title', 'إدارة الإعلانات - مكتبتي')

@php
  $pageTitle = 'إدارة الإعلانات';
  $breadcrumb = [['label' => 'إدارة الإعلانات', 'url' => null]];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>إدارة الإعلانات</h1>
    <p>الإعلانات المعروضة في أماكن مختلفة من الموقع ({{ $advertisements->count() }} إعلان)</p>
  </div>
  <a href="{{ route('admin.advertisements.create') }}" class="btn btn-gold"><i class="fa-solid fa-plus"></i> إضافة إعلان جديد</a>
</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>الإعلان</th>
        <th>النوع</th>
        <th>مكان العرض</th>
        <th>الحالة</th>
        <th>النقرات</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse ($advertisements as $advertisement)
        <tr>
          <td>{{ $advertisement->name }}</td>
          <td>{{ \App\Models\Advertisement::TYPES[$advertisement->type] ?? $advertisement->type }}</td>
          <td>{{ \App\Models\Advertisement::ZONES[$advertisement->zone] ?? $advertisement->zone }}</td>
          <td>
            <form method="POST" action="{{ route('admin.advertisements.toggle-active', $advertisement) }}">
              @csrf
              @method('PUT')
              <button type="submit" class="status-badge {{ $advertisement->is_active ? 'published' : 'draft' }}" style="border:none; cursor:pointer;">
                {{ $advertisement->is_active ? 'مفعّل' : 'متوقف' }}
              </button>
            </form>
          </td>
          <td>{{ format_count($advertisement->clicks_count) }}</td>
          <td>
            <div class="admin-row-actions">
              <a href="{{ route('admin.advertisements.edit', $advertisement) }}" class="admin-icon-btn" title="تعديل" aria-label="edit"><i class="fa-solid fa-pen"></i></a>
              <form method="POST" action="{{ route('admin.advertisements.destroy', $advertisement) }}" data-confirm-delete data-item-title="{{ $advertisement->name }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr class="admin-empty-row">
          <td colspan="6">لا توجد إعلانات بعد</td>
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
    <h3 id="deleteModalTitle">هل تريد حذف هذا الإعلان؟</h3>
    <p>سيتم حذف الإعلان نهائيًا من المنصة.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush
