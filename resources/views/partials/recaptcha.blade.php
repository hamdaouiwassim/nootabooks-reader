@if (config('services.recaptcha.site_key'))
  <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
  @error('g-recaptcha-response')
    <span class="field-error">{{ $message }}</span>
  @enderror
  @once
    @push('scripts')
      <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endpush
  @endonce
@endif
