<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>
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
                <li class="sidebar-item"><a href="{{ route('adminDashboard') }}" class="sidebar-link"><i class="bi bi-house"></i><span>Dashboard</span></a></li>
                <li class="sidebar-item"><a href="{{ route('adminInventory') }}" class="sidebar-link"><i class="bi bi-box"></i><span>Inventory</span></a></li>
                <li class="sidebar-item"><a href="{{ route('adminSupplier') }}" class="sidebar-link"><i class="bi bi-layers-half"></i><span>Supplier</span></a></li>
                <li class="sidebar-item"><a href="{{ route('adminOrder') }}" class="sidebar-link"><i class="bi bi-cart"></i><span>Order</span></a></li>
                <li class="sidebar-item"><a href="{{ route('adminService') }}" class="sidebar-link"><i class="bi bi-gear"></i><span>Services</span></a></li>
                <li class="sidebar-item"><a href="{{ route('adminReservation') }}" class="sidebar-link"><i class="bi bi-file-earmark-text"></i><span>Reservation</span></a></li>
                <li class="sidebar-item reports">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-clipboard-data"></i><span>Reports</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li class="sidebar-item"><a href="{{ route('adminSalesReport') }}"><i class="bi bi-dot"></i>Sales Reports</a></li>
                        <li class="sidebar-item"><a href="{{ route('adminInventoryReport') }}"><i class="bi bi-dot"></i>Inventory Reports</a></li>
                    </ul>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="#" class="sidebar-link"><i class="bi bi-box-arrow-left"></i><span>Logout</span></a>
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
</body>
</html>
