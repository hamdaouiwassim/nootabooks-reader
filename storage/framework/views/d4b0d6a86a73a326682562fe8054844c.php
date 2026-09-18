<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $__env->yieldContent('title', 'نوته بوك - عالم من الكتب بين يديك'); ?></title>
<?php echo $__env->make('partials.favicons', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('partials.google-analytics', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('partials.google-adsense', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('partials.seo-meta', ['defaultRobots' => 'noindex, follow'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<link rel="preload" href="<?php echo e(asset('assets/fonts/tajawal/Iura6YBj_oCad4k1nzSBC45I.woff2')); ?>" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?php echo e(asset('assets/fonts/tajawal/Iurf6YBj_oCad4k1l5anHrRpiYlJ.woff2')); ?>" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/fonts.css')); ?>">
<link rel="preload" href="<?php echo e(asset_min('assets/css/vendor/fontawesome/fontawesome.css')); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="<?php echo e(asset_min('assets/css/vendor/fontawesome/fontawesome.css')); ?>"></noscript>
<link rel="preload" href="<?php echo e(asset_min('assets/css/style.css')); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="<?php echo e(asset_min('assets/css/style.css')); ?>"></noscript>
<link rel="preload" href="<?php echo e(asset_min('assets/css/read.css')); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="<?php echo e(asset_min('assets/css/read.css')); ?>"></noscript>
<?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="reader-body">

<?php echo $__env->make('partials.loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->yieldContent('content'); ?>

<script src="<?php echo e(asset_min('assets/js/read.js')); ?>" defer></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/layouts/reader.blade.php ENDPATH**/ ?>