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
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => $activeNav === 'dashboard']); ?>"><i class="fa-solid fa-gauge-high"></i> لوحة التحكم</a>

    <span class="admin-nav-label">المحتوى</span>
    <a href="<?php echo e(route('admin.books.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => $activeNav === 'books']); ?>"><i class="fa-solid fa-book"></i> إدارة الكتب <span class="badge-count"><?php echo e($sidebarBooksCount); ?></span></a>
    <a href="<?php echo e(route('admin.writers.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => $activeNav === 'writers']); ?>"><i class="fa-solid fa-feather"></i> المؤلفون <span class="badge-count"><?php echo e($sidebarWritersCount); ?></span></a>
    <a href="<?php echo e(route('admin.categories.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => $activeNav === 'categories']); ?>"><i class="fa-solid fa-layer-group"></i> التصنيفات <span class="badge-count"><?php echo e($sidebarCategoriesCount); ?></span></a>

    <span class="admin-nav-label">المجتمع</span>
    <a href="<?php echo e(route('admin.users.index')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => $activeNav === 'users']); ?>"><i class="fa-solid fa-users"></i> المستخدمون</a>
    <a href="#"><i class="fa-solid fa-people-group"></i> نوادي القراءة</a>
    <a href="#"><i class="fa-solid fa-comments"></i> المناقشات</a>

    <span class="admin-nav-label">النظام</span>
    <a href="<?php echo e(route('admin.statistics')); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['active' => $activeNav === 'statistics']); ?>"><i class="fa-solid fa-chart-line"></i> الإحصائيات</a>
    <a href="#"><i class="fa-solid fa-gear"></i> الإعدادات</a>
  </nav>

  <div class="admin-sidebar-foot">
    <a href="<?php echo e(route('home')); ?>"><i class="fa-solid fa-arrow-right-from-bracket fa-rotate-180"></i> العودة للموقع</a>
    <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
      <?php echo csrf_field(); ?>
      <button type="submit" class="logout"><i class="fa-solid fa-power-off"></i> تسجيل الخروج</button>
    </form>
  </div>
</aside>
<?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/admin/partials/admin-sidebar.blade.php ENDPATH**/ ?>