@extends('admin.layouts.admin')

@section('title', 'الإعدادات - مكتبتي')

@php
  $pageTitle = 'الإعدادات';
  $breadcrumb = [
    ['label' => 'الإعدادات', 'url' => null],
  ];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>الإعدادات</h1>
    <p>تحكم في روابط التواصل الاجتماعي الظاهرة في تذييل الموقع</p>
  </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" novalidate>
  @csrf
  @method('PUT')
  <div class="admin-form-section">
    <h3><i class="fa-solid fa-share-nodes"></i> روابط التواصل الاجتماعي</h3>
    <div class="admin-form-grid">
      <div class="admin-form-field">
        <label for="socialFacebook"><i class="fa-brands fa-facebook-f"></i> فيسبوك</label>
        <input type="url" id="socialFacebook" name="social_facebook" class="admin-input" value="{{ old('social_facebook', $settings['social_facebook']) }}" placeholder="https://facebook.com/..." dir="ltr">
        @error('social_facebook')
          <span class="admin-field-error">{{ $message }}</span>
        @enderror
      </div>
      <div class="admin-form-field">
        <label for="socialTwitter"><i class="fa-brands fa-twitter"></i> تويتر (X)</label>
        <input type="url" id="socialTwitter" name="social_twitter" class="admin-input" value="{{ old('social_twitter', $settings['social_twitter']) }}" placeholder="https://x.com/..." dir="ltr">
        @error('social_twitter')
          <span class="admin-field-error">{{ $message }}</span>
        @enderror
      </div>
      <div class="admin-form-field">
        <label for="socialInstagram"><i class="fa-brands fa-instagram"></i> انستغرام</label>
        <input type="url" id="socialInstagram" name="social_instagram" class="admin-input" value="{{ old('social_instagram', $settings['social_instagram']) }}" placeholder="https://instagram.com/..." dir="ltr">
        @error('social_instagram')
          <span class="admin-field-error">{{ $message }}</span>
        @enderror
      </div>
      <div class="admin-form-field">
        <label for="socialYoutube"><i class="fa-brands fa-youtube"></i> يوتيوب</label>
        <input type="url" id="socialYoutube" name="social_youtube" class="admin-input" value="{{ old('social_youtube', $settings['social_youtube']) }}" placeholder="https://youtube.com/..." dir="ltr">
        @error('social_youtube')
          <span class="admin-field-error">{{ $message }}</span>
        @enderror
      </div>
    </div>
  </div>

  <div class="admin-form-actions">
    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-floppy-disk"></i> حفظ التعديلات</button>
  </div>
</form>

@endsection
