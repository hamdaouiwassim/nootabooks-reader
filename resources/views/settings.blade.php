@extends('layouts.app')

@section('title', 'الإعدادات - نوته بوك')
@section('robots', 'noindex, nofollow')

@push('styles')
<link rel="stylesheet" href="{{ asset_min('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/auth.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/settings.css') }}">
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>الإعدادات</span>
  </nav>
</div>

<section class="section">
  <h1 class="settings-title">الإعدادات</h1>
  <p class="settings-subtitle">إدارة معلومات حسابك وتفضيلاتك</p>
</section>

<!-- ===================== SETTINGS LAYOUT ===================== -->
<section class="section settings-layout">

  <nav class="settings-nav">
    <button class="settings-nav-btn active" data-panel="personal"><i class="fa-regular fa-user"></i> المعلومات الشخصية</button>
    <button class="settings-nav-btn" data-panel="security"><i class="fa-solid fa-lock"></i> الأمان</button>
    <button class="settings-nav-btn" data-panel="notifications"><i class="fa-regular fa-bell"></i> الإشعارات</button>
    <button class="settings-nav-btn" data-panel="privacy"><i class="fa-solid fa-shield-halved"></i> الخصوصية</button>
    <button class="settings-nav-btn danger" data-panel="account"><i class="fa-solid fa-triangle-exclamation"></i> إدارة الحساب</button>
  </nav>

  <div class="settings-content">

    <!-- ---- Personal Info ---- -->
    <div class="settings-panel active" id="panel-personal">
      <h2>المعلومات الشخصية</h2>

      <div class="avatar-upload-row">
        <img src="https://i.pravatar.cc/120?img=13" alt="أحمد محمد">
        <div>
          <button type="button" class="btn btn-outline small">تغيير الصورة</button>
          <p class="field-hint">JPG أو PNG، بحد أقصى 2 ميجابايت</p>
        </div>
      </div>

      <form id="personalForm">
        <div class="form-field">
          <label for="fullName">الاسم الكامل</label>
          <div class="input-with-icon">
            <i class="fa-regular fa-user"></i>
            <input type="text" id="fullName" value="أحمد محمد">
          </div>
        </div>

        <div class="form-field">
          <label for="settingsEmail">البريد الإلكتروني</label>
          <div class="input-with-icon">
            <i class="fa-regular fa-envelope"></i>
            <input type="email" id="settingsEmail" value="ahmed.m@example.com">
          </div>
        </div>

        <div class="form-field">
          <label for="location">الموقع</label>
          <div class="input-with-icon">
            <i class="fa-solid fa-location-dot"></i>
            <input type="text" id="location" value="القاهرة، مصر">
          </div>
        </div>

        <div class="form-field">
          <label for="bio">نبذة عني</label>
          <textarea id="bio" rows="4">قارئ شغوف بالروايات النفسية والأدب العربي المعاصر. أشارك آرائي في الكتب وأبحث دائمًا عن توصية جديدة 📚</textarea>
        </div>

        <button type="submit" class="btn btn-teal">حفظ التغييرات</button>
        <p class="form-success" id="personalSuccess" hidden><i class="fa-solid fa-circle-check"></i> تم حفظ التغييرات بنجاح</p>
      </form>
    </div>

    <!-- ---- Security ---- -->
    <div class="settings-panel" id="panel-security">
      <h2>الأمان</h2>

      <form id="securityForm">
        <div class="form-field">
          <label for="currentPassword">كلمة المرور الحالية</label>
          <div class="input-with-icon">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="currentPassword" placeholder="ادخل كلمة المرور الحالية">
            <button type="button" class="toggle-password" aria-label="show password"><i class="fa-regular fa-eye"></i></button>
          </div>
        </div>

        <div class="form-field">
          <label for="newPassword">كلمة المرور الجديدة</label>
          <div class="input-with-icon">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="newPassword" placeholder="8 أحرف على الأقل">
            <button type="button" class="toggle-password" aria-label="show password"><i class="fa-regular fa-eye"></i></button>
          </div>
        </div>

        <div class="form-field">
          <label for="confirmPassword">تأكيد كلمة المرور الجديدة</label>
          <div class="input-with-icon">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="confirmPassword" placeholder="أعد كتابة كلمة المرور">
            <button type="button" class="toggle-password" aria-label="show password"><i class="fa-regular fa-eye"></i></button>
          </div>
          <span class="field-error" id="confirmPasswordError"></span>
        </div>

        <button type="submit" class="btn btn-teal">تحديث كلمة المرور</button>
        <p class="form-success" id="securitySuccess" hidden><i class="fa-solid fa-circle-check"></i> تم تحديث كلمة المرور بنجاح</p>
      </form>

      <div class="settings-divider"></div>

      <div class="switch-row">
        <div>
          <strong>المصادقة الثنائية</strong>
          <p>طبقة حماية إضافية عند تسجيل الدخول</p>
        </div>
        <label class="switch"><input type="checkbox"><span class="slider"></span></label>
      </div>
    </div>

    <!-- ---- Notifications ---- -->
    <div class="settings-panel" id="panel-notifications">
      <h2>الإشعارات</h2>

      <div class="switch-row">
        <div><strong>إشعارات البريد الإلكتروني</strong><p>استلام ملخص أسبوعي عبر البريد</p></div>
        <label class="switch"><input type="checkbox" checked><span class="slider"></span></label>
      </div>
      <div class="switch-row">
        <div><strong>الإعجابات والتعليقات</strong><p>عند تفاعل أحد مع مناقشاتك</p></div>
        <label class="switch"><input type="checkbox" checked><span class="slider"></span></label>
      </div>
      <div class="switch-row">
        <div><strong>نوادي القراءة</strong><p>تذكيرات بمواعيد المناقشات</p></div>
        <label class="switch"><input type="checkbox" checked><span class="slider"></span></label>
      </div>
      <div class="switch-row">
        <div><strong>إصدارات جديدة</strong><p>عند صدور كتاب من مؤلف تتابعه</p></div>
        <label class="switch"><input type="checkbox"><span class="slider"></span></label>
      </div>
      <div class="switch-row">
        <div><strong>النشرة الإخبارية</strong><p>عروض وتوصيات أسبوعية</p></div>
        <label class="switch"><input type="checkbox"><span class="slider"></span></label>
      </div>
    </div>

    <!-- ---- Privacy ---- -->
    <div class="settings-panel" id="panel-privacy">
      <h2>الخصوصية</h2>

      <div class="switch-row">
        <div><strong>حساب عام</strong><p>يمكن لأي شخص رؤية ملفك الشخصي ونشاطك</p></div>
        <label class="switch"><input type="checkbox" checked><span class="slider"></span></label>
      </div>
      <div class="switch-row">
        <div><strong>إظهار نشاط القراءة</strong><p>عرض الكتب التي تقرأها في ملفك الشخصي</p></div>
        <label class="switch"><input type="checkbox" checked><span class="slider"></span></label>
      </div>
      <div class="switch-row">
        <div><strong>السماح بالرسائل</strong><p>السماح للقراء الآخرين بمراسلتك مباشرة</p></div>
        <label class="switch"><input type="checkbox"><span class="slider"></span></label>
      </div>
    </div>

    <!-- ---- Account Management ---- -->
    <div class="settings-panel" id="panel-account">
      <h2>إدارة الحساب</h2>

      <div class="account-action-row">
        <div><strong>تصدير بياناتي</strong><p>حمّل نسخة من جميع بياناتك وتقييماتك ونشاطك</p></div>
        <button class="btn btn-outline">تصدير البيانات</button>
      </div>

      <div class="danger-zone">
        <h3><i class="fa-solid fa-triangle-exclamation"></i> منطقة الخطر</h3>
        <p>حذف حسابك إجراء نهائي ولا يمكن التراجع عنه. سيتم حذف جميع بياناتك ومراجعاتك ونشاطك في المجتمع بشكل دائم.</p>
        <button class="btn btn-danger" id="deleteAccountBtn">حذف الحساب نهائيًا</button>
      </div>
    </div>

  </div>
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/auth.js') }}"></script>
<script src="{{ asset_min('assets/js/settings.js') }}"></script>
@endpush
