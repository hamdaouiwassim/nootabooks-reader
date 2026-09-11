@extends('layouts.app')

@section('title', 'الملف الشخصي - نوته بوك')
@section('robots', 'noindex, nofollow')

@push('styles')
<link rel="stylesheet" href="{{ asset_min('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/book-details.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/community.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/profile.css') }}">
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>الملف الشخصي</span>
  </nav>
</div>

<!-- ===================== PROFILE HEADER ===================== -->
<section class="section">
  <div class="profile-card">
    <div class="profile-cover"></div>
    <div class="profile-header">
      <div class="profile-avatar-wrap">
        <img src="https://i.pravatar.cc/240?img=13" width="240" height="240" decoding="async" alt="أحمد محمد">
        <button class="avatar-edit-btn" aria-label="edit avatar"><i class="fa-solid fa-camera"></i></button>
      </div>
      <div class="profile-info">
        <h1>أحمد محمد</h1>
        <p class="profile-bio">قارئ شغوف بالروايات النفسية والأدب العربي المعاصر. أشارك آرائي في الكتب وأبحث دائمًا عن توصية جديدة 📚</p>
        <div class="profile-meta-row">
          <span><i class="fa-solid fa-calendar-days"></i> انضم في يناير 2024</span>
          <span><i class="fa-solid fa-location-dot"></i> القاهرة، مصر</span>
        </div>
      </div>
      <div class="profile-actions">
        <a href="{{ route('settings') }}" class="btn btn-outline"><i class="fa-solid fa-pen"></i> تعديل الملف الشخصي</a>
      </div>
    </div>

    <div class="profile-stats">
      <div class="profile-stat"><strong>47</strong><span>كتاب مقروء</span></div>
      <div class="profile-stat"><strong>32</strong><span>تقييم</span></div>
      <div class="profile-stat"><strong>1,240</strong><span>متابِع</span></div>
      <div class="profile-stat"><strong>380</strong><span>يتابع</span></div>
      <div class="profile-stat"><strong>2,150</strong><span>نقطة</span></div>
    </div>
  </div>
</section>

<!-- ===================== TABS ===================== -->
<section class="section tabs-section">
  <div class="tabs-nav">
    <button class="tab-btn active" data-tab="activity">النشاط</button>
    <button class="tab-btn" data-tab="favorites">المفضلة</button>
    <button class="tab-btn" data-tab="reviews">التقييمات</button>
    <button class="tab-btn" data-tab="achievements">الإنجازات</button>
  </div>

  <!-- ---- Activity ---- -->
  <div class="tab-panel active" id="tab-activity">
    <div class="activity-list">
      <div class="activity-item">
        <span class="activity-icon completed"><i class="fa-solid fa-circle-check"></i></span>
        <p>أنهى قراءة <a href="{{ route('book-details', 'blue-elephant') }}">الفيل الأزرق</a> <span class="activity-time">منذ يومين</span></p>
      </div>
      <div class="activity-item">
        <span class="activity-icon review"><i class="fa-solid fa-star"></i></span>
        <p>قيّم <a href="#">يوتوبيا</a> بـ 5 نجوم <span class="activity-time">منذ 3 أيام</span></p>
      </div>
      <div class="activity-item">
        <span class="activity-icon club"><i class="fa-solid fa-people-group"></i></span>
        <p>انضم إلى نادي <a href="{{ route('club-details', 'arabic-literature') }}">أدب عربي معاصر</a> <span class="activity-time">منذ أسبوع</span></p>
      </div>
      <div class="activity-item">
        <span class="activity-icon comment"><i class="fa-solid fa-comment"></i></span>
        <p>علّق على مناقشة <a href="{{ route('discussion-details') }}">حول الفيل الأزرق</a> <span class="activity-time">منذ أسبوعين</span></p>
      </div>
      <div class="activity-item">
        <span class="activity-icon follow"><i class="fa-solid fa-user-plus"></i></span>
        <p>بدأ متابعة <a href="{{ route('writer-details', 'ahmed-mourad') }}">أحمد مراد</a> <span class="activity-time">منذ 3 أسابيع</span></p>
      </div>
    </div>
  </div>

  <!-- ---- Favorites ---- -->
  <div class="tab-panel" id="tab-favorites">
    <div class="favorites-grid">
      <article class="book-card">
        <a href="{{ route('book-details', 'blue-elephant') }}" class="book-cover cover-1">
          <span class="cover-badge">B</span>
          <span class="cover-title">الفيل الأزرق</span>
        </a>
        <h3><a href="{{ route('book-details', 'blue-elephant') }}">الفيل الأزرق</a></h3>
        <p class="author">أحمد مراد</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.5</p>
        <a href="{{ route('book-details', 'blue-elephant') }}" class="btn btn-outline w-full"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>
      <article class="book-card">
        <span class="book-cover cover-3">
          <span class="cover-badge">B</span>
          <span class="cover-title">الخيميائي</span>
        </span>
        <h3>الخيميائي</h3>
        <p class="author">باولو كويلو</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.6</p>
        <a href="{{ route('book-details', 'the-alchemist') }}" class="btn btn-outline w-full"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>
      <article class="book-card">
        <span class="book-cover cover-4">
          <span class="cover-badge">B</span>
          <span class="cover-title">منذ عام من العزلة</span>
        </span>
        <h3>منذ عام من العزلة</h3>
        <p class="author">غابرييل غارسيا ماركيز</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.7</p>
        <a href="{{ route('book-details', 'one-hundred-years-of-solitude') }}" class="btn btn-outline w-full"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>
      <article class="book-card">
        <a href="{{ route('writer-details', 'ahmed-mourad') }}" class="book-cover pcover-1">
          <span class="cover-badge">B</span>
          <span class="cover-title">تراب الماس</span>
        </a>
        <h3><a href="{{ route('writer-details', 'ahmed-mourad') }}">تراب الماس</a></h3>
        <p class="author">أحمد مراد</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.6</p>
        <a href="{{ route('book-details', 'turab-al-mas') }}" class="btn btn-outline w-full"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>
    </div>
  </div>

  <!-- ---- Reviews ---- -->
  <div class="tab-panel" id="tab-reviews">
    <div class="my-reviews-list">
      <article class="my-review-card">
        <span class="book-cover cover-2 mini"><span class="cover-title">يوتوبيا</span></span>
        <div class="my-review-body">
          <h4>يوتوبيا</h4>
          <p class="my-review-author">أحمد خالد توفيق</p>
          <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span>
          <p class="my-review-text">من أقوى الروايات العربية في الديستوبيا، أسلوب أحمد خالد توفيق مميز جدًا في بناء عالم مرعب لكنه قريب من واقعنا.</p>
          <span class="my-review-date">منذ 3 أيام</span>
        </div>
      </article>
      <article class="my-review-card">
        <span class="book-cover cover-1 mini"><span class="cover-title">الفيل الأزرق</span></span>
        <div class="my-review-body">
          <h4>الفيل الأزرق</h4>
          <p class="my-review-author">أحمد مراد</p>
          <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></span>
          <p class="my-review-text">حبكة مشوقة ونهاية غير متوقعة، لكن بعض الأحداث في المنتصف شعرت إنها بطيئة شوية.</p>
          <span class="my-review-date">منذ أسبوعين</span>
        </div>
      </article>
    </div>
  </div>

  <!-- ---- Achievements ---- -->
  <div class="tab-panel" id="tab-achievements">
    <div class="achievements-grid">
      <div class="achievement-card unlocked">
        <span class="achievement-icon"><i class="fa-solid fa-book-open-reader"></i></span>
        <strong>قارئ نهم</strong>
        <p>أنهيت قراءة 25 كتابًا</p>
      </div>
      <div class="achievement-card unlocked">
        <span class="achievement-icon"><i class="fa-solid fa-pen-nib"></i></span>
        <strong>ناقد أدبي</strong>
        <p>كتبت 30 تقييمًا</p>
      </div>
      <div class="achievement-card unlocked">
        <span class="achievement-icon"><i class="fa-solid fa-people-group"></i></span>
        <strong>عضو فعّال</strong>
        <p>انضممت إلى 3 نوادي قراءة</p>
      </div>
      <div class="achievement-card">
        <span class="achievement-icon"><i class="fa-solid fa-fire"></i></span>
        <strong>سلسلة القراءة</strong>
        <p>اقرأ 30 يومًا متتاليًا (12/30)</p>
      </div>
      <div class="achievement-card">
        <span class="achievement-icon"><i class="fa-solid fa-crown"></i></span>
        <strong>خبير المكتبة</strong>
        <p>أنهِ قراءة 100 كتاب</p>
      </div>
      <div class="achievement-card">
        <span class="achievement-icon"><i class="fa-solid fa-comments"></i></span>
        <strong>صوت المجتمع</strong>
        <p>احصل على 100 إعجاب على تعليقاتك</p>
      </div>
    </div>
  </div>
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/profile.js') }}"></script>
@endpush
