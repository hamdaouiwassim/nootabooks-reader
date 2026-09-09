<?php
  $flashAlerts = [
    'success' => ['message' => session('status'), 'icon' => 'fa-circle-check'],
    'error' => ['message' => session('error'), 'icon' => 'fa-circle-exclamation'],
    'warning' => ['message' => session('warning'), 'icon' => 'fa-triangle-exclamation'],
    'info' => ['message' => session('info'), 'icon' => 'fa-circle-info'],
  ];
  $hasFlashAlerts = collect($flashAlerts)->contains(fn ($alert) => $alert['message']);
?>

<?php if($hasFlashAlerts): ?>
  <div class="alert-wrap" id="flashAlerts">
    <?php $__currentLoopData = $flashAlerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <?php if($alert['message']): ?>
        <div class="alert alert-<?php echo e($type); ?>" role="alert">
          <i class="fa-solid <?php echo e($alert['icon']); ?>"></i>
          <span><?php echo e($alert['message']); ?></span>
          <button type="button" class="alert-close" aria-label="إغلاق"><i class="fa-solid fa-xmark"></i></button>
        </div>
      <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <script>
    (function () {
      document.querySelectorAll('#flashAlerts .alert').forEach((el) => {
        void el.offsetWidth; // force layout so the hidden starting state is committed before transitioning
        el.classList.add('alert-show');

        const dismiss = () => {
          el.classList.remove('alert-show');
          el.classList.add('alert-hide');
          el.addEventListener('transitionend', () => el.remove(), { once: true });
        };

        el.querySelector('.alert-close')?.addEventListener('click', dismiss);
        setTimeout(dismiss, 5000);
      });
    })();
  </script>
<?php endif; ?>
<?php /**PATH C:\Users\USER\Desktop\nootabooksui-reader\resources\views/partials/alert.blade.php ENDPATH**/ ?>