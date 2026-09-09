<?php $__env->startSection('title', 'إنشاء حساب - نوته بوك'); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-wrapper">

  <!-- ===================== VISUAL PANEL ===================== -->
  <div class="auth-visual register-visual">
    <a href="<?php echo e(route('home')); ?>" class="logo">
      <i class="fa-solid fa-book-bookmark logo-icon"></i>
      <div class="logo-text">
        <span class="logo-title">نوته بوك</span>
        <span class="logo-tagline">عالم من الكتب بين يديك</span>
      </div>
    </a>

    <h1 class="auth-visual-heading">إنشاء حساب جديد</h1>

    <div class="auth-illustration"></div>

    <blockquote class="auth-quote">"إقرأ. اكتشف. حمّل. عالم من الكتب بين يديك"</blockquote>
  </div>

  <!-- ===================== FORM PANEL ===================== -->
  <div class="auth-form-panel">
    <div class="auth-form-box">
      <h1 class="auth-form-heading">إنشاء حساب جديد</h1>
      <p class="auth-subtitle">انضم إلى مجتمع القراء وابدأ رحلتك مع آلاف الكتب</p>

      <div class="social-auth-buttons">
        <button type="button" class="btn btn-outline social-btn"><i class="fa-brands fa-google"></i> التسجيل عبر جوجل</button>
        <button type="button" class="btn btn-outline social-btn"><i class="fa-brands fa-facebook-f"></i> التسجيل عبر فيسبوك</button>
      </div>

      <div class="auth-divider"><span>أو عبر البريد الإلكتروني</span></div>

      <form id="registerForm" method="POST" action="<?php echo e(route('register.submit')); ?>" novalidate>
        <?php echo csrf_field(); ?>
        <div class="form-field">
          <label for="registerName">الاسم الكامل</label>
          <div class="input-with-icon">
            <i class="fa-regular fa-user"></i>
            <input type="text" id="registerName" name="name" value="<?php echo e(old('name')); ?>" placeholder="ادخل اسمك الكامل" required>
          </div>
          <span class="field-error" id="registerNameError"><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>
        </div>

        <div class="form-field">
          <label for="registerEmail">البريد الإلكتروني</label>
          <div class="input-with-icon">
            <i class="fa-regular fa-envelope"></i>
            <input type="email" id="registerEmail" name="email" value="<?php echo e(old('email')); ?>" placeholder="ادخل بريدك الإلكتروني" required>
          </div>
          <span class="field-error" id="registerEmailError"><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>
        </div>

        <div class="form-field">
          <label for="registerPassword">كلمة المرور</label>
          <div class="input-with-icon">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="registerPassword" name="password" placeholder="8 أحرف على الأقل" required minlength="8">
            <button type="button" class="toggle-password" aria-label="show password"><i class="fa-regular fa-eye"></i></button>
          </div>
          <div class="password-strength" id="passwordStrength">
            <div class="strength-bar"><div class="strength-fill"></div></div>
            <span class="strength-label"></span>
          </div>
          <span class="field-error" id="registerPasswordError"><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>
        </div>

        <div class="form-field">
          <label for="registerConfirm">تأكيد كلمة المرور</label>
          <div class="input-with-icon">
            <i class="fa-solid fa-lock"></i>
            <input type="password" id="registerConfirm" name="password_confirmation" placeholder="أعد كتابة كلمة المرور" required>
            <button type="button" class="toggle-password" aria-label="show password"><i class="fa-regular fa-eye"></i></button>
          </div>
          <span class="field-error" id="registerConfirmError"></span>
        </div>

        <label class="checkbox-row terms-row">
          <input type="checkbox" id="registerTerms" name="terms" required>
          <span>أوافق على <a href="<?php echo e(route('terms')); ?>">الشروط والأحكام</a> و<a href="<?php echo e(route('privacy')); ?>">سياسة الخصوصية</a></span>
        </label>
        <span class="field-error" id="registerTermsError"><?php $__errorArgs = ['terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><?php echo e($message); ?><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></span>

        <button type="submit" class="btn btn-teal full">إنشاء حساب</button>
      </form>
      <p class="form-success" id="registerSuccess" hidden><i class="fa-solid fa-circle-check"></i> تم إنشاء حسابك بنجاح، جارِ التحويل ...</p>

      <p class="auth-switch">لديك حساب بالفعل؟ <a href="<?php echo e(route('login')); ?>">تسجيل الدخول</a></p>
    </div>
  </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('assets/js/register.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/auth/register.blade.php ENDPATH**/ ?>