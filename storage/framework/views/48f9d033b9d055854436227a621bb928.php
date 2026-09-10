<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>الصفحة غير موجودة - نوته بوك</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/style.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/errors.css')); ?>">
</head>
<body>

<?php echo $__env->make('partials.loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="error-page">
  <div class="error-badge tone-gold"><i class="fa-solid fa-compass"></i></div>
  <div class="error-code">404</div>
  <h1 class="error-title">الصفحة غير موجودة</h1>
  <p class="error-desc">عذرًا، الصفحة التي تبحث عنها غير موجودة أو تم نقلها أو حذفها. جرب البحث عن ما تريد أو عد إلى الرئيسية.</p>

  <div class="error-search">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" placeholder="ابحث عن كتاب، مؤلف، او موضوع ...">
  </div>

  <div class="error-actions">
    <a href="<?php echo e(route('home')); ?>" class="btn btn-gold"><i class="fa-solid fa-house"></i> العودة للرئيسية</a>
    <a href="<?php echo e(route('discover')); ?>" class="btn btn-outline"><i class="fa-solid fa-compass"></i> استكشاف الكتب</a>
  </div>

  <p class="error-footer-note">ما زلت بحاجة لمساعدة؟ <a href="<?php echo e(route('contact')); ?>">تواصل مع الدعم</a></p>
</div>

<script src="<?php echo e(asset_min('assets/js/script.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/errors/404.blade.php ENDPATH**/ ?>