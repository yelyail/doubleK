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
            <div class="col-md-3 text-end">
                <button type="button" class="btn btn-custom" id="plus-button" style="border-radius: 7px; height: 2.3rem; border: none;">
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
                        <input type="text" id="searchInput" class="form-control" placeholder="Search..." aria-label="Search">
                        <button class="btn custom-btn" type="button" id="searchButton">Search</button>
                    </div>
                </div>

                <!-- Filter by Date -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date_filter"><b>Filter by Date:</b></label>
                        <form id="dateFilterForm" method="GET" onsubmit="filterSalesReport(event)">
                            <div class="input-group mb-3">
                                <span class="input-group-text"><b>From</b></span>
                                <input type="date" id="from_date" name="from_date" class="form-control me-2" required>
                                <span class="input-group-text"><b>To</b></span>
                                <input type="date" id="to_date" name="to_date" class="form-control" required>
                                <button type="submit" class="btn btn-custom" id="filterButton">Filter</button>
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
                        <tr>
                            <td>{{ ucwords(strtolower($orderReceipt->customer_name ?? 'N/A')) }}</td>
                            <td>{{ $orderReceipt->particulars ?? 'N/A' }}</td>
                            <td>{{ $orderReceipt->qty_order }}</td>
                            <td>₱ {{ number_format($orderReceipt->unit_price, 2) }}</td>
                            <td>₱ {{ number_format($orderReceipt->payment, 2) }}</td>
                            <td>{{ $orderReceipt->payment_type ?? 'N/A' }}</td>
                            <td>{{ $orderReceipt->reference_num ?? 'N/A' }}</td>
                            <td>{{ $orderReceipt->order_date }}</td>
                            <td>
                                @php
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
                                {{ $warranty }} {{ $warrantyUnit }}
                            </td>
                            <td>{{ $salesRecipient }}</td>
                            <td>
                                @php
                                    $return = tblreturn::where('ordDet_ID', $orderReceipt->ordDet_ID)->first();
                                @endphp
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

    <script src="{{ asset('assets/js/salesReport.js') }}"></script>
    
    <script>
        function filterSalesReport(event) {
            event.preventDefault(); 

            const fromDate = document.getElementById('from_date').value;
            const toDate = document.getElementById('to_date').value;
            if (!fromDate || !toDate) {
                alert('Please select both From and To dates.');
                return;
            }
            fetch(`{{ route('generateSalesReport') }}?from_date=${fromDate}&to_date=${toDate}`, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('tbody');
                tableBody.innerHTML = ''; 
                data.orderReceipts.forEach(orderReceipt => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${orderReceipt.customer_name}</td>
                        <td>${orderReceipt.particulars}</td>
                        <td>${orderReceipt.quantity_ordered}</td>
                        <td>₱ ${parseFloat(orderReceipt.unit_price).toFixed(2)}</td>
                        <td>₱ ${parseFloat(orderReceipt.payment).toFixed(2)}</td>
                        <td>${orderReceipt.payment_type || 'N/A'}</td>
                        <td>${orderReceipt.reference_num || 'N/A'}</td>
                        <td>${orderReceipt.order_date}</td>
                        <td>${orderReceipt.warranty}</td>
                        <td>${orderReceipt.sales_recipient}</td>
                        <td>
                            <button type="button" class="btn btn-outline-secondary btn-repair" 
                                    ${orderReceipt.particulars && orderReceipt.warranty > 0 ? '' : 'disabled'}>
                                Request Repair
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error fetching filtered data:', error);
            });
        }

    </script>
@endsection
