@extends('user.side')

@section('title', 'Double-K Computer')

@section('content')
<div class="main">
    <div class="text">
        <h1 class="prod_title">Sales Transaction</h1>
    </div>
    <div class="container mt-5">
        <!-- Progress Bar -->
        <div class="progress-container">
            <div class="progress-line"></div>
            <div class="progress-line-active" id="progressLine" style="width: 33%; left: 0;"></div>
            <div class="progress-step active" data-step="1">
                <div class="step-icon"><i class="bi bi-cart"></i></div>
                <div class="progress-label">Order</div>
            </div>
            <div class="progress-step" data-step="2">
                <div class="step-icon"><i class="bi bi-person"></i></div>
                <div class="progress-label">Customer Information</div>
            </div>
            <div class="progress-step" data-step="3">
                <div class="step-icon"><i class="bi bi-check-circle"></i></div>
                <div class="progress-label">Preview & Confirm</div>
            </div>
        </div>

        <!-- Step 1: Order Section -->
        <div class="step step-1 active">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6 mt-4">
                    <div class="input-group search-bar mt-3">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search..." aria-label="Search">
                        <button class="btn custom-btn" type="button" onclick="filterTable()">Search</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="categoryfilter" class="form-label">Filter</label>
                    <select class="form-control" id="categoryfilter" name="categoryfilter" onchange="filterCategory()">
                        <option value="product" selected>Product</option>
                        <option value="services">Services</option>
                        <option value="custDebt">Customer Credit</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7">
                    <div class="card card-cstm-bt mt-4">
                        <div id="productTable">
                            <div class="table-responsive">
                                <table class="table table-striped custom-table">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Category Name</th>
                                            <th>Description</th>
                                            <th>Stocks</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            @if ($product->archived == 0 && $product->inventory->stock_qty > 0)
                                                <tr data-item-id="{{ $product->product_id }}" data-item-type="product">
                                                    <td>{{ ucwords(strtolower($product->product_name)) }}</td>
                                                    <td>{{ ucwords(strtolower($product->categoryName)) }}</td>
                                                    <td>{{ ucwords(strtolower($product->product_desc)) }}</td>
                                                    <td>{{ $product->inventory->stock_qty }}</td>
                                                    <td>₱ {{ number_format($product->unit_price, 2) }}</td>
                                                    <td>
                                                        <button class="btn btn-success btn-sm add-product" data-bs-toggle="modal" data-bs-target="#productModal"
                                                                data-item-id="{{ $product->product_id }}" data-item-type="product"
                                                                data-name="{{ ucwords(strtolower($product->product_name)) }}"
                                                                data-category="{{ ucwords(strtolower($product->categoryName)) }}"
                                                                data-desc="{{ ucwords(strtolower($product->product_desc)) }}"
                                                                data-price="{{ $product->unit_price }}">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Services Table (Initially Hidden) -->
                        <div id="serviceInput" style="display: none;">
                            <div class="table-responsive">
                                <table class="table table-striped custom-table">
                                    <thead>
                                        <tr>
                                            <th>Service Name</th>
                                            <th>Description</th>
                                            <th>Service Fee</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($services as $service)
                                            @if ($service->service_status == 0)
                                                <tr data-item-id="{{ $service->service_ID }}" data-item-type="service">
                                                    <td>{{ ucwords(strtolower($service->service_name)) }}</td>
                                                    <td>{{ ucwords(strtolower($service->description)) }}</td>
                                                    <td>₱ {{ number_format($service->service_fee, 2) }}</td>
                                                    <td>
                                                        <button class="btn btn-success btn-sm add-service" 
                                                                data-item-id="{{ $service->service_ID }}" data-item-type="service"
                                                                data-name="{{ ucwords(strtolower($service->service_name)) }}" 
                                                                data-fee="{{ $service->service_fee }}">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Customer Debt Table (Initially Hidden) -->
                        <div id="reserve" style="display: none;">
                            <div class="table-responsive">
                                <table class="table table-striped custom-table">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Particulars</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Initial Payment</th>
                                            <th>Remaining Balance</th>
                                            <th>Reserved/Debt Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-md-5">
                    <div class="card card-cstm-btn mt-4">
                        <div class="card-header">
                            <h5 class="card-title-text"><b>Order Summary</b></h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <thead class="orderSummaryHead">
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Void</th>
                                    </tr>
                                </thead>
                                <tbody id="orderSummaryBody1">
                                    <!-- Order items will be appended here -->
                                </tbody>
                            </table>
                            <div class="card-title-text text-end mt-4">
                                <strong>Total Amount:</strong> 
                                <span id="totalAmount">₱ 0.00</span>
                            </div>
                            <div class="text-end mt-4">
                                <button id="nextToCustomerInfo" class="btn btn-success" disabled>Place Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Customer Information -->
        <div class="step step-2" style="display: none;">
            <div class="container mt-5">
                <form id="orderForm">
                    @csrf
                    <h3 class="prod_name">Personal Details</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="custName" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="custName" name="custName" placeholder="Fullname (Optional)">
                                
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Address (Optional)">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="deliveryMethod" class="form-label">Delivery Option</label>
                                <select class="form-control" id="deliveryMethod" name="deliveryMethod" required>
                                    <option value="" disabled selected>Choose a delivery option</option>
                                    <option value="deliver">Home delivery</option>
                                    <option value="pickup">Walk-In</option>
                                </select>
                                <div id="deliverDate" style="display: none;">
                                    <label for="deliveryDate" class="form-label">Delivery Date</label>
                                    <input type="date" class="form-control" id="deliveryDate" name="deliveryDate">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="paymentMethod" class="form-label">Payment Method</label>
                                <select class="form-control" id="paymentMethod" name="paymentType" required>
                                    <option value="" disabled selected>Choose a payment method</option>
                                    <option value="cash">Cash</option>
                                    <option value="gcash">GCash</option>
                                    <option value="banktransfer">Bank Transfer</option>
                                </select>
                            </div>

                            <!-- Cash -->
                            <div id="cashAmountInput" style="display: none;">
                                <label for="cashAmount" class="form-label">Cash Amount</label>
                                <input type="number" class="form-control" id="cashAmount" name="cashPayment" placeholder="Enter the total amount" required>
                            </div>

                            <!-- GCash Transfer -->
                            <div id="gcashDetailsInput" style="display: none;">
                                <label for="senderName" class="form-label">Sender Name</label>
                                <input type="text" class="form-control" id="senderName" name="gcashCustomerName" placeholder="Full Name">
                                
                                <label for="gcashAmount" class="form-label">Amount</label>
                                <input type="number" class="form-control" id="gcashAmount" name="gcashPayment" placeholder="GCash amount" required>

                                <label for="referenceNum" class="form-label">Reference Number</label>
                                <input type="number" class="form-control" id="referenceNum" name="gcashReferenceNum" placeholder="GCash reference number" required>
                            </div>

                            <!-- Bank Transfer -->
                            <div id="bankTransferDetails" style="display: none;">
                                <label for="bankName" class="form-label">Bank Name</label>
                                <input type="text" class="form-control" id="bankName" name="bankPaymentType" placeholder="Bank Name">
                                
                                <label for="accHold" class="form-label">Account Holder</label>
                                <input type="text" class="form-control" id="accHold" name="bankCustomerName" placeholder="Account Name">
                                
                                <label for="amount" class="form-label">Transaction Amount</label>
                                <input type="number" class="form-control" id="amount" name="bankPayment" required>
                                
                                <label for="transactDate" class="form-label">Transaction Date</label>
                                <input type="date" class="form-control" id="transactDate" name="bankTransactionDate">

                                <label for="transactRef" class="form-label">Transaction Reference</label>
                                <input type="number" class="form-control" id="transactRef" name="bankReferenceNum" placeholder="Transaction Reference" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="backToOrder" type="button" class="btn btn-secondary btn-medium" style=" width:100px; margin-right: 10px;">Back to Product</button>
                        <button id="confirmPay"  class="btn btn-success btn-medium" style="width:100px;">Confirm Payment</button>                    
                    </div>
                </form>
            </div>
        </div>

       <!-- Step 3: Confirmation -->
        <div class="step step-3" style="display: none;">
            <div class="container">
                <div class="order-confirmation">
                    <div class="order-details mt-4">
                        <div class="grand-total-container">
                            <span class="order-total">Grand Total:</span>
                            <span class="order-peso" id="totalConfirmation">₱0.00</span>
                        </div>
                        <div class="buttons">
                            <button type="submit" id="placeOrderButton" class="place-btn">Place Order</button>
                            <button type="submit" id="reservationButton" class="place-btn-1">Make Reservation</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h3><b>Customer Information</b></h3>
                                <p class="orderinfo" id="finalCustomerName"><b>Customer Name</b></p>
                            </div>
                            <div class="col-md-6">
                                <h3><b>Delivery Address</b></h3>
                                <p class="orderinfo" id="displayAddress"></p>
                                <p class="orderinfo" id="displayDeliveryMethod"></p>
                                <p class="orderinfo" id="displayDeliveryDate"></p>
                            </div>
                        </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h3><b>Payment Method</b></h3>
                                    <p class="orderinfo" id="displayPaymentMethod"></p>
                                    <p class="orderinfo" id="displayPaymentDetails"></p>
                                    <p class="orderinfo" id="displayChange"></p>
                                </div>
                                <div class="col-md-6">
                                    <h3><b>Billing Address</b></h3>
                                    <p class="orderinfo" id="displayBillingAddress"><b>Billing Address</b></p>
                                    <p class="orderinfo" id="displayBillingDate">Transaction Date</p>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button id="backToCustomerInfo" type="button" class="btn btn-secondary btn-medium" style="width:100px;">Back</button>
                            </div>
                        </div>
                    </div>

                    <div class="card card-cstm mt-5 p-3">
                        <h2>Order Summary</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Particulars</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="orderSummaryBody">
                                <!-- Order summary details here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> 
            </div>
            <div class="modal-body">
                <input type="hidden" id="modalProductId">
                <h3 class="prod_name" id="modalProductName"></h3>
                <h5 class="prod_cat" id="modalProductCategory"></h5>
                <p class="prod_desc" id="modalProductDesc"></p>
                <h3 class="mt-5"><strong>Price:</strong> ₱ <span id="modalProductPrice"></span></h3>
                <div class="quantity">
                    <label for="quantity">Quantity:</label>
                    <input type="number" class="format-control-prod" id="quantity" name="qty_order" value="1" min="1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="addProductButton">Add Product</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/order.js') }}"></script>
@endsection