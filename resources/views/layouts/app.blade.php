<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            overflow: hidden;
        }

        .sidebar {
            width: 260px;
            transition: width .3s ease;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar.collapsed .menu-text,
        .sidebar.collapsed .sidebar-title,
        .sidebar.collapsed .menu-arrow {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            text-align: center;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0 !important;
        }

        .sidebar-menu {
            overflow-y: auto;
        }
    </style>
</head>

<body class="bg-light">

{{-- Navbar --}}
@include('layouts.navigation')

<div class="d-flex" style="height: calc(100vh - 56px);">
    {{-- Sidebar --}}
    <aside id="sidebar" class="bg-white border-end sidebar">
        @include('layouts.sidebar')
    </aside>

    {{-- Main Content --}}
    <div class="flex-fill d-flex flex-column overflow-hidden">

        {{-- Page Heading --}}
        @isset($header)
            <header class="bg-white border-bottom shadow-sm">
                <div class="container-fluid py-3">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Page Content --}}
        <main class="flex-fill overflow-auto bg-light">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('layouts.footer')

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('toggleSidebar')
        ?.addEventListener('click', function () {
            document.getElementById('sidebar')
                .classList.toggle('collapsed');
        });
</script>

</body>
</html>
