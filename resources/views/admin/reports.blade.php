@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
    <div class="main p-3">
        <div class="row mb-4 align-items-center">
            <div class="col-md-9">
                <h1 class="prod_title">Inventory Reports</h1>
            </div>
            <div class="col-md-3 text-end">
            <button type="button" class="btn btn-custom" id="plus-button" style="border-radius: 7px; height: 2.3rem; border: none;" onclick="generateInventoryReport()">
                <i class="bi bi-printer"></i> Generate Inventory Reports
            </button>
        </div>
        </div>

        <div class="container mt-4">
            <div class="row mb-2 align-items-center">
                <div class="col-md-6 mt-4">
                    <div class="input-group search-bar">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search..." aria-label="Search">
                        <button class="btn custom-btn" type="button" onclick="filterTable()">Search</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date_filter"><b>Filter by Date:</b></label>
                        <form id="dateFilterForm" onsubmit="filterInventoryReport(event)">
                            <div class="input-group md-3">
                                <span class="input-group-text"><b>From</b></span>
                                <input type="date" id="from_date" name="from_date" class="form-control me-2" placeholder="From Date" required>
                                <span class="input-group-text"><b>To</b></span>
                                <input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" required>
                                <button type="submit" class="btn btn-custom">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped cstm-table">
                <thead>
                    <tr>
                        <th>Product Reference</th>
                        <th>Category</th>
                        <th>Product Name</th>
                        <th>Supplier Name</th>
                        <th>Current Stock</th>
                        <th>Quantity Sold</th>
                        <th>Price</th>
                        <th>Date Added</th>
                        <th>Restock Date</th>
                        <th>Return Date</th>
                        <th>Return Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td style="text-align:center">{{ $loop->iteration }}</td>
                            <td>{{ ucwords(strtolower($product->categoryName ?? 'N/A')) }}</td>
                            <td>{{ ucwords(strtolower($product->product_name ?? 'N/A')) }}</td>
                            <td>{{ ucwords(strtolower($product->supplier_name ?? 'N/A')) }}</td>
                            <td style="text-align:center">{{ $product->stock_qty ?? 'N/A' }}</td>
                            <td style="text-align:center">{{ $product->total_qty_sold ?? 'N/A' }}</td>
                            <td>₱ {{ number_format($product->unit_price, 2) ?? 'N/A' }}</td>
                            <td>{{ $product->prod_add ?? 'N/A' }}</td>
                            <td>{{ $product->nextRestockDate ?? 'N/A' }}</td>
                            <td>{{ $product->returnDate ?? 'N/A' }}</td>
                            <td>{{ $product->returnReason ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('searchInput').addEventListener('keyup', filterTable);
        document.getElementById('dateFilterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            filterTable(); 
        });

        function filterTable() {
            let table = document.querySelector('.cstm-table');
            console.log(table);
            if (!table) {
                console.error("Table not found");
                return; 
            }

            let searchInput = document.getElementById('searchInput').value.toLowerCase();
            let fromDate = document.getElementById('from_date').value ? new Date(document.getElementById('from_date').value) : null;
            let toDate = document.getElementById('to_date').value ? new Date(document.getElementById('to_date').value) : null;

            let tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td');
                let showRow = true;
                if ((td[1] && td[1].textContent.toLowerCase().indexOf(searchInput) === -1) && 
                    (td[2] && td[2].textContent.toLowerCase().indexOf(searchInput) === -1)) {
                    showRow = false;
                }
                let tdDate = td[7];
                if (tdDate) {
                    let rowDate = new Date(tdDate.textContent.trim());
                    if ((fromDate && rowDate < fromDate) || (toDate && rowDate > toDate)) {
                        showRow = false;
                    }
                }
                tr[i].style.display = showRow ? '' : 'none';
            }
        }
    });
    document.getElementById('plus-button').addEventListener('click', function() {
        window.location.href = "{{ route('generateInventoryReports') }}"; 
    });
</script>

@endsection
