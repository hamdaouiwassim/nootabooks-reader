@extends('layouts.app')

@section('title', 'استكشاف - نوته بوك')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/writers.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/discover.css') }}">
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>استكشاف</span>
  </nav>
</div>

<!-- ===================== PAGE HEADING ===================== -->
<section class="section writers-hero">
  <h1>استكشف عالم الكتب</h1>
  <p>ابحث عن كتابك القادم من بين آلاف العناوين في مختلف المجالات</p>

  <div class="writers-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" id="discoverSearch" placeholder="ابحث بعنوان الكتاب أو اسم المؤلف ...">
  </div>
</section>

<!-- ===================== DISCOVER LAYOUT ===================== -->
<section class="section discover-layout">

  <!-- ---- Filters Sidebar ---- -->
  <aside class="filters-sidebar">
    <div class="filters-head">
      <h3>الفلاتر</h3>
      <button class="clear-filters-btn" id="clearFilters">مسح الكل</button>
    </div>

    <div class="filter-group">
      <h4>التصنيف</h4>
      <label class="checkbox-row"><input type="checkbox" value="روايات"> <span>روايات</span></label>
      <label class="checkbox-row"><input type="checkbox" value="أدب عربي"> <span>أدب عربي</span></label>
      <label class="checkbox-row"><input type="checkbox" value="أدب عالمي"> <span>أدب عالمي</span></label>
      <label class="checkbox-row"><input type="checkbox" value="تنمية ذاتية"> <span>تنمية ذاتية</span></label>
      <label class="checkbox-row"><input type="checkbox" value="تاريخ"> <span>تاريخ</span></label>
    </div>

    <div class="filter-group">
      <h4>اللغة</h4>
      <label class="checkbox-row"><input type="radio" name="lang" value="all" checked> <span>الكل</span></label>
      <label class="checkbox-row"><input type="radio" name="lang" value="عربي"> <span>العربية</span></label>
      <label class="checkbox-row"><input type="radio" name="lang" value="أجنبي"> <span>مترجم</span></label>
    </div>

    <div class="filter-group">
      <h4>التقييم</h4>
      <label class="checkbox-row rating-filter" data-min="4.5"><input type="checkbox" value="4.5"> <span class="stars sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></span> فأعلى</label>
      <label class="checkbox-row rating-filter" data-min="4"><input type="checkbox" value="4"> <span class="stars sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></span> فأعلى</label>
      <label class="checkbox-row rating-filter" data-min="3"><input type="checkbox" value="3"> <span class="stars sm"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i></span> فأعلى</label>
    </div>

    <div class="filter-group">
      <h4>الصيغة</h4>
      <label class="checkbox-row"><input type="checkbox" value="PDF"> <span>PDF</span></label>
      <label class="checkbox-row"><input type="checkbox" value="EPUB"> <span>EPUB</span></label>
      <label class="checkbox-row"><input type="checkbox" value="صوتي"> <span>كتاب صوتي</span></label>
    </div>
  </aside>

  <!-- ---- Results ---- -->
  <div class="discover-results">
    <div class="results-toolbar">
      <span class="results-count"><strong id="resultsCount">12</strong> كتاب متاح</span>
      <div class="sort-wrap">
        <label for="sortSelect">ترتيب حسب</label>
        <select id="sortSelect">
          <option value="popular">الأكثر شعبية</option>
          <option value="newest">الأحدث</option>
          <option value="rating">الأعلى تقييمًا</option>
          <option value="az">أبجديًا</option>
        </select>
      </div>
    </div>

    <div class="discover-grid" id="discoverGrid">

      <article class="book-card" data-title="الفيل الأزرق" data-author="أحمد مراد" data-rating="4.5" data-year="2012" data-category="روايات">
        <a href="{{ route('book-details', 'blue-elephant') }}" class="book-cover cover-1">
          <span class="cover-badge">B</span>
          <span class="cover-title">الفيل الأزرق</span>
          <span class="cover-sub">نسنا</span>
        </a>
        <h3><a href="{{ route('book-details', 'blue-elephant') }}">الفيل الأزرق</a></h3>
        <p class="author">أحمد مراد</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.5</p>
        <a href="{{ route('book-details', 'blue-elephant') }}" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

      <article class="book-card" data-title="أرض زيكولا" data-author="عمرو عبد الحميد" data-rating="4.8" data-year="2019" data-category="روايات">
        <a href="{{ route('read', 'zikola-land') }}" class="book-cover dcover-5">
          <span class="cover-badge">B</span>
          <span class="cover-title">أرض زيكولا</span>
        </a>
        <h3><a href="{{ route('read', 'zikola-land') }}">أرض زيكولا</a></h3>
        <p class="author">عمرو عبد الحميد</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.8</p>
        <a href="{{ route('book-details', 'zikola-land') }}" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

      <article class="book-card" data-title="الخيميائي" data-author="باولو كويلو" data-rating="4.6" data-year="1988" data-category="أدب عالمي">
        <a href="#" class="book-cover cover-3">
          <span class="cover-badge">B</span>
          <span class="cover-title">الخيميائي</span>
        </a>
        <h3><a href="#">الخيميائي</a></h3>
        <p class="author">باولو كويلو</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.6</p>
        <a href="{{ route('book-details', 'the-alchemist') }}" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

      <article class="book-card" data-title="منذ عام من العزلة" data-author="غابرييل غارسيا ماركيز" data-rating="4.7" data-year="1967" data-category="أدب عالمي">
        <a href="#" class="book-cover cover-4">
          <span class="cover-badge">B</span>
          <span class="cover-title">منذ عام من العزلة</span>
        </a>
        <h3><a href="#">منذ عام من العزلة</a></h3>
        <p class="author">غابرييل غارسيا ماركيز</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.7</p>
        <a href="{{ route('book-details', 'one-hundred-years-of-solitude') }}" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

      <article class="book-card" data-title="1984" data-author="جورج أورويل" data-rating="4.8" data-year="1949" data-category="أدب عالمي">
        <a href="#" class="book-cover dcover-9">
          <span class="cover-badge">B</span>
          <span class="cover-title">1984</span>
        </a>
        <h3><a href="#">1984</a></h3>
        <p class="author">جورج أورويل</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.8</p>
        <a href="{{ route('book-details', '1984') }}" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

      <article class="book-card" data-title="عزازيل" data-author="يوسف زيدان" data-rating="4.6" data-year="2008" data-category="أدب عربي">
        <a href="#" class="book-cover dcover-10">
          <span class="cover-badge">B</span>
          <span class="cover-title">عزازيل</span>
        </a>
        <h3><a href="#">عزازيل</a></h3>
        <p class="author">يوسف زيدان</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.6</p>
        <a href="{{ route('book-details', 'azazeel') }}" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

      <article class="book-card" data-title="موسم الهجرة إلى الشمال" data-author="الطيب صالح" data-rating="4.7" data-year="1966" data-category="أدب عربي">
        <a href="#" class="book-cover dcover-11">
          <span class="cover-badge">B</span>
          <span class="cover-title">موسم الهجرة إلى الشمال</span>
        </a>
        <h3><a href="#">موسم الهجرة إلى الشمال</a></h3>
        <p class="author">الطيب صالح</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.7</p>
        <a href="{{ route('book-details', 'season-of-migration-to-the-north') }}" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

      <article class="book-card" data-title="فيرتيجو" data-author="أحمد مراد" data-rating="4.4" data-year="2010" data-category="روايات">
        <a href="{{ route('writer-details', 'ahmed-mourad') }}" class="book-cover dcover-3">
          <span class="cover-badge">B</span>
          <span class="cover-title">فيرتيجو</span>
        </a>
        <h3><a href="{{ route('writer-details', 'ahmed-mourad') }}">فيرتيجو</a></h3>
        <p class="author">أحمد مراد</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.4</p>
        <a href="{{ route('book-details', 'vertigo') }}" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

      <article class="book-card" data-title="تراب الماس" data-author="أحمد مراد" data-rating="4.6" data-year="2011" data-category="روايات">
        <a href="{{ route('writer-details', 'ahmed-mourad') }}" class="book-cover dcover-4">
          <span class="cover-badge">B</span>
          <span class="cover-title">تراب الماس</span>
        </a>
        <h3><a href="{{ route('writer-details', 'ahmed-mourad') }}">تراب الماس</a></h3>
        <p class="author">أحمد مراد</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.6</p>
        <a href="{{ route('book-details', 'turab-al-mas') }}" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

      <article class="book-card" data-title="رجال في الشمس" data-author="غسان كنفاني" data-rating="4.5" data-year="1963" data-category="أدب عربي">
        <a href="#" class="book-cover dcover-12">
          <span class="cover-badge">B</span>
          <span class="cover-title">رجال في الشمس</span>
        </a>
        <h3><a href="#">رجال في الشمس</a></h3>
        <p class="author">غسان كنفاني</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.5</p>
        <a href="{{ route('book-details', 'men-in-the-sun') }}" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

      <article class="book-card" data-title="الأمير الصغير" data-author="أنطوان دو سانت-إكزوبيري" data-rating="4.9" data-year="1943" data-category="أدب عالمي">
        <a href="#" class="book-cover dcover-13">
          <span class="cover-badge">B</span>
          <span class="cover-title">الأمير الصغير</span>
        </a>
        <h3><a href="#">الأمير الصغير</a></h3>
        <p class="author">أنطوان دو سانت-إكزوبيري</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.9</p>
        <a href="{{ route('book-details', 'the-little-prince') }}" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

      <article class="book-card" data-title="يوتوبيا" data-author="أحمد خالد توفيق" data-rating="4.5" data-year="2008" data-category="روايات">
        <a href="#" class="book-cover cover-2">
          <span class="cover-badge">B</span>
          <span class="cover-title">يوتوبيا</span>
        </a>
        <h3><a href="#">يوتوبيا</a></h3>
        <p class="author">أحمد خالد توفيق</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.5</p>
        <a href="{{ route('book-details', 'utopia') }}" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

    </div>

    <p class="no-results" id="noResults" hidden>لا توجد كتب مطابقة لهذا البحث أو الفلاتر المحددة.</p>

    <nav class="pagination">
      <button class="page-btn" aria-label="previous"><i class="fa-solid fa-chevron-right"></i></button>
      <button class="page-btn active">1</button>
      <button class="page-btn">2</button>
      <button class="page-btn">3</button>
      <button class="page-btn" aria-label="next"><i class="fa-solid fa-chevron-left"></i></button>
    </nav>
  </div>

</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/discover.js') }}"></script>
@endpush
