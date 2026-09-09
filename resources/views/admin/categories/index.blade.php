@extends('admin.layouts.admin')

@section('title', 'إدارة التصنيفات - مكتبتي')

@php
  $pageTitle = 'إدارة التصنيفات';
  $breadcrumb = [['label' => 'إدارة التصنيفات', 'url' => null]];
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/categories.css') }}">
@endpush

@section('content')

<div class="admin-page-head">
  <div>
    <h1>إدارة التصنيفات</h1>
    <p>عرض وتعديل وإضافة تصنيفات الكتب على المنصة ({{ $categories->count() }} تصنيف)</p>
  </div>
  <button type="button" class="btn btn-gold" id="addCategoryBtn"><i class="fa-solid fa-plus"></i> إضافة تصنيف جديد</button>
</div>

<div class="admin-toolbar">
  <div class="admin-search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" id="categorySearchInput" placeholder="ابحث باسم التصنيف ...">
  </div>
</div>

<div class="admin-categories-grid" id="categoriesGridBody">

  @foreach ($categories as $category)
    <div class="admin-category-card" data-cat-id="{{ $category->id }}" data-cat-name="{{ $category->name }}" data-cat-icon="{{ $category->icon ?? 'fa-book' }}" data-cat-color="{{ $category->color ?? 'cat-navy' }}">
      <span class="cat-icon-circle {{ $category->color ?? 'cat-navy' }}"><i class="fa-solid {{ $category->icon ?? 'fa-book' }}"></i></span>
      <div class="admin-category-info"><strong>{{ $category->name }}</strong><span>{{ number_format($category->books_count) }} كتاب</span></div>
      <div class="admin-category-actions">
        <button type="button" class="admin-icon-btn" data-cat-edit-trigger title="تعديل" aria-label="edit"><i class="fa-solid fa-pen"></i></button>
        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" data-confirm-delete data-item-title="{{ $category->name }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
        </form>
      </div>
    </div>
  @endforeach

</div>

<p class="admin-empty-row" id="categoriesEmptyState" hidden style="text-align:center; padding:50px 20px; color:var(--text-gray);">لا توجد تصنيفات مطابقة لبحثك</p>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="categoryModal">
  <div class="admin-modal form-modal">
    <h3 id="categoryModalTitle">إضافة تصنيف جديد</h3>
    <p class="admin-alert error" id="categoryModalError" hidden></p>
    <form id="categoryForm">
      <div class="admin-form-field">
        <label for="categoryName">اسم التصنيف</label>
        <input type="text" id="categoryName" class="admin-input" placeholder="مثال: أدب عربي" required>
      </div>

      <div class="admin-form-grid" style="margin-top:16px;">
        <div class="admin-form-field">
          <label for="categoryIcon">الأيقونة</label>
          <select id="categoryIcon" class="admin-select">
            <option value="fa-book">كتاب</option>
            <option value="fa-bookmark">إشارة مرجعية</option>
            <option value="fa-earth-africa">أدب عالمي</option>
            <option value="fa-bell">جرس</option>
            <option value="fa-landmark">معلم تاريخي</option>
            <option value="fa-landmark-dome">سياسة</option>
            <option value="fa-atom">علوم</option>
            <option value="fa-child">أطفال</option>
            <option value="fa-mosque">دين</option>
            <option value="fa-feather">شعر</option>
            <option value="fa-microchip">تكنولوجيا</option>
            <option value="fa-leaf">فلسفة</option>
            <option value="fa-brain">تنمية ذهنية</option>
            <option value="fa-file-lines">قصص قصيرة</option>
            <option value="fa-chart-line">اقتصاد</option>
            <option value="fa-palette">فنون</option>
          </select>
        </div>
        <div class="admin-form-field">
          <label for="categoryColor">اللون</label>
          <select id="categoryColor" class="admin-select">
            <option value="cat-navy">كحلي</option>
            <option value="cat-teal">أخضر مزرق</option>
            <option value="cat-green">أخضر</option>
            <option value="cat-rose">وردي</option>
            <option value="cat-purple">بنفسجي</option>
            <option value="cat-brown">بني</option>
            <option value="cat-gold">ذهبي</option>
          </select>
        </div>
      </div>

      <div class="admin-category-preview-row">
        <span class="cat-icon-circle cat-navy" id="categoryPreview"><i class="fa-solid fa-book"></i></span>
      </div>

      <div class="admin-modal-actions" style="margin-top:18px;">
        <button type="button" class="btn btn-outline" id="categoryModalCancel">إلغاء</button>
        <button type="submit" class="btn btn-gold">حفظ</button>
      </div>
    </form>
  </div>
</div>

<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا التصنيف؟</h3>
    <p>سيتم حذف التصنيف نهائيًا. لا يمكن حذف تصنيف مرتبط بكتب موجودة.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush

@push('scripts')
<script src="{{ asset('assets/js/categories-admin.js') }}"></script>
@endpush
