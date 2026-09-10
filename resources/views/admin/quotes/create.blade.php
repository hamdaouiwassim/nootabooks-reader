@extends('admin.layouts.admin')

@section('title', 'إضافة اقتباس جديد - مكتبتي')

@php
  $pageTitle = 'إضافة اقتباس جديد';
  $breadcrumb = [
    ['label' => 'إدارة الاقتباسات', 'url' => route('admin.quotes.index')],
    ['label' => 'إضافة اقتباس', 'url' => null],
  ];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>إضافة اقتباس جديد</h1>
    <p>يظهر هذا الاقتباس ضمن البطاقات المتحركة في قسم الواجهة الرئيسية</p>
  </div>
  <a href="{{ route('admin.quotes.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<form method="POST" action="{{ route('admin.quotes.store') }}" novalidate>
  @csrf
  <div class="admin-form-section">
    <div class="admin-form-grid">
      <div class="admin-form-field full">
        <label for="quoteText">نص الاقتباس</label>
        <textarea id="quoteText" name="text" class="admin-textarea" rows="3" placeholder="مثال: الكتب هي نوافذ نرى من خلالها عوالم أخرى" required maxlength="400">{{ old('text') }}</textarea>
        @error('text')
          <span class="admin-field-error">{{ $message }}</span>
        @enderror
      </div>
      <div class="admin-form-field full">
        <label for="quoteAuthor">اسم الكاتب (اختياري)</label>
        <input type="text" id="quoteAuthor" name="author" class="admin-input" value="{{ old('author') }}" placeholder="مثال: نجيب محفوظ">
        @error('author')
          <span class="admin-field-error">{{ $message }}</span>
        @enderror
      </div>
    </div>
  </div>

  <div class="admin-form-actions">
    <a href="{{ route('admin.quotes.index') }}" class="btn btn-outline">إلغاء</a>
    <button type="submit" class="btn btn-gold"><i class="fa-solid fa-paper-plane"></i> نشر الاقتباس</button>
  </div>
</form>

@endsection
