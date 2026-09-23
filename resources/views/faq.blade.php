@extends('layouts.app')

@section('title', 'الأسئلة الشائعة - نوتابوكس')
@section('meta_description', 'إجابات على أكثر الأسئلة شيوعًا حول منصة نوتابوكس: القراءة والتحميل، الحسابات، حقوق النشر، والدعم الفني.')

@push('styles')
<link rel="preload" href="{{ asset_min('assets/css/legal.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/legal.css') }}"></noscript>
<link rel="preload" href="{{ asset_min('assets/css/faq.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset_min('assets/css/faq.css') }}"></noscript>
@endpush

@section('content')
<main>

<!-- ===================== BREADCRUMB ===================== -->
<div class="section breadcrumb-wrap">
  <nav class="breadcrumb">
    <a href="{{ route('home') }}">الرئيسية</a>
    <i class="fa-solid fa-chevron-left"></i>
    <span>الأسئلة الشائعة</span>
  </nav>
</div>

<!-- ===================== FAQ PAGE ===================== -->
<section class="section faq-section">

  <div class="legal-head">
    <h1>الأسئلة الشائعة</h1>
    <p class="legal-intro">إجابات على أكثر الأسئلة التي تصلنا حول استخدام منصة "نوتابوكس".</p>
  </div>

  <div class="faq-list">

    <details class="faq-item" open>
      <summary>ما هي منصة نوتابوكس؟<i class="fa-solid fa-chevron-down"></i></summary>
      <div class="faq-answer">
        <p>"نوتابوكس" منصة عربية تتيح لك اكتشاف الكتب والروايات في مختلف المجالات، وقراءتها مباشرة أونلاين أو تحميلها لقراءتها لاحقًا.</p>
      </div>
    </details>

    <details class="faq-item">
      <summary>هل استخدام المنصة وقراءة الكتب مجاني؟<i class="fa-solid fa-chevron-down"></i></summary>
      <div class="faq-answer">
        <p>نعم، تصفح الكتب وقراءتها وتحميلها على المنصة مجاني بالكامل.</p>
      </div>
    </details>

    <details class="faq-item">
      <summary>هل أحتاج إلى إنشاء حساب لقراءة الكتب؟<i class="fa-solid fa-chevron-down"></i></summary>
      <div class="faq-answer">
        <p>يمكنك تصفح المكتبة دون حساب، لكن إنشاء حساب مجاني يتيح لك حفظ الكتب في مكتبتك الخاصة، ومتابعة قراءتك من حيث توقفت، والمشاركة في المجتمع والمناقشات.</p>
      </div>
    </details>

    <details class="faq-item">
      <summary>بأي صيغة تُتاح الكتب للتحميل؟<i class="fa-solid fa-chevron-down"></i></summary>
      <div class="faq-answer">
        <p>تتوفر معظم الكتب بصيغة PDF، يمكنك تحميلها وقراءتها على أي جهاز يدعم هذه الصيغة.</p>
      </div>
    </details>

    <details class="faq-item">
      <summary>لماذا لا يمكنني تحميل أو قراءة بعض الكتب؟<i class="fa-solid fa-chevron-down"></i></summary>
      <div class="faq-answer">
        <p>بعض الكتب قد تكون القراءة أو التحميل معطلين لها بسبب بلاغ متعلق بحقوق النشر، أو لأسباب تقنية مؤقتة. راجع صفحة <a href="{{ route('copyright') }}">حقوق النشر</a> لمزيد من التفاصيل.</p>
      </div>
    </details>

    <details class="faq-item">
      <summary>هل تملكون حقوق نشر الكتب المعروضة على المنصة؟<i class="fa-solid fa-chevron-down"></i></summary>
      <div class="faq-answer">
        <p>لا، نحن لا نطبع الكتب ولا ندّعي ملكيتها؛ المحتوى مُعاد مشاركته من مصادر أخرى متاحة على الإنترنت بهدف تسهيل وصول القراء إليه. جميع الحقوق تبقى لأصحابها الأصليين. اطّلع على <a href="{{ route('copyright') }}">صفحة حقوق النشر</a> لمعرفة التفاصيل الكاملة.</p>
      </div>
    </details>

    <details class="faq-item">
      <summary>كيف أُبلغ عن كتاب ينتهك حقوق النشر؟<i class="fa-solid fa-chevron-down"></i></summary>
      <div class="faq-answer">
        <p>افتح صفحة الكتاب المعني واضغط على زر "الإبلاغ عن حقوق النشر"، ثم املأ نموذج البلاغ ببياناتك وتفاصيل الانتهاك. يراجع فريقنا كل بلاغ ويتخذ الإجراء المناسب دون تأخير.</p>
      </div>
    </details>

    <details class="faq-item">
      <summary>كيف يمكنني التواصل مع فريق الدعم؟<i class="fa-solid fa-chevron-down"></i></summary>
      <div class="faq-answer">
        <p>يمكنك التواصل معنا في أي وقت عبر <a href="{{ route('contact') }}">صفحة تواصل معنا</a>، وسنقوم بالرد على استفسارك في أقرب وقت ممكن.</p>
      </div>
    </details>

  </div>

</section>

</main>
@endsection
