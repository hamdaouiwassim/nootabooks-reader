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

  <form method="GET" action="{{ route('reading-clubs') }}" class="writers-search">
    <button type="submit" aria-label="بحث"><i class="fa-solid fa-magnifying-glass"></i></button>
    <input type="text" name="q" value="{{ $search }}" placeholder="ابحث عن نادي قراءة ...">
  </form>

  <div class="filter-tabs">
    <a href="{{ route('reading-clubs', array_filter(['q' => $search, 'sort' => 'popular'])) }}" class="filter-tab @if ($sort !== 'newest') active @endif">الأكثر أعضاء</a>
    <a href="{{ route('reading-clubs', array_filter(['q' => $search, 'sort' => 'newest'])) }}" class="filter-tab @if ($sort === 'newest') active @endif">الأحدث</a>
  </div>
</section>

<!-- ===================== CREATE CLUB BANNER ===================== -->
<section class="section">
  <div class="create-club-banner">
    <div>
      <h3>لم تجد ناديًا يناسبك؟</h3>
      <p>أنشئ ناديك الخاص وادعُ أصدقاءك لمناقشة كتبكم المفضلة</p>
    </div>
    @auth
      <button type="button" class="btn btn-gold" id="toggleCreateClub"><i class="fa-solid fa-plus"></i> إنشاء نادي جديد</button>
    @else
      <a href="{{ route('login') }}" class="btn btn-gold"><i class="fa-solid fa-plus"></i> سجل الدخول لإنشاء نادي</a>
    @endauth
  </div>

  @auth
    <form method="POST" action="{{ route('clubs.store') }}" class="create-club-form" id="createClubForm" hidden>
      @csrf
      <div class="form-field">
        <label for="clubName">اسم النادي</label>
        <input type="text" id="clubName" name="name" value="{{ old('name') }}" placeholder="مثال: أدب عربي معاصر" required>
        @error('name')<span class="field-error">{{ $message }}</span>@enderror
      </div>
      <div class="form-field">
        <label for="clubCategory">التصنيف</label>
        <select id="clubCategory" name="category">
          @foreach (['أدب عربي', 'أدب عالمي', 'روايات', 'تاريخ', 'تنمية ذاتية', 'شعر', 'فلسفة'] as $category)
            <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-field">
        <label for="clubDescription">وصف النادي</label>
        <textarea id="clubDescription" name="description" rows="3" placeholder="عن ماذا سيناقش النادي؟" required>{{ old('description') }}</textarea>
        @error('description')<span class="field-error">{{ $message }}</span>@enderror
      </div>
      <div class="form-field">
        <label for="clubBook">الكتاب الأول الذي سيقرأه النادي</label>
        <select id="clubBook" name="book" required>
          <option value="" disabled selected>اختر كتابًا</option>
          @foreach ($books as $book)
            <option value="{{ $book->slug }}" @selected(old('book') === $book->slug)>{{ $book->title }}</option>
          @endforeach
        </select>
        @error('book')<span class="field-error">{{ $message }}</span>@enderror
      </div>
      <button type="submit" class="btn btn-teal">إنشاء النادي</button>
    </form>
  @endauth
</section>

<!-- ===================== CLUBS GRID ===================== -->
<section class="section">
  <div class="section-head">
    <h2 class="section-title">جميع النوادي</h2>
    <span class="results-count"><span id="resultsCount">{{ $clubs->total() }}</span> نادي</span>
  </div>

  @if ($clubs->isNotEmpty())
    <div class="clubs-grid" id="clubsGrid">
      @foreach ($clubs as $club)
        @php $currentBook = $club->currentBook(); @endphp
        <a href="{{ route('club-details', $club->slug) }}" class="club-card">
          <div class="club-card-icon"><i class="fa-solid fa-people-group"></i></div>
          <h3>{{ $club->name }}</h3>
          <p class="club-desc">{{ $club->description }}</p>
          @if ($currentBook)
            <p class="club-reading"><i class="fa-solid fa-book-open"></i> يقرأون الآن: {{ $currentBook->title }}</p>
          @endif
          <div class="club-card-footer">
            <span><i class="fa-solid fa-users"></i> {{ number_format($club->members_count) }} عضو</span>
            <span class="btn btn-outline small">عرض النادي</span>
          </div>
        </a>
      @endforeach
    </div>

    {{ $clubs->links() }}
  @else
    <p class="no-results">لا توجد نوادي مطابقة لبحثك.</p>
  @endif
</section>

</main>
@endsection

@push('scripts')
<script src="{{ asset_min('assets/js/reading-clubs.js') }}"></script>
@endpush
