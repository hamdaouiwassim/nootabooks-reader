<aside class="admin-sidebar" id="adminSidebar">
  <div class="admin-sidebar-head">
    <img src="{{ asset('assets/images/logo.png') }}" alt="نوتابوكس" class="logo-icon">
    <div class="logo-text">
      <span class="logo-tagline">لوحة التحكم</span>
    </div>
    <button class="admin-sidebar-close" id="adminSidebarClose" aria-label="close"><i class="fa-solid fa-xmark"></i></button>
  </div>

  <nav class="admin-nav">
    <span class="admin-nav-label">الرئيسية</span>
    <a href="{{ route('admin.dashboard') }}" @class(['active' => $activeNav === 'dashboard'])><i class="fa-solid fa-gauge-high"></i> لوحة التحكم</a>

    <span class="admin-nav-label">المحتوى</span>
    <a href="{{ route('admin.books.index') }}" @class(['active' => $activeNav === 'books'])><i class="fa-solid fa-book"></i> إدارة الكتب <span class="badge-count">{{ $sidebarBooksCount }}</span></a>
    <a href="{{ route('admin.writers.index') }}" @class(['active' => $activeNav === 'writers'])><i class="fa-solid fa-feather"></i> المؤلفون <span class="badge-count">{{ $sidebarWritersCount }}</span></a>
    <a href="{{ route('admin.categories.index') }}" @class(['active' => $activeNav === 'categories'])><i class="fa-solid fa-layer-group"></i> التصنيفات <span class="badge-count">{{ $sidebarCategoriesCount }}</span></a>
    <a href="{{ route('admin.quotes.index') }}" @class(['active' => $activeNav === 'quotes'])><i class="fa-solid fa-quote-right"></i> الاقتباسات <span class="badge-count">{{ $sidebarQuotesCount }}</span></a>
    <a href="{{ route('admin.advertisements.index') }}" @class(['active' => $activeNav === 'advertisements'])><i class="fa-solid fa-rectangle-ad"></i> الإعلانات <span class="badge-count">{{ $sidebarAdvertisementsCount }}</span></a>

    <span class="admin-nav-label">المجتمع</span>
    <a href="{{ route('admin.users.index') }}" @class(['active' => $activeNav === 'users'])><i class="fa-solid fa-users"></i> المستخدمون <span class="badge-count">{{ $sidebarUsersCount }}</span></a>
    <a href="{{ route('admin.clubs.index') }}" @class(['active' => $activeNav === 'clubs'])><i class="fa-solid fa-people-group"></i> نوادي القراءة <span class="badge-count">{{ $sidebarClubsCount }}</span></a>
    <a href="{{ route('admin.discussions.index') }}" @class(['active' => $activeNav === 'discussions'])><i class="fa-solid fa-comments"></i> المناقشات <span class="badge-count">{{ $sidebarDiscussionsCount }}</span></a>
    <a href="{{ route('admin.comments.index') }}" @class(['active' => $activeNav === 'comments'])><i class="fa-solid fa-comment-dots"></i> التعليقات <span class="badge-count">{{ $sidebarCommentsCount }}</span></a>
    <a href="{{ route('admin.book-reports.index') }}" @class(['active' => $activeNav === 'book-reports'])><i class="fa-solid fa-flag"></i> بلاغات حقوق النشر <span class="badge-count">{{ $sidebarBookReportsCount }}</span></a>

    <span class="admin-nav-label">النظام</span>
    <a href="{{ route('admin.statistics') }}" @class(['active' => $activeNav === 'statistics'])><i class="fa-solid fa-chart-line"></i> الإحصائيات</a>
    <a href="{{ route('admin.search-logs.index') }}" @class(['active' => $activeNav === 'search-logs'])><i class="fa-solid fa-magnifying-glass"></i> سجل بحث المستخدمين</a>
    <a href="{{ route('admin.file-audit') }}" @class(['active' => $activeNav === 'file-audit'])><i class="fa-solid fa-hard-drive"></i> فحص حجم الملفات</a>
    <a href="{{ route('admin.backup.index') }}" @class(['active' => $activeNav === 'backup'])><i class="fa-solid fa-database"></i> النسخ الاحتياطي</a>
    <a href="{{ route('admin.settings.index') }}" @class(['active' => $activeNav === 'settings'])><i class="fa-solid fa-gear"></i> الإعدادات</a>
  </nav>

  <div class="admin-sidebar-foot">
    <a href="{{ route('home') }}"><i class="fa-solid fa-arrow-right-from-bracket fa-rotate-180"></i> العودة للموقع</a>
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button type="submit" class="logout"><i class="fa-solid fa-power-off"></i> تسجيل الخروج</button>
    </form>
  </div>
</aside>
