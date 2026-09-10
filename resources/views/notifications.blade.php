@extends('layouts.app')

@section('title', 'الإشعارات - نوته بوك')
@section('robots', 'noindex, nofollow')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/notifications.css') }}">
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>الإشعارات</span>
  </nav>
</div>

<!-- ===================== NOTIFICATIONS PAGE ===================== -->
<section class="section notif-page-section">

  <div class="notif-page-head">
    <div>
      <h1 class="notif-page-title">الإشعارات</h1>
      <p class="notif-page-subtitle">تابع آخر التحديثات والتفاعلات على حسابك</p>
    </div>
    <button class="mark-read-btn large" id="markAllReadPageBtn"><i class="fa-solid fa-check-double"></i> تحديد الكل كمقروء</button>
  </div>

  <div class="notif-filter-tabs">
    <button class="notif-filter-tab active" data-filter="all">الكل</button>
    <button class="notif-filter-tab" data-filter="unread">غير مقروءة</button>
    <button class="notif-filter-tab" data-filter="social">الإعجابات والتعليقات</button>
    <button class="notif-filter-tab" data-filter="clubs">نوادي القراءة</button>
    <button class="notif-filter-tab" data-filter="books">الكتب والتحميلات</button>
  </div>

  <!-- ---- Today ---- -->
  <div class="notif-group">
    <h2 class="notif-group-title">اليوم</h2>
    <div class="notif-page-list">
      <a href="{{ route('discussion-details') }}" class="notif-page-item unread" data-filter="social">
        <span class="notif-icon like"><i class="fa-solid fa-thumbs-up"></i></span>
        <div class="notif-text">
          <p><strong>محمد العتيبي</strong> أعجب بمنشورك في المناقشة حول "الفيل الأزرق"</p>
          <span class="notif-time">منذ 5 دقائق</span>
        </div>
        <button class="notif-dismiss-btn" aria-label="dismiss"><i class="fa-solid fa-xmark"></i></button>
      </a>
      <a href="{{ route('discussion-details') }}" class="notif-page-item unread" data-filter="social">
        <span class="notif-icon comment"><i class="fa-solid fa-comment"></i></span>
        <div class="notif-text">
          <p><strong>سارة محمود</strong> علّقت على مناقشتك: "رأيكم إيه في النهاية؟"</p>
          <span class="notif-time">منذ ساعة</span>
        </div>
        <button class="notif-dismiss-btn" aria-label="dismiss"><i class="fa-solid fa-xmark"></i></button>
      </a>
      <a href="{{ route('book-details', 'blue-elephant') }}" class="notif-page-item unread" data-filter="books">
        <span class="notif-icon book"><i class="fa-solid fa-book"></i></span>
        <div class="notif-text">
          <p>صدر كتاب جديد لـ <strong>أحمد مراد</strong> بعنوان "تراب الماس"</p>
          <span class="notif-time">منذ 3 ساعات</span>
        </div>
        <button class="notif-dismiss-btn" aria-label="dismiss"><i class="fa-solid fa-xmark"></i></button>
      </a>
    </div>
  </div>

  <!-- ---- Yesterday ---- -->
  <div class="notif-group">
    <h2 class="notif-group-title">أمس</h2>
    <div class="notif-page-list">
      <a href="{{ route('club-details', 'arabic-literature') }}" class="notif-page-item" data-filter="clubs">
        <span class="notif-icon club"><i class="fa-solid fa-people-group"></i></span>
        <div class="notif-text">
          <p>تذكير: مناقشة نادي "أدب عربي معاصر" غدًا الساعة 8 مساءً</p>
          <span class="notif-time">أمس</span>
        </div>
        <button class="notif-dismiss-btn" aria-label="dismiss"><i class="fa-solid fa-xmark"></i></button>
      </a>
      <a href="{{ route('my-library') }}" class="notif-page-item" data-filter="books">
        <span class="notif-icon download"><i class="fa-solid fa-circle-check"></i></span>
        <div class="notif-text">
          <p>تم تحميل كتاب "1984" بنجاح إلى مكتبتك</p>
          <span class="notif-time">أمس</span>
        </div>
        <button class="notif-dismiss-btn" aria-label="dismiss"><i class="fa-solid fa-xmark"></i></button>
      </a>
      <a href="{{ route('writer-details', 'amr-abdelhamid') }}" class="notif-page-item" data-filter="social">
        <span class="notif-icon follow"><i class="fa-solid fa-user-plus"></i></span>
        <div class="notif-text">
          <p><strong>عمرو عبد الحميد</strong> بدأ متابعتك</p>
          <span class="notif-time">أمس</span>
        </div>
        <button class="notif-dismiss-btn" aria-label="dismiss"><i class="fa-solid fa-xmark"></i></button>
      </a>
    </div>
  </div>

  <!-- ---- This week ---- -->
  <div class="notif-group">
    <h2 class="notif-group-title">هذا الأسبوع</h2>
    <div class="notif-page-list">
      <a href="{{ route('profile') }}" class="notif-page-item" data-filter="social">
        <span class="notif-icon achievement"><i class="fa-solid fa-award"></i></span>
        <div class="notif-text">
          <p>حصلت على إنجاز جديد: <strong>"قارئ نهم"</strong></p>
          <span class="notif-time">منذ 3 أيام</span>
        </div>
        <button class="notif-dismiss-btn" aria-label="dismiss"><i class="fa-solid fa-xmark"></i></button>
      </a>
      <a href="{{ route('reading-clubs') }}" class="notif-page-item" data-filter="clubs">
        <span class="notif-icon club"><i class="fa-solid fa-people-group"></i></span>
        <div class="notif-text">
          <p>دعوة للانضمام إلى نادي "روايات عالمية"</p>
          <span class="notif-time">منذ 4 أيام</span>
        </div>
        <button class="notif-dismiss-btn" aria-label="dismiss"><i class="fa-solid fa-xmark"></i></button>
      </a>
      <a href="{{ route('book-details', 'blue-elephant') }}" class="notif-page-item" data-filter="books">
        <span class="notif-icon book"><i class="fa-solid fa-book"></i></span>
        <div class="notif-text">
          <p>تذكير: أنهِ قراءة "الفيل الأزرق" لإكمال تحدي القراءة الشهري</p>
          <span class="notif-time">منذ 5 أيام</span>
        </div>
        <button class="notif-dismiss-btn" aria-label="dismiss"><i class="fa-solid fa-xmark"></i></button>
      </a>
    </div>
  </div>

  <!-- ---- Empty state (hidden unless a filter matches nothing) ---- -->
  <div class="notif-empty" id="notifEmpty" hidden>
    <i class="fa-regular fa-bell-slash"></i>
    <p>لا توجد إشعارات في هذا التصنيف</p>
  </div>

</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/notifications.js') }}"></script>
@endpush
