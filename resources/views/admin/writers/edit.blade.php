@extends('admin.layouts.admin')

@section('title', 'تعديل المؤلف: '.$writer->name.' - مكتبتي')

@php
  $pageTitle = 'تعديل المؤلف';
  $breadcrumb = [
    ['label' => 'المؤلفون', 'url' => route('admin.writers.index')],
    ['label' => 'تعديل', 'url' => null],
  ];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>تعديل المؤلف: {{ $writer->name }}</h1>
    <p>حدّث بيانات المؤلف ثم اضغط حفظ التعديلات</p>
  </div>
  <a href="{{ route('admin.writers.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<form id="writerForm" method="POST" action="{{ route('admin.writers.update', $writer) }}" enctype="multipart/form-data" novalidate>
  @csrf
  @method('PUT')
  <div class="admin-form-layout">

    <!-- ---- Main fields ---- -->
    <div>
      <div class="admin-form-section">
        <h3>معلومات المؤلف</h3>
        <div class="admin-form-grid">
          <div class="admin-form-field full">
            <label for="writerName">الاسم الكامل</label>
            <input type="text" id="writerName" name="name" class="admin-input" value="{{ old('name', $writer->name) }}" required>
          </div>
          <div class="admin-form-field">
            <label for="writerGenre">التصنيف الأدبي</label>
            <input type="text" id="writerGenre" name="genre_tag" class="admin-input" value="{{ old('genre_tag', $writer->genre_tag) }}">
          </div>
          <div class="admin-form-field">
            <label for="writerJoined">نشط منذ (سنة)</label>
            <input type="number" id="writerJoined" name="joined_year" class="admin-input" value="{{ old('joined_year', $writer->joined_year) }}" min="1900" max="2100">
          </div>
          <div class="admin-form-field full">
            <label for="writerBio">نبذة تفصيلية</label>
            <textarea id="writerBio" name="bio" class="admin-textarea" rows="6">{{ old('bio', $writer->bio) }}</textarea>
          </div>
        </div>
      </div>

      <div class="admin-form-section">
        <h3>الإحصائيات والحالة</h3>
        <div class="admin-form-grid">
          <div class="admin-form-field">
            <label for="writerFollowers">عدد المتابعين</label>
            <input type="number" id="writerFollowers" name="followers_count" class="admin-input" value="{{ old('followers_count', $writer->followers_count) }}" min="0">
          </div>
          <div class="admin-form-field">
            <label for="writerRating">متوسط التقييم (0-5)</label>
            <input type="number" id="writerRating" name="rating_average" class="admin-input" value="{{ old('rating_average', $writer->rating_average) }}" min="0" max="5" step="0.1">
          </div>
        </div>
        <div class="admin-toggle-row" style="margin-top:16px;">
          <div>
            <strong>مؤلف مميز</strong>
            <p>يظهر في قسم "مؤلف الأسبوع" على المنصة</p>
          </div>
          <label class="admin-switch">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $writer->is_featured))>
            <span class="admin-switch-slider"></span>
          </label>
        </div>
      </div>

      <div class="admin-form-section">
        <h3>كتب هذا المؤلف ({{ $writer->books_count }})</h3>
        <div class="admin-table-wrap">
          <table class="admin-table">
            <thead>
              <tr>
                <th>الكتاب</th>
                <th>التصنيف</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse ($books as $book)
                <tr>
                  <td>{{ $book->title }}</td>
                  <td>{{ $book->category->name }}</td>
                  <td>
                    <a href="{{ route('admin.books.edit', $book) }}" class="admin-icon-btn" title="تعديل الكتاب" aria-label="edit"><i class="fa-solid fa-pen"></i></a>
                  </td>
                </tr>
              @empty
                <tr class="admin-empty-row">
                  <td colspan="3">لا توجد كتب مرتبطة بهذا المؤلف بعد</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <!-- ---- Photo sidebar ---- -->
    <div>
      <div class="admin-form-section">
        <h3>الصورة الشخصية</h3>
        <label class="admin-cover-upload @if($writer->photo) has-image @endif" id="coverUpload" style="aspect-ratio:1/1;">
          <i class="fa-solid fa-user"></i>
          <span>اضغط لرفع صورة المؤلف<br>(JPG أو PNG، مربعة الشكل)</span>
          <img id="coverPreview" @if($writer->photo) src="{{ $writer->photo_url }}" @else hidden @endif alt="معاينة الصورة">
        </label>
        <input type="file" id="coverInput" name="photo" accept="image/*">
      </div>
    </div>

  </div>

  <div class="admin-form-actions">
    <a href="{{ route('admin.writers.index') }}" class="btn btn-outline">إلغاء</a>
    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-floppy-disk"></i> حفظ التعديلات</button>
  </div>
</form>

<div class="admin-form-section" style="border-color:#f3c7c1; background:#fdecea; margin-top:22px;">
  <h3 style="color:#c0392b;"><i class="fa-solid fa-triangle-exclamation"></i> منطقة الخطر</h3>
  <p style="font-size:13px; color:#7a3129; line-height:1.8; margin-bottom:16px;">حذف هذا المؤلف نهائي. تبقى كتبه على المنصة لكنها تصبح بدون مؤلف محدد.</p>
  <form method="POST" action="{{ route('admin.writers.destroy', $writer) }}" data-confirm-delete data-item-title="{{ $writer->name }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
      <i class="fa-solid fa-trash"></i> حذف هذا المؤلف نهائيًا
    </button>
  </form>
</div>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا المؤلف؟</h3>
    <p>سيتم حذف المؤلف نهائيًا. تبقى كتبه على المنصة لكنها تصبح بدون مؤلف محدد.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush

@push('scripts')
<script src="{{ asset('assets/js/book-form.js') }}"></script>
@endpush
