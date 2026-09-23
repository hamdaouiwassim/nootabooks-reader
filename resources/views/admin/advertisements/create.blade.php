@extends('admin.layouts.admin')

@section('title', 'إضافة إعلان - مكتبتي')

@php
  $pageTitle = 'إضافة إعلان';
  $breadcrumb = [
    ['label' => 'إدارة الإعلانات', 'url' => route('admin.advertisements.index')],
    ['label' => 'إضافة', 'url' => null],
  ];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>إضافة إعلان جديد</h1>
    <p>يتم عرض إعلان واحد عشوائيًا من بين الإعلانات المفعّلة في كل مكان عرض</p>
  </div>
  <a href="{{ route('admin.advertisements.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<form method="POST" action="{{ route('admin.advertisements.store') }}" enctype="multipart/form-data" novalidate>
  @csrf

  <div class="admin-form-section">
    <h3>معلومات الإعلان</h3>
    <div class="admin-form-grid">
      <div class="admin-form-field">
        <label for="adName">اسم الإعلان (للإدارة فقط، لا يظهر للزوار)</label>
        <input type="text" id="adName" name="name" class="admin-input" value="{{ old('name') }}" required>
        @error('name')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field">
        <label for="adType">نوع الإعلان</label>
        <select id="adType" name="type" class="admin-input" required>
          @foreach (\App\Models\Advertisement::TYPES as $value => $label)
            <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('type')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field">
        <label for="adZone">مكان العرض</label>
        <select id="adZone" name="zone" class="admin-input" required>
          @foreach (\App\Models\Advertisement::ZONES as $value => $label)
            <option value="{{ $value }}" @selected(old('zone') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('zone')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field">
        <label for="adTargetUrl">رابط الوجهة (عند النقر على الإعلان)</label>
        <input type="url" id="adTargetUrl" name="target_url" class="admin-input" value="{{ old('target_url') }}" placeholder="https://" dir="ltr" required>
        @error('target_url')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field full" id="adHeadingField" hidden>
        <label for="adHeading">عنوان الإعلان</label>
        <input type="text" id="adHeading" name="heading" class="admin-input" value="{{ old('heading') }}">
        @error('heading')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field full" id="adBodyField" hidden>
        <label for="adBodyText">نص الإعلان</label>
        <textarea id="adBodyText" name="body_text" class="admin-textarea" rows="3">{{ old('body_text') }}</textarea>
        @error('body_text')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field full">
        <label for="adAltText">النص البديل للصورة (alt)</label>
        <input type="text" id="adAltText" name="alt_text" class="admin-input" value="{{ old('alt_text') }}" required>
        @error('alt_text')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
    </div>
  </div>

  <div class="admin-form-section">
    <h3>الجدولة والحالة</h3>
    <div class="admin-form-grid">
      <div class="admin-form-field">
        <label for="adStartDate">تاريخ البداية (اختياري)</label>
        <input type="date" id="adStartDate" name="start_date" class="admin-input" value="{{ old('start_date') }}">
        @error('start_date')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field">
        <label for="adEndDate">تاريخ الانتهاء (اختياري)</label>
        <input type="date" id="adEndDate" name="end_date" class="admin-input" value="{{ old('end_date') }}">
        @error('end_date')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field">
        <div class="admin-toggle-row">
          <div><strong>مفعّل</strong></div>
          <label class="admin-switch">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
            <span class="admin-switch-slider"></span>
          </label>
        </div>
      </div>
    </div>
  </div>

  <div class="admin-form-section">
    <h3>ملف الإعلان</h3>
    <p style="font-size:11px; color:var(--text-gray); margin-bottom:14px; line-height:1.7;">صورة أو بانر متحرك (GIF) حسب النوع المختار أعلاه — يُضغط تلقائيًا إلا في حالة البانر المتحرك (لتفادي فقدان الحركة).</p>
    <div>
      <label class="admin-cover-upload" id="creativeUpload">
        <i class="fa-solid fa-image"></i>
        <span>اضغط لرفع ملف الإعلان</span>
        <img id="creativePreview" alt="معاينة الإعلان" hidden>
      </label>
      <input type="file" id="creativeInput" name="creative" accept="image/*,.gif,.webp" required>
      @error('creative')<span class="admin-field-error">{{ $message }}</span>@enderror
    </div>
  </div>

  <div class="admin-form-actions">
    <a href="{{ route('admin.advertisements.index') }}" class="btn btn-outline">إلغاء</a>
    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-paper-plane"></i> إضافة الإعلان</button>
  </div>
</form>

@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/advertisement-form.js') }}" defer></script>
@endpush
