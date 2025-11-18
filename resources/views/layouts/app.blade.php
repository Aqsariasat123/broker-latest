<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keystone Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
      <br>
  
      @yield('content')
    </div>
  </div>

  <!-- Mobile Toggle Button (onclick removed; handled in script to avoid double-call) -->
  <button class="toggle-btn" id="toggleBtn" aria-label="Toggle sidebar">☰</button>

  <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
