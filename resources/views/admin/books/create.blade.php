@extends('admin.layouts.admin')

@section('title', 'إضافة كتاب جديد - مكتبتي')

@php
  $pageTitle = 'إضافة كتاب جديد';
  $breadcrumb = [
    ['label' => 'إدارة الكتب', 'url' => route('admin.books.index')],
    ['label' => 'إضافة كتاب', 'url' => null],
  ];
  $selectedFormats = old('formats', []);
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>إضافة كتاب جديد</h1>
    <p>أدخل بيانات الكتاب ثم اضغط نشر لإضافته إلى المنصة</p>
  </div>
  <a href="{{ route('admin.books.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<form id="bookForm" method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" novalidate>
  @csrf
  <div class="admin-form-layout">

    <!-- ---- Main fields ---- -->
    <div>
      <div class="admin-form-section">
        <h3>معلومات الكتاب</h3>
        <div class="admin-form-grid">
          <div class="admin-form-field full">
            <label for="bookTitle">عنوان الكتاب</label>
            <input type="text" id="bookTitle" name="title" class="admin-input" value="{{ old('title') }}" placeholder="مثال: أرض زيكولا" required>
          </div>
          <div class="admin-form-field">
            <label for="bookWriter">المؤلف</label>
            <select id="bookWriter" name="writer_id" class="admin-select">
              <option value="">بدون مؤلف محدد</option>
              @foreach ($writers as $writer)
                <option value="{{ $writer->id }}" @selected(old('writer_id') == $writer->id)>{{ $writer->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="admin-form-field">
            <label for="bookCategory">التصنيف</label>
            <select id="bookCategory" name="category_id" class="admin-select" required>
              <option value="" disabled selected>اختر التصنيف</option>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="admin-form-field">
            <label for="bookLanguage">اللغة</label>
            <select id="bookLanguage" name="language" class="admin-select">
              @foreach (['العربية', 'الإنجليزية', 'مترجم إلى العربية'] as $language)
                <option @selected(old('language', 'العربية') === $language)>{{ $language }}</option>
              @endforeach
            </select>
          </div>
          <div class="admin-form-field">
            <label for="bookYear">سنة النشر</label>
            <input type="number" id="bookYear" name="published_year" class="admin-input" value="{{ old('published_year') }}" placeholder="2024" min="1900" max="2100">
          </div>
          <div class="admin-form-field">
            <label for="bookPages">عدد الصفحات</label>
            <input type="number" id="bookPages" name="pages_count" class="admin-input" value="{{ old('pages_count') }}" placeholder="300" min="1">
            <span class="hint">يتم تعبئته تلقائيًا عند رفع ملف PDF</span>
          </div>
          <div class="admin-form-field full">
            <label for="bookDescShort">نبذة مختصرة</label>
            <input type="text" id="bookDescShort" name="description_short" class="admin-input" value="{{ old('description_short') }}" placeholder="جملة أو جملتان تظهر في قوائم الكتب">
          </div>
          <div class="admin-form-field full">
            <label for="bookDesc">وصف الكتاب</label>
            <textarea id="bookDesc" name="description" class="admin-textarea" rows="5" placeholder="اكتب وصفًا مفصلًا عن الكتاب ...">{{ old('description') }}</textarea>
          </div>
        </div>
      </div>

      <div class="admin-form-section">
        <h3>الملف والصيغ</h3>
        <div class="admin-form-grid">
          <div class="admin-form-field">
            <label for="bookFileSize">حجم الملف (ميجابايت)</label>
            <input type="number" id="bookFileSize" name="file_size_mb" class="admin-input" value="{{ old('file_size_mb') }}" placeholder="2.1" min="0" step="0.01">
            <span class="hint">يتم تعبئته تلقائيًا عند رفع ملف الكتاب أدناه</span>
          </div>
          <div class="admin-form-field">
            <label for="bookTags">الوسوم (مفصولة بفاصلة)</label>
            <input type="text" id="bookTags" name="tags" class="admin-input" value="{{ old('tags') }}" placeholder="إثارة، غموض">
          </div>
          <div class="admin-form-field full">
            <label>الصيغ المتوفرة</label>
            <div class="admin-toggle-row" style="gap:20px;">
              @foreach (['PDF', 'EPUB', 'MOBI'] as $format)
                <label style="display:flex; align-items:center; gap:6px; font-weight:600; font-size:13px;">
                  <input type="checkbox" name="formats[]" value="{{ $format }}" @checked(in_array($format, $selectedFormats))> {{ $format }}
                </label>
              @endforeach
            </div>
          </div>
          <div class="admin-form-field full">
            <label for="bookFile">ملف الكتاب (PDF، EPUB أو MOBI)</label>
            <input type="file" id="bookFile" name="book_file" class="admin-input" accept=".pdf,.epub,.mobi">
            <span class="admin-file-name" id="bookFileName"></span>
            @error('book_file')
              <span class="admin-field-error">{{ $message }}</span>
            @enderror
          </div>
        </div>
      </div>
    </div>

    <!-- ---- Cover sidebar ---- -->
    <div>
      <div class="admin-form-section">
        <h3>صورة الغلاف</h3>
        <label class="admin-cover-upload" id="coverUpload">
          <i class="fa-solid fa-image"></i>
          <span>اضغط لرفع صورة الغلاف<br>(JPG أو PNG، نسبة 2:3)</span>
          <img id="coverPreview" alt="معاينة الغلاف" hidden>
        </label>
        <input type="file" id="coverInput" name="cover_image" accept="image/*">
      </div>
    </div>

  </div>

  <div class="admin-form-actions">
    <a href="{{ route('admin.books.index') }}" class="btn btn-outline">إلغاء</a>
    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-paper-plane"></i> نشر الكتاب</button>
  </div>
</form>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/book-form.js') }}"></script>
@endpush
