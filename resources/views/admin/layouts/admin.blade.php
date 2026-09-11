<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'لوحة التحكم - مكتبتي')</title>
<meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="{{ asset_min('assets/css/fonts.css') }}">
<link rel="stylesheet" href="{{ asset_min('assets/css/vendor/fontawesome/fontawesome.css') }}">
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
