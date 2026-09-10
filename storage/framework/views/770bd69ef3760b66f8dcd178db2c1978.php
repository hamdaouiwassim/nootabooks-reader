<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title>تسجيل الدخول - لوحة تحكم مكتبتي</title>
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/fonts.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/vendor/fontawesome/all.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/style.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/admin.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset_min('assets/css/auth.css')); ?>">
</head>
<body>

<div class="auth-wrapper">
  <div class="auth-visual">
    <div class="admin-sidebar-head" style="border:none; padding:0;">
      <i class="fa-solid fa-book-bookmark logo-icon"></i>
      <div class="logo-text">
        <span class="logo-title">مكتبتي</span>
        <span class="logo-tagline">لوحة التحكم</span>
      </div>
    </div>

    <div class="auth-illustration">
      <span class="hero-plant"><i class="fa-solid fa-seedling"></i></span>
      <div class="hero-arch"></div>
      <span class="hero-open-book"><i class="fa-solid fa-book-open"></i></span>
    </div>

    <p class="auth-quote">"إدارة محتوى مكتبة رقمية تبدأ من هنا. سجّل دخولك للوصول إلى لوحة التحكم."</p>
  </div>

  <div class="auth-form-panel">
    <div class="auth-form-box">
      <h1>تسجيل دخول المشرفين</h1>
      <p class="auth-subtitle">أدخل بيانات حسابك للوصول إلى لوحة تحكم مكتبتي</p>

      <?php if(session('status')): ?>
        <div class="admin-alert success"><i class="fa-solid fa-circle-check"></i> <?php echo e(session('status')); ?></div>
      <?php endif; ?>

      <form method="POST" action="<?php echo e(route('admin.login')); ?>" novalidate>
        <?php echo csrf_field(); ?>

        <div class="form-field">
          <label for="email">البريد الإلكتروني</label>
          <div class="input-with-icon">
            <i class="fa-regular fa-envelope"></i>
            <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="admin@example.com" required autofocus autocomplete="username">
          </div>
          <span class="field-error"><?php echo e($errors->first('email')); ?></span>
        </div>

        <div class="form-field">
          <label for="password">كلمة المرور</label>
          <div class="input-with-icon">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
            <button type="button" class="toggle-password" id="togglePassword" aria-label="إظهار كلمة المرور"><i class="fa-regular fa-eye"></i></button>
          </div>
          <span class="field-error"><?php echo e($errors->first('password')); ?></span>
        </div>

        <div class="form-row-between">
          <label class="checkbox-row">
            <input type="checkbox" name="remember"> تذكرني
          </label>
        </div>

        <button type="submit" class="btn btn-gold full"><i class="fa-solid fa-right-to-bracket"></i> تسجيل الدخول</button>
      </form>
    </div>
  </div>
</div>

<script src="<?php echo e(asset_min('assets/js/admin-login.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>