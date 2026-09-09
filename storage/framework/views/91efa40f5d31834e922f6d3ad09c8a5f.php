<header class="admin-topbar">
  <button class="admin-sidebar-toggle" id="adminSidebarToggle" aria-label="menu"><i class="fa-solid fa-bars"></i></button>
  <div>
    <div class="admin-page-title"><?php echo e($pageTitle ?? ''); ?></div>
    <div class="admin-breadcrumb">
      <a href="<?php echo e(route('admin.dashboard')); ?>">الرئيسية</a>
      <?php if(isset($breadcrumb)): ?>
        <?php $__currentLoopData = $breadcrumb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          / <?php if($crumb['url']): ?> <a href="<?php echo e($crumb['url']); ?>"><?php echo e($crumb['label']); ?></a> <?php else: ?> <?php echo e($crumb['label']); ?> <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="admin-topbar-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="بحث سريع ...">
  </div>

  <div class="admin-topbar-user">
    <div>
      <strong><?php echo e(auth('admin')->user()->name); ?></strong>
      <span>مدير النظام</span>
    </div>
    <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(auth('admin')->user()->name)); ?>&background=1c4a45&color=fff" alt="<?php echo e(auth('admin')->user()->name); ?>">
  </div>
</header>
<?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/admin/partials/admin-topbar.blade.php ENDPATH**/ ?>