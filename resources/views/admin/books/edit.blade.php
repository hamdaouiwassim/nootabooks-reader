@extends('admin.layouts.admin')

@section('title', 'تعديل الكتاب: '.$book->title.' - مكتبتي')

@php
  $pageTitle = 'تعديل الكتاب';
  $breadcrumb = [
    ['label' => 'إدارة الكتب', 'url' => route('admin.books.index')],
    ['label' => 'تعديل', 'url' => null],
  ];
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
          <div class="admin-form-field full">
            <label for="bookTitleEn">العنوان بالإنجليزية (اختياري)</label>
            <input type="text" id="bookTitleEn" name="title_en" class="admin-input" value="{{ old('title_en', $book->title_en) }}" dir="ltr">
          </div>
          <div class="admin-form-field">
            <label for="bookWriterSearch">المؤلف</label>
            @php $selectedWriterId = old('writer_id', $book->writer_id); $oldWriterName = $writers->firstWhere('id', (int) $selectedWriterId)?->name; @endphp
            <div class="admin-combobox" id="writerCombobox">
              <input type="text" id="bookWriterSearch" class="admin-input" placeholder="ابحث عن مؤلف ..." autocomplete="off" value="{{ $oldWriterName }}">
              <input type="hidden" name="writer_id" id="bookWriterId" value="{{ $selectedWriterId }}">
              <div class="admin-combobox-list" id="writerComboboxList" hidden>
                <div class="admin-combobox-option" data-id="" data-name="بدون مؤلف محدد">بدون مؤلف محدد</div>
                @foreach ($writers as $writer)
                  <div class="admin-combobox-option" data-id="{{ $writer->id }}" data-name="{{ $writer->name }}">{{ $writer->name }}</div>
                @endforeach
              </div>
            </div>
          </div>
          <div class="admin-form-field">
            <label for="bookCategory">التصنيف الأساسي</label>
            <select id="bookCategory" name="category_id" class="admin-select" required>
              @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id) == $category->id)>{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="admin-form-field full">
            <label>تصنيفات إضافية (اختياري)</label>
            <div class="admin-checkbox-grid">
              @foreach ($categories as $category)
                <label class="admin-checkbox-chip">
                  <input type="checkbox" name="categories[]" value="{{ $category->id }}" @checked(in_array($category->id, old('categories', $selectedCategoryIds)))>
                  <span>{{ $category->name }}</span>
                </label>
              @endforeach
            </div>
            <span class="hint">يمكن ربط الكتاب بأكثر من تصنيف، إلى جانب التصنيف الأساسي أعلاه</span>
          </div>
          <div class="admin-form-field">
            <label for="bookSeriesName">السلسلة (اختياري)</label>
            <input type="text" id="bookSeriesName" name="series_name" list="seriesDatalist" class="admin-input" value="{{ old('series_name', $book->series?->name) }}" placeholder="مثال: ثلاثية الأرض">
            <datalist id="seriesDatalist">
              @foreach ($allSeries as $series)
                <option value="{{ $series->name }}">
              @endforeach
            </datalist>
            <span class="hint">اكتب اسم سلسلة موجودة لربط الكتاب بها، أو اسمًا جديدًا لإنشاء سلسلة جديدة، أو اترُكه فارغًا لإلغاء الربط</span>
          </div>
          <div class="admin-form-field">
            <label for="bookSeriesOrder">رقم الجزء</label>
            <input type="number" id="bookSeriesOrder" name="series_order" class="admin-input" value="{{ old('series_order', $book->series_order) }}" min="1" placeholder="مثال: 1">
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
            <label for="bookStatus">حالة النشر</label>
            <select id="bookStatus" name="status" class="admin-select">
              <option value="published" @selected(old('status', $book->status) === 'published')>منشور (ظاهر للجميع)</option>
              <option value="draft" @selected(old('status', $book->status) === 'draft')>مخفي (غير ظاهر على المنصة)</option>
            </select>
          </div>
          <div class="admin-form-field">
            <label for="bookYear">سنة النشر</label>
            <input type="number" id="bookYear" name="published_year" class="admin-input" value="{{ old('published_year', $book->published_year) }}" min="1900" max="2100">
          </div>
          <div class="admin-form-field">
            <label for="bookPages">عدد الصفحات</label>
            <input type="number" id="bookPages" name="pages_count" class="admin-input" value="{{ old('pages_count', $book->pages_count) }}" min="1">
            <span class="hint">يتم تعبئته تلقائيًا عند رفع ملف PDF</span>
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
        <h3>إعدادات السيو (اختياري)</h3>
        <p style="font-size:11px; color:var(--text-gray); margin-bottom:14px; line-height:1.7;">اتركها فارغة ليتم توليد عنوان ووصف مناسبين لمحركات البحث تلقائيًا من عنوان الكتاب ونبذته. المُولَّد حاليًا:</p>
        <div class="admin-form-grid">
          <div class="admin-form-field full">
            <label for="bookSeoTitle">عنوان السيو</label>
            <input type="text" id="bookSeoTitle" name="seo_title" class="admin-input" value="{{ old('seo_title', $book->seo_title) }}" placeholder="{{ $book->resolved_seo_title }}">
          </div>
          <div class="admin-form-field full">
            <label for="bookSeoDescription">وصف السيو</label>
            <textarea id="bookSeoDescription" name="seo_description" class="admin-textarea" rows="3" maxlength="500" placeholder="{{ $book->resolved_seo_description }}">{{ old('seo_description', $book->seo_description) }}</textarea>
          </div>
        </div>
      </div>

      <div class="admin-form-section">
        <h3>ملف الكتاب</h3>
        <div class="admin-form-grid">
          <div class="admin-form-field">
            <label for="bookFileSize">حجم الملف (ميجابايت)</label>
            <input type="number" id="bookFileSize" name="file_size_mb" class="admin-input" value="{{ old('file_size_mb', $book->file_size_mb) }}" min="0" step="0.01">
            <span class="hint">يتم تعبئته تلقائيًا عند رفع ملف الكتاب</span>
          </div>
          <div class="admin-form-field">
            <label for="bookTags">الوسوم (مفصولة بفاصلة)</label>
            <input type="text" id="bookTags" name="tags" class="admin-input" value="{{ old('tags', implode('، ', $book->tags ?? [])) }}">
          </div>
          <div class="admin-form-field full">
            <label for="bookFile">ملف الكتاب (PDF)</label>
            <input type="file" id="bookFile" name="book_file" class="admin-input" accept=".pdf">
            @if ($book->file_path)
              <div class="admin-file-current">
                <i class="fa-solid fa-file-arrow-down"></i>
                <a href="{{ $book->file_url }}" target="_blank" rel="noopener">عرض الملف الحالي</a>
              </div>
            @endif
            <span class="admin-file-name" id="bookFileName"></span>
            @error('book_file')
              <span class="admin-field-error">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="admin-toggle-row" style="margin-top:16px;">
          <div>
            <strong>كتاب قريبًا</strong>
            <p>يظهر الكتاب بدون إمكانية القراءة أو التحميل مع إشارة "قريبًا"</p>
          </div>
          <label class="admin-switch">
            <input type="checkbox" name="is_coming_soon" value="1" @checked(old('is_coming_soon', $book->is_coming_soon))>
            <span class="admin-switch-slider"></span>
          </label>
        </div>
        <div class="admin-toggle-row" style="margin-top:16px;">
          <div>
            <strong>منع التحميل</strong>
            <p>يبقى الكتاب ظاهرًا في البحث والقوائم وصفحته، لكن زر التحميل يظهر معطلاً بعبارة "غير متاح للتحميل"</p>
          </div>
          <label class="admin-switch">
            <input type="checkbox" name="download_disabled" value="1" @checked(old('download_disabled', $book->download_disabled))>
            <span class="admin-switch-slider"></span>
          </label>
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
        <a href="{{ route('admin.books.stats', $book) }}" class="btn btn-outline" style="margin-top:16px; width:100%; justify-content:center;"><i class="fa-solid fa-chart-line"></i> عرض إحصائيات التحميل اليومية</a>
      </div>

    </div>

    <!-- ---- Cover sidebar ---- -->
    <div>
      <div class="admin-form-section">
        <h3>غلاف الكتاب</h3>
        <p style="font-size:11px; color:var(--text-gray); margin-bottom:14px; line-height:1.7;">يتم ضغط الصورة تلقائيًا إلى 300×450 عند الرفع وتُستخدم بهذا الحجم في كل صفحات الموقع. رفع غلاف جديد يستبدل الحالي.</p>

        <div>
          <label for="coverInput" class="admin-cover-caption">غلاف الكتاب</label>
          <label class="admin-cover-upload @if($book->cover_image) has-image @endif" id="coverUpload">
            <i class="fa-solid fa-image"></i>
            <span>اضغط لرفع غلاف الكتاب<br>(JPG أو PNG، نسبة 2:3)</span>
            <img id="coverPreview" @if($book->cover_image) src="{{ $book->cover_image_url }}" @else hidden @endif alt="معاينة الغلاف">
          </label>
          <input type="file" id="coverInput" name="cover_image" accept="image/*">
          @error('cover_image')
            <span class="admin-field-error">{{ $message }}</span>
          @enderror
        </div>
      </div>
    </div>

  </div>

  <div class="admin-form-actions">
    <a href="{{ route('admin.books.index') }}" class="btn btn-outline">إلغاء</a>
    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-floppy-disk"></i> حفظ التعديلات</button>
  </div>
</form>

<div class="admin-form-section" style="border-color:#f3c7c1; background:#fdecea; margin-top:22px;">
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
<script src="{{ asset_min('assets/js/book-form.js') }}" defer></script>
@endpush
