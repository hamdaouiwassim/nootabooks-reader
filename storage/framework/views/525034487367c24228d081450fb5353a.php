<?php $__env->startSection('title', 'لوحة التحكم - مكتبتي'); ?>

<?php
  $pageTitle = 'لوحة التحكم';
?>

<?php $__env->startSection('content'); ?>

<div class="admin-page-head">
  <div>
    <h1>مرحبًا 👋</h1>
    <p>هذه نظرة سريعة على أداء منصة مكتبتي اليوم</p>
  </div>
</div>

<div class="admin-stats-grid">
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-gold"><i class="fa-solid fa-book"></i></span>
    <div>
      <strong><?php echo e(number_format($booksCount)); ?></strong>
      <span>كتاب في المنصة</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-teal"><i class="fa-solid fa-users"></i></span>
    <div>
      <strong><?php echo e(number_format($usersCount)); ?></strong>
      <span>مستخدم مسجل</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-navy"><i class="fa-solid fa-cloud-arrow-down"></i></span>
    <div>
      <strong><?php echo e(number_format($downloadsSum)); ?></strong>
      <span>إجمالي التحميلات</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-rose"><i class="fa-solid fa-layer-group"></i></span>
    <div>
      <strong><?php echo e(number_format($categoriesCount)); ?></strong>
      <span>تصنيف</span>
    </div>
  </div>
</div>

<div class="admin-panel">
  <div class="admin-panel-head">
    <h3>أحدث الكتب المضافة</h3>
    <a href="<?php echo e(route('admin.books.index')); ?>" class="admin-breadcrumb">عرض كل الكتب <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>الكتاب</th>
          <th>التصنيف</th>
          <th>التحميلات</th>
          <th>تاريخ الإضافة</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $latestBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td>
              <div class="admin-book-cell">
                <?php if($book->cover_image): ?>
                  <img class="admin-book-cover" src="<?php echo e($book->cover_image_sm_url); ?>" alt="<?php echo e($book->title); ?>">
                <?php else: ?>
                  <span class="admin-book-cover placeholder"><i class="fa-solid fa-book"></i></span>
                <?php endif; ?>
                <div><strong><?php echo e($book->title); ?></strong><span><?php echo e($book->writer?->name ?? 'بدون مؤلف'); ?></span></div>
              </div>
            </td>
            <td><?php echo e($book->category->name); ?></td>
            <td><?php echo e(number_format($book->downloads_count)); ?></td>
            <td><?php echo e($book->created_at->diffForHumans()); ?></td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr class="admin-empty-row">
            <td colspan="4">لا توجد كتب بعد</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>