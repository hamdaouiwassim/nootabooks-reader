<?php $__env->startSection('title', 'التصنيفات - نوته بوك'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/writers.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/categories.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="<?php echo e(route('home')); ?>">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>التصنيفات</span>
  </nav>
</div>

<!-- ===================== PAGE HEADING ===================== -->
<section class="section writers-hero">
  <h1>تصفح حسب التصنيف</h1>
  <p>اكتشف آلاف الكتب مرتبة حسب اهتماماتك المفضلة</p>

  <div class="writers-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" id="categorySearch" placeholder="ابحث عن تصنيف ...">
  </div>

  <div class="filter-tabs">
    <button class="filter-tab active" data-sort="all">الكل</button>
    <button class="filter-tab" data-sort="popular">الأكثر كتبًا</button>
    <button class="filter-tab" data-sort="az">أبجديًا</button>
  </div>
</section>

<!-- ===================== TOP CATEGORIES STRIP ===================== -->
<section class="section">
  <div class="top-categories-strip">
    <span class="strip-label"><i class="fa-solid fa-fire"></i> الأكثر رواجًا</span>
    <a href="#" class="strip-chip">روايات</a>
    <a href="#" class="strip-chip">أدب عربي</a>
    <a href="#" class="strip-chip">تنمية ذاتية</a>
    <a href="#" class="strip-chip">أدب عالمي</a>
    <a href="#" class="strip-chip">تاريخ</a>
  </div>
</section>

<!-- ===================== CATEGORIES GRID ===================== -->
<section class="section">
  <div class="section-head">
    <h2 class="section-title">جميع التصنيفات</h2>
    <span class="results-count"><span id="resultsCount">16</span> تصنيف</span>
  </div>

  <div class="categories-full-grid" id="categoriesGrid">

    <?php
      $categories = [
        ['name' => 'روايات', 'count' => 1560, 'color' => 'cat-purple', 'icon' => 'fa-bookmark'],
        ['name' => 'أدب عربي', 'count' => 1240, 'color' => 'cat-navy', 'icon' => 'fa-book'],
        ['name' => 'أدب عالمي', 'count' => 980, 'color' => 'cat-teal', 'icon' => 'fa-earth-africa'],
        ['name' => 'تنمية ذاتية', 'count' => 720, 'color' => 'cat-brown', 'icon' => 'fa-bell'],
        ['name' => 'تاريخ', 'count' => 610, 'color' => 'cat-navy', 'icon' => 'fa-landmark'],
        ['name' => 'علوم', 'count' => 540, 'color' => 'cat-green', 'icon' => 'fa-atom'],
        ['name' => 'أطفال', 'count' => 450, 'color' => 'cat-rose', 'icon' => 'fa-child'],
        ['name' => 'دين وروحانيات', 'count' => 480, 'color' => 'cat-gold', 'icon' => 'fa-mosque'],
        ['name' => 'شعر', 'count' => 390, 'color' => 'cat-teal', 'icon' => 'fa-feather'],
        ['name' => 'تكنولوجيا', 'count' => 340, 'color' => 'cat-teal', 'icon' => 'fa-microchip'],
        ['name' => 'فلسفة', 'count' => 320, 'color' => 'cat-green', 'icon' => 'fa-leaf'],
        ['name' => 'تنمية ذهنية', 'count' => 310, 'color' => 'cat-green', 'icon' => 'fa-brain'],
        ['name' => 'قصص قصيرة', 'count' => 275, 'color' => 'cat-navy', 'icon' => 'fa-file-lines'],
        ['name' => 'اقتصاد وأعمال', 'count' => 260, 'color' => 'cat-navy', 'icon' => 'fa-chart-line'],
        ['name' => 'سياسة', 'count' => 210, 'color' => 'cat-navy', 'icon' => 'fa-landmark-dome'],
        ['name' => 'فنون وتصميم', 'count' => 190, 'color' => 'cat-rose', 'icon' => 'fa-palette'],
      ];
    ?>

    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="#" class="category-full-card" data-name="<?php echo e($category['name']); ?>" data-count="<?php echo e($category['count']); ?>">
        <span class="cat-icon-circle <?php echo e($category['color']); ?>"><i class="fa-solid <?php echo e($category['icon']); ?>"></i></span>
        <h3><?php echo e($category['name']); ?></h3>
        <p><?php echo e(number_format($category['count'])); ?> كتاب</p>
      </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

  </div>

  <p class="no-results" id="noResults" hidden>لا يوجد تصنيفات مطابقة لبحثك.</p>
</section>

</main>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('assets/js/categories.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/categories.blade.php ENDPATH**/ ?>