<?php $__env->startSection('title', 'المؤلفون - نوته بوك'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/writers.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<main>

<?php
  $followedSlugs = auth()->user()?->followedWriters()->pluck('slug')->toArray() ?? [];
?>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="<?php echo e(route('home')); ?>">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>المؤلفون</span>
  </nav>
</div>

<!-- ===================== PAGE HEADING ===================== -->
<section class="section writers-hero">
  <h1>المؤلفون</h1>
  <p>تعرف على أبرز الكتّاب والمؤلفين في مكتبتنا واكتشف أعمالهم</p>

  <div class="writers-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" id="writerSearch" placeholder="ابحث عن مؤلف بالاسم ...">
  </div>

  <div class="filter-tabs">
    <button class="filter-tab active" data-filter="all">الكل</button>
    <button class="filter-tab" data-filter="popular">الأكثر متابعة</button>
    <button class="filter-tab" data-filter="arabic">أدب عربي</button>
    <button class="filter-tab" data-filter="world">أدب عالمي</button>
    <button class="filter-tab" data-filter="classic">كلاسيكيات</button>
  </div>
</section>

<!-- ===================== FEATURED AUTHOR ===================== -->
<section class="section">
  <div class="featured-author">
    <img src="https://i.pravatar.cc/240?img=59" alt="نجيب محفوظ" class="featured-photo">
    <div class="featured-info">
      <span class="featured-chip"><i class="fa-solid fa-star"></i> مؤلف الأسبوع</span>
      <h2>نجيب محفوظ</h2>
      <p>روائي مصري، حائز على جائزة نوبل للآداب عام 1988، ويُعد أحد أعمدة الرواية العربية الحديثة. تركت أعماله مثل "الثلاثية" و"أولاد حارتنا" أثرًا عميقًا في الأدب العربي والعالمي.</p>
      <div class="featured-stats">
        <span><i class="fa-solid fa-book"></i> 34 كتاب</span>
        <span><i class="fa-solid fa-users"></i> 289,000 متابع</span>
        <span><i class="fa-solid fa-star"></i> 4.8 تقييم</span>
      </div>
      <div class="featured-actions">
        <a href="<?php echo e(route('writer-details', 'naguib-mahfouz')); ?>" class="btn btn-gold">عرض الأعمال</a>
        <?php if(auth()->guard()->check()): ?>
          <form method="POST" action="<?php echo e(route('writers.follow', 'naguib-mahfouz')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-gold follow-btn <?php if(in_array('naguib-mahfouz', $followedSlugs)): ?> following <?php endif; ?>">
              <?php echo e(in_array('naguib-mahfouz', $followedSlugs) ? 'تتم المتابعة' : 'متابعة'); ?>

            </button>
          </form>
        <?php else: ?>
          <a href="<?php echo e(route('login')); ?>" class="btn btn-gold follow-btn">متابعة</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- ===================== WRITERS GRID ===================== -->
<section class="section">
  <div class="section-head">
    <h2 class="section-title">جميع المؤلفين</h2>
    <span class="results-count"><span id="resultsCount">10</span> مؤلف</span>
  </div>

  <div class="writers-grid" id="writersGrid">

    <?php
      $writers = [
        ['slug' => 'naguib-mahfouz', 'img' => 59, 'name' => 'نجيب محفوظ', 'tags' => 'arabic classic popular', 'tag' => 'أدب عربي كلاسيكي', 'desc' => 'حائز على جائزة نوبل للآداب، من أعمدة الرواية العربية الحديثة.', 'books' => '34 كتاب', 'followers' => '289K'],
        ['slug' => 'ahmed-mourad', 'img' => 14, 'name' => 'أحمد مراد', 'tags' => 'arabic popular', 'tag' => 'إثارة وغموض', 'desc' => 'روائي وسيناريست مصري، من أبرز كتاب الرواية البوليسية المعاصرة.', 'books' => '8 كتب', 'followers' => '152K'],
        ['slug' => 'ahmed-khaled-tawfik', 'img' => 68, 'name' => 'أحمد خالد توفيق', 'tags' => 'arabic popular', 'tag' => 'رعب وخيال علمي', 'desc' => 'رائد أدب الرعب والخيال العلمي في المكتبة العربية.', 'books' => '21 كتاب', 'followers' => '198K'],
        ['slug' => 'ihsan-abdel-quddous', 'img' => 52, 'name' => 'أحسان عبد القدوس', 'tags' => 'arabic', 'tag' => 'أدب عربي كلاسيكي', 'desc' => 'رائد الرواية الرومانسية والاجتماعية في الأدب المصري.', 'books' => '45 كتاب', 'followers' => '97K'],
        ['slug' => 'amr-abdelhamid', 'img' => 13, 'name' => 'عمرو عبد الحميد', 'tags' => 'arabic', 'tag' => 'أدب عربي معاصر', 'desc' => 'روايات فلسفية وتشويقية حظيت بانتشار واسع بين الشباب.', 'books' => '12 كتاب', 'followers' => '134K'],
        ['slug' => 'youssef-ziedan', 'img' => 33, 'name' => 'يوسف زيدان', 'tags' => 'arabic', 'tag' => 'أدب عربي معاصر', 'desc' => 'روائي ومفكر إسلامي، صاحب رواية "عزازيل" الشهيرة.', 'books' => '15 كتاب', 'followers' => '88K'],
        ['slug' => 'gabriel-garcia-marquez', 'img' => 8, 'name' => 'غابرييل غارسيا ماركيز', 'tags' => 'world classic popular', 'tag' => 'أدب عالمي', 'desc' => 'رائد الواقعية السحرية، حائز على جائزة نوبل للآداب.', 'books' => '19 كتاب', 'followers' => '210K'],
        ['slug' => 'paulo-coelho', 'img' => 51, 'name' => 'باولو كويلو', 'tags' => 'world', 'tag' => 'أدب عالمي', 'desc' => 'روائي برازيلي عالمي الشهرة، صاحب رواية "الخيميائي".', 'books' => '30 كتاب', 'followers' => '176K'],
        ['slug' => 'george-orwell', 'img' => 12, 'name' => 'جورج أورويل', 'tags' => 'world classic popular', 'tag' => 'أدب عالمي', 'desc' => 'كاتب بريطاني وناقد سياسي، صاحب رواية "1984" الخالدة.', 'books' => '9 كتب', 'followers' => '245K'],
        ['slug' => 'tayeb-salih', 'img' => 53, 'name' => 'الطيب صالح', 'tags' => 'arabic classic', 'tag' => 'أدب عربي كلاسيكي', 'desc' => 'روائي سوداني عالمي، صاحب "موسم الهجرة إلى الشمال".', 'books' => '7 كتب', 'followers' => '76K'],
      ];
    ?>

    <?php $__currentLoopData = $writers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $writer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <article class="writer-card" data-tags="<?php echo e($writer['tags']); ?>" data-href="<?php echo e(route('writer-details', $writer['slug'])); ?>">
        <img src="https://i.pravatar.cc/140?img=<?php echo e($writer['img']); ?>" alt="<?php echo e($writer['name']); ?>">
        <h3><?php echo e($writer['name']); ?></h3>
        <span class="writer-tag"><?php echo e($writer['tag']); ?></span>
        <p><?php echo e($writer['desc']); ?></p>
        <div class="writer-stats">
          <span><i class="fa-solid fa-book"></i> <?php echo e($writer['books']); ?></span>
          <span><i class="fa-solid fa-users"></i> <?php echo e($writer['followers']); ?></span>
        </div>
        <?php if(auth()->guard()->check()): ?>
          <form method="POST" action="<?php echo e(route('writers.follow', $writer['slug'])); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-outline follow-btn <?php if(in_array($writer['slug'], $followedSlugs)): ?> following <?php endif; ?>">
              <?php echo e(in_array($writer['slug'], $followedSlugs) ? 'تتم المتابعة' : 'متابعة'); ?>

            </button>
          </form>
        <?php else: ?>
          <a href="<?php echo e(route('login')); ?>" class="btn btn-outline follow-btn">متابعة</a>
        <?php endif; ?>
      </article>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

  </div>

  <p class="no-results" id="noResults" hidden>لا يوجد مؤلفون مطابقون لبحثك.</p>

  <button class="btn btn-outline center load-more-btn">تحميل المزيد</button>
</section>

</main>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('assets/js/writers.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/writers.blade.php ENDPATH**/ ?>