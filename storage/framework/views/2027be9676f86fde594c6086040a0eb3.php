<?php $__env->startSection('title', 'قراءة: '.$currentBook->title.' - نوته بوك'); ?>

<?php $__env->startSection('content'); ?>

<?php
  $bookDownloadUrl = $currentBook->downloadUrl();
  $bookStreamUrl = $currentBook->streamUrl();
?>

<div class="reader-page">

  <!-- ===================== READER TOPBAR ===================== -->
  <header class="reader-topbar">
    <a href="<?php echo e(route('book-details', $currentBook->slug)); ?>" class="reader-back">
      <i class="fa-solid fa-arrow-right"></i>
      <span>رجوع</span>
    </a>

    <div class="reader-book-info">
      <?php if($currentBook->cover_image): ?>
        <img src="<?php echo e($currentBook->cover_image_sm_url); ?>" width="300" height="450" loading="lazy" decoding="async" alt="<?php echo e($currentBook->cover_alt); ?>" class="reader-mini-cover">
      <?php endif; ?>
      <div class="reader-book-info-text">
        <strong><?php echo e($currentBook->title); ?></strong>
        <span><?php echo e($currentBook->writer?->name ?? 'بدون مؤلف'); ?></span>
      </div>
    </div>

    <div class="reader-actions">
      <?php if($bookDownloadUrl): ?>
        <a href="<?php echo e($bookDownloadUrl); ?>" class="reader-action-btn" title="تحميل الكتاب" aria-label="download">
          <i class="fa-solid fa-download"></i>
        </a>
      <?php endif; ?>
      <button class="reader-action-btn" id="readerFullscreenBtn" title="ملء الشاشة" aria-label="fullscreen">
        <i class="fa-solid fa-expand"></i>
      </button>
    </div>
  </header>

  <!-- ===================== PDF VIEWER ===================== -->
  <main class="reader-frame-wrap" id="readerFrameWrap">
    <?php if($bookStreamUrl): ?>
      <iframe src="<?php echo e($bookStreamUrl); ?>" class="reader-frame" title="<?php echo e($currentBook->title); ?>"></iframe>
    <?php else: ?>
      <div class="reader-empty">
        <i class="fa-solid fa-file-circle-exclamation"></i>
        <p>لا يتوفر ملف لهذا الكتاب حاليًا</p>
      </div>
    <?php endif; ?>
  </main>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.reader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/read.blade.php ENDPATH**/ ?>