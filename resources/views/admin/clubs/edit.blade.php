@extends('admin.layouts.admin')

@section('title', 'تعديل النادي: '.$club->name.' - مكتبتي')

@php
  $pageTitle = 'تعديل النادي';
  $breadcrumb = [
    ['label' => 'نوادي القراءة', 'url' => route('admin.clubs.index')],
    ['label' => 'تعديل', 'url' => null],
  ];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>تعديل النادي: {{ $club->name }}</h1>
    <p>حدّث بيانات النادي ثم اضغط حفظ التعديلات</p>
  </div>
  <a href="{{ route('admin.clubs.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<div class="admin-stats-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom:22px;">
  <div class="admin-stat-card">
    <div><strong>{{ number_format($club->members_count) }}</strong><span>عضو</span></div>
  </div>
  <div class="admin-stat-card">
    <div><strong>{{ number_format($club->discussions_count) }}</strong><span>مناقشة</span></div>
  </div>
</div>

<form method="POST" action="{{ route('admin.clubs.update', $club) }}" novalidate>
  @csrf
  @method('PUT')
  <div class="admin-form-section">
    <h3>معلومات النادي</h3>
    <div class="admin-form-grid">
      <div class="admin-form-field full">
        <label for="clubName">اسم النادي</label>
        <input type="text" id="clubName" name="name" class="admin-input" value="{{ old('name', $club->name) }}" required>
        @error('name')
          <span class="admin-field-error">{{ $message }}</span>
        @enderror
      </div>
      <div class="admin-form-field">
        <label for="clubCategory">التصنيف</label>
        <select id="clubCategory" name="category" class="admin-select">
          <option value="">بدون تصنيف</option>
          @foreach (['أدب عربي', 'أدب عالمي', 'روايات', 'تاريخ', 'تنمية ذاتية', 'شعر', 'فلسفة'] as $category)
            <option value="{{ $category }}" @selected(old('category', $club->category) === $category)>{{ $category }}</option>
          @endforeach
        </select>
      </div>
      <div class="admin-form-field full">
        <label for="clubDescription">وصف النادي</label>
        <textarea id="clubDescription" name="description" class="admin-textarea" rows="4" required>{{ old('description', $club->description) }}</textarea>
        @error('description')
          <span class="admin-field-error">{{ $message }}</span>
        @enderror
      </div>
      <div class="admin-form-field full">
        <label for="clubRules">قوانين النادي (اختياري، كل قانون في سطر منفصل)</label>
        <textarea id="clubRules" name="rules" class="admin-textarea" rows="4">{{ old('rules', $club->rules) }}</textarea>
        @error('rules')
          <span class="admin-field-error">{{ $message }}</span>
        @enderror
      </div>
    </div>
  </div>

  <div class="admin-form-actions">
    <a href="{{ route('admin.clubs.index') }}" class="btn btn-outline">إلغاء</a>
    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-floppy-disk"></i> حفظ التعديلات</button>
  </div>
</form>

<div class="admin-form-section" style="margin-top:22px;">
  <h3>الكتاب الحالي للنادي</h3>
  @if ($currentBook)
    <p style="font-size:14px; color:var(--text-gray); margin-bottom:14px;">
      يقرأ النادي حاليًا: <strong style="color:var(--navy);">{{ $currentBook->title }}</strong>
    </p>
  @else
    <p style="font-size:14px; color:var(--text-gray); margin-bottom:14px;">لا يقرأ النادي أي كتاب حاليًا.</p>
  @endif

  <form method="POST" action="{{ route('admin.clubs.set-current-book', $club) }}" class="admin-toggle-row" novalidate>
    @csrf
    <select name="book_id" class="admin-select" required style="flex:1;">
      <option value="" disabled selected>اختر كتابًا ليبدأ النادي بقراءته</option>
      @foreach ($books as $book)
        <option value="{{ $book->id }}">{{ $book->title }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn btn-teal">{{ $currentBook ? 'تغيير الكتاب' : 'تحديد الكتاب' }}</button>
  </form>

  @if ($pastBooks->isNotEmpty())
    <h3 style="margin-top:22px; font-size:14px;">الكتب السابقة</h3>
    <ul style="font-size:13px; color:var(--text-gray); line-height:2;">
      @foreach ($pastBooks as $pastBook)
        <li>{{ $pastBook->title }} — انتهى {{ \Carbon\Carbon::parse($pastBook->pivot->finished_at)->diffForHumans() }}</li>
      @endforeach
    </ul>
  @endif
</div>

<div class="admin-form-section" style="border-color:#f3c7c1; background:#fdecea; margin-top:22px;">
  <h3 style="color:#c0392b;"><i class="fa-solid fa-triangle-exclamation"></i> منطقة الخطر</h3>
  <p style="font-size:13px; color:#7a3129; line-height:1.8; margin-bottom:16px;">حذف هذا النادي إجراء نهائي وسيحذف جميع مناقشاته أيضًا. لا يمكن التراجع عن هذا الإجراء.</p>
  <form method="POST" action="{{ route('admin.clubs.destroy', $club) }}" data-confirm-delete data-item-title="{{ $club->name }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
      <i class="fa-solid fa-trash"></i> حذف هذا النادي نهائيًا
    </button>
  </form>
</div>

@endsection

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا النادي؟</h3>
    <p>سيتم حذف النادي وجميع مناقشاته بشكل نهائي، ولن تتمكن من التراجع عن هذا الإجراء.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush
