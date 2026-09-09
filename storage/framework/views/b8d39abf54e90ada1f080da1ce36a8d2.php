<?php $__env->startSection('title', 'إدارة المؤلفين - مكتبتي'); ?>

<?php
  $pageTitle = 'إدارة المؤلفين';
  $breadcrumb = [['label' => 'إدارة المؤلفين', 'url' => null]];
?>

<?php $__env->startSection('content'); ?>

<div class="admin-page-head">
  <div>
    <h1>إدارة المؤلفين</h1>
    <p>عرض وتعديل وإضافة المؤلفين المتوفرين على المنصة (<?php echo e($totalWriters); ?> مؤلف)</p>
  </div>
  <a href="<?php echo e(route('admin.writers.create')); ?>" class="btn btn-gold"><i class="fa-solid fa-plus"></i> إضافة مؤلف جديد</a>
</div>

<form class="admin-toolbar" method="GET" action="<?php echo e(route('admin.writers.index')); ?>">
  <div class="admin-search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="ابحث باسم المؤلف ...">
  </div>
  <button type="submit" class="btn btn-outline">بحث</button>
</form>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>المؤلف</th>
        <th>التصنيف الأدبي</th>
        <th>عدد الكتب</th>
        <th>المتابعون</th>
        <th>التقييم</th>
        <th></th>
      </tr>
    </thead>
    <tbody id="writersTableBody">
      <?php $__empty_1 = true; $__currentLoopData = $writers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $writer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          <td>
            <div class="admin-book-cell">
              <?php if($writer->photo): ?>
                <img class="admin-book-cover" style="border-radius:50%; width:44px; height:44px;" src="<?php echo e($writer->photo_url); ?>" alt="<?php echo e($writer->name); ?>">
              <?php else: ?>
                <span class="admin-book-cover placeholder" style="border-radius:50%; width:44px; height:44px;"><i class="fa-solid fa-feather"></i></span>
              <?php endif; ?>
              <div>
                <strong><?php echo e($writer->name); ?></strong>
                <?php if($writer->is_featured): ?>
                  <span class="status-badge published">مميز</span>
                <?php endif; ?>
              </div>
            </div>
          </td>
          <td><?php echo e($writer->genre_tag ?? '—'); ?></td>
          <td><?php echo e($writer->books_count); ?></td>
          <td><?php echo e(number_format($writer->followers_count)); ?></td>
          <td><i class="fa-solid fa-star" style="color:var(--star)"></i> <?php echo e(number_format($writer->rating_average, 1)); ?></td>
          <td>
            <div class="admin-row-actions">
              <a href="<?php echo e(route('writer-details', $writer->slug)); ?>" class="admin-icon-btn" title="عرض" aria-label="view"><i class="fa-regular fa-eye"></i></a>
              <a href="<?php echo e(route('admin.writers.edit', $writer)); ?>" class="admin-icon-btn" title="تعديل" aria-label="edit"><i class="fa-solid fa-pen"></i></a>
              <form method="POST" action="<?php echo e(route('admin.writers.destroy', $writer)); ?>" data-confirm-delete data-item-title="<?php echo e($writer->name); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr class="admin-empty-row">
          <td colspan="6">لا يوجد مؤلفون مطابقون لبحثك</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="admin-pagination">
  <span class="admin-pagination-info"><?php echo e($writers->total() ? "عرض {$writers->firstItem()}-{$writers->lastItem()} من {$writers->total()} مؤلف" : 'لا توجد نتائج'); ?></span>
  <div class="admin-pagination-controls">
    <?php echo e($writers->onEachSide(1)->links('admin.pagination.admin')); ?>

  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('modals'); ?>
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا المؤلف؟</h3>
    <p>سيتم حذف المؤلف نهائيًا. تبقى كتبه على المنصة لكنها تصبح بدون مؤلف محدد.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/admin/writers/index.blade.php ENDPATH**/ ?>