@php
    use App\Models\tblreturn;
@endphp

@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
    <div class="main p-3">
        <div class="row mb-4 align-items-center">
            <div class="col-md-9">
                <h1 class="prod_title">Sales Reports</h1>
            </div>
            <!-- Generate Sales Reports Button -->
            <div class="col-md-3 text-end">
                <button type="button" class="btn btn-cstm" id="plus-button" style="border-radius: 7px; height: 2.5rem; border: none;" onclick="generateSalesReport()">
                    <i class="bi bi-printer"></i> Generate Sales Reports
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
                        <select name="payment_method" class="form-select me-2" id="payment_method_filter">
                            <option value="">All Payment</option>
                            <option value="cash">Cash</option>
                            <option value="gcash">GCash</option>
                            <option value="bank transfer">Bank Transfer</option>
                        </select>
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search..." aria-label="Search">
                        <button class="btn custom-btn" type="button" onclick="filterTable()">Search</button>
                    </div>
                </div>

                <!-- Date Filter Form -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date_filter"><b>Filter by Date:</b></label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><b>From</b></span>
                            <input type="date" id="from_date" class="form-control me-2" required>
                            <span class="input-group-text"><b>To</b></span>
                            <input type="date" id="to_date" class="form-control me-2" required>
                            <button type="button" class="btn btn-custom" id="filter-button">Filter</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped cstm-table">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Particulars</th>
                        <th>Quantity Ordered</th>
                        <th>Unit Price</th>
                        <th>Payment</th>
                        <th>Payment Type</th>
                        <th>Reference Number</th>
                        <th>Order Date</th>
                        <th>Warranty</th>
                        <th>Sales Recipient</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderReceipts as $orderReceipt)
                        @php
                            $return = tblreturn::where('ordDet_ID', $orderReceipt->ordDet_ID)->first();
                            $warranty = $orderReceipt->warranty;
                            $warrantyUnit = 'days';

                            if ($warranty >= 30) {
                                $warranty = round($warranty / 30, 1);
                                $warrantyUnit = 'months';
                            } elseif ($warranty >= 7) {
                                $warranty = round($warranty / 7, 1);
                                $warrantyUnit = 'weeks';
                            }
                        @endphp
                        <tr>
                            <td>{{ ucwords(strtolower($orderReceipt->customer_name ?? 'N/A')) }}</td>
                            <td>{{ $orderReceipt->particulars ?? 'N/A' }}</td>
                            <td>{{ $orderReceipt->qty_order }}</td>
                            <td>₱ {{ number_format($orderReceipt->unit_price, 2) }}</td>
                            <td>₱ {{ number_format($orderReceipt->payment, 2) }}</td>
                            <td>{{ $orderReceipt->payment_type ?? 'N/A' }}</td>
                            <td>{{ $orderReceipt->reference_num ?? 'N/A' }}</td>
                            <td>{{ $orderReceipt->order_date }}</td>
                            <td>{{ $warranty }} {{ $warrantyUnit }}</td>
                            <td>{{ $salesRecipient }}</td>
                            <td>
                                @if($return)
                                    <button class="btn btn-success ongoing-btn" 
                                            data-ord-det-id="{{ $return->ordDet_ID }}"
                                            style="cursor: pointer;" 
                                            title="Click to confirm">
                                        Confirm
                                    </button>
                                @else
                                    <button type='button' class='btn btn-outline-secondary btn-repair' 
                                            {{ $orderReceipt->particulars && $orderReceipt->warranty > 0 ? '' : 'disabled' }} 
                                            onclick="showTransferAlert('{{ $orderReceipt->ordDet_ID }}')">
                                        Request Repair
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('searchInput').addEventListener('keyup', filterTable);
            document.getElementById('payment_method_filter').addEventListener('change', filterTable);
            document.getElementById('filter-button').addEventListener('click', filterTable);

            function filterTable() {
                let table = document.querySelector('.cstm-table');
                let searchInput = document.getElementById('searchInput').value.toLowerCase();
                let fromDate = document.getElementById('from_date').value ? new Date(document.getElementById('from_date').value) : null;
                let toDate = document.getElementById('to_date').value ? new Date(document.getElementById('to_date').value) : null;
                let selectedPaymentMethod = document.getElementById('payment_method_filter').value.toLowerCase().trim(); 
                let tr = table.getElementsByTagName('tr');

                for (let i = 1; i < tr.length; i++) {
                    let td = tr[i].getElementsByTagName('td');
                    let showRow = true;

                    // Search input filtering
                    if ((td[0] && td[0].textContent.toLowerCase().indexOf(searchInput) === -1) && 
                        (td[1] && td[1].textContent.toLowerCase().indexOf(searchInput) === -1)) {
                        showRow = false;
                    }

                    // Payment method filtering
                    let tdPaymentType = td[5];
                    if (tdPaymentType) {
                        let paymentTypeText = tdPaymentType.textContent.toLowerCase().trim(); 
                        if (selectedPaymentMethod && paymentTypeText !== selectedPaymentMethod) {
                            showRow = false;
                        }
                    }

                    // Date filtering
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
        function generateSalesReport() {
            let fromDate = document.getElementById('from_date').value;
            let toDate = document.getElementById('to_date').value;

            if (fromDate && toDate) {
                let params = new URLSearchParams({
                    from_date: fromDate,
                    to_date: toDate,
                    download: true 
                });

                fetch("{{ route('generateSalesReport') }}?" + params.toString())
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                if (data.error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'No Records Found',
                                        text: data.error,
                                        confirmButtonText: 'Okay'
                                    });
                                }
                            });
                        } else {
                            window.location.href = "{{ route('generateSalesReport') }}?" + params.toString();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while generating the report. Please try again later.',
                            confirmButtonText: 'Okay'
                        });
                    });
            } else {
                // Show SweetAlert for invalid date range
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Date Range',
                    text: 'Please select a valid date range before generating the report.',
                    confirmButtonText: 'Okay'
                });
            }
        }

        function toggleFilter() {
            var filterDropdown = document.getElementById("payment_method_filter");
            filterDropdown.style.display = (filterDropdown.style.display === "none" || filterDropdown.style.display === "") ? "block" : "none";
        }

        function showTransferAlert(ordDet_ID) {
            Swal.fire({
                title: 'Confirmation',
                text: "Are you sure you want to request a repair for this item?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, request!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Add the logic to request a repair for the item here
                }
            });
        }
    </script>
@endsection
