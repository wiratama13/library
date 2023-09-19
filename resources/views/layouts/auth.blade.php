<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Library') }}</title>

    <!-- Fonts -->
    @include('includes.style')
    @stack('styles')
    <!-- Scripts -->
  
</head>
<body>
     <div id="app">

        @yield('content')

          
    @include('includes.script')
</body>
</html>
