<?php $__env->startSection('title', 'اكتشف الكتب والروايات واقرأها وحمّلها أونلاين | نوته بوك'); ?>
<?php $__env->startSection('meta_description', 'اكتشف الكتب والروايات العربية والمترجمة على نوته بوك، وابحث عن الكتب حسب المؤلف والتصنيف واستكشف ما يناسب اهتماماتك واقرأه أونلاين.'); ?>

<?php $__env->startSection('content'); ?>

<main>

<!-- ===================== HERO ===================== -->
<section class="hero" aria-labelledby="hero-heading">
  <img class="hero-background" src="<?php echo e(asset('assets/images/hero-section.jpg')); ?>"
    srcset="<?php echo e(asset('assets/images/hero-section-sm.webp')); ?> 746w, <?php echo e(asset('assets/images/hero-section.jpg')); ?> 1342w"
    sizes="100vw" width="1342" height="900" fetchpriority="high" decoding="async" alt="">
  <div class="hero-content">
    <?php if($heroQuotes->isNotEmpty()): ?>
      <div class="hero-quote-stack <?php if($heroQuotes->count() < 2): ?> static <?php endif; ?>">
        <?php $__currentLoopData = $heroQuotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <blockquote class="hero-quote-card" style="animation-delay: <?php echo e(($loop->index / $heroQuotes->count()) * 15); ?>s">
            <i class="fa-solid fa-quote-right" aria-hidden="true"></i>
            <p>"<?php echo e($quote->text); ?>"</p>
            <?php if($quote->author): ?>
              <cite>— <?php echo e($quote->author); ?></cite>
            <?php endif; ?>
          </blockquote>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>
    <h1 class="hero-title" id="hero-heading">اكتشف الكتب والروايات واقرأها أونلاين</h1>
    <p class="hero-subtitle">عالم من الكتب بين يديك</p>
    <form class="hero-search" method="GET" action="<?php echo e(route('discover')); ?>">
      <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
      <label for="homepage-search" class="sr-only">ابحث عن كتاب أو مؤلف أو موضوع</label>
      <input id="homepage-search" type="search" name="q" value="<?php echo e(request('q')); ?>" placeholder="ابحث عن كتاب، مؤلف، أو موضوع...">
      <button type="submit" class="btn btn-gold">ابحث</button>
    </form>
  </div>
</section>

<!-- ===================== STATS BAR ===================== -->
<section class="stats-bar">
  <div class="stat-item">
    <div class="stat-text stat-text-lg"><strong class="stat-count" data-count="<?php echo e($booksCount); ?>">0</strong><span>كتاب متوفر</span></div>
  </div>
</section>

<!-- ===================== TRENDING BOOKS ===================== -->
<section class="section trending-section home-books-section" aria-labelledby="trending-heading">
  <div class="section-head">
    <div class="section-title-wrap">
      <h2 class="section-title" id="trending-heading">الأكثر تحميلاً</h2>
      <p class="section-sub">اكتشف أكثر الكتب تحميلًا من قبل مجتمعنا</p>
    </div>
    <a href="<?php echo e(route('discover')); ?>" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="carousel-wrap">
    <button class="carousel-btn prev" aria-label="previous"><i class="fa-solid fa-chevron-right"></i></button>

    <div class="book-carousel">
      <?php $__currentLoopData = $trendingBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('book-details', $book->slug)); ?>" class="book-card">
          <?php if($book->cover_image): ?>
            <div class="cover-wrap">
              <img class="book-cover cover-photo" src="<?php echo e($book->cover_image_sm_url); ?>"
                width="300" height="450" loading="lazy" decoding="async" alt="<?php echo e($book->cover_alt); ?>">
              <span class="brand-ribbon">nootabooks.com</span>
              <?php if($book->is_coming_soon): ?>
                <span class="coming-soon-badge">قريبًا</span>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <div class="book-cover cover-<?php echo e(($book->id % 5) + 1); ?>">
              <span class="cover-badge">B</span>
              <span class="cover-title"><?php echo e($book->title); ?></span>
              <span class="brand-ribbon">nootabooks.com</span>
              <?php if($book->is_coming_soon): ?>
                <span class="coming-soon-badge">قريبًا</span>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <h3><?php echo e($book->title); ?></h3>
          <p class="author"><?php echo e($book->writer?->name); ?></p>
          <?php if($book->rating_count > 0): ?>
            <p class="rating"><i class="fa-solid fa-star"></i> <?php echo e(number_format($book->rating_average, 1)); ?></p>
          <?php else: ?>
            <p class="rating no-rating">لا توجد تقييمات بعد</p>
          <?php endif; ?>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <button class="carousel-btn next" aria-label="next"><i class="fa-solid fa-chevron-left"></i></button>
  </div>
</section>

<?php if($booksCount > 10): ?>
<!-- ===================== RECENT BOOKS ===================== -->
<section class="section trending-section home-books-section" aria-labelledby="recent-heading">
  <div class="section-head">
    <div class="section-title-wrap">
      <h2 class="section-title" id="recent-heading">أحدث الكتب</h2>
      <p class="section-sub">آخر الكتب المضافة إلى المنصة</p>
    </div>
    <a href="<?php echo e(route('discover', ['sort' => 'newest'])); ?>" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="carousel-wrap">
    <button class="carousel-btn prev" aria-label="previous"><i class="fa-solid fa-chevron-right"></i></button>

    <div class="book-carousel">
      <?php $__currentLoopData = $recentBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('book-details', $book->slug)); ?>" class="book-card">
          <?php if($book->cover_image): ?>
            <div class="cover-wrap">
              <img class="book-cover cover-photo" src="<?php echo e($book->cover_image_sm_url); ?>"
                width="300" height="450" loading="lazy" decoding="async" alt="<?php echo e($book->cover_alt); ?>">
              <span class="brand-ribbon">nootabooks.com</span>
              <?php if($book->is_coming_soon): ?>
                <span class="coming-soon-badge">قريبًا</span>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <div class="book-cover cover-<?php echo e(($book->id % 5) + 1); ?>">
              <span class="cover-badge">B</span>
              <span class="cover-title"><?php echo e($book->title); ?></span>
              <span class="brand-ribbon">nootabooks.com</span>
              <?php if($book->is_coming_soon): ?>
                <span class="coming-soon-badge">قريبًا</span>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <h3><?php echo e($book->title); ?></h3>
          <p class="author"><?php echo e($book->writer?->name); ?></p>
          <?php if($book->rating_count > 0): ?>
            <p class="rating"><i class="fa-solid fa-star"></i> <?php echo e(number_format($book->rating_average, 1)); ?></p>
          <?php else: ?>
            <p class="rating no-rating">لا توجد تقييمات بعد</p>
          <?php endif; ?>
        </a>
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
      <?php $tagColors = ['tag-purple', 'tag-green', 'tag-brown', 'tag-blue']; ?>
      <?php $__currentLoopData = $categories->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('category-details', $category->slug)); ?>" class="tag-item">
          <span class="tag-icon <?php echo e($tagColors[$loop->index % count($tagColors)]); ?>"><?php echo e(mb_substr($category->name, 0, 1)); ?></span>
          <span><?php echo e($category->name); ?></span>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="discover-left">
      <div class="discover-text">
        <h3>اكتشف عوالم جديدة</h3>
        <p><?php echo e(number_format($booksCount)); ?> كتاب في انتظارك ...</p>
        <a href="<?php echo e(route('discover')); ?>" class="btn btn-gold small"><i class="fa-solid fa-arrow-left"></i> <span class="btn-label">استكشف</span></a>
      </div>
    </div>
  </div>
</section>

<!-- ===================== AUTHORS + SIMILAR BOOKS ===================== -->
<section class="section two-col">
  <section class="col" aria-labelledby="suggestions-heading">
    <div class="section-head">
      <h2 class="section-title" id="suggestions-heading">اقتراحات للقراءة</h2>
      <a href="<?php echo e(route('discover')); ?>" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="similar-row">
      <?php $__currentLoopData = $similarBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('book-details', $book->slug)); ?>" class="mini-book">
          <?php if($book->cover_image): ?>
            <img class="mini-cover cover-photo" src="<?php echo e($book->cover_image_sm_url); ?>"
              width="300" height="450" loading="lazy" decoding="async" alt="<?php echo e($book->cover_alt); ?>">
          <?php else: ?>
            <div class="mini-cover mc-<?php echo e(($book->id % 4) + 1); ?>"><span class="cover-badge sm">B</span></div>
          <?php endif; ?>
          <h3><?php echo e($book->title); ?></h3>
          <p><?php echo e($book->writer?->name); ?></p>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </section>

  <section class="col" aria-labelledby="writers-heading">
    <div class="section-head">
      <h2 class="section-title" id="writers-heading">مؤلفون مميزون</h2>
      <a href="<?php echo e(route('writers')); ?>" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
    </div>
    <div class="authors-row">
      <?php $__empty_1 = true; $__currentLoopData = $popularWriters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $writer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <a href="<?php echo e(route('writer-details', $writer->slug)); ?>" class="author-card">
          <?php if($writer->photo): ?>
            <img src="<?php echo e($writer->photo_xs_url); ?>" width="200" height="200" loading="lazy" decoding="async" alt="صورة المؤلف <?php echo e($writer->name); ?>">
          <?php else: ?>
            <span class="avatar-placeholder"><i class="fa-solid fa-feather"></i></span>
          <?php endif; ?>
          <p><?php echo e($writer->name); ?></p>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="no-results">لا يوجد مؤلفون بعد</p>
      <?php endif; ?>
    </div>
  </section>
</section>

<!-- ===================== CATEGORIES ===================== -->
<section class="section" aria-labelledby="categories-heading">
  <div class="section-head">
    <h2 class="section-title" id="categories-heading">تصفح حسب التصنيف</h2>
    <a href="<?php echo e(route('categories')); ?>" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="categories-grid">
    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <a href="<?php echo e(route('category-details', $category->slug)); ?>" class="category-card"><span><?php echo e($category->name); ?></span></a>
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
      <label for="newsletterEmail" class="sr-only">بريدك الإلكتروني</label>
      <input type="email" id="newsletterEmail" name="email" placeholder="أدخل بريدك الإلكتروني" required>
      <button type="submit" class="btn btn-teal"><i class="fa-solid fa-paper-plane"></i> <span class="btn-label">اشترك الآن</span></button>
    </form>
  </div>
</section>

</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/home.blade.php ENDPATH**/ ?>