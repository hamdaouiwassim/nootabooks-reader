@extends('layouts.app')

@section('title', 'مكتبتي - نوته بوك')
@section('robots', 'noindex, nofollow')

@push('styles')
<link rel="stylesheet" href="{{ asset_min('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/my-library.css') }}">
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>مكتبتي</span>
  </nav>
</div>

<!-- ===================== PAGE HEADING ===================== -->
<section class="section writers-hero">
  <h1>مكتبتي</h1>
  <p>جميع كتبك المحملة والمفضلة وتقدمك في القراءة، في مكان واحد</p>
</section>

<!-- ===================== LIBRARY STATS ===================== -->
<section class="section">
  <div class="library-stats">
    <div class="lib-stat-card">
      <i class="fa-solid fa-book"></i>
      <div><strong id="statTotal">10</strong><span>إجمالي الكتب</span></div>
    </div>
    <div class="lib-stat-card">
      <i class="fa-solid fa-book-open-reader"></i>
      <div><strong id="statReading">5</strong><span>قيد القراءة</span></div>
    </div>
    <div class="lib-stat-card">
      <i class="fa-solid fa-circle-check"></i>
      <div><strong id="statCompleted">5</strong><span>مكتملة</span></div>
    </div>
    <div class="lib-stat-card">
      <i class="fa-solid fa-heart"></i>
      <div><strong id="statFavorite">4</strong><span>المفضلة</span></div>
    </div>
  </div>
</section>

<!-- ===================== LIBRARY GRID ===================== -->
<section class="section">
  <div class="filter-tabs">
    <button class="filter-tab active" data-filter="all">الكل</button>
    <button class="filter-tab" data-filter="reading">قيد القراءة</button>
    <button class="filter-tab" data-filter="completed">مكتملة</button>
    <button class="filter-tab" data-filter="favorite">المفضلة</button>
  </div>

  <div class="library-grid" id="libraryGrid">

    <article class="library-card" data-status="reading" data-favorite="false">
      <div class="lib-cover-wrap">
        <a href="{{ route('book-details', 'blue-elephant') }}" class="book-cover cover-1">
          <span class="cover-badge">B</span>
          <span class="cover-title">الفيل الأزرق</span>
          <span class="cover-sub">نسنا</span>
        </a>
        <button class="fav-btn" aria-label="favorite"><i class="fa-regular fa-heart"></i></button>
      </div>
      <h3><a href="{{ route('book-details', 'blue-elephant') }}">الفيل الأزرق</a></h3>
      <p class="author">أحمد مراد</p>
      <div class="progress-row">
        <div class="progress-bar"><div class="progress-fill" style="width:65%"></div></div>
        <span class="progress-pct">65%</span>
      </div>
      <button class="btn btn-teal"><i class="fa-solid fa-play"></i> متابعة القراءة</button>
    </article>

    <article class="library-card" data-status="completed" data-favorite="true">
      <div class="lib-cover-wrap">
        <a href="#" class="book-cover cover-2">
          <span class="cover-badge">B</span>
          <span class="cover-title">يوتوبيا</span>
        </a>
        <span class="completed-badge"><i class="fa-solid fa-check"></i></span>
        <button class="fav-btn active" aria-label="favorite"><i class="fa-solid fa-heart"></i></button>
      </div>
      <h3><a href="#">يوتوبيا</a></h3>
      <p class="author">أحمد خالد توفيق</p>
      <p class="completed-label"><i class="fa-solid fa-circle-check"></i> اكتملت القراءة</p>
      <button class="btn btn-outline"><i class="fa-solid fa-rotate-left"></i> قراءة مرة أخرى</button>
    </article>

    <article class="library-card" data-status="completed" data-favorite="false">
      <div class="lib-cover-wrap">
        <a href="#" class="book-cover cover-3">
          <span class="cover-badge">B</span>
          <span class="cover-title">الخيميائي</span>
        </a>
        <span class="completed-badge"><i class="fa-solid fa-check"></i></span>
        <button class="fav-btn" aria-label="favorite"><i class="fa-regular fa-heart"></i></button>
      </div>
      <h3><a href="#">الخيميائي</a></h3>
      <p class="author">باولو كويلو</p>
      <p class="completed-label"><i class="fa-solid fa-circle-check"></i> اكتملت القراءة</p>
      <button class="btn btn-outline"><i class="fa-solid fa-rotate-left"></i> قراءة مرة أخرى</button>
    </article>

    <article class="library-card" data-status="reading" data-favorite="true">
      <div class="lib-cover-wrap">
        <a href="#" class="book-cover cover-4">
          <span class="cover-badge">B</span>
          <span class="cover-title">منذ عام من العزلة</span>
        </a>
        <button class="fav-btn active" aria-label="favorite"><i class="fa-solid fa-heart"></i></button>
      </div>
      <h3><a href="#">منذ عام من العزلة</a></h3>
      <p class="author">غابرييل غارسيا ماركيز</p>
      <div class="progress-row">
        <div class="progress-bar"><div class="progress-fill" style="width:40%"></div></div>
        <span class="progress-pct">40%</span>
      </div>
      <button class="btn btn-teal"><i class="fa-solid fa-play"></i> متابعة القراءة</button>
    </article>

    <article class="library-card" data-status="reading" data-favorite="false">
      <div class="lib-cover-wrap">
        <a href="{{ route('read', 'zikola-land') }}" class="book-cover cover-5">
          <span class="cover-badge">B</span>
          <span class="cover-title">أرض زيكولا</span>
        </a>
        <button class="fav-btn" aria-label="favorite"><i class="fa-regular fa-heart"></i></button>
      </div>
      <h3><a href="{{ route('read', 'zikola-land') }}">أرض زيكولا</a></h3>
      <p class="author">عمرو عبد الحميد</p>
      <div class="progress-row">
        <div class="progress-bar"><div class="progress-fill" style="width:30%"></div></div>
        <span class="progress-pct">30%</span>
      </div>
      <button class="btn btn-teal"><i class="fa-solid fa-play"></i> متابعة القراءة</button>
    </article>

    <article class="library-card" data-status="completed" data-favorite="true">
      <div class="lib-cover-wrap">
        <a href="#" class="book-cover lcover-1">
          <span class="cover-badge">B</span>
          <span class="cover-title">عزازيل</span>
        </a>
        <span class="completed-badge"><i class="fa-solid fa-check"></i></span>
        <button class="fav-btn active" aria-label="favorite"><i class="fa-solid fa-heart"></i></button>
      </div>
      <h3><a href="#">عزازيل</a></h3>
      <p class="author">يوسف زيدان</p>
      <p class="completed-label"><i class="fa-solid fa-circle-check"></i> اكتملت القراءة</p>
      <button class="btn btn-outline"><i class="fa-solid fa-rotate-left"></i> قراءة مرة أخرى</button>
    </article>

    <article class="library-card" data-status="reading" data-favorite="false">
      <div class="lib-cover-wrap">
        <a href="#" class="book-cover lcover-2">
          <span class="cover-badge">B</span>
          <span class="cover-title">1984</span>
        </a>
        <button class="fav-btn" aria-label="favorite"><i class="fa-regular fa-heart"></i></button>
      </div>
      <h3><a href="#">1984</a></h3>
      <p class="author">جورج أورويل</p>
      <div class="progress-row">
        <div class="progress-bar"><div class="progress-fill" style="width:80%"></div></div>
        <span class="progress-pct">80%</span>
      </div>
      <button class="btn btn-teal"><i class="fa-solid fa-play"></i> متابعة القراءة</button>
    </article>

    <article class="library-card" data-status="completed" data-favorite="false">
      <div class="lib-cover-wrap">
        <a href="#" class="book-cover lcover-3">
          <span class="cover-badge">B</span>
          <span class="cover-title">موسم الهجرة إلى الشمال</span>
        </a>
        <span class="completed-badge"><i class="fa-solid fa-check"></i></span>
        <button class="fav-btn" aria-label="favorite"><i class="fa-regular fa-heart"></i></button>
      </div>
      <h3><a href="#">موسم الهجرة إلى الشمال</a></h3>
      <p class="author">الطيب صالح</p>
      <p class="completed-label"><i class="fa-solid fa-circle-check"></i> اكتملت القراءة</p>
      <button class="btn btn-outline"><i class="fa-solid fa-rotate-left"></i> قراءة مرة أخرى</button>
    </article>

    <article class="library-card" data-status="reading" data-favorite="true">
      <div class="lib-cover-wrap">
        <a href="{{ route('writer-details', 'ahmed-mourad') }}" class="book-cover lcover-4">
          <span class="cover-badge">B</span>
          <span class="cover-title">تراب الماس</span>
        </a>
        <button class="fav-btn active" aria-label="favorite"><i class="fa-solid fa-heart"></i></button>
      </div>
      <h3><a href="{{ route('writer-details', 'ahmed-mourad') }}">تراب الماس</a></h3>
      <p class="author">أحمد مراد</p>
      <div class="progress-row">
        <div class="progress-bar"><div class="progress-fill" style="width:45%"></div></div>
        <span class="progress-pct">45%</span>
      </div>
      <button class="btn btn-teal"><i class="fa-solid fa-play"></i> متابعة القراءة</button>
    </article>

    <article class="library-card" data-status="completed" data-favorite="false">
      <div class="lib-cover-wrap">
        <a href="{{ route('writer-details', 'ahmed-mourad') }}" class="book-cover lcover-5">
          <span class="cover-badge">B</span>
          <span class="cover-title">فيرتيجو</span>
        </a>
        <span class="completed-badge"><i class="fa-solid fa-check"></i></span>
        <button class="fav-btn" aria-label="favorite"><i class="fa-regular fa-heart"></i></button>
      </div>
      <h3><a href="{{ route('writer-details', 'ahmed-mourad') }}">فيرتيجو</a></h3>
      <p class="author">أحمد مراد</p>
      <p class="completed-label"><i class="fa-solid fa-circle-check"></i> اكتملت القراءة</p>
      <button class="btn btn-outline"><i class="fa-solid fa-rotate-left"></i> قراءة مرة أخرى</button>
    </article>

  </div>

  <div class="empty-state" id="emptyState" hidden>
    <i class="fa-regular fa-folder-open"></i>
    <p>لا توجد كتب في هذا القسم بعد</p>
    <a href="{{ route('discover') }}" class="btn btn-gold">استكشف الكتب</a>
  </div>
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/my-library.js') }}"></script>
@endpush
