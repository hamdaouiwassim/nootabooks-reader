@php
  $navItems = [
    'home' => ['url' => route('home'), 'label' => 'الرئيسية', 'icon' => 'fa-house'],
    'discover' => ['url' => route('discover'), 'label' => 'استكشاف', 'icon' => 'fa-compass'],
    'categories' => ['url' => route('categories'), 'label' => 'التصنيفات', 'icon' => 'fa-layer-group'],
    'writers' => ['url' => route('writers'), 'label' => 'المؤلفون', 'icon' => 'fa-feather'],
    'community' => ['url' => route('community'), 'label' => 'المجتمع', 'icon' => 'fa-users'],
  ];
@endphp
<!-- ===================== HEADER ===================== -->
<header class="site-header">
  <div class="header-inner">
    <a href="{{ route('home') }}" class="logo">
      <i class="fa-solid fa-book-bookmark logo-icon"></i>
      <div class="logo-text">
        <span class="logo-title">نوته بوك</span>
        <span class="logo-tagline">عالم من الكتب بين يديك</span>
      </div>
    </a>

    <button class="mobile-nav-toggle" id="mobileNavToggle" aria-label="menu" aria-expanded="false">
      <i class="fa-solid fa-bars"></i>
    </button>

    <nav class="main-nav" id="mainNav">
      <ul>
        @foreach ($navItems as $key => $item)
          <li><a href="{{ $item['url'] }}" @class(['active' => ($activeNav ?? null) === $key])><i class="fa-solid {{ $item['icon'] }}"></i> {{ $item['label'] }}</a></li>
        @endforeach
      </ul>

      <div class="mobile-nav-account">
        @auth
          <div class="user-panel-head">
            <img src="{{ auth()->user()->avatar ?? 'https://i.pravatar.cc/64?img=13' }}" width="64" height="64" loading="lazy" decoding="async" alt="{{ auth()->user()->name }}">
            <div>
              <strong>{{ auth()->user()->name }}</strong>
              <span>عرض الملف الشخصي</span>
            </div>
          </div>
          <a href="{{ route('my-library') }}" class="dropdown-item"><i class="fa-solid fa-book"></i> مكتبتي</a>
          <a href="#" class="dropdown-item"><i class="fa-regular fa-heart"></i> المفضلة</a>
          <a href="{{ route('profile') }}" class="dropdown-item"><i class="fa-regular fa-user"></i> الملف الشخصي</a>
          <a href="{{ route('settings') }}" class="dropdown-item"><i class="fa-solid fa-gear"></i> الإعدادات</a>
          <div class="dropdown-divider"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> تسجيل الخروج</button>
          </form>
        @else
          <a href="{{ route('login') }}" class="dropdown-item"><i class="fa-solid fa-arrow-right-to-bracket"></i> تسجيل الدخول</a>
          <a href="{{ route('register') }}" class="dropdown-item"><i class="fa-regular fa-user"></i> إنشاء حساب</a>
        @endauth
      </div>
    </nav>

    <div class="header-icons">
      @auth
        <div class="dropdown notif-dropdown">
          <button class="icon-btn" aria-label="notifications" id="notifBtn">
            <i class="fa-regular fa-bell"></i>
            <span class="badge-dot" id="notifBadge"></span>
          </button>
          <div class="dropdown-panel notif-panel" id="notifPanel">
            <div class="dropdown-panel-head">
              <h4>الإشعارات</h4>
              <button class="mark-read-btn" id="markAllReadBtn">تحديد الكل كمقروء</button>
            </div>
            <div class="notif-list">
              <a href="{{ route('discussion-details', 'blue-elephant') }}" class="notif-item unread">
                <span class="notif-icon like"><i class="fa-solid fa-thumbs-up"></i></span>
                <div class="notif-text">
                  <p><strong>محمد العتيبي</strong> أعجب بمنشورك في المناقشة</p>
                  <span class="notif-time">منذ 5 دقائق</span>
                </div>
              </a>
              <a href="{{ route('discussion-details', 'blue-elephant') }}" class="notif-item unread">
                <span class="notif-icon comment"><i class="fa-solid fa-comment"></i></span>
                <div class="notif-text">
                  <p><strong>سارة محمود</strong> علّقت على مناقشتك</p>
                  <span class="notif-time">منذ ساعة</span>
                </div>
              </a>
              <a href="{{ route('book-details', 'blue-elephant') }}" class="notif-item">
                <span class="notif-icon book"><i class="fa-solid fa-book"></i></span>
                <div class="notif-text">
                  <p>صدر كتاب جديد لـ <strong>أحمد مراد</strong></p>
                  <span class="notif-time">منذ 3 ساعات</span>
                </div>
              </a>
              <a href="{{ route('club-details', 'arabic-lit') }}" class="notif-item">
                <span class="notif-icon club"><i class="fa-solid fa-people-group"></i></span>
                <div class="notif-text">
                  <p>تذكير: مناقشة نادي "أدب عربي معاصر" غدًا</p>
                  <span class="notif-time">أمس</span>
                </div>
              </a>
              <a href="{{ route('my-library') }}" class="notif-item">
                <span class="notif-icon download"><i class="fa-solid fa-circle-check"></i></span>
                <div class="notif-text">
                  <p>تم تحميل كتاب "1984" بنجاح</p>
                  <span class="notif-time">منذ يومين</span>
                </div>
              </a>
            </div>
            <a href="{{ route('notifications') }}" class="dropdown-panel-footer">عرض كل الإشعارات</a>
          </div>
        </div>
      @endauth
    </div>

    @auth
      <div class="dropdown user-dropdown">
        <div class="user-profile" id="userMenuBtn" tabindex="0" role="button">
          <i class="fa-solid fa-chevron-down"></i>
          <span class="user-name">{{ auth()->user()->name }}</span>
          <img src="{{ auth()->user()->avatar ?? 'https://i.pravatar.cc/64?img=13' }}" width="64" height="64" decoding="async" alt="{{ auth()->user()->name }}" class="avatar">
        </div>
        <div class="dropdown-panel user-panel" id="userPanel">
          <div class="user-panel-head">
            <img src="{{ auth()->user()->avatar ?? 'https://i.pravatar.cc/64?img=13' }}" width="64" height="64" loading="lazy" decoding="async" alt="{{ auth()->user()->name }}">
            <div>
              <strong>{{ auth()->user()->name }}</strong>
              <span>عرض الملف الشخصي</span>
            </div>
          </div>
          <a href="{{ route('my-library') }}" class="dropdown-item"><i class="fa-solid fa-book"></i> مكتبتي</a>
          <a href="#" class="dropdown-item"><i class="fa-regular fa-heart"></i> المفضلة</a>
          <a href="{{ route('profile') }}" class="dropdown-item"><i class="fa-regular fa-user"></i> الملف الشخصي</a>
          <a href="{{ route('settings') }}" class="dropdown-item"><i class="fa-solid fa-gear"></i> الإعدادات</a>
          <div class="dropdown-divider"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> تسجيل الخروج</button>
          </form>
        </div>
      </div>
    @else
      <div class="header-auth-actions">
        <a href="{{ route('login') }}" class="btn btn-outline small">تسجيل الدخول</a>
        <a href="{{ route('register') }}" class="btn btn-teal small">إنشاء حساب</a>
      </div>
    @endauth
  </div>
</header>
