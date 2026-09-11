@extends('admin.layouts.admin')

@section('title', 'إضافة نادٍ جديد - مكتبتي')

@php
  $pageTitle = 'إضافة نادٍ جديد';
  $breadcrumb = [
    ['label' => 'نوادي القراءة', 'url' => route('admin.clubs.index')],
    ['label' => 'إضافة نادٍ', 'url' => null],
  ];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>إضافة نادٍ جديد</h1>
    <p>أنشئ ناديًا رسميًا يظهر مباشرة على المنصة</p>
  </div>
  <a href="{{ route('admin.clubs.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<form method="POST" action="{{ route('admin.clubs.store') }}" novalidate>
  @csrf
  <div class="admin-form-section">
    <div class="admin-form-grid">
      <div class="admin-form-field full">
        <label for="clubName">اسم النادي</label>
        <input type="text" id="clubName" name="name" class="admin-input" value="{{ old('name') }}" placeholder="مثال: أدب عربي معاصر" required>
        @error('name')
          <span class="admin-field-error">{{ $message }}</span>
        @enderror
      </div>
      <div class="admin-form-field">
        <label for="clubCategory">التصنيف</label>
        <select id="clubCategory" name="category" class="admin-select">
          <option value="">بدون تصنيف</option>
          @foreach (['أدب عربي', 'أدب عالمي', 'روايات', 'تاريخ', 'تنمية ذاتية', 'شعر', 'فلسفة'] as $category)
            <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
          @endforeach
        </select>
      </div>
      <div class="admin-form-field full">
        <label for="clubDescription">وصف النادي</label>
        <textarea id="clubDescription" name="description" class="admin-textarea" rows="4" placeholder="عن ماذا سيناقش النادي؟" required>{{ old('description') }}</textarea>
        @error('description')
          <span class="admin-field-error">{{ $message }}</span>
        @enderror
      </div>
      <div class="admin-form-field full">
        <label for="clubRules">قوانين النادي (اختياري، كل قانون في سطر منفصل)</label>
        <textarea id="clubRules" name="rules" class="admin-textarea" rows="4" placeholder="يُرجى تجنب حرق الأحداث (Spoilers)&#10;الاحترام المتبادل في كل النقاشات">{{ old('rules') }}</textarea>
        @error('rules')
          <span class="admin-field-error">{{ $message }}</span>
        @enderror
      </div>
    </div>
  </div>

  <div class="admin-form-actions">
    <a href="{{ route('admin.clubs.index') }}" class="btn btn-outline">إلغاء</a>
    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-paper-plane"></i> إنشاء النادي</button>
  </div>
</form>

@endsection
