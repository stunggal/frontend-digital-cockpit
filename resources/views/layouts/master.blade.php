<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-sidebar="light"
    data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-bs-theme="light" data-topbar="light">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')| Velzon - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />

    <!-- PWA -->
    <meta name="theme-color" content="#6777ef">
    <link rel="apple-touch-icon" href="{{ asset('logo.PNG') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico') }}">
    @include('layouts.head-css')
</head>

@section('body')
    @include('layouts.body')
@show
<!-- Begin page -->
<div id="layout-wrapper">
    @include('layouts.topbar')
    @include('layouts.sidebar')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        @include('layouts.footer')
    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->

{{-- @include('layouts.customizer') --}}

<!-- JAVASCRIPT -->
@include('layouts.vendor-scripts')
<script src="{{ asset('/sw.js') }}"></script>
<script>
    // Hanya daftarkan jika browser mendukung Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js').then(function(registration) {
                // Pendaftaran berhasil
                console.log('Service Worker berhasil didaftarkan: ', registration.scope);
            }, function(err) {
                // Pendaftaran gagal
                console.log('Service Worker gagal didaftarkan: ', err);
            });
        });
    }
</script>
</body>

</html>
