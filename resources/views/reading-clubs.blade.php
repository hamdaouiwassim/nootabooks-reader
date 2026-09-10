@extends('layouts.app')

@section('title', 'نوادي القراءة - نوته بوك')
@section('robots', 'noindex, follow')

@push('styles')
<link rel="stylesheet" href="{{ asset_min('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/reading-clubs.css') }}">
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
    <span>نوادي القراءة</span>
  </nav>
</div>

<!-- ===================== PAGE HEADING ===================== -->
<section class="section writers-hero">
  <h1>نوادي القراءة</h1>
  <p>انضم إلى نادٍ يشاركك اهتماماتك، واقرأ معًا كتابًا في كل مرة</p>

  <div class="writers-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" id="clubSearch" placeholder="ابحث عن نادي قراءة ...">
  </div>

  <div class="filter-tabs">
    <button class="filter-tab active" data-sort="all">الكل</button>
    <button class="filter-tab" data-sort="popular">الأكثر أعضاء</button>
    <button class="filter-tab" data-sort="newest">الأحدث</button>
  </div>
</section>

<!-- ===================== CREATE CLUB BANNER ===================== -->
<section class="section">
  <div class="create-club-banner">
    <div>
      <h3>لم تجد ناديًا يناسبك؟</h3>
      <p>أنشئ ناديك الخاص وادعُ أصدقاءك لمناقشة كتبكم المفضلة</p>
    </div>
    <button class="btn btn-gold"><i class="fa-solid fa-plus"></i> إنشاء نادي جديد</button>
  </div>
</section>

<!-- ===================== CLUBS GRID ===================== -->
<section class="section">
  <div class="section-head">
    <h2 class="section-title">جميع النوادي</h2>
    <span class="results-count"><span id="resultsCount">8</span> نادي</span>
  </div>

  <div class="clubs-grid" id="clubsGrid">

    @php
      $clubs = [
        ['slug' => 'arabic-literature', 'name' => 'أدب عربي معاصر', 'members' => 1240, 'category' => 'أدب عربي', 'desc' => 'نناقش أبرز الروايات العربية الحديثة أسبوعيًا', 'reading' => 'تراب الماس'],
        ['slug' => 'sci-fi-lovers', 'name' => 'عشاق الخيال العلمي', 'members' => 890, 'category' => 'أدب عالمي', 'desc' => 'لمحبي الخيال العلمي والروايات المستقبلية', 'reading' => '1984'],
        ['slug' => 'translated-novels', 'name' => 'روايات مترجمة', 'members' => 670, 'category' => 'أدب عالمي', 'desc' => 'أفضل الروايات العالمية المترجمة للعربية', 'reading' => 'الخيميائي'],
        ['slug' => 'crime-novels', 'name' => 'نادي الروايات البوليسية', 'members' => 780, 'category' => 'روايات', 'desc' => 'نحل الألغاز ونناقش الجرائم في الروايات', 'reading' => 'فيرتيجو'],
        ['slug' => 'history-readers', 'name' => 'قراء التاريخ', 'members' => 410, 'category' => 'تاريخ', 'desc' => 'لعشاق التاريخ والحضارات القديمة', 'reading' => 'تاريخ الطبري'],
        ['slug' => 'self-development', 'name' => 'تنمية ذاتية وتطوير', 'members' => 540, 'category' => 'تنمية ذاتية', 'desc' => 'نناقش كتب التطوير الذاتي شهريًا', 'reading' => 'العادات الذرية'],
        ['slug' => 'poets-era', 'name' => 'شعراء العصر', 'members' => 320, 'category' => 'شعر', 'desc' => 'نادي محبي الشعر العربي الحديث والكلاسيكي', 'reading' => 'ديوان أدونيس'],
        ['slug' => 'philosophy-reflection', 'name' => 'فلسفة وتأمل', 'members' => 260, 'category' => 'فلسفة', 'desc' => 'نقاشات فلسفية عميقة أسبوعية', 'reading' => 'هكذا تكلم زرادشت'],
      ];
    @endphp

    @foreach ($clubs as $club)
      <a href="{{ route('club-details', $club['slug']) }}" class="club-card" data-name="{{ $club['name'] }}" data-members="{{ $club['members'] }}" data-category="{{ $club['category'] }}">
        <div class="club-card-icon"><i class="fa-solid fa-people-group"></i></div>
        <h3>{{ $club['name'] }}</h3>
        <p class="club-desc">{{ $club['desc'] }}</p>
        <p class="club-reading"><i class="fa-solid fa-book-open"></i> يقرأون الآن: {{ $club['reading'] }}</p>
        <div class="club-card-footer">
          <span><i class="fa-solid fa-users"></i> {{ number_format($club['members']) }} عضو</span>
          <span class="btn btn-outline small">انضمام</span>
        </div>
      </a>
    @endforeach

  </div>

  <p class="no-results" id="noResults" hidden>لا توجد نوادي مطابقة لبحثك.</p>
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/reading-clubs.js') }}"></script>
@endpush
