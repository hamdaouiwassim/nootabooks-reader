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
      <img src="{{ $ad->creative_url }}" alt="{{ $ad->alt_text }}" loading="lazy" class="ad-slot-creative">
      @if ($ad->type === 'image_text')
        <div class="ad-slot-text">
          <strong class="ad-slot-heading">{{ $ad->heading }}</strong>
          <p class="ad-slot-body">{{ $ad->body_text }}</p>
        </div>
      @endif
    </a>
  </div>
@endif
