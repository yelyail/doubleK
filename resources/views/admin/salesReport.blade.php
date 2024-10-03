@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
    <div class="main p-3">
        <div class="row mb-4 align-items-center">
            <div class="col-md-9">
                <h1 class="prod_title">Sales Reports</h1>
            </div>
            <div class="col-md-3 text-end">
                <button type="button" class="btn btn-custom" id="plus-button" style="border-radius: 7px; height: 2.3rem; border: none;">
                    <i class="bi bi-printer"></i> Generate Reports
                </button>
            </div>
        </div>

        <div class="container mt-4">
            <div class="row mb-2 align-items-center">
                <div class="col-md-6 mt-4">
                    <div class="input-group search-bar">
                        <span class="input-group-text" id="basic-addon1" style="cursor: pointer; font-size:20px" onclick="toggleFilter()">
                            <i class="bi bi-filter-left"></i>
                        </span>
                        
                        <select name="payment_method" class="form-select me-2" id="payment_method_filter" style="display: none;">
                            <option value="">Filter Payment</option>
                            <option value="cash">Cash</option>
                            <option value="gcash">GCash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>

                        <!-- Search Input -->
                        <input type="text" id="searchInput" class="form-control" placeholder="Search..." aria-label="Search">
                        
                        <!-- Search Button -->
                        <button class="btn custom-btn" type="button" onclick="filterTable()">Search</button>
                    </div>
                </div>

                <!-- Filter by Date -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date_filter"><b>Filter by Date:</b></label>
                        <form method="get">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><b>From</b></span>
                                <input type="date" name="from_date" class="form-control me-2" placeholder="From Date">
                                <span class="input-group-text"><b>To</b></span>
                                <input type="date" name="to_date" class="form-control" placeholder="To Date">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Report Table -->
        <div class="table-responsive">
            <table class="table table-striped custom-table">
                <thead>
                    <tr>
                        <th>Particulars</th>
                        <th>Quantity Sold</th>
                        <th>Unit Price</th>
                        <th>Total Sales</th>
                        <th>Transaction Date</th>
                        <th>Payment Method</th>
                        <th>Customer Name</th>
                        <th>Sales Recipient</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated here -->
                </tbody>
            </table>
        </div>
    </div>

<script>
    function toggleFilter() {
        var filterDropdown = document.getElementById("payment_method_filter");
        // Toggle between 'none' and 'block' to show or hide the dropdown
        if (filterDropdown.style.display === "none" || filterDropdown.style.display === "") {
            filterDropdown.style.display = "block";
        } else {
            filterDropdown.style.display = "none";
        }
    }
</script>
@endsection
