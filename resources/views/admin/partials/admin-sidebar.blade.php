<aside class="admin-sidebar" id="adminSidebar">
  <div class="admin-sidebar-head">
    <i class="fa-solid fa-book-bookmark logo-icon"></i>
    <div class="logo-text">
      <span class="logo-title">مكتبتي</span>
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

    <span class="admin-nav-label">المجتمع</span>
    <a href="{{ route('admin.users.index') }}" @class(['active' => $activeNav === 'users'])><i class="fa-solid fa-users"></i> المستخدمون</a>
    <a href="#"><i class="fa-solid fa-people-group"></i> نوادي القراءة</a>
    <a href="#"><i class="fa-solid fa-comments"></i> المناقشات</a>

    <span class="admin-nav-label">النظام</span>
    <a href="#"><i class="fa-solid fa-chart-line"></i> الإحصائيات</a>
    <a href="#"><i class="fa-solid fa-gear"></i> الإعدادات</a>
  </nav>

  <div class="admin-sidebar-foot">
    <a href="{{ route('home') }}"><i class="fa-solid fa-arrow-right-from-bracket fa-rotate-180"></i> العودة للموقع</a>
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button type="submit" class="logout"><i class="fa-solid fa-power-off"></i> تسجيل الخروج</button>
    </form>
  </div>
</aside>
