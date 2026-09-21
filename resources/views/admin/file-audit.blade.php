@extends('admin.layouts.admin')

@section('title', 'فحص حجم الملفات - مكتبتي')

@php
  $pageTitle = 'فحص حجم الملفات';
  $breadcrumb = [['label' => 'فحص حجم الملفات', 'url' => null]];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>فحص حجم الملفات</h1>
    <p>مراجعة حجم ملفات PDF وصور الأغلفة لكل كتاب، مع تمييز الملفات كبيرة الحجم</p>
  </div>
</div>

<div class="admin-stats-grid">
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-navy"><i class="fa-solid fa-book"></i></span>
    <div>
      <strong>{{ number_format($summary['totalBooks']) }}</strong>
      <span>كتاب يحتوي على ملفات</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-gold"><i class="fa-solid fa-file-pdf"></i></span>
    <div>
      <strong>{{ format_file_size($summary['totalPdfMb']) }}</strong>
      <span>إجمالي حجم ملفات PDF</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-teal"><i class="fa-solid fa-image"></i></span>
    <div>
      <strong>{{ format_file_size($summary['totalCoverMb']) }}</strong>
      <span>إجمالي حجم صور الأغلفة</span>
    </div>
  </div>
  <div class="admin-stat-card">
    <span class="admin-stat-icon tone-rose"><i class="fa-solid fa-triangle-exclamation"></i></span>
    <div>
      <strong>{{ number_format($summary['oversizedPdfCount'] + $summary['oversizedCoverCount']) }}</strong>
      <span>ملفات تحتاج مراجعة</span>
    </div>
  </div>
</div>

<div class="admin-panel" style="margin-top: 22px;">
  <div class="admin-panel-head">
    <h3>تفاصيل الملفات لكل كتاب</h3>
    <span class="admin-breadcrumb">تنبيه عند تجاوز PDF لـ {{ $pdfWarnMb }} ميجابايت أو الغلاف لـ {{ $coverWarnKb }} كيلوبايت</span>
  </div>

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>الكتاب</th>
          <th>حجم PDF</th>
          <th>حجم الغلاف</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($rows as $row)
          <tr>
            <td>
              <div class="admin-book-cell">
                @if ($row->book->cover_image)
                  <img class="admin-book-cover" src="{{ $row->book->cover_image_sm_url }}" width="300" height="450" loading="lazy" decoding="async" alt="{{ $row->book->title }}">
                @else
                  <span class="admin-book-cover placeholder"><i class="fa-solid fa-book"></i></span>
                @endif
                <div><strong>{{ $row->book->title }}</strong></div>
              </div>
            </td>
            <td>
              @if ($row->pdfIsExternal)
                <span class="status-badge draft">رابط خارجي</span>
              @elseif ($row->pdfSizeMb === null)
                <span>—</span>
              @else
                <span @class(['file-audit-oversized' => $row->pdfOversized])>{{ format_file_size($row->pdfSizeMb) }}</span>
                @if ($row->pdfOversized)
                  <i class="fa-solid fa-triangle-exclamation file-audit-warn-icon" title="يتجاوز {{ $pdfWarnMb }} ميجابايت"></i>
                @endif
              @endif
            </td>
            <td>
              @if ($row->coverIsExternal)
                <span class="status-badge draft">رابط خارجي</span>
              @elseif ($row->coverSizeKb === null)
                <span>—</span>
              @else
                <span @class(['file-audit-oversized' => $row->coverOversized])>{{ number_format($row->coverSizeKb, 1) }} KB</span>
                @if ($row->coverOversized)
                  <i class="fa-solid fa-triangle-exclamation file-audit-warn-icon" title="يتجاوز {{ $coverWarnKb }} كيلوبايت"></i>
                @endif
              @endif
            </td>
          </tr>
        @empty
          <tr class="admin-empty-row">
            <td colspan="3">لا توجد كتب تحتوي على ملفات بعد</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="admin-pagination">
  <span class="admin-pagination-info">{{ $rows->total() ? "عرض {$rows->firstItem()}-{$rows->lastItem()} من {$rows->total()} كتاب" : 'لا توجد نتائج' }}</span>
  <div class="admin-pagination-controls">
    {{ $rows->onEachSide(1)->links('admin.pagination.admin') }}
  </div>
</div>

@endsection
