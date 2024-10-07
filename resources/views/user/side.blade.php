<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <title>@yield('title', 'Double-K Computer Parts')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/table.css') }}">
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside id="sidebar">
            <div class="d-flex">
                <a href="#" id="logo-btn" data-collapse="sidebar">
                    <img src="{{ asset('assets/images/logo.jpg') }}" alt="Logo" width="85" class="pic">
                </a>
            </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item"><a href="{{ route('userDashboard') }}" class="sidebar-link"><i class="bi bi-house"></i><span>Dashboard</span></a></li>
                    <li class="sidebar-item"><a href="{{ route('userOrder') }}" class="sidebar-link"><i class="bi bi-pc-display"></i><span>Sales Transaction</span></a></li>
                    <li class="sidebar-item"><a href="{{ route('userReports') }}" class="sidebar-link"><i class="bi bi-file-earmark-text"></i> <span>Inventory Reports</span></a></li>
                </ul>
                <div class="sidebar-footer">
                    <a href="{{ route('signout')}}" class="sidebar-link"><i class="bi bi-box-arrow-left"></i><span>Logout</span></a>
                </div>
        </aside>

        <!-- Main content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Additional JS -->
    <script src="{{ asset('assets/js/style.js') }}"></script>
    <script src="{{ asset('assets/js/search.js') }}"></script>
    <script src="{{ asset('assets/js/status.js') }}"></script>
    <script src="{{ asset('assets/js/crud.js') }}"></script>
</body>
</html>
