<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @include('layouts.head')
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="layout-wrapper">
        @include('layouts.topbar')

        <!-- App Menu -->
        <div class="app-menu navbar-menu">
            @include('layouts.sidebar')
        </div>

        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="page-content">
                <!-- Page Heading -->
                @if (isset($header))
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                {{ $header }}
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>

            @include('layouts.footer')
        </div>
    </div>

    @include('layouts.customizer')
    @include('layouts.script')
</body>
</html>