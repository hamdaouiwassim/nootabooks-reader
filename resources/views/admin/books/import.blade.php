@extends('admin.layouts.admin')

@section('title', 'استيراد كتب من CSV - مكتبتي')

@php
  $pageTitle = 'استيراد كتب من CSV';
  $breadcrumb = [
    ['label' => 'إدارة الكتب', 'url' => route('admin.books.index')],
    ['label' => 'استيراد من CSV', 'url' => null],
  ];
  $results = session('importResults');
@endphp

@section('content')

<div class="admin-page-head">
  <div>
    <h1>استيراد كتب من CSV</h1>
    <p>ارفع ملف CSV لإضافة عدة كتب دفعة واحدة</p>
  </div>
  <a href="{{ route('admin.books.index') }}" class="btn btn-outline"><i class="fa-solid fa-arrow-right"></i> العودة للقائمة</a>
</div>

<div class="admin-form-section">
  <h3>رفع الملف</h3>
  <form method="POST" action="{{ route('admin.books.import.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="admin-form-field">
      <label for="csvFile">ملف CSV</label>
      <input type="file" id="csvFile" name="csv_file" accept=".csv,text/csv" class="admin-input" required>
      <span id="csvFileError" class="hint"></span>
      <span class="hint">يجب أن يحتوي الملف على صف عناوين الأعمدة، ثم صف لكل كتاب: العنوان، وصف الكتاب، التصنيف، الكاتب، وصف الكاتب، الحالة (1 = متاح الآن، 0 = قريبًا)، كاتب جديد (1/0)، تصنيف جديد (1/0)، الكاتب بالإنجليزية، مترجم (1/0)، إسم المترجم، عنوان الكتاب بالإنجليزية. ترتيب الأعمدة غير مهم، يتم التعرف عليها من عناوينها.</span>
    </div>
    <div class="admin-form-actions">
      <button type="submit" class="btn btn-gold"><i class="fa-solid fa-upload"></i> استيراد</button>
    </div>
  </form>
</div>

@if ($results)
  @php
    $counts = $results->countBy(fn ($row) => $row['outcome']);
  @endphp
  <div class="admin-form-section">
    <h3>نتيجة الاستيراد</h3>
    <p class="hint">
      تم إنشاء {{ $counts->get('created', 0) }} كتاب،
      تخطي {{ $counts->get('skipped', 0) }} (مكرر)،
      وفشل {{ $counts->get('error', 0) }} صف.
    </p>

    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>الصف</th>
            <th>العنوان</th>
            <th>النتيجة</th>
            <th>ملاحظات</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($results as $row)
            <tr>
              <td>{{ $row['row'] }}</td>
              <td>{{ $row['title'] }}</td>
              <td>
                @if ($row['outcome'] === 'created')
                  <span class="status-badge published">تم الإنشاء</span>
                @elseif ($row['outcome'] === 'skipped')
                  <span class="status-badge coming-soon">تخطي</span>
                @else
                  <span class="status-badge copyright">فشل</span>
                @endif
              </td>
              <td>{{ $row['message'] ?? '—' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endif

@push('scripts')
<script>
  document.getElementById('csvFile')?.addEventListener('change', function () {
    const file = this.files?.[0];
    const error = document.getElementById('csvFileError');
    error.textContent = '';
    error.classList.remove('admin-field-error');

    if (file && !file.name.toLowerCase().endsWith('.csv')) {
      error.textContent = 'يجب اختيار ملف بصيغة CSV فقط.';
      error.classList.add('admin-field-error');
      this.value = '';
    }
  });
</script>
@endpush
@endsection
