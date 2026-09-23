{{--
  Looks up and renders one random active ad for a given zone. Deliberately
  queried here at render time, NOT inside PageController::home()'s
  Cache::remember('home.page.data', ...) 6-hour cache — folding this into
  that cache would serve a stale/expired ad selection for up to 6 hours.
--}}
@php
  $ad = \App\Models\Advertisement::active()->forZone($zone)->inRandomOrder()->first();
@endphp
@if ($ad)
  <div class="ad-slot ad-slot-{{ $zone }}">
    <span class="ad-slot-label">إعلان</span>
    <a href="{{ $ad->clickUrl() }}" target="_blank" rel="sponsored noopener" class="ad-slot-link">
      @if ($ad->type === 'animated_banner')
        {{-- Animated ads are one file for every device — no per-device
             uploads for GIFs, since resizing would mean re-encoding, which
             would destroy the animation. --}}
        <img src="{{ $ad->creative_url }}" alt="{{ $ad->alt_text }}" loading="lazy" class="ad-slot-creative">
      @else
        {{-- Admin uploads an independent image per device (see the admin
             form) rather than one source being auto-resized; each falls
             back to the desktop image if that device's upload is empty. --}}
        <picture>
          <source media="(max-width: 640px)" srcset="{{ $ad->creative_mobile_url }}">
          <source media="(max-width: 1024px)" srcset="{{ $ad->creative_tablet_url }}">
          <img src="{{ $ad->creative_url }}" alt="{{ $ad->alt_text }}" loading="lazy" class="ad-slot-creative">
        </picture>
      @endif
      @if ($ad->type === 'image_text')
        <div class="ad-slot-text">
          <strong class="ad-slot-heading">{{ $ad->heading }}</strong>
          <p class="ad-slot-body">{{ $ad->body_text }}</p>
        </div>
      @endif
    </a>
  </div>
@endif
