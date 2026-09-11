<?php $__env->startSection('title', $currentBook->title.' - نوته بوك'); ?>
<?php $__env->startSection('meta_description', $currentBook->description_short ?: \Illuminate\Support\Str::limit(strip_tags((string) $currentBook->description), 160) ?: 'اقرأ وحمّل كتاب '.$currentBook->title.' على نوته بوك.'); ?>
<?php $__env->startSection('og_type', 'book'); ?>
<?php $__env->startSection('og_image', $currentBook->cover_image_url ?? asset('assets/images/hero-section.jpg')); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/book-details.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('schema'); ?>
<script type="application/ld+json">
<?php echo json_encode(array_filter([
    '<?php $__contextArgs = [];
if (context()->has($__contextArgs[0])) :
if (isset($value)) { $__contextPrevious[] = $value; }
$value = context()->get($__contextArgs[0]); ?>' => 'https://schema.org',
    '@type' => 'Book',
    'name' => $currentBook->title,
    'description' => $currentBook->description_short ?: strip_tags((string) $currentBook->description),
    'inLanguage' => $currentBook->language,
    'numberOfPages' => $currentBook->pages_count,
    'image' => $currentBook->cover_image_url,
    'author' => $currentBook->writer ? [
        '@type' => 'Person',
        'name' => $currentBook->writer->name,
    ] : null,
    'aggregateRating' => $currentBook->rating_count > 0 ? [
        '@type' => 'AggregateRating',
        'ratingValue' => (string) $currentBook->rating_average,
        'reviewCount' => $currentBook->rating_count,
    ] : null,
]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>

</script>
<script type="application/ld+json">
<?php echo json_encode([
    '<?php $__contextArgs = [];
if (context()->has($__contextArgs[0])) :
if (isset($value)) { $__contextPrevious[] = $value; }
$value = context()->get($__contextArgs[0]); ?>' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => array_values(array_filter([
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'الرئيسية', 'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'التصنيفات', 'item' => route('categories')],
        $currentBook->category ? ['@type' => 'ListItem', 'position' => 3, 'name' => $currentBook->category->name, 'item' => route('category-details', $currentBook->category->slug)] : null,
        ['@type' => 'ListItem', 'position' => $currentBook->category ? 4 : 3, 'name' => $currentBook->title, 'item' => route('book-details', $currentBook->slug)],
    ])),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>

</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<main>

<?php
  $isFollowingBookAuthor = $currentBook->writer && (auth()->user()?->followedWriters()->where('slug', $currentBook->writer->slug)->exists() ?? false);
?>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="<?php echo e(route('home')); ?>">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <a href="<?php echo e(route('categories')); ?>">التصنيفات</a>
    <?php if($currentBook->category): ?>
      <i class="fa-solid fa-chevron-left"></i>
      <a href="<?php echo e(route('category-details', $currentBook->category->slug)); ?>"><?php echo e($currentBook->category->name); ?></a>
    <?php endif; ?>
    <i class="fa-solid fa-chevron-left"></i>
    <span><?php echo e($currentBook->title); ?></span>
  </nav>
</div>

<!-- ===================== BOOK HERO ===================== -->
<section class="section book-hero">
  <div class="book-hero-cover">
    <?php if($currentBook->cover_image): ?>
      <img class="hero-cover-img cover-photo" src="<?php echo e($currentBook->cover_image_url); ?>" alt="<?php echo e($currentBook->title); ?>">
    <?php else: ?>
      <div class="hero-cover-img cover-<?php echo e(($currentBook->id % 5) + 1); ?>">
        <span class="cover-badge">B</span>
        <span class="cover-title"><?php echo e($currentBook->title); ?></span>
      </div>
    <?php endif; ?>
    <span class="brand-ribbon">nootabooks.com</span>
    <?php if($currentBook->is_coming_soon): ?>
      <span class="coming-soon-badge">قريبًا</span>
    <?php endif; ?>
    <button class="wishlist-btn" aria-label="add to wishlist"><i class="fa-regular fa-heart"></i></button>
  </div>

  <div class="book-hero-info">
    <?php if($currentBook->category): ?>
      <span class="genre-chip"><?php echo e($currentBook->category->name); ?></span>
    <?php endif; ?>
    <h1 class="book-title"><?php echo e($currentBook->title); ?></h1>
    <?php if($currentBook->writer): ?>
      <p class="book-author">تأليف <a href="<?php echo e(route('writer-details', $currentBook->writer->slug)); ?>"><?php echo e($currentBook->writer->name); ?></a></p>
    <?php endif; ?>

    <div class="rating-row">
      <span class="stars">
        <?php for($i = 1; $i <= 5; $i++): ?>
          <?php if($currentBook->rating_average >= $i): ?>
            <i class="fa-solid fa-star"></i>
          <?php elseif($currentBook->rating_average >= $i - 0.5): ?>
            <i class="fa-solid fa-star-half-stroke"></i>
          <?php else: ?>
            <i class="fa-regular fa-star"></i>
          <?php endif; ?>
        <?php endfor; ?>
      </span>
      <strong><?php echo e(number_format($currentBook->rating_average, 1)); ?></strong>
      <span class="review-count">(<?php echo e(number_format($currentBook->rating_count)); ?> تقييم)</span>
    </div>

    <?php if($currentBook->description_short): ?>
      <p class="book-desc-short"><?php echo e($currentBook->description_short); ?></p>
    <?php endif; ?>

    <div class="book-meta-grid">
      <div class="meta-item"><i class="fa-solid fa-file-lines"></i><span>عدد الصفحات</span><strong><?php echo e($currentBook->pages_count ? number_format($currentBook->pages_count).' صفحة' : '—'); ?></strong></div>
      <div class="meta-item"><i class="fa-solid fa-language"></i><span>اللغة</span><strong><?php echo e($currentBook->language); ?></strong></div>
      <div class="meta-item"><i class="fa-solid fa-calendar-days"></i><span>تاريخ النشر</span><strong><?php echo e($currentBook->published_year ?? '—'); ?></strong></div>
      <div class="meta-item"><i class="fa-solid fa-file-arrow-down"></i><span>حجم الملف</span><strong><?php echo e($currentBook->file_size_mb ? $currentBook->file_size_mb.' MB' : '—'); ?></strong></div>
      <div class="meta-item"><i class="fa-solid fa-book-open-reader"></i><span>الصيغة</span><strong><?php echo e($currentBook->formats ? implode(', ', $currentBook->formats) : '—'); ?></strong></div>
      <div class="meta-item"><i class="fa-solid fa-cloud-arrow-down"></i><span>مرات التحميل</span><strong><?php echo e(number_format($currentBook->downloads_count)); ?></strong></div>
    </div>

    <div class="book-actions">
      <?php if($currentBook->is_coming_soon): ?>
        <button class="btn btn-teal" disabled title="هذا الكتاب سيتوفر قريبًا"><i class="fa-solid fa-clock"></i> قريبًا</button>
        <button class="btn btn-gold" disabled title="هذا الكتاب سيتوفر قريبًا"><i class="fa-solid fa-download"></i> تحميل الكتاب</button>
      <?php else: ?>
        <a href="<?php echo e(route('read', $currentBook->slug)); ?>" class="btn btn-teal"><i class="fa-solid fa-headphones"></i> قراءة الآن</a>
        <?php if($currentBook->downloadUrl()): ?>
          <a href="<?php echo e($currentBook->downloadUrl()); ?>" class="btn btn-gold"><i class="fa-solid fa-download"></i> تحميل الكتاب</a>
        <?php else: ?>
          <button class="btn btn-gold" disabled title="الملف غير متوفر حاليًا"><i class="fa-solid fa-download"></i> تحميل الكتاب</button>
        <?php endif; ?>
      <?php endif; ?>
      <button class="btn btn-navy" aria-label="مشاركة"><i class="fa-solid fa-share-nodes"></i> مشاركة</button>
      <a href="<?php echo e(route('community', ['book' => $currentBook->slug])); ?>" class="btn btn-outline"><i class="fa-solid fa-comments"></i> دردش حول الكتاب</a>
    </div>
  </div>
</section>

<!-- ===================== TABS ===================== -->
<section class="section tabs-section">
  <div class="tabs-nav">
    <button class="tab-btn active" data-tab="about">نبذة عن الكتاب</button>
    <button class="tab-btn" data-tab="reviews">التقييمات والمراجعات</button>
    <button class="tab-btn" data-tab="author">عن المؤلف</button>
  </div>

  <div class="tab-panel active" id="tab-about">
    <h2>نبذة عن الكتاب</h2>
    <?php if($currentBook->description): ?>
      <?php $__currentLoopData = explode("\n", $currentBook->description); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paragraph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(trim($paragraph) === '') continue; ?>
        <p><?php echo e($paragraph); ?></p>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php elseif($currentBook->description_short): ?>
      <p><?php echo e($currentBook->description_short); ?></p>
    <?php endif; ?>

    <?php if($currentBook->tags): ?>
      <div class="genre-tags">
        <?php $__currentLoopData = $currentBook->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <span>#<?php echo e($tag); ?></span>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="tab-panel" id="tab-reviews">
    <?php
      $bookRatingAverage = $currentBook->rating_average ?? 0;
      $bookRatingCount = $currentBook->rating_count ?? 0;
    ?>
    <div class="reviews-overview">
      <div class="rating-big">
        <span class="big-number"><?php echo e(number_format($bookRatingAverage, 1)); ?></span>
        <span class="stars">
          <?php for($i = 1; $i <= 5; $i++): ?>
            <?php if($bookRatingAverage >= $i): ?>
              <i class="fa-solid fa-star"></i>
            <?php elseif($bookRatingAverage >= $i - 0.5): ?>
              <i class="fa-solid fa-star-half-stroke"></i>
            <?php else: ?>
              <i class="fa-regular fa-star"></i>
            <?php endif; ?>
          <?php endfor; ?>
        </span>
        <span class="review-count">من <?php echo e(number_format($bookRatingCount)); ?> تقييم</span>
      </div>
      <div class="rating-bars">
        <?php $__currentLoopData = ($ratingBreakdown ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stars => $pct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="bar-row"><span><?php echo e($stars); ?></span><div class="bar"><div class="fill" style="width:<?php echo e($pct); ?>%"></div></div><span class="pct"><?php echo e($pct); ?>%</span></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      <?php if(auth()->guard()->check()): ?>
        <button type="button" class="btn btn-outline add-review-btn" id="toggleReviewForm"><i class="fa-solid fa-pen"></i> أضف تقييمك</button>
      <?php else: ?>
        <a href="<?php echo e(route('login')); ?>" class="btn btn-outline add-review-btn"><i class="fa-solid fa-pen"></i> سجل الدخول لإضافة تقييم</a>
      <?php endif; ?>
    </div>

    <?php if(auth()->guard()->check()): ?>
      <form method="POST" action="<?php echo e(route('reviews.store', $currentBook->slug)); ?>" class="add-review-form" id="addReviewForm" hidden>
        <?php echo csrf_field(); ?>
        <div class="form-field">
          <label for="reviewRating">تقييمك</label>
          <select name="rating" id="reviewRating" required>
            <option value="">اختر تقييمًا</option>
            <option value="5">5 - ممتاز</option>
            <option value="4">4 - جيد جدًا</option>
            <option value="3">3 - جيد</option>
            <option value="2">2 - مقبول</option>
            <option value="1">1 - ضعيف</option>
          </select>
        </div>
        <textarea name="comment" rows="3" placeholder="اكتب رأيك في الكتاب (اختياري) ..."></textarea>
        <?php echo $__env->make('partials.recaptcha', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <button type="submit" class="btn btn-teal">إرسال التقييم</button>
      </form>
    <?php endif; ?>

    <div class="review-list">
      <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <article class="review-card">
          <img src="<?php echo e($review->user->avatar ?? 'https://i.pravatar.cc/72?img=' . (($review->user_id % 70) + 1)); ?>" alt="<?php echo e($review->user->name); ?>">
          <div class="review-body">
            <div class="review-head">
              <strong><?php echo e($review->user->name); ?></strong>
              <span class="stars sm">
                <?php for($i = 1; $i <= 5; $i++): ?>
                  <i class="fa-<?php echo e($i <= $review->rating ? 'solid' : 'regular'); ?> fa-star"></i>
                <?php endfor; ?>
              </span>
              <span class="review-date"><?php echo e($review->created_at->diffForHumans()); ?></span>
            </div>
            <?php if($review->comment): ?>
              <p><?php echo e($review->comment); ?></p>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="no-results">لا توجد تقييمات بعد. كن أول من يقيّم هذا الكتاب.</p>
      <?php endif; ?>
    </div>

    <?php echo e($reviews->links()); ?>

  </div>

  <div class="tab-panel" id="tab-author">
    <?php if($currentBook->writer): ?>
      <div class="author-mini-card">
        <img src="<?php echo e($currentBook->writer->photo ?? 'https://i.pravatar.cc/120?img=' . (($currentBook->writer->id % 70) + 1)); ?>" alt="<?php echo e($currentBook->writer->name); ?>">
        <div class="author-mini-info">
          <h3><a href="<?php echo e(route('writer-details', $currentBook->writer->slug)); ?>"><?php echo e($currentBook->writer->name); ?></a></h3>
          <?php if($currentBook->writer->bio): ?>
            <p><?php echo e($currentBook->writer->bio); ?></p>
          <?php endif; ?>
          <div class="author-mini-stats">
            <span><i class="fa-solid fa-book"></i> <?php echo e(number_format($currentBook->writer->books()->count())); ?> كتاب</span>
            <span><i class="fa-solid fa-users"></i> <?php echo e(number_format($currentBook->writer->followers_count)); ?> متابع</span>
          </div>
        </div>
        <?php if(auth()->guard()->check()): ?>
          <form method="POST" action="<?php echo e(route('writers.follow', $currentBook->writer->slug)); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-outline follow-btn <?php if($isFollowingBookAuthor): ?> following <?php endif; ?>">
              <?php echo e($isFollowingBookAuthor ? 'تتم المتابعة' : 'متابعة'); ?>

            </button>
          </form>
        <?php else: ?>
          <a href="<?php echo e(route('login')); ?>" class="btn btn-outline follow-btn">متابعة</a>
        <?php endif; ?>
      </div>
    <?php else: ?>
      <p class="no-results">لا توجد معلومات عن المؤلف لهذا الكتاب.</p>
    <?php endif; ?>
  </div>
</section>

<!-- ===================== SIMILAR BOOKS ===================== -->
<section class="section trending-section">
  <div class="section-head">
    <div class="section-title-wrap">
      <h2 class="section-title">كتب مشابهة قد تعجبك</h2>
      <p class="section-sub">اختيارات مبنية على قراءة هذا الكتاب</p>
    </div>
    <a href="<?php echo e(route('discover')); ?>" class="view-all">عرض الكل <i class="fa-solid fa-arrow-left"></i></a>
  </div>

  <div class="carousel-wrap">
    <button class="carousel-btn prev" aria-label="previous"><i class="fa-solid fa-chevron-right"></i></button>

    <div class="book-carousel">
      <?php $__empty_1 = true; $__currentLoopData = $similarBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $similarBook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <article class="book-card">
          <?php if($similarBook->cover_image): ?>
            <div class="cover-wrap">
              <img class="book-cover cover-photo" src="<?php echo e($similarBook->cover_image_sm_url); ?>" alt="<?php echo e($similarBook->title); ?>">
              <span class="brand-ribbon">nootabooks.com</span>
              <?php if($similarBook->is_coming_soon): ?>
                <span class="coming-soon-badge">قريبًا</span>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <div class="book-cover cover-<?php echo e(($similarBook->id % 5) + 1); ?>">
              <span class="cover-badge">B</span>
              <span class="cover-title"><?php echo e($similarBook->title); ?></span>
              <span class="brand-ribbon">nootabooks.com</span>
              <?php if($similarBook->is_coming_soon): ?>
                <span class="coming-soon-badge">قريبًا</span>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <h3><?php echo e($similarBook->title); ?></h3>
          <p class="author"><?php echo e($similarBook->writer?->name); ?></p>
          <p class="rating"><i class="fa-solid fa-star"></i> <?php echo e(number_format($similarBook->rating_average, 1)); ?></p>
          <a href="<?php echo e(route('book-details', $similarBook->slug)); ?>" class="btn btn-outline w-full"><i class="fa-solid fa-eye"></i> شاهد</a>
        </article>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="no-results">لا توجد كتب مشابهة في نفس التصنيف حاليًا.</p>
      <?php endif; ?>
    </div>

    <button class="carousel-btn next" aria-label="next"><i class="fa-solid fa-chevron-left"></i></button>
  </div>
</section>

</main>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset_min('assets/js/book-details.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/book-details.blade.php ENDPATH**/ ?>