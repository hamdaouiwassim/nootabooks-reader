@extends('admin.layouts.admin')

@section('title', 'تعديل الكتاب: '.$book->title.' - مكتبتي')

@php
  $pageTitle = 'تعديل الكتاب';
  $breadcrumb = [
    ['label' => 'إدارة الكتب', 'url' => route('admin.books.index')],
    ['label' => 'تعديل', 'url' => null],
  ];
  $selectedFormats = old('formats', $book->formats ?? []);
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>تعديل الكتاب: {{ $book->title }}</h1>
    <p>حدّث بيانات الكتاب ثم اضغط حفظ التعديلات</p>
  </div>
  <a href="{{ route('admin.books.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<form id="bookForm" method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data" novalidate>
  @csrf
  @method('PUT')
  <div class="admin-form-layout">

    <!-- ---- Main fields ---- -->
    <div>
      <div class="admin-form-section">
        <h3>معلومات الكتاب</h3>
        <div class="admin-form-grid">
          <div class="admin-form-field full">
            <label for="bookTitle">عنوان الكتاب</label>
            <input type="text" id="bookTitle" name="title" class="admin-input" value="{{ old('title', $book->title) }}" required>
          </div>
          <div class="admin-form-field">
            <label for="bookWriter">المؤلف</label>
            <select id="bookWriter" name="writer_id" class="admin-select">
              <option value="">بدون مؤلف محدد</option>
              @foreach ($writers as $writer)
                <option value="{{ $writer->id }}" @selected(old('writer_id', $book->writer_id) == $writer->id)>{{ $writer->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="admin-form-field">
            <label for="bookCategory">التصنيف</label>
            <select id="bookCategory" name="category_id" class="admin-select" required>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id) == $category->id)>{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="admin-form-field">
            <label for="bookLanguage">اللغة</label>
            <select id="bookLanguage" name="language" class="admin-select">
              @foreach (['العربية', 'الإنجليزية', 'مترجم إلى العربية'] as $language)
                <option @selected(old('language', $book->language) === $language)>{{ $language }}</option>
              @endforeach
            </select>
          </div>
          <div class="admin-form-field">
            <label for="bookYear">سنة النشر</label>
            <input type="number" id="bookYear" name="published_year" class="admin-input" value="{{ old('published_year', $book->published_year) }}" min="1900" max="2100">
          </div>
          <div class="admin-form-field">
            <label for="bookPages">عدد الصفحات</label>
            <input type="number" id="bookPages" name="pages_count" class="admin-input" value="{{ old('pages_count', $book->pages_count) }}" min="1">
          </div>
          <div class="admin-form-field full">
            <label for="bookDescShort">نبذة مختصرة</label>
            <input type="text" id="bookDescShort" name="description_short" class="admin-input" value="{{ old('description_short', $book->description_short) }}">
          </div>
          <div class="admin-form-field full">
            <label for="bookDesc">وصف الكتاب</label>
            <textarea id="bookDesc" name="description" class="admin-textarea" rows="5">{{ old('description', $book->description) }}</textarea>
          </div>
        </div>
      </div>

      <div class="admin-form-section">
        <h3>الملف والصيغ</h3>
        <div class="admin-form-grid">
          <div class="admin-form-field">
            <label for="bookFileSize">حجم الملف (ميجابايت)</label>
            <input type="number" id="bookFileSize" name="file_size_mb" class="admin-input" value="{{ old('file_size_mb', $book->file_size_mb) }}" min="0" step="0.01">
          </div>
          <div class="admin-form-field">
            <label for="bookTags">الوسوم (مفصولة بفاصلة)</label>
            <input type="text" id="bookTags" name="tags" class="admin-input" value="{{ old('tags', implode('، ', $book->tags ?? [])) }}">
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
        </div>
      </div>

      <div class="admin-form-section">
        <h3>إحصائيات</h3>
        <div class="admin-stats-grid" style="grid-template-columns: repeat(3, 1fr);">
          <div class="admin-stat-card">
            <div><strong>{{ number_format($book->downloads_count) }}</strong><span>تحميل</span></div>
          </div>
          <div class="admin-stat-card">
            <div><strong>{{ number_format($book->rating_average, 1) }}</strong><span>متوسط التقييم</span></div>
          </div>
          <div class="admin-stat-card">
            <div><strong>{{ number_format($book->rating_count) }}</strong><span>عدد التقييمات</span></div>
          </div>
        </div>
      </div>

      <div class="admin-form-section" style="border-color:#f3c7c1; background:#fdecea;">
        <h3 style="color:#c0392b;"><i class="fa-solid fa-triangle-exclamation"></i> منطقة الخطر</h3>
        <p style="font-size:13px; color:#7a3129; line-height:1.8; margin-bottom:16px;">حذف هذا الكتاب إجراء نهائي ولا يمكن التراجع عنه. سيتم إزالته فورًا من المنصة ومن مكتبات جميع المستخدمين.</p>
        <form method="POST" action="{{ route('admin.books.destroy', $book) }}" data-confirm-delete data-item-title="{{ $book->title }}">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">
            <i class="fa-solid fa-trash"></i> حذف هذا الكتاب نهائيًا
          </button>
        </form>
      </div>
    </div>

    <!-- ---- Cover sidebar ---- -->
    <div>
      <div class="admin-form-section">
        <h3>صورة الغلاف</h3>
        <label class="admin-cover-upload @if($book->cover_image) has-image @endif" id="coverUpload">
          <i class="fa-solid fa-image"></i>
          <span>اضغط لرفع صورة الغلاف<br>(JPG أو PNG، نسبة 2:3)</span>
          <img id="coverPreview" @if($book->cover_image) src="{{ $book->cover_image_url }}" @else hidden @endif alt="معاينة الغلاف">
        </label>
        <input type="file" id="coverInput" name="cover_image" accept="image/*">
      </div>
    </div>

  </div>

  <div class="admin-form-actions">
    <a href="{{ route('admin.books.index') }}" class="btn btn-outline">إلغاء</a>
    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-floppy-disk"></i> حفظ التعديلات</button>
  </div>
</form>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا الكتاب؟</h3>
    <p>سيتم حذف الكتاب بشكل نهائي من المنصة، ولن تتمكن من التراجع عن هذا الإجراء.</p>
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
