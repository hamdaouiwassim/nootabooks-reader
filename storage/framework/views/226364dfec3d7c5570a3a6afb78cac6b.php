<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo $__env->yieldContent('title', 'لوحة التحكم - مكتبتي'); ?></title>
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/fonts.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/vendor/fontawesome/all.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/style.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/admin.css')); ?>">
<?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="admin-body">

<?php echo $__env->make('partials.loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="admin-sidebar-backdrop" id="adminSidebarBackdrop"></div>

<div class="admin-shell">
  <?php echo $__env->make('admin.partials.admin-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <div class="admin-content">

    <?php echo $__env->make('admin.partials.admin-topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="admin-main">
      <?php if(session('status')): ?>
        <div class="admin-alert success"><i class="fa-solid fa-circle-check"></i> <?php echo e(session('status')); ?></div>
      <?php endif; ?>
      <?php if($errors->any()): ?>
        <div class="admin-alert error">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
        </div>
      <?php endif; ?>
      <?php echo $__env->yieldContent('content'); ?>
    </main>
  </div>
</div>

<?php echo $__env->yieldPushContent('modals'); ?>

<script src="<?php echo e(asset_min('assets/js/admin.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/admin/layouts/admin.blade.php ENDPATH**/ ?>