@extends('admin.layouts.admin')

@section('title', 'رفع أغلفة بالجملة - مكتبتي')

@php
  $pageTitle = 'رفع أغلفة بالجملة';
  $breadcrumb = [
    ['label' => 'إدارة الكتب', 'url' => route('admin.books.index')],
    ['label' => 'رفع أغلفة بالجملة', 'url' => null],
  ];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>رفع أغلفة بالجملة</h1>
    <p>لتحديث أغلفة عدة كتب دفعة واحدة بعد ضغطها/تحسينها على جهازك</p>
  </div>
  <a href="{{ route('admin.books.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<div class="admin-form-section">
  <h3>كيف تعمل هذه الميزة</h3>
  <p style="font-size:13px; color:var(--text-gray); line-height:1.9;">
    سمِّ كل ملف صورة برابط الكتاب (slug) كما يظهر في الجدول أسفل الصفحة — مثلًا <code>blue-elephant.jpg</code> لكتاب رابطه <code>blue-elephant</code> — ثم اختر كل الملفات معًا وارفعها. سيتم التعرف على كل ملف تلقائيًا وتحديث غلاف الكتاب المطابق له، مع توليد نفس نسخ الصورة المصغّرة (WebP) المستخدمة في بقية المنصة تلقائيًا — لا حاجة لأي معالجة إضافية غير ما تقوم به أنت لضغط/تحسين الصور محليًا قبل الرفع.
  </p>
</div>

@if (session('bulkUpdated') || session('bulkUnmatched'))
  <div class="admin-form-section" style="margin-top:20px;">
    <h3>نتيجة آخر عملية رفع</h3>

    @if (session('bulkUpdated') && count(session('bulkUpdated')))
      <p style="font-size:13px; color:#2f7a4a; font-weight:700; margin-bottom:8px;">
        <i class="fa-solid fa-circle-check"></i> تم تحديث غلاف {{ count(session('bulkUpdated')) }} كتاب:
      </p>
      <ul style="font-size:13px; color:var(--text-gray); line-height:1.9; margin-bottom:16px;">
        @foreach (session('bulkUpdated') as $title)
          <li>{{ $title }}</li>
        @endforeach
      </ul>
    @endif

    @if (session('bulkUnmatched') && count(session('bulkUnmatched')))
      <p style="font-size:13px; color:#c0392b; font-weight:700; margin-bottom:8px;">
        <i class="fa-solid fa-triangle-exclamation"></i> لم يتم العثور على كتاب مطابق لهذه الملفات (تأكد أن اسم الملف يطابق رابط الكتاب بالضبط):
      </p>
      <ul style="font-size:13px; color:var(--text-gray); line-height:1.9;">
        @foreach (session('bulkUnmatched') as $filename)
          <li>{{ $filename }}</li>
        @endforeach
      </ul>
    @endif
  </div>
@endif

<form method="POST" action="{{ route('admin.books.bulk-covers.store') }}" enctype="multipart/form-data" class="admin-form-section" style="margin-top:20px;">
  @csrf
  <h3>رفع الملفات</h3>
  <div class="admin-form-field full">
    <label for="bulkCovers">اختر صور الأغلفة (يمكن اختيار عدة ملفات معًا)</label>
    <input type="file" id="bulkCovers" name="covers[]" class="admin-input" accept="image/*" multiple required>
    @error('covers')
      <span class="admin-field-error">{{ $message }}</span>
    @enderror
    @error('covers.*')
      <span class="admin-field-error">{{ $message }}</span>
    @enderror
  </div>
  <div class="admin-form-actions">
    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-cloud-arrow-up"></i> رفع وتحديث الأغلفة</button>
  </div>
</form>

<div class="admin-form-section" style="margin-top:20px;">
  <h3>روابط الكتب (slugs)</h3>
  <div class="admin-search-box" style="margin-bottom:14px;">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" id="bookSlugSearch" placeholder="ابحث بعنوان الكتاب أو رابطه ...">
  </div>
  <div class="admin-table-wrap">
    <table class="admin-table" id="bookSlugTable">
      <thead>
        <tr>
          <th>الكتاب</th>
          <th>رابط الكتاب (اسم الملف المطلوب)</th>
          <th>الغلاف الحالي</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($books as $book)
          <tr data-search="{{ \Illuminate\Support\Str::lower($book->title.' '.$book->slug) }}">
            <td>{{ $book->title }}</td>
            <td><code>{{ $book->slug }}</code></td>
            <td>{{ $book->cover_image ? 'يوجد غلاف' : '—' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@endsection

@push('scripts')
<script>
  document.getElementById('bookSlugSearch')?.addEventListener('input', function () {
    const term = this.value.trim().toLowerCase();
    document.querySelectorAll('#bookSlugTable tbody tr').forEach((row) => {
      row.hidden = term !== '' && !row.dataset.search.includes(term);
    });
  });
</script>
@endpush
