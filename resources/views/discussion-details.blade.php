@extends('layouts.app')

@section('title', 'مناقشة: الفيل الأزرق - نوته بوك')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/book-details.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/community.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/discussion-details.css') }}">
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
    <span>مناقشة حول الفيل الأزرق</span>
  </nav>
</div>

<!-- ===================== SUBJECT LAYOUT ===================== -->
<section class="section subject-layout">

  <!-- ---- Main Thread ---- -->
  <div class="subject-main">

    <article class="subject-post">
      <div class="discussion-head">
        <img src="https://i.pravatar.cc/64?img=32" alt="سارة محمود">
        <div>
          <strong>سارة محمود</strong>
          <span class="discussion-time">منذ ساعتين · في <a href="{{ route('community') }}">مجتمع القراء</a></span>
        </div>
        <a href="{{ route('book-details', 'blue-elephant') }}" class="book-tag"><i class="fa-solid fa-book"></i> الفيل الأزرق</a>
      </div>

      <p class="discussion-text">
        انتهيت للتو من قراءة "الفيل الأزرق" لأحمد مراد، والله الحبكة كانت مشوقة جدًا لدرجة إني ما قدرت أسيب الكتاب! أسلوب الكاتب في تصوير الحالة النفسية لبطل الرواية "يحيى" كان محكم جدًا، والانتقال بين الواقع والهلاوس خلاني قاعدة أشكك في كل حاجة بيقولها.
      </p>
      <p class="discussion-text">
        رأيكم إيه في النهاية؟ حسيت إنها مفاجئة أكتر من اللازم 😅 حد شافها متوقعة من البداية؟ ومين قرأ الجزء الثاني وعايز يقارن بينهم من غير سبويلرز؟
      </p>

      <div class="subject-post-footer">
        <button class="engage-btn like-btn liked"><i class="fa-solid fa-thumbs-up"></i> <span class="count">128</span> إعجاب</button>
        <button class="engage-btn"><i class="fa-regular fa-comment"></i> <span class="count">34</span> تعليق</button>
        <button class="engage-btn"><i class="fa-solid fa-share-nodes"></i> مشاركة</button>
        <button class="engage-btn follow-topic-btn"><i class="fa-regular fa-bell"></i> متابعة المناقشة</button>
      </div>
    </article>

    <div class="comments-section">
      <div class="comments-head">
        <h2>34 تعليق</h2>
        <div class="sort-wrap">
          <label for="commentSort">ترتيب حسب</label>
          <select id="commentSort">
            <option>الأحدث</option>
            <option>الأكثر إعجابًا</option>
          </select>
        </div>
      </div>

      <form class="comment-form" id="commentForm">
        <img src="https://i.pravatar.cc/64?img=13" alt="أحمد محمد">
        <input type="text" id="commentInput" placeholder="أضف تعليقك ...">
        <button type="submit" class="btn btn-gold small">إرسال</button>
      </form>

      <div class="comment-list" id="commentList">

        <article class="comment-item">
          <img src="https://i.pravatar.cc/64?img=45" alt="محمد العتيبي">
          <div class="comment-body">
            <div class="comment-bubble">
              <strong>محمد العتيبي</strong>
              <p>النهاية كانت متوقعة شوية بالنسبة لي، بس طريقة السرد اللي وصلنا بيها ليها كانت هي الأهم. أحمد مراد بيتقن بناء التوتر النفسي فعلاً.</p>
            </div>
            <div class="comment-actions">
              <span class="comment-time">منذ ساعة</span>
              <button class="mini-like-btn"><i class="fa-regular fa-thumbs-up"></i> <span class="count">18</span></button>
              <button class="reply-btn">رد</button>
            </div>

            <div class="comment-item reply">
              <img src="https://i.pravatar.cc/64?img=32" alt="سارة محمود">
              <div class="comment-body">
                <div class="comment-bubble">
                  <strong>سارة محمود</strong>
                  <p>متفقة معاك تمامًا، خصوصًا مشاهد المستشفى في النص الأول.</p>
                </div>
                <div class="comment-actions">
                  <span class="comment-time">منذ 40 دقيقة</span>
                  <button class="mini-like-btn"><i class="fa-regular fa-thumbs-up"></i> <span class="count">6</span></button>
                  <button class="reply-btn">رد</button>
                </div>
              </div>
            </div>
          </div>
        </article>

        <article class="comment-item">
          <img src="https://i.pravatar.cc/64?img=21" alt="ليلى حسن">
          <div class="comment-body">
            <div class="comment-bubble">
              <strong>ليلى حسن</strong>
              <p>قرأت الجزء الأول والتاني، وشخصيًا حسيت إن الأول أقوى في البناء النفسي للشخصية. مين قرأهم الاتنين يشاركني رأيه؟</p>
            </div>
            <div class="comment-actions">
              <span class="comment-time">منذ ساعتين</span>
              <button class="mini-like-btn"><i class="fa-regular fa-thumbs-up"></i> <span class="count">11</span></button>
              <button class="reply-btn">رد</button>
            </div>
          </div>
        </article>

        <article class="comment-item">
          <img src="https://i.pravatar.cc/64?img=68" alt="عمر خالد">
          <div class="comment-body">
            <div class="comment-bubble">
              <strong>عمر خالد</strong>
              <p>من رأيي إن النهاية كانت ضرورية عشان تخلي القارئ يعيد التفكير في كل الأحداث اللي قبلها من زاوية تانية تمامًا. من أذكى النهايات اللي قريتها.</p>
            </div>
            <div class="comment-actions">
              <span class="comment-time">منذ 3 ساعات</span>
              <button class="mini-like-btn"><i class="fa-regular fa-thumbs-up"></i> <span class="count">24</span></button>
              <button class="reply-btn">رد</button>
            </div>
          </div>
        </article>

      </div>

      <button class="btn btn-outline center">تحميل المزيد من التعليقات</button>
    </div>
  </div>

  <!-- ---- Sidebar ---- -->
  <aside class="subject-sidebar">

    <div class="sidebar-card book-mini-card">
      <h3>الكتاب المُناقَش</h3>
      <a href="{{ route('book-details', 'blue-elephant') }}" class="book-mini-link">
        <span class="book-cover cover-1 mini"><span class="cover-title">الفيل الأزرق</span></span>
        <div>
          <strong>الفيل الأزرق</strong>
          <p>أحمد مراد</p>
          <span class="rating"><i class="fa-solid fa-star"></i> 4.5</span>
        </div>
      </a>
      <a href="{{ route('book-details', 'blue-elephant') }}" class="btn btn-outline">عرض صفحة الكتاب</a>
    </div>

    <div class="sidebar-card">
      <h3>كاتب المناقشة</h3>
      <div class="contributor-item">
        <img src="https://i.pravatar.cc/64?img=32" alt="سارة محمود">
        <div class="contributor-info"><strong>سارة محمود</strong><span>150 منشور · 3,450 نقطة</span></div>
      </div>
      <button class="btn btn-outline follow-user-btn">متابعة</button>
    </div>

    <div class="sidebar-card">
      <h3>مناقشات مشابهة</h3>
      <a href="#" class="related-topic">
        <p>هل "يوتوبيا" لأحمد خالد توفيق تنبأت بمستقبلنا؟</p>
        <span><i class="fa-regular fa-comment"></i> 21 تعليق</span>
      </a>
      <a href="#" class="related-topic">
        <p>أفضل روايات الإثارة النفسية العربية حتى الآن</p>
        <span><i class="fa-regular fa-comment"></i> 45 تعليق</span>
      </a>
      <a href="#" class="related-topic">
        <p>مقارنة بين الفيل الأزرق 1 و2: أيهما أفضل؟</p>
        <span><i class="fa-regular fa-comment"></i> 63 تعليق</span>
      </a>
    </div>

  </aside>

</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/discussion-details.js') }}"></script>
@endpush
