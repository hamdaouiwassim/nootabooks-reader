@extends('admin.layouts.admin')

@section('title', 'تعديل الإعلان - مكتبتي')

@php
  $pageTitle = 'تعديل الإعلان';
  $breadcrumb = [
    ['label' => 'إدارة الإعلانات', 'url' => route('admin.advertisements.index')],
    ['label' => 'تعديل', 'url' => null],
  ];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>تعديل الإعلان</h1>
  </div>
  <a href="{{ route('admin.advertisements.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<form method="POST" action="{{ route('admin.advertisements.update', $advertisement) }}" enctype="multipart/form-data" novalidate>
  @csrf
  @method('PUT')

  <div class="admin-form-section">
    <h3>معلومات الإعلان</h3>
    <div class="admin-form-grid">
      <div class="admin-form-field">
        <label for="adName">اسم الإعلان (للإدارة فقط، لا يظهر للزوار)</label>
        <input type="text" id="adName" name="name" class="admin-input" value="{{ old('name', $advertisement->name) }}" required>
        @error('name')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field">
        <label for="adType">نوع الإعلان</label>
        <select id="adType" name="type" class="admin-input" required>
          @foreach (\App\Models\Advertisement::TYPES as $value => $label)
            <option value="{{ $value }}" @selected(old('type', $advertisement->type) === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('type')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field">
        <label for="adZone">مكان العرض</label>
        <select id="adZone" name="zone" class="admin-input" required>
          @foreach (\App\Models\Advertisement::ZONES as $value => $label)
            <option value="{{ $value }}" @selected(old('zone', $advertisement->zone) === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('zone')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field">
        <label for="adTargetUrl">رابط الوجهة (عند النقر على الإعلان)</label>
        <input type="url" id="adTargetUrl" name="target_url" class="admin-input" value="{{ old('target_url', $advertisement->target_url) }}" placeholder="https://" dir="ltr" required>
        @error('target_url')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field full" id="adHeadingField" @if ($advertisement->type !== 'image_text') hidden @endif>
        <label for="adHeading">عنوان الإعلان</label>
        <input type="text" id="adHeading" name="heading" class="admin-input" value="{{ old('heading', $advertisement->heading) }}">
        @error('heading')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field full" id="adBodyField" @if ($advertisement->type !== 'image_text') hidden @endif>
        <label for="adBodyText">نص الإعلان</label>
        <textarea id="adBodyText" name="body_text" class="admin-textarea" rows="3">{{ old('body_text', $advertisement->body_text) }}</textarea>
        @error('body_text')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field full">
        <label for="adAltText">النص البديل للصورة (alt)</label>
        <input type="text" id="adAltText" name="alt_text" class="admin-input" value="{{ old('alt_text', $advertisement->alt_text) }}" required>
        @error('alt_text')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
    </div>
  </div>

  <div class="admin-form-section">
    <h3>الجدولة والحالة</h3>
    <div class="admin-form-grid">
      <div class="admin-form-field">
        <label for="adStartDate">تاريخ البداية (اختياري)</label>
        <input type="date" id="adStartDate" name="start_date" class="admin-input" value="{{ old('start_date', $advertisement->start_date?->toDateString()) }}">
        @error('start_date')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field">
        <label for="adEndDate">تاريخ الانتهاء (اختياري)</label>
        <input type="date" id="adEndDate" name="end_date" class="admin-input" value="{{ old('end_date', $advertisement->end_date?->toDateString()) }}">
        @error('end_date')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field">
        <div class="admin-toggle-row">
          <div><strong>مفعّل</strong></div>
          <label class="admin-switch">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $advertisement->is_active))>
            <span class="admin-switch-slider"></span>
          </label>
        </div>
      </div>
    </div>
  </div>

  <div class="admin-form-section">
    <h3>ملفات الإعلان</h3>
    <p style="font-size:11px; color:var(--text-gray); margin-bottom:14px; line-height:1.7;">ارفع صورة مستقلة لكل نوع جهاز — تُضغط كل صورة تلقائيًا عند الرفع، إلا في حالة البانر المتحرك (GIF) حيث تُحفظ كما هي لتفادي فقدان الحركة. صورتا الأجهزة اللوحية والجوال اختياريتان: إن لم تُرفعا يُستخدم بدلًا منهما ملف سطح المكتب. رفع ملف جديد يستبدل الحالي.</p>
    <div class="admin-form-grid">
      <div class="admin-form-field">
        <label for="creativeInput">سطح المكتب</label>
        <label class="admin-cover-upload has-image" id="creativeUpload">
          <i class="fa-solid fa-image"></i>
          <span>اضغط لرفع ملف جديد</span>
          <img id="creativePreview" src="{{ $advertisement->creative_url }}" alt="معاينة الإعلان">
        </label>
        <input type="file" id="creativeInput" name="creative" accept="image/*,.gif,.webp">
        @error('creative')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field" id="creativeTabletField">
        <label for="creativeTabletInput">الأجهزة اللوحية (اختياري)</label>
        <label class="admin-cover-upload @if ($advertisement->creative_path_tablet) has-image @endif" id="creativeTabletUpload">
          <i class="fa-solid fa-image"></i>
          <span>اضغط لرفع صورة جديدة</span>
          <img id="creativeTabletPreview" @if ($advertisement->creative_path_tablet) src="{{ $advertisement->creative_tablet_url }}" @else hidden @endif alt="معاينة صورة الأجهزة اللوحية">
        </label>
        <input type="file" id="creativeTabletInput" name="creative_tablet" accept="image/*">
        @error('creative_tablet')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
      <div class="admin-form-field" id="creativeMobileField">
        <label for="creativeMobileInput">الجوال (اختياري)</label>
        <label class="admin-cover-upload @if ($advertisement->creative_path_mobile) has-image @endif" id="creativeMobileUpload">
          <i class="fa-solid fa-image"></i>
          <span>اضغط لرفع صورة جديدة</span>
          <img id="creativeMobilePreview" @if ($advertisement->creative_path_mobile) src="{{ $advertisement->creative_mobile_url }}" @else hidden @endif alt="معاينة صورة الجوال">
        </label>
        <input type="file" id="creativeMobileInput" name="creative_mobile" accept="image/*">
        @error('creative_mobile')<span class="admin-field-error">{{ $message }}</span>@enderror
      </div>
    </div>
  </div>

  <div class="admin-form-actions">
    <a href="{{ route('admin.advertisements.index') }}" class="btn btn-outline">إلغاء</a>
    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-floppy-disk"></i> حفظ التعديلات</button>
  </div>
</form>

<div class="admin-form-section" style="border-color:#f3c7c1; background:#fdecea; margin-top:22px;">
  <h3 style="color:#c0392b;"><i class="fa-solid fa-triangle-exclamation"></i> منطقة الخطر</h3>
  <p style="font-size:13px; color:#7a3129; line-height:1.8; margin-bottom:16px;">حذف هذا الإعلان نهائي ولا يمكن التراجع عنه.</p>
  <form method="POST" action="{{ route('admin.advertisements.destroy', $advertisement) }}" data-confirm-delete data-item-title="{{ $advertisement->name }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
      <i class="fa-solid fa-trash"></i> حذف هذا الإعلان نهائيًا
    </button>
  </form>
</div>

@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/advertisement-form.js') }}" defer></script>
@endpush

@push('modals')
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا الإعلان؟</h3>
    <p>سيتم حذف الإعلان نهائيًا من المنصة.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
@endpush
