<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    @include('includes.style')
    @stack('styles')
    <!-- Scripts -->
  
</head>
<body>
     <div id="app">

       <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
        <!-- Menu -->

        @include('includes.sidebar')
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

         @include('includes.navbar')

          <!-- / Navbar -->

    
        @yield('content')

           @include('includes.footer')

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
      </div>
    </div>
    @include('includes.script')
    @stack('script')
</body>
</html>
