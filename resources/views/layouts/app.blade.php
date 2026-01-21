<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Keystone Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="container-custom">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
      @include('partials.sidebar')
    </div>
   
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
      <!-- Global Page Header -->
      <div class="page-header">
        <div class="page-header-left">
          <button class="toggle-btn" id="toggleBtn" aria-label="Toggle sidebar">
            <span class="toggle-icon-open">☰</span>
            <span class="toggle-icon-close">✕</span>
          </button>
          <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div class="page-header-right">
          @if(auth()->user()->image)
            <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="Profile" class="header-avatar">
          @else
            <div class="header-avatar header-avatar-initials">
              {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
          @endif
          <a href="{{ route('logout') }}" class="header-logout" title="Logout">
            <i class="fa-solid fa-right-from-bracket"></i>
          </a>
        </div>
      </div>

      @yield('content')
    </div>
  </div>

  <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
