@extends('layouts.app')

@section('title', 'أدب عربي معاصر - نوته بوك')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/book-details.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/community.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/club-details.css') }}">
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <a href="{{ route('community') }}">المجتمع</a>
    <i class="fa-solid fa-chevron-left"></i>
    <a href="{{ route('reading-clubs') }}">نوادي القراءة</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>أدب عربي معاصر</span>
  </nav>
</div>

<!-- ===================== CLUB HERO ===================== -->
<section class="section club-hero">
  <div class="club-hero-icon"><i class="fa-solid fa-people-group"></i></div>
  <div class="club-hero-info">
    <span class="genre-chip">أدب عربي</span>
    <h1 class="club-hero-name">أدب عربي معاصر</h1>
    <p class="club-hero-desc">نناقش أبرز الروايات العربية الحديثة أسبوعيًا، ونستضيف أحيانًا مؤلفين للحديث عن أعمالهم مباشرة مع الأعضاء. النادي مفتوح لكل محبي الأدب العربي المعاصر من كافة المستويات.</p>

    <div class="club-hero-meta">
      <div class="meta-item"><i class="fa-solid fa-users"></i><span>الأعضاء</span><strong>1,240</strong></div>
      <div class="meta-item"><i class="fa-solid fa-comments"></i><span>المناقشات</span><strong>86</strong></div>
      <div class="meta-item"><i class="fa-solid fa-book"></i><span>كتب أُنجزت</span><strong>24</strong></div>
      <div class="meta-item"><i class="fa-solid fa-calendar-days"></i><span>تأسس في</span><strong>2023</strong></div>
    </div>

    <div class="club-hero-actions">
      <button class="btn btn-teal join-club-btn"><i class="fa-solid fa-user-plus"></i> انضمام للنادي</button>
      <button class="icon-btn-outline" aria-label="share"><i class="fa-solid fa-share-nodes"></i></button>
    </div>
  </div>
</section>

<!-- ===================== TABS ===================== -->
<section class="section tabs-section">
  <div class="tabs-nav">
    <button class="tab-btn active" data-tab="reading">يقرأون الآن</button>
    <button class="tab-btn" data-tab="discussions">المناقشات</button>
    <button class="tab-btn" data-tab="members">الأعضاء</button>
    <button class="tab-btn" data-tab="about">نبذة وقوانين</button>
  </div>

  <!-- ---- Currently Reading ---- -->
  <div class="tab-panel active" id="tab-reading">
    <div class="current-book-card">
      <a href="{{ route('writer-details', 'ahmed-mourad') }}" class="book-cover wcover-4">
        <span class="cover-badge">B</span>
        <span class="cover-title">تراب الماس</span>
      </a>
      <div class="current-book-info">
        <span class="genre-chip">كتاب الشهر</span>
        <h3>تراب الماس</h3>
        <p class="author">أحمد مراد</p>
        <div class="progress-row">
          <div class="progress-bar"><div class="progress-fill" style="width:58%"></div></div>
          <span class="progress-pct">58% من الأعضاء أنهوا القراءة</span>
        </div>
        <p class="schedule-text"><i class="fa-solid fa-calendar-check"></i> موعد المناقشة القادمة: الخميس القادم الساعة 8 مساءً</p>
        <a href="{{ route('writer-details', 'ahmed-mourad') }}" class="btn btn-outline">عرض الكتاب</a>
      </div>
    </div>

    <h3 class="mini-heading">الكتب السابقة</h3>
    <div class="past-books-row">
      <div class="mini-book"><span class="book-cover cover-1 mini"><span class="cover-title">الفيل الأزرق</span></span><p>الفيل الأزرق</p></div>
      <div class="mini-book"><span class="book-cover cover-3 mini"><span class="cover-title">الخيميائي</span></span><p>الخيميائي</p></div>
      <div class="mini-book"><span class="book-cover dcover-10 mini"><span class="cover-title">عزازيل</span></span><p>عزازيل</p></div>
      <div class="mini-book"><span class="book-cover dcover-9 mini"><span class="cover-title">1984</span></span><p>1984</p></div>
    </div>
  </div>

  <!-- ---- Discussions ---- -->
  <div class="tab-panel" id="tab-discussions">
    <div class="discussion-feed">
      <article class="discussion-card">
        <div class="discussion-head">
          <img src="https://i.pravatar.cc/64?img=21" alt="ليلى حسن">
          <div>
            <strong>ليلى حسن</strong>
            <span class="discussion-time">أمس</span>
          </div>
          <a href="{{ route('writer-details', 'ahmed-mourad') }}" class="book-tag"><i class="fa-solid fa-book"></i> تراب الماس</a>
        </div>
        <p class="discussion-text">
          وصلت لمنتصف الرواية، والتحول اللي حصل للشخصية الرئيسية غير متوقع خالص! حد وصل لنفس النقطة؟
        </p>
        <div class="discussion-footer">
          <a href="{{ route('discussion-details') }}" class="engage-btn"><i class="fa-regular fa-thumbs-up"></i> <span class="count">42</span></a>
          <a href="{{ route('discussion-details') }}" class="engage-btn"><i class="fa-regular fa-comment"></i> <span class="count">15</span></a>
          <button class="engage-btn"><i class="fa-solid fa-share-nodes"></i> مشاركة</button>
        </div>
      </article>

      <article class="discussion-card">
        <div class="discussion-head">
          <img src="https://i.pravatar.cc/64?img=45" alt="محمد العتيبي">
          <div>
            <strong>محمد العتيبي</strong>
            <span class="discussion-time">منذ 3 أيام</span>
          </div>
        </div>
        <p class="discussion-text">
          تذكير بموعد المناقشة الأسبوعية يوم الخميس الساعة 8 مساءً، هنتكلم عن الفصول من 1 إلى 10. جهزوا أسئلتكم!
        </p>
        <div class="discussion-footer">
          <a href="{{ route('discussion-details') }}" class="engage-btn"><i class="fa-regular fa-thumbs-up"></i> <span class="count">67</span></a>
          <a href="{{ route('discussion-details') }}" class="engage-btn"><i class="fa-regular fa-comment"></i> <span class="count">9</span></a>
          <button class="engage-btn"><i class="fa-solid fa-share-nodes"></i> مشاركة</button>
        </div>
      </article>
    </div>
  </div>

  <!-- ---- Members ---- -->
  <div class="tab-panel" id="tab-members">
    <div class="members-grid">
      <div class="member-card">
        <img src="https://i.pravatar.cc/100?img=21" alt="ليلى حسن">
        <strong>ليلى حسن</strong>
        <span class="member-role admin">مشرفة النادي</span>
      </div>
      <div class="member-card">
        <img src="https://i.pravatar.cc/100?img=45" alt="محمد العتيبي">
        <strong>محمد العتيبي</strong>
        <span class="member-role admin">مشرف</span>
      </div>
      <div class="member-card">
        <img src="https://i.pravatar.cc/100?img=32" alt="سارة محمود">
        <strong>سارة محمود</strong>
        <span class="member-role">عضو</span>
      </div>
      <div class="member-card">
        <img src="https://i.pravatar.cc/100?img=68" alt="عمر خالد">
        <strong>عمر خالد</strong>
        <span class="member-role">عضو</span>
      </div>
      <div class="member-card">
        <img src="https://i.pravatar.cc/100?img=13" alt="أحمد محمد">
        <strong>أحمد محمد</strong>
        <span class="member-role">عضو</span>
      </div>
      <div class="member-card">
        <img src="https://i.pravatar.cc/100?img=52" alt="نور الدين">
        <strong>نور الدين</strong>
        <span class="member-role">عضو</span>
      </div>
    </div>
    <p class="members-more">و 1,234 عضوًا آخر</p>
  </div>

  <!-- ---- About ---- -->
  <div class="tab-panel" id="tab-about">
    <h2>عن النادي</h2>
    <p>
      نادي "أدب عربي معاصر" هو مساحة لعشاق الرواية العربية الحديثة، نقرأ كتابًا واحدًا شهريًا ونجتمع أسبوعيًا لمناقشة تقدمنا فيه. هدفنا خلق حوار عميق حول القضايا التي تطرحها الأعمال الأدبية العربية المعاصرة.
    </p>
    <h3 class="mini-heading">قوانين النادي</h3>
    <ul class="club-rules">
      <li><i class="fa-solid fa-check"></i> يُرجى تجنب حرق الأحداث (Spoilers) خارج الفصول المحددة للمناقشة</li>
      <li><i class="fa-solid fa-check"></i> الاحترام المتبادل في كل النقاشات والتعليقات</li>
      <li><i class="fa-solid fa-check"></i> المشاركة الأسبوعية مُستحسنة لكن غير إلزامية</li>
      <li><i class="fa-solid fa-check"></i> يمكن اقتراح الكتاب القادم في نهاية كل شهر عبر التصويت</li>
    </ul>
  </div>
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/club-details.js') }}"></script>
@endpush
