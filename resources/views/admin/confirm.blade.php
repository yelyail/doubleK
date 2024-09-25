@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
<div class="main p-2">
    <h1 class="prod_title">Order Confirmation</h1> 
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-cstm p-3">
                    <div class="row mb-3 align-items-center">
                        <div class="container mt-4">
                            <div class="progress-container">
                                <div class="progress-line"></div>
                                <div class="progress-line-active" id="progressLine" style="width: 0%; left: 0;"></div>

                                <div class="progress-step active" data-route="{{ route('adminOrder') }}" data-index="0">
                                    <div class="step-icon"><i class="bi bi-cart"></i></div>
                                    <div class="progress-label">Order</div>
                                </div>

                                <div class="progress-step active" data-route="{{ route('adminCustInfo') }}" data-index="1">
                                    <div class="step-icon"><i class="bi bi-person"></i></div>
                                    <div class="progress-label">Customer Information</div>
                                </div>

                                <div class="progress-step active" data-route="{{ route('adminConfirm') }}" data-index="3">
                                    <div class="step-icon"><i class="bi bi-check-circle"></i></div>
                                    <div class="progress-label">Preview & Confirm</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="order-confirmation">
                            <h2></h2>
                            <div class="order-details">
                                <span class="order-total">Order Total:</span>
                                <span class="order-peso" id="orderTotal">₱0.00</span>
                                <button class="place-btn">Place Order</button>
                                <button class="place-btn-1">Make Reservation</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h3><b>Customer Information</b></h3>
                            <h4 class="orderinfo" id="displayCustName"><b>customer name</b></h4>
                        </div> 
                        <div class="col-md-6">
                            <h3><b>Delivery Address</b></h3>
                            <h4 class="orderinfo" id="displayAddress"></h4>
                            <h4 class="orderinfo" id="displayDeliveryMethod"></h4>
                            <h4 class="orderinfo" id="displayDeliveryDate"></h4>
                        </div>
                        <div class="col-md-6 mb-2">
                            <h3><b>Payment Method</b></h3>
                            <h4 class="orderinfo" id="displayPaymentMethod"><b></b></h4>
                            <h4 class="orderinfo" id="displayPaymentDetails"></h4><br>
                        </div>
                        <div class="col-md-6">
                            <h3><b>Billing Address</b></h3>
                            <h4 class="orderinfo" id="displayBillingAddress"><b>Billing Address</b></h4>
                            <h4 class="orderinfo" id="displayBillingDate">Transaction Date</h4>
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <a href="{{ route('adminCustInfo') }}" class="btn btn-secondary" style="width:100px;">Back</a>
                    </div>
                </div>
                <h2>Order Summary</h2>
                <div class="card card-cstm p-3">
                    <table>
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="orderSummaryBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Section -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const progressLine = document.getElementById('progressLine');
        const steps = document.querySelectorAll('.progress-step');

        function setProgressLine() {
            let activeCount = 0;

            steps.forEach((step, index) => {
                if (step.classList.contains('active')) {
                    activeCount++;
                }
            });

            progressLine.style.width = `100%`;
            progressLine.style.left = `0%`; 
        }

        window.navigateTo = function(page, index) {
            progressLine.style.left = `${index * (100 / steps.length)}%`;
            window.location.href = page;
        };

        steps.forEach((step, index) => {
            step.onclick = function() {
                if (!step.classList.contains('active')) {
                    navigateTo(step.dataset.route, index); 
                }
            };
        });
        setProgressLine();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const customerInfo = JSON.parse(localStorage.getItem('customerInfo'));
        
        if (!customerInfo) {
            console.error("Customer information not found in localStorage!");
            return;
        }

        function toProperCase(str) {
            return str
                .toLowerCase()
                .split(' ')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        }

        document.getElementById('displayCustName').textContent = toProperCase(String(customerInfo.custName || "N/A"));
        document.getElementById('displayAddress').textContent = toProperCase(customerInfo.address || "N/A");
        document.getElementById('displayDeliveryMethod').textContent = toProperCase(customerInfo.deliveryMethod === 'deliver' ? 'Home Delivery' : 'In-store Pickup');
        document.getElementById('displayDeliveryDate').textContent = toProperCase(customerInfo.deliveryDate || "N/A");

        const paymentMethod = toProperCase(customerInfo.paymentMethod || "N/A");
        document.getElementById('displayPaymentMethod').textContent = paymentMethod; 

        let paymentDetails = "";
        if (paymentMethod === 'Cash') {
            paymentDetails = `Cash Payment: ₱${customerInfo.paymentDetails.cashPayment ? toProperCase(String(customerInfo.paymentDetails.cashPayment)) : 'N/A'}`;
        } else if (paymentMethod === 'Gcash') {
            paymentDetails = `Sender Name: ${customerInfo.paymentDetails.senderName ? toProperCase(customerInfo.paymentDetails.senderName) : 'N/A'}<br><br>
                             GCash Amount: ₱${customerInfo.paymentDetails.gcashAmount ? toProperCase(String(customerInfo.paymentDetails.gcashAmount)) : 'N/A'}<br><br>
                             Ref. No.: ${customerInfo.paymentDetails.gcashReferenceNum ? toProperCase(customerInfo.paymentDetails.gcashReferenceNum) : 'N/A'}`;
        } else if (paymentMethod === 'Banktransfer') {
            paymentDetails = `Bank: ${customerInfo.paymentDetails.bankName ? toProperCase(customerInfo.paymentDetails.bankName) : 'N/A'}<br><br>
                             Account Holder: ${customerInfo.paymentDetails.accHold ? toProperCase(customerInfo.paymentDetails.accHold) : 'N/A'}<br><br>
                             Amount: ₱${customerInfo.paymentDetails.bankPayment ? toProperCase(String(customerInfo.paymentDetails.bankPayment)) : 'N/A'}<br><br>
                             Transaction Date: ${customerInfo.paymentDetails.bankTransactionDate ? toProperCase(customerInfo.paymentDetails.bankTransactionDate) : 'N/A'}<br><br>
                             Ref. No.: ${customerInfo.paymentDetails.bankReferenceNum ? toProperCase(customerInfo.paymentDetails.bankReferenceNum) : 'N/A'}`;
        }

        // Set innerHTML to allow line breaks
        document.getElementById('displayPaymentDetails').innerHTML = toProperCase(String(paymentDetails || "N/A"));
        document.getElementById('displayBillingAddress').textContent = toProperCase(String(customerInfo.address || "N/A")); 
        document.getElementById('displayBillingDate').textContent = toProperCase(new Date().toLocaleDateString()); 
        
        const orderTotal = localStorage.getItem('orderTotal') || "0.00"; 
        document.getElementById('orderTotal').textContent = `₱${parseFloat(orderTotal).toFixed(2)}`;
    });
</script>


<!-- Order Summary -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const orderSummaryBody = document.getElementById('orderSummaryBody');
        const savedOrderItems = JSON.parse(localStorage.getItem('orderSummary'));

        if (savedOrderItems) {
            savedOrderItems.forEach(item => {
                const newRow = orderSummaryBody.insertRow();
                newRow.innerHTML = `
                    <td>${item.name}</td>
                    <td class="text-center">${item.quantity}</td>
                    <td>₱ ${item.price.toFixed(2)}</td>
                    <td>₱ ${item.total.toFixed(2)}</td>
                `;
            });
        }
    });
</script>
@endsection
