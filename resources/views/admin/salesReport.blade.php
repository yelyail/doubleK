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
                        <select name="payment_method" class="form-select me-2" id="payment_method_filter" onchange="filterByPaymentMethod()">
                            <option value="">All Payment</option>
                            <option value="cash">Cash</option>
                            <option value="gcash">GCash</option>
                            <option value="banktransfer">Bank Transfer</option>
                        </select>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search..." aria-label="Search">
                        <button class="btn custom-btn" type="button" onclick="filterTable()">Search</button>
                    </div>
                </div>

            <!-- Filter by Date -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="date_filter"><b>Filter by Date:</b></label>
                        <form id="dateFilterForm">
                            <div class="input-group md-3">
                                <span class="input-group-text"><b>From</b></span>
                                <input type="date" id="from_date" name="from_date" class="form-control me-2" placeholder="From Date">
                                <span class="input-group-text"><b>To</b></span>
                                <input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date">
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
                        <th>Customer Name</th>
                        <th>Particulars</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                        <th>Amount Paid</th>
                        <th>Payment Method</th>
                        <th>Reference Number</th>
                        <th>Transaction Date</th>
                        <th>Warranty</th>
                        <th>Sales Recipient</th>
                        <th>Request Repair</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderReceipts as $orderReceipt)
                        <tr>
                            <td>{{ ucwords(strtolower($orderReceipt->customer_name ?? 'N/A' ))}}</td>
                            <td>{{ ucwords(strtolower($orderReceipt->particulars ?? 'N/A')) }}</td>
                            <td>{{ $orderReceipt->qty_order }}</td>
                            <td>{{ $orderReceipt->unit_price }}</td>
                            <td>{{ $orderReceipt->total_price }}</td>
                            <td>{{ $orderReceipt->payment ?? 'N/A' }}</td>
                            <td>{{ $orderReceipt->payment_type ?? 'N/A' }}</td>
                            <td>{{ $orderReceipt->reference_num ?? 'N/A' }}</td>
                            <td>{{ $orderReceipt->order_date }}</td>
                            <td>{{ $orderReceipt->warranty ?? 'N/A' }}</td>
                            <td>{{ $salesRecipient }}</td>
                            <td>
                                <button type="button" class="btn btn-outline-secondary btn-repair" 
                                        {{ $orderReceipt->particulars && $orderReceipt->warranty > 0 ? '' : 'disabled' }}>
                                    Request Repair
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('assets/js/salesReport.js') }}"></script>
@endsection
