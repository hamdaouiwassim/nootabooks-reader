@extends('admin.layouts.admin')

@section('title', 'إضافة مؤلف جديد - مكتبتي')

@php
  $pageTitle = 'إضافة مؤلف جديد';
  $breadcrumb = [
    ['label' => 'المؤلفون', 'url' => route('admin.writers.index')],
    ['label' => 'إضافة مؤلف', 'url' => null],
  ];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>إضافة مؤلف جديد</h1>
    <p>أدخل بيانات المؤلف ثم اضغط نشر لإضافته إلى المنصة</p>
  </div>
  <a href="{{ route('admin.writers.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<form id="writerForm" method="POST" action="{{ route('admin.writers.store') }}" enctype="multipart/form-data" novalidate>
  @csrf
  <div class="admin-form-layout">

    <!-- ---- Main fields ---- -->
    <div>
      <div class="admin-form-section">
        <h3>معلومات المؤلف</h3>
        <div class="admin-form-grid">
          <div class="admin-form-field full">
            <label for="writerName">الاسم الكامل</label>
            <input type="text" id="writerName" name="name" class="admin-input" value="{{ old('name') }}" placeholder="مثال: أحمد مراد" required>
          </div>
          <div class="admin-form-field">
            <label for="writerGenre">التصنيف الأدبي</label>
            <input type="text" id="writerGenre" name="genre_tag" class="admin-input" value="{{ old('genre_tag') }}" placeholder="مثال: روايات بوليسية">
          </div>
          <div class="admin-form-field">
            <label for="writerJoined">نشط منذ (سنة)</label>
            <input type="number" id="writerJoined" name="joined_year" class="admin-input" value="{{ old('joined_year') }}" placeholder="2008" min="1900" max="2100">
          </div>
          <div class="admin-form-field full">
            <label for="writerBio">نبذة تفصيلية</label>
            <textarea id="writerBio" name="bio" class="admin-textarea" rows="6" placeholder="اكتب نبذة تفصيلية عن المؤلف ومسيرته الأدبية ...">{{ old('bio') }}</textarea>
          </div>
        </div>
      </div>

      <div class="admin-form-section">
        <h3>الإحصائيات والحالة</h3>
        <div class="admin-form-grid">
          <div class="admin-form-field">
            <label for="writerFollowers">عدد المتابعين</label>
            <input type="number" id="writerFollowers" name="followers_count" class="admin-input" value="{{ old('followers_count', 0) }}" min="0">
          </div>
          <div class="admin-form-field">
            <label for="writerRating">متوسط التقييم (0-5)</label>
            <input type="number" id="writerRating" name="rating_average" class="admin-input" value="{{ old('rating_average', 0) }}" min="0" max="5" step="0.1">
          </div>
        </div>
        <div class="admin-toggle-row" style="margin-top:16px;">
          <div>
            <strong>مؤلف مميز</strong>
            <p>يظهر في قسم "مؤلف الأسبوع" على المنصة</p>
          </div>
          <label class="admin-switch">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured'))>
            <span class="admin-switch-slider"></span>
          </label>
        </div>
      </div>
    </div>

    <!-- ---- Photo sidebar ---- -->
    <div>
      <div class="admin-form-section">
        <h3>الصورة الشخصية</h3>
        <label class="admin-cover-upload" id="coverUpload" style="aspect-ratio:1/1;">
          <i class="fa-solid fa-user"></i>
          <span>اضغط لرفع صورة المؤلف<br>(JPG أو PNG، مربعة الشكل)</span>
          <img id="coverPreview" alt="معاينة الصورة" hidden>
        </label>
        <input type="file" id="coverInput" name="photo" accept="image/*">
      </div>
    </div>

  </div>

  <div class="admin-form-actions">
    <a href="{{ route('admin.writers.index') }}" class="btn btn-outline">إلغاء</a>
    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-paper-plane"></i> نشر المؤلف</button>
  </div>
</form>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/book-form.js') }}"></script>
@endpush
