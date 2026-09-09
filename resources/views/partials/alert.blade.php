@php
  $flashAlerts = [
    'success' => ['message' => session('status'), 'icon' => 'fa-circle-check'],
    'error' => ['message' => session('error'), 'icon' => 'fa-circle-exclamation'],
    'warning' => ['message' => session('warning'), 'icon' => 'fa-triangle-exclamation'],
    'info' => ['message' => session('info'), 'icon' => 'fa-circle-info'],
  ];
  $hasFlashAlerts = collect($flashAlerts)->contains(fn ($alert) => $alert['message']);
@endphp

@if ($hasFlashAlerts)
  <div class="alert-wrap" id="flashAlerts">
    @foreach ($flashAlerts as $type => $alert)
      @if ($alert['message'])
        <div class="alert alert-{{ $type }}" role="alert">
          <i class="fa-solid {{ $alert['icon'] }}"></i>
          <span>{{ $alert['message'] }}</span>
          <button type="button" class="alert-close" aria-label="إغلاق"><i class="fa-solid fa-xmark"></i></button>
        </div>
      @endif
    @endforeach
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
@endif
