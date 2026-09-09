<header class="admin-topbar">
  <button class="admin-sidebar-toggle" id="adminSidebarToggle" aria-label="menu"><i class="fa-solid fa-bars"></i></button>
  <div>
    <div class="admin-page-title">{{ $pageTitle ?? '' }}</div>
    <div class="admin-breadcrumb">
      <a href="{{ route('admin.dashboard') }}">الرئيسية</a>
      @isset($breadcrumb)
        @foreach ($breadcrumb as $crumb)
          / @if($crumb['url']) <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a> @else {{ $crumb['label'] }} @endif
        @endforeach
      @endisset
    </div>
  </div>

  <div class="admin-topbar-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="بحث سريع ...">
  </div>

  <div class="admin-topbar-user">
    <div>
      <strong>{{ auth('admin')->user()->name }}</strong>
      <span>مدير النظام</span>
    </div>
    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth('admin')->user()->name) }}&background=1c4a45&color=fff" alt="{{ auth('admin')->user()->name }}">
  </div>
</header>
