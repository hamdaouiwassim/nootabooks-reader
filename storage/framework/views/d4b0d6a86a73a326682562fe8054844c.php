<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $__env->yieldContent('title', 'نوته بوك - عالم من الكتب بين يديك'); ?></title>
<?php echo $__env->make('partials.seo-meta', ['defaultRobots' => 'noindex, nofollow'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/fonts.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/vendor/fontawesome/all.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/style.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/read.css')); ?>">
<?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="reader-body">

<?php echo $__env->make('partials.loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->yieldContent('content'); ?>

<script src="<?php echo e(asset_min('assets/js/read.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/layouts/reader.blade.php ENDPATH**/ ?>