<?php if($paginator->hasPages()): ?>
  <nav class="pagination" aria-label="Pagination">
    <?php if($paginator->onFirstPage()): ?>
      <button class="page-btn" aria-label="previous" disabled><i class="fa-solid fa-chevron-right"></i></button>
    <?php else: ?>
      <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="page-btn" aria-label="previous"><i class="fa-solid fa-chevron-right"></i></a>
    <?php endif; ?>

    <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php if(is_string($element)): ?>
        <span class="page-btn" aria-hidden="true"><?php echo e($element); ?></span>
      <?php endif; ?>

      <?php if(is_array($element)): ?>
        <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php if($page == $paginator->currentPage()): ?>
            <button class="page-btn active"><?php echo e($page); ?></button>
          <?php else: ?>
            <a href="<?php echo e($url); ?>" class="page-btn"><?php echo e($page); ?></a>
          <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php if($paginator->hasMorePages()): ?>
      <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="page-btn" aria-label="next"><i class="fa-solid fa-chevron-left"></i></a>
    <?php else: ?>
      <button class="page-btn" aria-label="next" disabled><i class="fa-solid fa-chevron-left"></i></button>
    <?php endif; ?>
  </nav>
<?php endif; ?>
<?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/pagination/default.blade.php ENDPATH**/ ?>