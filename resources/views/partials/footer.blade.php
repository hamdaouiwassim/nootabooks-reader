<!-- ===================== FOOTER ===================== -->
<footer class="site-footer">
  <div class="footer-top">
    <div class="footer-brand">
      <a href="{{ route('home') }}" class="logo">
        <img src="{{ asset('assets/images/logo.png') }}" alt="نوته بوك" class="logo-icon">
      </a>
      <p class="footer-about">منصة عربية تجمع عشاق القراءة، وتساعدك على اكتشاف الكتب وقراءتها أونلاين في مختلف المجالات.</p>
      @php
        $socialLinks = [
          'youtube' => \App\Models\Setting::get('social_youtube'),
          'instagram' => \App\Models\Setting::get('social_instagram'),
          'twitter' => \App\Models\Setting::get('social_twitter'),
          'facebook' => \App\Models\Setting::get('social_facebook'),
        ];
      @endphp
      @if (array_filter($socialLinks))
        <div class="social-icons">
          @if ($socialLinks['youtube'])
            <a href="{{ $socialLinks['youtube'] }}" target="_blank" rel="noopener noreferrer" aria-label="يوتيوب"><i class="fa-brands fa-youtube"></i></a>
          @endif
          @if ($socialLinks['instagram'])
            <a href="{{ $socialLinks['instagram'] }}" target="_blank" rel="noopener noreferrer" aria-label="انستغرام"><i class="fa-brands fa-instagram"></i></a>
          @endif
          @if ($socialLinks['twitter'])
            <a href="{{ $socialLinks['twitter'] }}" target="_blank" rel="noopener noreferrer" aria-label="تويتر"><i class="fa-brands fa-twitter"></i></a>
          @endif
          @if ($socialLinks['facebook'])
            <a href="{{ $socialLinks['facebook'] }}" target="_blank" rel="noopener noreferrer" aria-label="فيسبوك"><i class="fa-brands fa-facebook-f"></i></a>
          @endif
        </div>
      @endif
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
      <a href="{{ route('faq') }}">الأسئلة الشائعة</a>
      <a href="{{ route('privacy') }}">سياسة الخصوصية</a>
      <a href="{{ route('terms') }}">شروط الاستخدام</a>
      <a href="{{ route('copyright') }}">حقوق النشر</a>
      <a href="{{ route('contact') }}">تواصل معنا</a>
    </div>

    {{-- Store badges hidden for now — no mobile app yet, re-enable once one exists.
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
    --}}
  </div>

  <div class="footer-bottom">
    <p>نوته بوك © {{ date('Y') }}. جميع الحقوق محفوظة</p>
    <div class="footer-legal">
      <a href="{{ route('privacy') }}">الخصوصية</a> | <a href="{{ route('terms') }}">الشروط</a> | <a href="{{ route('copyright') }}">حقوق النشر</a> | <a href="{{ route('contact') }}">اتصل بنا</a>
    </div>
  </div>
</footer>
