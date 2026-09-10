<?php $__env->startSection('title', 'إدارة الكتب - مكتبتي'); ?>

<?php
  $pageTitle = 'إدارة الكتب';
  $breadcrumb = [['label' => 'إدارة الكتب', 'url' => null]];
?>

<?php $__env->startSection('content'); ?>

<div class="admin-page-head">
  <div>
    <h1>إدارة الكتب</h1>
    <p>عرض وتعديل وإضافة الكتب المتوفرة على المنصة (<?php echo e($totalBooks); ?> كتاب)</p>
  </div>
  <a href="<?php echo e(route('admin.books.create')); ?>" class="btn btn-gold"><i class="fa-solid fa-plus"></i> إضافة كتاب جديد</a>
</div>

<form class="admin-toolbar" method="GET" action="<?php echo e(route('admin.books.index')); ?>">
  <div class="admin-search-box">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="ابحث بعنوان الكتاب أو اسم المؤلف ...">
  </div>
  <select class="admin-filter-select" name="category" onchange="this.form.submit()">
    <option value="">كل التصنيفات</option>
    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <option value="<?php echo e($category->id); ?>" <?php if(request('category') == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </select>
  <button type="submit" class="btn btn-outline">بحث</button>
</form>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>الكتاب</th>
        <th>التصنيف</th>
        <th>الصيغ</th>
        <th>التقييم</th>
        <th>التحميلات</th>
        <th>تاريخ الإضافة</th>
        <th></th>
      </tr>
    </thead>
    <tbody id="booksTableBody">
      <?php $__empty_1 = true; $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
          <td><?php echo e($book->formats ? implode('، ', $book->formats) : '—'); ?></td>
          <td><i class="fa-solid fa-star" style="color:var(--star)"></i> <?php echo e(number_format($book->rating_average, 1)); ?></td>
          <td><?php echo e(number_format($book->downloads_count)); ?></td>
          <td><?php echo e($book->created_at->diffForHumans()); ?></td>
          <td>
            <div class="admin-row-actions">
              <a href="<?php echo e(route('read', $book->slug)); ?>" class="admin-icon-btn" title="عرض" aria-label="view"><i class="fa-regular fa-eye"></i></a>
              <a href="<?php echo e(route('admin.books.edit', $book)); ?>" class="admin-icon-btn" title="تعديل" aria-label="edit"><i class="fa-solid fa-pen"></i></a>
              <form method="POST" action="<?php echo e(route('admin.books.destroy', $book)); ?>" data-confirm-delete data-item-title="<?php echo e($book->title); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="admin-icon-btn danger" title="حذف" aria-label="delete"><i class="fa-solid fa-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr class="admin-empty-row">
          <td colspan="7">لا توجد كتب مطابقة لبحثك</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="admin-pagination">
  <span class="admin-pagination-info"><?php echo e($books->total() ? "عرض {$books->firstItem()}-{$books->lastItem()} من {$books->total()} كتاب" : 'لا توجد نتائج'); ?></span>
  <div class="admin-pagination-controls">
    <?php echo e($books->onEachSide(1)->links('admin.pagination.admin')); ?>

  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('modals'); ?>
<div class="admin-modal-overlay" id="deleteModal">
  <div class="admin-modal">
    <div class="admin-modal-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h3 id="deleteModalTitle">هل تريد حذف هذا الكتاب؟</h3>
    <p>سيتم حذف الكتاب بشكل نهائي من المنصة، ولن تتمكن من التراجع عن هذا الإجراء.</p>
    <div class="admin-modal-actions">
      <button type="button" class="btn btn-outline" id="deleteModalCancel">إلغاء</button>
      <button type="button" class="btn btn-danger" id="deleteModalConfirm">حذف نهائيًا</button>
    </div>
  </div>
</div>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/admin/books/index.blade.php ENDPATH**/ ?>