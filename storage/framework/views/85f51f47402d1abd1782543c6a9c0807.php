<!-- ===================== FOOTER ===================== -->
<footer class="site-footer">
  <div class="footer-top">
    <div class="footer-brand">
      <a href="<?php echo e(route('home')); ?>" class="logo">
        <i class="fa-solid fa-book-bookmark logo-icon"></i>
        <div class="logo-text">
          <span class="logo-title">نوته بوك</span>
          <span class="logo-tagline">عالم من الكتب بين يديك</span>
        </div>
      </a>
      <div class="social-icons">
        <a href="#"><i class="fa-brands fa-youtube"></i></a>
        <a href="#"><i class="fa-brands fa-instagram"></i></a>
        <a href="#"><i class="fa-brands fa-twitter"></i></a>
        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
      </div>
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
      <a href="#">الأسئلة الشائعة</a>
      <a href="<?php echo e(route('privacy')); ?>">سياسة الخصوصية</a>
      <a href="<?php echo e(route('terms')); ?>">شروط الاستخدام</a>
      <a href="<?php echo e(route('contact')); ?>">تواصل معنا</a>
    </div>

    <div class="footer-col">
      <h4>عن نوته بوك</h4>
      <p class="footer-about">منصة عربية تجمع عشاق الفراءة. نوفر لك الاف الكتب للقراءة أونلاين والتحميل في مختلف المجالات.</p>
    </div>

    
  </div>

  <div class="footer-bottom">
    <p>نوته بوك © <?php echo e(date('Y')); ?>. جميع الحقوق محفوظة</p>
    <div class="footer-legal">
      <a href="<?php echo e(route('privacy')); ?>">الخصوصية</a> | <a href="<?php echo e(route('terms')); ?>">الشروط</a> | <a href="<?php echo e(route('contact')); ?>">اتصل بنا</a>
    </div>
  </div>
</footer>
<?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/partials/footer.blade.php ENDPATH**/ ?>