@extends('admin.layouts.admin')

@section('title', 'النسخ الاحتياطي - مكتبتي')

@php
  $pageTitle = 'النسخ الاحتياطي';
  $breadcrumb = [['label' => 'النسخ الاحتياطي', 'url' => null]];
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>النسخ الاحتياطي</h1>
    <p>تنزيل نسخة احتياطية كاملة من المنصة</p>
  </div>
</div>

<div class="admin-panel">
  <div class="admin-panel-head">
    <h3>نسخة احتياطية كاملة</h3>
  </div>

  <p style="font-size:13px; color:var(--text-gray); line-height:1.9; margin-bottom:18px;">
    يقوم هذا الإجراء بإنشاء ملف مضغوط (zip) واحد يحتوي على:
  </p>
  <ul style="font-size:13px; color:var(--text-dark); line-height:2.2; margin-bottom:22px; padding-inline-start:20px;">
    <li><i class="fa-solid fa-database" style="color:var(--gold-dark); margin-left:8px;"></i> نسخة كاملة من قاعدة البيانات (ملف SQL)</li>
    <li><i class="fa-solid fa-image" style="color:var(--gold-dark); margin-left:8px;"></i> جميع صور الأغلفة وصور المؤلفين</li>
    <li><i class="fa-solid fa-file-pdf" style="color:var(--gold-dark); margin-left:8px;"></i> جميع ملفات الكتب (PDF)</li>
  </ul>

  <div class="admin-alert info">
    <i class="fa-solid fa-circle-info"></i>
    <span>قد تستغرق هذه العملية عدة دقائق حسب حجم المكتبة، ويبدأ التنزيل تلقائيًا بعد الانتهاء. تجنب إغلاق الصفحة أو مغادرتها أثناء التنفيذ.</span>
  </div>

  <form method="POST" action="{{ route('admin.backup.create') }}" id="backupForm" style="margin-top:18px;">
    @csrf
    <button type="submit" class="btn btn-gold" id="backupBtn"><i class="fa-solid fa-download"></i> إنشاء وتنزيل نسخة احتياطية كاملة</button>
  </form>
</div>

<div class="admin-panel" style="margin-top:22px;">
  <div class="admin-panel-head">
    <h3>سجل النسخ الاحتياطية</h3>
    <span class="admin-breadcrumb">آخر 20 عملية</span>
  </div>

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>التاريخ</th>
          <th>المسؤول</th>
          <th>اسم الملف</th>
          <th>الحجم</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($backupLogs as $log)
          <tr>
            <td>{{ $log->created_at->format('Y-m-d H:i') }} <span style="color:var(--text-gray); font-size:12px;">({{ $log->created_at->diffForHumans() }})</span></td>
            <td>{{ $log->admin?->name ?? '—' }}</td>
            <td>{{ $log->file_name }}</td>
            <td>{{ number_format($log->file_size_bytes / 1024 / 1024, 1) }} MB</td>
          </tr>
        @empty
          <tr class="admin-empty-row">
            <td colspan="4">لم يتم إنشاء أي نسخة احتياطية بعد</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // A form POST that returns a file download doesn't navigate away, so
  // there's no page-load event to reset the button on — just re-enable it
  // after a short delay (only meant to block an accidental instant
  // double-click; the server-side throttle handles real abuse).
  document.getElementById('backupForm')?.addEventListener('submit', function () {
    const btn = document.getElementById('backupBtn');
    if (!btn) return;
    const originalLabel = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> جارِ إنشاء النسخة الاحتياطية، قد يستغرق هذا عدة دقائق ...';
    setTimeout(() => {
      btn.disabled = false;
      btn.innerHTML = originalLabel;
    }, 10000);
  });
</script>
@endpush
