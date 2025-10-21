<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Admin Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- AdminKit CSS --}}
    <link href="{{ asset('adminkit/css/app.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('adminkit/css/admin-settings.css') }}" />
    {{-- Custom CSS --}}
    <link href="{{ asset('adminkit/css/admin-sidebar.css') }}" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body data-old-category-id="{{ old('category_id') }}" data-old-subcategory-id="{{ old('subcategory_id') }}">

    <body data-warning="{{ session('warning') }}">

    <div class="wrapper">
        {{-- Sidebar --}}
        @include('admin.layouts.sidebar')

        <div class="main">
            {{-- Navbar --}}
            @include('admin.layouts.navbar')

            <main class="content">
                <div class="container-fluid p-0">

                    {{-- Page Title --}}
                    @hasSection('admin_page_title')
                        <h1 class="h3 mb-3">@yield('admin_page_title')</h1>
                    @endif

                    {{-- Page Content --}}
                    @yield('content')

                </div>
            </main>

            {{-- Footer --}}
            @include('admin.layouts.footer')
        </div>
    </div>

    <script src="{{ asset('adminkit/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Custom JS --}}
    <script src="{{ asset('adminkit/js/admin-custom.js') }}"></script>

    {{-- Chart.js + Datalabels Plugin --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <!-- Bootstrap JS (for dismissible alerts, modals, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
