@if (config('services.recaptcha.site_key'))
  <input type="hidden" name="g-recaptcha-response" class="recaptcha-token">
  @error('g-recaptcha-response')
    <span class="field-error">{{ $message }}</span>
  @enderror
  @once
    @push('scripts')
      <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
      <script>
        (function () {
          var siteKey = '{{ config('services.recaptcha.site_key') }}';

          // Attach on window "load" (fires after every other script's own
          // DOMContentLoaded listener, e.g. login.js/register.js field
          // validation) so this listener always runs last and can defer to
          // an earlier validation failure via e.defaultPrevented below.
          window.addEventListener('load', function () {
            document.querySelectorAll('form').forEach(function (form) {
              var tokenInput = form.querySelector('.recaptcha-token');
              if (!tokenInput) return;

              form.addEventListener('submit', function (e) {
                if (e.defaultPrevented) return;
                e.preventDefault();

                grecaptcha.ready(function () {
                  grecaptcha.execute(siteKey, { action: form.dataset.recaptchaAction || 'submit' }).then(function (token) {
                    tokenInput.value = token;
                    form.submit();
                  });
                });
              });
            });
          });
        })();
      </script>
    @endpush
  @endonce
@endif
