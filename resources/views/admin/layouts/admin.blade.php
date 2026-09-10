<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'لوحة التحكم - مكتبتي')</title>
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="{{ asset_min('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/admin.css') }}">
@stack('styles')
</head>
<body class="admin-body">

@include('partials.loader')

<div class="admin-sidebar-backdrop" id="adminSidebarBackdrop"></div>

<div class="admin-shell">
  @include('admin.partials.admin-sidebar')

  <div class="admin-content">

    @include('admin.partials.admin-topbar')

    <main class="admin-main">
      @if (session('status'))
        <div class="admin-alert success"><i class="fa-solid fa-circle-check"></i> {{ session('status') }}</div>
      @endif
      @if ($errors->any())
        <div class="admin-alert error">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      @yield('content')
    </main>
  </div>
</div>

@stack('modals')

<script src="{{ asset_min('assets/js/admin.js') }}"></script>
@stack('scripts')
</body>
</html>
