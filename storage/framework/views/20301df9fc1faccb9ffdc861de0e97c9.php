<?php $__env->startSection('title', 'أحمد مراد - نوته بوك'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/book-details.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/writers.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/writer-details.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<main>

<?php
  $currentWriterSlug = $writerSlug ?? 'ahmed-mourad';
  $isFollowingWriter = auth()->user()?->followedWriters()->where('slug', $currentWriterSlug)->exists() ?? false;
?>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="<?php echo e(route('home')); ?>">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <a href="<?php echo e(route('writers')); ?>">المؤلفون</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>أحمد مراد</span>
  </nav>
</div>

<!-- ===================== AUTHOR HERO ===================== -->
<section class="section writer-hero">
  <img src="https://i.pravatar.cc/240?img=14" alt="أحمد مراد" class="writer-hero-photo">

  <div class="writer-hero-info">
    <span class="genre-chip">إثارة وغموض</span>
    <h1 class="writer-hero-name">أحمد مراد</h1>
    <p class="writer-hero-desc">
      روائي وسيناريست وفوتوغرافي مصري، من أبرز كتاب الرواية البوليسية والنفسية في الأدب العربي المعاصر. بدأ مسيرته الأدبية عام 2008 وتحولت عدة أعمال له إلى أفلام سينمائية ناجحة حققت إيرادات مرتفعة في مصر والعالم العربي.
    </p>

    <div class="writer-hero-meta">
      <div class="meta-item"><i class="fa-solid fa-book"></i><span>عدد الكتب</span><strong>8 كتب</strong></div>
      <div class="meta-item"><i class="fa-solid fa-users"></i><span>المتابعون</span><strong>152,000</strong></div>
      <div class="meta-item"><i class="fa-solid fa-star"></i><span>متوسط التقييم</span><strong>4.6 / 5</strong></div>
      <div class="meta-item"><i class="fa-solid fa-calendar-days"></i><span>انضم منذ</span><strong>2008</strong></div>
    </div>

    <div class="writer-hero-actions">
      <?php if(auth()->guard()->check()): ?>
        <form method="POST" action="<?php echo e(route('writers.follow', $currentWriterSlug)); ?>">
          <?php echo csrf_field(); ?>
          <button type="submit" class="btn btn-teal follow-btn <?php if($isFollowingWriter): ?> following <?php endif; ?>">
            <i class="fa-solid <?php echo e($isFollowingWriter ? 'fa-user-check' : 'fa-user-plus'); ?>"></i>
            <?php echo e($isFollowingWriter ? 'تتم المتابعة' : 'متابعة'); ?>

          </button>
        </form>
      <?php else: ?>
        <a href="<?php echo e(route('login')); ?>" class="btn btn-teal follow-btn"><i class="fa-solid fa-user-plus"></i> متابعة</a>
      <?php endif; ?>
      <button class="icon-btn-outline" aria-label="share"><i class="fa-solid fa-share-nodes"></i></button>
      <div class="writer-socials">
        <a href="#"><i class="fa-brands fa-twitter"></i></a>
        <a href="#"><i class="fa-brands fa-instagram"></i></a>
        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
      </div>
    </div>
  </div>
</section>

<!-- ===================== AUTHOR BOOKS ===================== -->
<section class="section">
  <div class="section-head">
    <div class="section-title-wrap">
      <h2 class="section-title">كتب أحمد مراد</h2>
      <p class="section-sub">جميع الأعمال المنشورة للمؤلف</p>
    </div>
    <span class="results-count"><span id="booksCount">8</span> كتاب</span>
  </div>

  <div class="filter-tabs">
    <button class="filter-tab active" data-sort="all">الكل</button>
    <button class="filter-tab" data-sort="popular">الأكثر تحميلاً</button>
    <button class="filter-tab" data-sort="newest">الأحدث</button>
  </div>

  <div class="writer-books-grid" id="writerBooksGrid">
    <article class="book-card" data-year="2012" data-downloads="18540">
      <a href="<?php echo e(route('book-details', 'blue-elephant')); ?>" class="book-cover cover-1">
        <span class="cover-badge">B</span>
        <span class="cover-title">الفيل الأزرق</span>
        <span class="cover-sub">نسنا</span>
      </a>
      <h3><a href="<?php echo e(route('book-details', 'blue-elephant')); ?>">الفيل الأزرق</a></h3>
      <p class="author">2012</p>
      <p class="rating"><i class="fa-solid fa-star"></i> 4.5</p>
      <a href="<?php echo e(route('book-details', 'blue-elephant')); ?>" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
    </article>

    <?php
      $moreBooks = [
        ['title' => 'الفيل الأزرق 2', 'cover' => 'wcover-2', 'year' => 2015, 'rating' => 4.3, 'slug' => null],
        ['title' => 'فيرتيجو', 'cover' => 'wcover-3', 'year' => 2010, 'rating' => 4.4, 'slug' => 'vertigo'],
        ['title' => 'تراب الماس', 'cover' => 'wcover-4', 'year' => 2011, 'rating' => 4.6, 'slug' => 'turab-al-mas'],
        ['title' => '1919', 'cover' => 'wcover-5', 'year' => 2014, 'rating' => 4.7, 'slug' => null],
        ['title' => 'كيره والجن', 'cover' => 'wcover-6', 'year' => 2018, 'rating' => 4.2, 'slug' => null],
        ['title' => 'أرض الإله', 'cover' => 'wcover-7', 'year' => 2020, 'rating' => 4.5, 'slug' => null],
        ['title' => 'ماذا لو', 'cover' => 'wcover-8', 'year' => 2022, 'rating' => 4.4, 'slug' => null],
      ];
    ?>

    <?php $__currentLoopData = $moreBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <article class="book-card" data-year="<?php echo e($book['year']); ?>">
        <a href="#" class="book-cover <?php echo e($book['cover']); ?>">
          <span class="cover-badge">B</span>
          <span class="cover-title"><?php echo e($book['title']); ?></span>
        </a>
        <h3><a href="#"><?php echo e($book['title']); ?></a></h3>
        <p class="author"><?php echo e($book['year']); ?></p>
        <p class="rating"><i class="fa-solid fa-star"></i> <?php echo e($book['rating']); ?></p>
        <a href="<?php echo e(route('book-details', $book['slug'] ?? null)); ?>" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
</section>

<!-- ===================== SIMILAR AUTHORS ===================== -->
<section class="section">
  <div class="section-head">
    <h2 class="section-title">مؤلفون مشابهون</h2>
    <a href="<?php echo e(route('writers')); ?>" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="authors-row wide">
    <a href="<?php echo e(route('writer-details', 'ahmed-khaled-tawfik')); ?>" class="author-card">
      <img src="https://i.pravatar.cc/120?img=68" alt="أحمد خالد توفيق">
      <p>أحمد خالد توفيق</p>
    </a>
    <a href="<?php echo e(route('writer-details', 'amr-abdelhamid')); ?>" class="author-card">
      <img src="https://i.pravatar.cc/120?img=13" alt="عمرو عبد الحميد">
      <p>عمرو عبد الحميد</p>
    </a>
    <a href="<?php echo e(route('writer-details', 'youssef-ziedan')); ?>" class="author-card">
      <img src="https://i.pravatar.cc/120?img=33" alt="يوسف زيدان">
      <p>يوسف زيدان</p>
    </a>
    <a href="<?php echo e(route('writer-details', 'naguib-mahfouz')); ?>" class="author-card">
      <img src="https://i.pravatar.cc/120?img=59" alt="نجيب محفوظ">
      <p>نجيب محفوظ</p>
    </a>
  </div>
</section>

</main>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('assets/js/writer-details.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/writer-details.blade.php ENDPATH**/ ?>