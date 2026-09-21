<!-- ===================== FOOTER ===================== -->
<footer class="site-footer">
  <div class="footer-top">
    <div class="footer-brand">
      <a href="<?php echo e(route('home')); ?>" class="logo">
        <img src="<?php echo e(asset('assets/logos/light-logo-nootabooks-with-slogan.png')); ?>" alt="نوته بوك" class="logo-icon logo-light">
        <img src="<?php echo e(asset('assets/logos/dark-logo-nootabooks-with-slogan.png')); ?>" alt="نوته بوك" class="logo-icon logo-dark">
      </a>
      <p class="footer-about">منصة عربية تجمع عشاق القراءة، وتساعدك على اكتشاف الكتب وقراءتها أونلاين في مختلف المجالات.</p>
      <?php
        $socialLinks = [
          'youtube' => \App\Models\Setting::get('social_youtube'),
          'instagram' => \App\Models\Setting::get('social_instagram'),
          'twitter' => \App\Models\Setting::get('social_twitter'),
          'facebook' => \App\Models\Setting::get('social_facebook'),
        ];
      ?>
      <?php if(array_filter($socialLinks)): ?>
        <div class="social-icons">
          <?php if($socialLinks['youtube']): ?>
            <a href="<?php echo e($socialLinks['youtube']); ?>" target="_blank" rel="noopener noreferrer" aria-label="يوتيوب"><i class="fa-brands fa-youtube"></i></a>
          <?php endif; ?>
          <?php if($socialLinks['instagram']): ?>
            <a href="<?php echo e($socialLinks['instagram']); ?>" target="_blank" rel="noopener noreferrer" aria-label="انستغرام"><i class="fa-brands fa-instagram"></i></a>
          <?php endif; ?>
          <?php if($socialLinks['twitter']): ?>
            <a href="<?php echo e($socialLinks['twitter']); ?>" target="_blank" rel="noopener noreferrer" aria-label="تويتر"><i class="fa-brands fa-twitter"></i></a>
          <?php endif; ?>
          <?php if($socialLinks['facebook']): ?>
            <a href="<?php echo e($socialLinks['facebook']); ?>" target="_blank" rel="noopener noreferrer" aria-label="فيسبوك"><i class="fa-brands fa-facebook-f"></i></a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="footer-col">
      <h4>روابط سريعة</h4>
      <a href="<?php echo e(route('home')); ?>">الرئيسية</a>
      <a href="<?php echo e(route('discover')); ?>">استكشاف</a>
      <a href="<?php echo e(route('categories')); ?>">التصنيفات</a>
      <a href="<?php echo e(route('my-library')); ?>">مكتبتي</a>
    </div>

    <div class="footer-col">
      <h4>مساعدة</h4>
      <a href="<?php echo e(route('faq')); ?>">الأسئلة الشائعة</a>
      <a href="<?php echo e(route('privacy')); ?>">سياسة الخصوصية</a>
      <a href="<?php echo e(route('terms')); ?>">شروط الاستخدام</a>
      <a href="<?php echo e(route('copyright')); ?>">حقوق النشر</a>
      <a href="<?php echo e(route('contact')); ?>">تواصل معنا</a>
    </div>

    
  </div>

  <div class="footer-bottom">
    <p>نوته بوك © <?php echo e(date('Y')); ?>. جميع الحقوق محفوظة</p>
    <div class="footer-legal">
      <a href="<?php echo e(route('privacy')); ?>">الخصوصية</a> | <a href="<?php echo e(route('terms')); ?>">الشروط</a> | <a href="<?php echo e(route('copyright')); ?>">حقوق النشر</a> | <a href="<?php echo e(route('contact')); ?>">اتصل بنا</a>
    </div>
  </div>
</footer>
<?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/partials/footer.blade.php ENDPATH**/ ?>