<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $__env->yieldContent('title', 'نوته بوك - عالم من الكتب بين يديك'); ?></title>
<?php echo $__env->make('partials.seo-meta', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<script type="application/ld+json">
<?php echo json_encode([
    '<?php $__contextArgs = [];
if (context()->has($__contextArgs[0])) :
if (isset($value)) { $__contextPrevious[] = $value; }
$value = context()->get($__contextArgs[0]); ?>' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'نوته بوك',
    'url' => url('/'),
    'inLanguage' => 'ar',
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>

</script>
<script type="application/ld+json">
<?php echo json_encode([
    '<?php $__contextArgs = [];
if (context()->has($__contextArgs[0])) :
if (isset($value)) { $__contextPrevious[] = $value; }
$value = context()->get($__contextArgs[0]); ?>' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'نوته بوك',
    'url' => url('/'),
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>

</script>
<?php echo $__env->yieldPushContent('schema'); ?>
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/fonts.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/vendor/fontawesome/all.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/style.css')); ?>">
<?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<?php echo $__env->make('partials.loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->make('partials.alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->yieldContent('content'); ?>

<?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script src="<?php echo e(asset_min('assets/js/script.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/layouts/app.blade.php ENDPATH**/ ?>