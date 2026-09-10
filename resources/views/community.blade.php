@extends('layouts.app')

@section('title', 'المجتمع - نوته بوك')
@section('robots', 'noindex, follow')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/my-library.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/community.css') }}">
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>المجتمع</span>
  </nav>
</div>

<!-- ===================== PAGE HEADING ===================== -->
<section class="section writers-hero">
  <h1>مجتمع القراء</h1>
  <p>شارك آراءك، ناقش كتبك المفضلة، وتواصل مع قراء يشاركونك الشغف</p>
</section>

<!-- ===================== COMMUNITY STATS ===================== -->
<section class="section">
  <div class="library-stats">
    <div class="lib-stat-card">
      <i class="fa-solid fa-users"></i>
      <div><strong>24,500</strong><span>عضو</span></div>
    </div>
    <div class="lib-stat-card">
      <i class="fa-solid fa-comments"></i>
      <div><strong>3,200</strong><span>مناقشة</span></div>
    </div>
    <div class="lib-stat-card">
      <i class="fa-solid fa-people-group"></i>
      <div><strong>85</strong><span>نادي قراءة</span></div>
    </div>
    <div class="lib-stat-card">
      <i class="fa-solid fa-fire"></i>
      <div><strong>142</strong><span>منشور اليوم</span></div>
    </div>
  </div>
</section>

<!-- ===================== COMMUNITY LAYOUT ===================== -->
<section class="section community-layout">

  <!-- ---- Main Feed ---- -->
  <div class="community-main">

    <div class="filter-tabs">
      <button class="filter-tab active" data-filter="latest">الأحدث</button>
      <button class="filter-tab" data-filter="popular">الأكثر تفاعلاً</button>
      <button class="filter-tab" data-filter="following">متابعينك</button>
    </div>

    <form class="new-post-box" id="newPostForm">
      <img src="https://i.pravatar.cc/64?img=13" alt="أحمد محمد">
      <input type="text" id="newPostInput" placeholder="شارك رأيك أو ابدأ نقاشًا جديدًا ...">
      <button type="submit" class="btn btn-gold small">نشر</button>
    </form>

    <div class="discussion-feed" id="discussionFeed">

      <article class="discussion-card">
        <div class="discussion-head">
          <img src="https://i.pravatar.cc/64?img=32" alt="سارة محمود">
          <div>
            <strong>سارة محمود</strong>
            <span class="discussion-time">منذ ساعتين</span>
          </div>
          <a href="{{ route('book-details', 'blue-elephant') }}" class="book-tag"><i class="fa-solid fa-book"></i> الفيل الأزرق</a>
        </div>
        <p class="discussion-text">
          انتهيت للتو من قراءة "الفيل الأزرق" لأحمد مراد، والله الحبكة كانت مشوقة جدًا لدرجة إني ما قدرت أسيب الكتاب! رأيكم إيه في النهاية؟ حسيت إنها مفاجئة أكتر من اللازم 😅
        </p>
        <div class="discussion-footer">
          <button class="engage-btn like-btn"><i class="fa-regular fa-thumbs-up"></i> <span class="count">128</span></button>
          <a href="{{ route('discussion-details', 'blue-elephant-ending') }}" class="engage-btn"><i class="fa-regular fa-comment"></i> <span class="count">34</span></a>
          <button class="engage-btn"><i class="fa-solid fa-share-nodes"></i> مشاركة</button>
        </div>
      </article>

      <article class="discussion-card">
        <div class="discussion-head">
          <img src="https://i.pravatar.cc/64?img=45" alt="محمد العتيبي">
          <div>
            <strong>محمد العتيبي</strong>
            <span class="discussion-time">منذ 5 ساعات</span>
          </div>
          <a href="#" class="book-tag"><i class="fa-solid fa-book"></i> 1984</a>
        </div>
        <p class="discussion-text">
          هل تعتقدون أن رواية "1984" لجورج أورويل أصبحت أكثر واقعية في عصرنا الحالي؟ أشعر أن كثيرًا مما تنبأ به الكاتب عن المراقبة أصبح جزءًا من حياتنا اليومية دون أن ننتبه.
        </p>
        <div class="discussion-footer">
          <button class="engage-btn like-btn"><i class="fa-regular fa-thumbs-up"></i> <span class="count">256</span></button>
          <a href="{{ route('discussion-details') }}" class="engage-btn"><i class="fa-regular fa-comment"></i> <span class="count">67</span></a>
          <button class="engage-btn"><i class="fa-solid fa-share-nodes"></i> مشاركة</button>
        </div>
      </article>

      <article class="discussion-card">
        <div class="discussion-head">
          <img src="https://i.pravatar.cc/64?img=21" alt="ليلى حسن">
          <div>
            <strong>ليلى حسن</strong>
            <span class="discussion-time">أمس</span>
          </div>
          <a href="{{ route('writer-details', 'ahmed-mourad') }}" class="book-tag"><i class="fa-solid fa-user-pen"></i> أحمد مراد</a>
        </div>
        <p class="discussion-text">
          نادي "أدب عربي معاصر" هيبدأ مناقشة رواية جديدة الأسبوع الجاي، مين حابب ينضم لينا؟ هنختار بين "تراب الماس" و"فيرتيجو" لنفس الكاتب. صوتوا في التعليقات 👇
        </p>
        <div class="discussion-footer">
          <button class="engage-btn like-btn"><i class="fa-regular fa-thumbs-up"></i> <span class="count">89</span></button>
          <a href="{{ route('discussion-details') }}" class="engage-btn"><i class="fa-regular fa-comment"></i> <span class="count">52</span></a>
          <button class="engage-btn"><i class="fa-solid fa-share-nodes"></i> مشاركة</button>
        </div>
      </article>

      <article class="discussion-card">
        <div class="discussion-head">
          <img src="https://i.pravatar.cc/64?img=68" alt="عمر خالد">
          <div>
            <strong>عمر خالد</strong>
            <span class="discussion-time">منذ يومين</span>
          </div>
          <a href="#" class="book-tag"><i class="fa-solid fa-book"></i> عزازيل</a>
        </div>
        <p class="discussion-text">
          "عزازيل" ليوسف زيدان من أعمق الروايات العربية التي قرأتها، الأسلوب التاريخي ممزوج بصراع داخلي مؤثر جدًا. من قرأها ويحب يناقشها بعمق أكتر يا ريت يتواصل معايا.
        </p>
        <div class="discussion-footer">
          <button class="engage-btn like-btn"><i class="fa-regular fa-thumbs-up"></i> <span class="count">143</span></button>
          <a href="{{ route('discussion-details') }}" class="engage-btn"><i class="fa-regular fa-comment"></i> <span class="count">28</span></a>
          <button class="engage-btn"><i class="fa-solid fa-share-nodes"></i> مشاركة</button>
        </div>
      </article>

    </div>

    <button class="btn btn-outline center">تحميل المزيد</button>
  </div>

  <!-- ---- Sidebar ---- -->
  <aside class="community-sidebar">

    <div class="sidebar-card">
      <h3>نوادي القراءة النشطة</h3>

      <div class="club-item">
        <a href="{{ route('club-details', 'arabic-literature') }}" class="club-item-link">
          <span class="club-icon"><i class="fa-solid fa-people-group"></i></span>
          <div class="club-info">
            <strong>أدب عربي معاصر</strong>
            <p>يقرأون الآن: تراب الماس</p>
            <span class="club-members">1,240 عضو</span>
          </div>
        </a>
        <button class="btn btn-outline small join-btn">انضمام</button>
      </div>

      <div class="club-item">
        <a href="{{ route('club-details', 'sci-fi-lovers') }}" class="club-item-link">
          <span class="club-icon"><i class="fa-solid fa-people-group"></i></span>
          <div class="club-info">
            <strong>عشاق الخيال العلمي</strong>
            <p>يقرأون الآن: 1984</p>
            <span class="club-members">890 عضو</span>
          </div>
        </a>
        <button class="btn btn-outline small join-btn">انضمام</button>
      </div>

      <div class="club-item">
        <a href="{{ route('club-details', 'translated-novels') }}" class="club-item-link">
          <span class="club-icon"><i class="fa-solid fa-people-group"></i></span>
          <div class="club-info">
            <strong>روايات مترجمة</strong>
            <p>يقرأون الآن: الخيميائي</p>
            <span class="club-members">670 عضو</span>
          </div>
        </a>
        <button class="btn btn-outline small join-btn">انضمام</button>
      </div>

      <a href="{{ route('reading-clubs') }}" class="view-all-link">عرض جميع النوادي <i class="fa-solid fa-arrow-left"></i></a>
    </div>

    <div class="sidebar-card">
      <h3>أفضل المساهمين</h3>

      <div class="contributor-item">
        <span class="rank gold">1</span>
        <img src="https://i.pravatar.cc/64?img=32" alt="سارة محمود">
        <div class="contributor-info"><strong>سارة محمود</strong><span>3,450 نقطة</span></div>
      </div>
      <div class="contributor-item">
        <span class="rank silver">2</span>
        <img src="https://i.pravatar.cc/64?img=45" alt="محمد العتيبي">
        <div class="contributor-info"><strong>محمد العتيبي</strong><span>2,980 نقطة</span></div>
      </div>
      <div class="contributor-item">
        <span class="rank bronze">3</span>
        <img src="https://i.pravatar.cc/64?img=21" alt="ليلى حسن">
        <div class="contributor-info"><strong>ليلى حسن</strong><span>2,410 نقطة</span></div>
      </div>
      <div class="contributor-item">
        <span class="rank">4</span>
        <img src="https://i.pravatar.cc/64?img=68" alt="عمر خالد">
        <div class="contributor-info"><strong>عمر خالد</strong><span>1,875 نقطة</span></div>
      </div>
    </div>

    <div class="sidebar-card">
      <h3>مواضيع رائجة</h3>
      <div class="trend-tags">
        <a href="#">#الفيل_الأزرق</a>
        <a href="#">#أدب_عربي</a>
        <a href="#">#روايات_2026</a>
        <a href="#">#نادي_القراءة</a>
        <a href="#">#عزازيل</a>
        <a href="#">#1984</a>
      </div>
    </div>

  </aside>

</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/community.js') }}"></script>
@endpush
