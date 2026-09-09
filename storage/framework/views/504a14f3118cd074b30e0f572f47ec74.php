<?php $__env->startSection('title', 'الفيل الأزرق - نوته بوك'); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/book-details.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<main>

<?php
  $bookAuthorSlug = 'ahmed-mourad';
  $isFollowingBookAuthor = auth()->user()?->followedWriters()->where('slug', $bookAuthorSlug)->exists() ?? false;
?>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="<?php echo e(route('home')); ?>">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <a href="<?php echo e(route('categories')); ?>">التصنيفات</a>
    <i class="fa-solid fa-chevron-left"></i>
    <a href="#">روايات عربية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>الفيل الأزرق</span>
  </nav>
</div>

<!-- ===================== BOOK HERO ===================== -->
<section class="section book-hero">
  <div class="book-hero-cover">
    <div class="hero-cover-img cover-1">
      <span class="cover-badge">B</span>
      <span class="cover-title">الفيل الأزرق</span>
      <span class="cover-sub">نسنا</span>
    </div>
    <button class="wishlist-btn" aria-label="add to wishlist"><i class="fa-regular fa-heart"></i></button>
  </div>

  <div class="book-hero-info">
    <span class="genre-chip">روايات عربية</span>
    <h1 class="book-title">الفيل الأزرق</h1>
    <p class="book-author">تأليف <a href="<?php echo e(route('writer-details', 'ahmed-mourad')); ?>">أحمد مراد</a></p>

    <div class="rating-row">
      <span class="stars">
        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
      </span>
      <strong>4.5</strong>
      <span class="review-count">(2,340 تقييم)</span>
    </div>

    <p class="book-desc-short">
      يحيى الراوي، طبيب تخدير يعود إلى مستشفى العباسية للأمراض النفسية بعد غياب خمس سنوات، ليجد نفسه في مواجهة قضية غامضة تتشابك فيها حدود الواقع والجنون، في رواية تجمع بين الإثارة النفسية والغموض البوليسي.
    </p>

    <div class="book-meta-grid">
      <div class="meta-item"><i class="fa-solid fa-file-lines"></i><span>عدد الصفحات</span><strong>320 صفحة</strong></div>
      <div class="meta-item"><i class="fa-solid fa-language"></i><span>اللغة</span><strong>العربية</strong></div>
      <div class="meta-item"><i class="fa-solid fa-calendar-days"></i><span>تاريخ النشر</span><strong>2012</strong></div>
      <div class="meta-item"><i class="fa-solid fa-file-arrow-down"></i><span>حجم الملف</span><strong>4.2 MB</strong></div>
      <div class="meta-item"><i class="fa-solid fa-book-open-reader"></i><span>الصيغة</span><strong>PDF, EPUB</strong></div>
      <div class="meta-item"><i class="fa-solid fa-cloud-arrow-down"></i><span>مرات التحميل</span><strong>18,540</strong></div>
    </div>

    <div class="book-actions">
      <button class="btn btn-teal"><i class="fa-solid fa-headphones"></i> قراءة الآن</button>
      <button class="btn btn-gold"><i class="fa-solid fa-download"></i> تحميل الكتاب</button>
      <button class="icon-btn-outline" aria-label="share"><i class="fa-solid fa-share-nodes"></i></button>
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
    <p>
      تدور أحداث رواية "الفيل الأزرق" حول يحيى الراوي، طبيب تخدير يعود للعمل في مستشفى العباسية للأمراض العقلية بعد غياب خمس سنوات قضاها بعيدًا عن مهنته إثر أزمة نفسية حادة. في أول أيامه، يصطدم بوجود صديق قديم من أيام الجامعة بين نزلاء المستشفى، ليجد نفسه منجذبًا شيئًا فشيئًا إلى عالم غامض يمتزج فيه الواقع بالخيال، والعلم بالغيبيات.
    </p>
    <p>
      تتصاعد الأحداث في نسيج بوليسي مشوق يطرح تساؤلات عميقة حول الجنون والعقل، الخير والشر، والحدود الرفيعة الفاصلة بينهما. تعد الرواية من أكثر الأعمال الأدبية العربية مبيعًا، وتحولت إلى فيلم سينمائي لاقى نجاحًا كبيرًا.
    </p>

    <div class="genre-tags">
      <span>#إثارة</span>
      <span>#غموض</span>
      <span>#نفسي</span>
      <span>#أدب عربي معاصر</span>
    </div>
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
      <form method="POST" action="<?php echo e(route('reviews.store', $currentBook->slug ?? 'blue-elephant')); ?>" class="add-review-form" id="addReviewForm" hidden>
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
        <button type="submit" class="btn btn-teal">إرسال التقييم</button>
      </form>
    <?php endif; ?>

    <div class="review-list">
      <?php $__empty_1 = true; $__currentLoopData = ($currentBook->reviews ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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

    <button class="btn btn-outline center">عرض كل التقييمات</button>
  </div>

  <div class="tab-panel" id="tab-author">
    <div class="author-mini-card">
      <img src="https://i.pravatar.cc/120?img=14" alt="أحمد مراد">
      <div class="author-mini-info">
        <h3><a href="<?php echo e(route('writer-details', 'ahmed-mourad')); ?>">أحمد مراد</a></h3>
        <p>روائي وسيناريست مصري، من أبرز كتاب الرواية البوليسية والنفسية في الأدب العربي المعاصر، له أكثر من 8 روايات تحول عدد كبير منها إلى أعمال سينمائية ناجحة.</p>
        <div class="author-mini-stats">
          <span><i class="fa-solid fa-book"></i> 8 كتب</span>
          <span><i class="fa-solid fa-users"></i> 152,000 متابع</span>
        </div>
      </div>
      <?php if(auth()->guard()->check()): ?>
        <form method="POST" action="<?php echo e(route('writers.follow', $bookAuthorSlug)); ?>">
          <?php echo csrf_field(); ?>
          <button type="submit" class="btn btn-outline follow-btn <?php if($isFollowingBookAuthor): ?> following <?php endif; ?>">
            <?php echo e($isFollowingBookAuthor ? 'تتم المتابعة' : 'متابعة'); ?>

          </button>
        </form>
      <?php else: ?>
        <a href="<?php echo e(route('login')); ?>" class="btn btn-outline follow-btn">متابعة</a>
      <?php endif; ?>
    </div>
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
      <article class="book-card">
        <div class="book-cover cover-2">
          <span class="cover-badge">B</span>
          <span class="cover-title">يوتوبيا</span>
        </div>
        <h3>يوتوبيا</h3>
        <p class="author">أحمد خالد توفيق</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.5</p>
        <a href="<?php echo e(route('book-details', 'utopia')); ?>" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

      <article class="book-card">
        <div class="book-cover mc-1-lg">
          <span class="cover-badge">B</span>
          <span class="cover-title">عزازيل</span>
        </div>
        <h3>عزازيل</h3>
        <p class="author">يوسف زيدان</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.6</p>
        <a href="<?php echo e(route('book-details', 'azazeel')); ?>" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

      <article class="book-card">
        <div class="book-cover mc-3-lg">
          <span class="cover-badge">B</span>
          <span class="cover-title">1984</span>
        </div>
        <h3>1984</h3>
        <p class="author">جورج أورويل</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.8</p>
        <a href="<?php echo e(route('book-details', '1984')); ?>" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

      <article class="book-card">
        <div class="book-cover mc-4-lg">
          <span class="cover-badge">B</span>
          <span class="cover-title">موسم الهجرة إلى الشمال</span>
        </div>
        <h3>موسم الهجرة إلى الشمال</h3>
        <p class="author">الطيب صالح</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.7</p>
        <a href="<?php echo e(route('book-details', 'season-of-migration-to-the-north')); ?>" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>

      <article class="book-card">
        <div class="book-cover cover-3">
          <span class="cover-badge">B</span>
          <span class="cover-title">الخيميائي</span>
        </div>
        <h3>الخيميائي</h3>
        <p class="author">باولو كويلو</p>
        <p class="rating"><i class="fa-solid fa-star"></i> 4.6</p>
        <a href="<?php echo e(route('book-details', 'the-alchemist')); ?>" class="btn btn-outline"><i class="fa-solid fa-eye"></i> شاهد</a>
      </article>
    </div>

    <button class="carousel-btn next" aria-label="next"><i class="fa-solid fa-chevron-left"></i></button>
  </div>
</section>

</main>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('assets/js/book-details.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/book-details.blade.php ENDPATH**/ ?>