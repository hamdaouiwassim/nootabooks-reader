<!-- ===================== FOOTER ===================== -->
<footer class="site-footer">
  <div class="footer-top">
    <div class="footer-brand">
      <a href="{{ route('home') }}" class="logo">
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
      <a href="{{ route('home') }}">الرئيسية</a>
      <a href="{{ route('discover') }}">استكشاف</a>
      <a href="{{ route('categories') }}">التصنيفات</a>
      <a href="{{ route('my-library') }}">مكتبتي</a>
    </div>

    <div class="footer-col">
      <h4>مساعدة</h4>
      <a href="#">الأسئلة الشائعة</a>
      <a href="{{ route('privacy') }}">سياسة الخصوصية</a>
      <a href="{{ route('terms') }}">شروط الاستخدام</a>
      <a href="{{ route('contact') }}">تواصل معنا</a>
    </div>

    <div class="footer-col">
      <h4>عن نوته بوك</h4>
      <p class="footer-about">منصة عربية تجمع عشاق الفراءة. نوفر لك الاف الكتب للقراءة أونلاين والتحميل في مختلف المجالات.</p>
    </div>

    <div class="footer-col store-badges">
      <a href="#" class="store-badge">
        <i class="fa-brands fa-google-play"></i>
        <span><small>GET IT ON</small><strong>Google Play</strong></span>
      </a>
      <a href="#" class="store-badge">
        <i class="fa-brands fa-apple"></i>
        <span><small>Download on the</small><strong>App Store</strong></span>
      </a>
    </div>
  </div>

  <div class="footer-bottom">
    <p>نوته بوك © {{ date('Y') }}. جميع الحقوق محفوظة</p>
    <div class="footer-legal">
      <a href="{{ route('privacy') }}">الخصوصية</a> | <a href="{{ route('terms') }}">الشروط</a> | <a href="{{ route('contact') }}">اتصل بنا</a>
    </div>
  </div>
</footer>
