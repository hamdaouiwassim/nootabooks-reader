<?php $__env->startSection('title', 'نوته بوك - عالم من الكتب بين يديك'); ?>
<?php $__env->startSection('meta_description', 'اكتشف أفضل الروايات والكتب العربية على نوته بوك، اقرأ وحمّل مجانًا، وتابع مؤلفيك المفضلين واطّلع على مراجعات القراء.'); ?>

<?php $__env->startSection('content'); ?>

<!-- ===================== HERO ===================== -->
<section class="hero">
  <div class="hero-content">
    <?php if($heroQuotes->isNotEmpty()): ?>
      <div class="hero-quote-stack <?php if($heroQuotes->count() < 2): ?> static <?php endif; ?>">
        <?php $__currentLoopData = $heroQuotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <blockquote class="hero-quote-card" style="animation-delay: <?php echo e(($loop->index / $heroQuotes->count()) * 15); ?>s">
            <i class="fa-solid fa-quote-right"></i>
            <p>"<?php echo e($quote->text); ?>"</p>
            <?php if($quote->author): ?>
              <cite>— <?php echo e($quote->author); ?></cite>
            <?php endif; ?>
          </blockquote>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>
    <h1 class="hero-title">إقرأ . اكتشف . حمّل</h1>
    <p class="hero-subtitle">عالم من الكتب بين يديك</p>
    <form class="hero-search" method="GET" action="<?php echo e(route('discover')); ?>">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="ابحث عن كتاب، مؤلف، او موضوع ...">
      <button type="submit" class="btn btn-gold">ابحث</button>
    </form>
  </div>
</section>

<!-- ===================== STATS BAR ===================== -->
<section class="stats-bar">
  <div class="stat-item">
    <div class="stat-text"><strong>تحميل مجاني</strong><span>بسهولة وأمان</span></div>
    <i class="fa-solid fa-cloud-arrow-down stat-icon"></i>
  </div>
  <div class="stat-item">
    <div class="stat-text"><strong>قراءة أونلاين</strong><span>في اي وقت</span></div>
    <i class="fa-solid fa-book stat-icon"></i>
  </div>
  <div class="stat-item">
    <div class="stat-text"><strong><?php echo e(number_format($categoriesCount)); ?></strong><span>تصنيف متنوع</span></div>
    <i class="fa-solid fa-layer-group stat-icon"></i>
  </div>
  <div class="stat-item">
    <div class="stat-text"><strong><?php echo e(number_format($writersCount)); ?></strong><span>مؤلف</span></div>
    <i class="fa-solid fa-book-open stat-icon"></i>
  </div>
  <div class="stat-item">
    <div class="stat-text"><strong><?php echo e(number_format($booksCount)); ?></strong><span>كتاب متوفر</span></div>
    <i class="fa-solid fa-gift stat-icon"></i>
  </div>
</section>

<main>

<!-- ===================== TRENDING BOOKS ===================== -->
<section class="section trending-section">
  <div class="section-head">
    <div class="section-title-wrap">
      <h2 class="section-title">الأكثر قراءة هذا الأسبوع <i class="fa-solid fa-fire fire-icon"></i></h2>
      <p class="section-sub">اكتشف اكثر الكتب قراءة من قبل مجتمعنا</p>
    </div>
    <a href="<?php echo e(route('discover')); ?>" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="carousel-wrap">
    <button class="carousel-btn prev" aria-label="previous"><i class="fa-solid fa-chevron-right"></i></button>

    <div class="book-carousel">
      <?php $__currentLoopData = $trendingBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <article class="book-card">
          <?php if($book->cover_image): ?>
            <img class="book-cover cover-photo" src="<?php echo e($book->cover_image_sm_url); ?>" alt="<?php echo e($book->title); ?>">
          <?php else: ?>
            <div class="book-cover cover-<?php echo e(($book->id % 5) + 1); ?>">
              <span class="cover-badge">B</span>
              <span class="cover-title"><?php echo e($book->title); ?></span>
            </div>
          <?php endif; ?>
          <h3><a href="<?php echo e(route('book-details', $book->slug)); ?>"><?php echo e($book->title); ?></a></h3>
          <p class="author"><?php echo e($book->writer?->name); ?></p>
          <p class="rating"><i class="fa-solid fa-star"></i> <?php echo e(number_format($book->rating_average, 1)); ?></p>
          <a href="<?php echo e(route('book-details', $book->slug)); ?>" class="btn btn-outline w-full"><i class="fa-solid fa-eye"></i> شاهد</a>
        </article>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <button class="carousel-btn next" aria-label="next"><i class="fa-solid fa-chevron-left"></i></button>
  </div>
</section>

<?php if($booksCount > 10): ?>
<!-- ===================== RECENT BOOKS ===================== -->
<section class="section trending-section">
  <div class="section-head">
    <div class="section-title-wrap">
      <h2 class="section-title">أحدث الكتب <i class="fa-solid fa-clock-rotate-left fire-icon"></i></h2>
      <p class="section-sub">آخر الكتب المضافة إلى المنصة</p>
    </div>
    <a href="<?php echo e(route('discover', ['sort' => 'newest'])); ?>" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="carousel-wrap">
    <button class="carousel-btn prev" aria-label="previous"><i class="fa-solid fa-chevron-right"></i></button>

    <div class="book-carousel">
      <?php $__currentLoopData = $recentBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <article class="book-card">
          <?php if($book->cover_image): ?>
            <img class="book-cover cover-photo" src="<?php echo e($book->cover_image_sm_url); ?>" alt="<?php echo e($book->title); ?>">
          <?php else: ?>
            <div class="book-cover cover-<?php echo e(($book->id % 5) + 1); ?>">
              <span class="cover-badge">B</span>
              <span class="cover-title"><?php echo e($book->title); ?></span>
            </div>
          <?php endif; ?>
          <h3><a href="<?php echo e(route('book-details', $book->slug)); ?>"><?php echo e($book->title); ?></a></h3>
          <p class="author"><?php echo e($book->writer?->name); ?></p>
          <p class="rating"><i class="fa-solid fa-star"></i> <?php echo e(number_format($book->rating_average, 1)); ?></p>
          <a href="<?php echo e(route('book-details', $book->slug)); ?>" class="btn btn-outline w-full"><i class="fa-solid fa-eye"></i> شاهد</a>
        </article>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <button class="carousel-btn next" aria-label="next"><i class="fa-solid fa-chevron-left"></i></button>
  </div>
</section>
<?php endif; ?>

<!-- ===================== DISCOVER BANNER ===================== -->
<section class="section">
  <div class="discover-banner">
    <div class="discover-tags">
      <div class="tag-item">
        <span class="tag-icon tag-purple"><i class="fa-solid fa-bookmark"></i></span>
        <span>روايات</span>
      </div>
      <div class="tag-item">
        <span class="tag-icon tag-green"><i class="fa-solid fa-mosque"></i></span>
        <span>تنمية ذهنية</span>
      </div>
      <div class="tag-item">
        <span class="tag-icon tag-brown"><i class="fa-solid fa-bell"></i></span>
        <span>تنمية ذاتية</span>
      </div>
      <div class="tag-item">
        <span class="tag-icon tag-blue"><i class="fa-solid fa-table-cells"></i></span>
        <span>غير ذلك</span>
      </div>
    </div>
    <div class="discover-left">
      <div class="discover-text">
        <h3>اكتشف عوالم جديدة</h3>
        <p>ألاف الكتب في انتظارك ...</p>
        <button class="btn btn-gold small"><i class="fa-solid fa-arrow-left"></i> <span class="btn-label">استكشف</span></button>
      </div>
    </div>
  </div>
</section>

<!-- ===================== AUTHORS + SIMILAR BOOKS ===================== -->
<section class="section two-col">
  <div class="col">
    <div class="section-head">
      <h2 class="section-title">كتب مشابهة لك</h2>
      <a href="<?php echo e(route('discover')); ?>" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="similar-row">
      <?php $__currentLoopData = $similarBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('book-details', $book->slug)); ?>" class="mini-book">
          <div class="mini-cover mc-<?php echo e(($book->id % 4) + 1); ?>"><span class="cover-badge sm">B</span></div>
          <h4><?php echo e($book->title); ?></h4>
          <p><?php echo e($book->writer?->name); ?></p>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>

  <div class="col">
    <div class="section-head">
      <h2 class="section-title">مؤلفون مميزون</h2>
      <a href="<?php echo e(route('writers')); ?>" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="authors-row">
      <?php $__empty_1 = true; $__currentLoopData = $popularWriters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $writer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <a href="<?php echo e(route('writer-details', $writer->slug)); ?>" class="author-card">
          <img src="<?php echo e($writer->photo_sm_url ?? 'https://i.pravatar.cc/120?img=' . (($writer->id % 70) + 1)); ?>" alt="<?php echo e($writer->name); ?>">
          <p><?php echo e($writer->name); ?></p>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="no-results">لا يوجد مؤلفون بعد</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ===================== CATEGORIES ===================== -->
<section class="section">
  <div class="section-head">
    <h2 class="section-title">تصفح حسب التصنيف</h2>
    <a href="<?php echo e(route('categories')); ?>" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="categories-grid">
    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route('category-details', $category->slug)); ?>" class="category-card"><i class="fa-solid <?php echo e($category->icon); ?>"></i><span><?php echo e($category->name); ?></span></a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
</section>

<!-- ===================== NEWSLETTER ===================== -->
<section class="section">
  <div class="newsletter-banner">
    <div class="newsletter-text">
      <h3>كن دائمًا على اطلاع</h3>
      <p>اشترك في نشرتنا البريدية للحصول على أحدث الكتب والمقالات</p>
    </div>
    <form class="newsletter-form" id="newsletterForm">
      <input type="email" placeholder="أدخل بريدك الاكتروني" required>
      <button type="submit" class="btn btn-teal"><i class="fa-solid fa-paper-plane"></i> <span class="btn-label">اشترك الآن</span></button>
    </form>
  </div>
</section>

</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/home.blade.php ENDPATH**/ ?>