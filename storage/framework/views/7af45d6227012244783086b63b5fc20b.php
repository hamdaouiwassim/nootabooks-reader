<?php
  $navItems = [
    'home' => ['url' => route('home'), 'label' => 'الرئيسية', 'icon' => 'fa-house'],
    'discover' => ['url' => route('discover'), 'label' => 'استكشاف', 'icon' => 'fa-compass'],
    'categories' => ['url' => route('categories'), 'label' => 'التصنيفات', 'icon' => 'fa-layer-group'],
    'writers' => ['url' => route('writers'), 'label' => 'المؤلفون', 'icon' => 'fa-feather'],
    'community' => ['url' => route('community'), 'label' => 'المجتمع', 'icon' => 'fa-users'],
  ];
?>
<!-- ===================== HEADER ===================== -->
<header class="site-header">
  <div class="header-inner">
    <a href="<?php echo e(route('home')); ?>" class="logo">
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
        <?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li><a href="<?php echo e($item['url']); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => ($activeNav ?? null) === $key]); ?>"><i class="fa-solid <?php echo e($item['icon']); ?>"></i> <?php echo e($item['label']); ?></a></li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>

      <div class="mobile-nav-account">
        <?php if(auth()->guard()->check()): ?>
          <div class="user-panel-head">
            <img src="<?php echo e(auth()->user()->avatar ?? 'https://i.pravatar.cc/64?img=13'); ?>" alt="<?php echo e(auth()->user()->name); ?>">
            <div>
              <strong><?php echo e(auth()->user()->name); ?></strong>
              <span>عرض الملف الشخصي</span>
            </div>
          </div>
          <a href="<?php echo e(route('my-library')); ?>" class="dropdown-item"><i class="fa-solid fa-book"></i> مكتبتي</a>
          <a href="#" class="dropdown-item"><i class="fa-regular fa-heart"></i> المفضلة</a>
          <a href="<?php echo e(route('profile')); ?>" class="dropdown-item"><i class="fa-regular fa-user"></i> الملف الشخصي</a>
          <a href="<?php echo e(route('settings')); ?>" class="dropdown-item"><i class="fa-solid fa-gear"></i> الإعدادات</a>
          <div class="dropdown-divider"></div>
          <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="dropdown-item logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> تسجيل الخروج</button>
          </form>
        <?php else: ?>
          <a href="<?php echo e(route('login')); ?>" class="dropdown-item"><i class="fa-solid fa-arrow-right-to-bracket"></i> تسجيل الدخول</a>
          <a href="<?php echo e(route('register')); ?>" class="dropdown-item"><i class="fa-regular fa-user"></i> إنشاء حساب</a>
        <?php endif; ?>
      </div>
    </nav>

    <div class="header-icons">
      <?php if(auth()->guard()->check()): ?>
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
              <a href="<?php echo e(route('discussion-details', 'blue-elephant')); ?>" class="notif-item unread">
                <span class="notif-icon like"><i class="fa-solid fa-thumbs-up"></i></span>
                <div class="notif-text">
                  <p><strong>محمد العتيبي</strong> أعجب بمنشورك في المناقشة</p>
                  <span class="notif-time">منذ 5 دقائق</span>
                </div>
              </a>
              <a href="<?php echo e(route('discussion-details', 'blue-elephant')); ?>" class="notif-item unread">
                <span class="notif-icon comment"><i class="fa-solid fa-comment"></i></span>
                <div class="notif-text">
                  <p><strong>سارة محمود</strong> علّقت على مناقشتك</p>
                  <span class="notif-time">منذ ساعة</span>
                </div>
              </a>
              <a href="<?php echo e(route('book-details', 'blue-elephant')); ?>" class="notif-item">
                <span class="notif-icon book"><i class="fa-solid fa-book"></i></span>
                <div class="notif-text">
                  <p>صدر كتاب جديد لـ <strong>أحمد مراد</strong></p>
                  <span class="notif-time">منذ 3 ساعات</span>
                </div>
              </a>
              <a href="<?php echo e(route('club-details', 'arabic-lit')); ?>" class="notif-item">
                <span class="notif-icon club"><i class="fa-solid fa-people-group"></i></span>
                <div class="notif-text">
                  <p>تذكير: مناقشة نادي "أدب عربي معاصر" غدًا</p>
                  <span class="notif-time">أمس</span>
                </div>
              </a>
              <a href="<?php echo e(route('my-library')); ?>" class="notif-item">
                <span class="notif-icon download"><i class="fa-solid fa-circle-check"></i></span>
                <div class="notif-text">
                  <p>تم تحميل كتاب "1984" بنجاح</p>
                  <span class="notif-time">منذ يومين</span>
                </div>
              </a>
            </div>
            <a href="<?php echo e(route('notifications')); ?>" class="dropdown-panel-footer">عرض كل الإشعارات</a>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <?php if(auth()->guard()->check()): ?>
      <div class="dropdown user-dropdown">
        <div class="user-profile" id="userMenuBtn" tabindex="0" role="button">
          <i class="fa-solid fa-chevron-down"></i>
          <span class="user-name"><?php echo e(auth()->user()->name); ?></span>
          <img src="<?php echo e(auth()->user()->avatar ?? 'https://i.pravatar.cc/64?img=13'); ?>" alt="<?php echo e(auth()->user()->name); ?>" class="avatar">
        </div>
        <div class="dropdown-panel user-panel" id="userPanel">
          <div class="user-panel-head">
            <img src="<?php echo e(auth()->user()->avatar ?? 'https://i.pravatar.cc/64?img=13'); ?>" alt="<?php echo e(auth()->user()->name); ?>">
            <div>
              <strong><?php echo e(auth()->user()->name); ?></strong>
              <span>عرض الملف الشخصي</span>
            </div>
          </div>
          <a href="<?php echo e(route('my-library')); ?>" class="dropdown-item"><i class="fa-solid fa-book"></i> مكتبتي</a>
          <a href="#" class="dropdown-item"><i class="fa-regular fa-heart"></i> المفضلة</a>
          <a href="<?php echo e(route('profile')); ?>" class="dropdown-item"><i class="fa-regular fa-user"></i> الملف الشخصي</a>
          <a href="<?php echo e(route('settings')); ?>" class="dropdown-item"><i class="fa-solid fa-gear"></i> الإعدادات</a>
          <div class="dropdown-divider"></div>
          <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="dropdown-item logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> تسجيل الخروج</button>
          </form>
        </div>
      </div>
    <?php else: ?>
      <div class="header-auth-actions">
        <a href="<?php echo e(route('login')); ?>" class="btn btn-outline small">تسجيل الدخول</a>
        <a href="<?php echo e(route('register')); ?>" class="btn btn-teal small">إنشاء حساب</a>
      </div>
    <?php endif; ?>
  </div>
</header>
<?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/partials/header.blade.php ENDPATH**/ ?>