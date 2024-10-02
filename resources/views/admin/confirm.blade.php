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
                                <span class="order-total">Grand Total:</span>
                                <span class="order-peso" id="orderTotal">₱0.00</span>
                                

                                <form action="{{ route('storeReceipt') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="customer_id" id="customer_id" value="">
                                    <input type="hidden" name="service_ID" id="service_ID" value="">
                                    <input type="hidden" name="payment_id" id="payment_id" value="">
                                    <input type="hidden" name="user_ID" id="user_ID" value="">
                                    <input type="hidden" name="qty_order" id="qty_order" value="1">
                                    <input type="hidden" name="total_price" id="total_price" value="1000">
                                    <input type="hidden" name="order_date" id="order_date" value="{{ now() }}">
                                    <input type="hidden" name="delivery_date" id="delivery_date" value="2024-09-30">
                                    <input type="hidden" name="orderSummary" id="orderSummary" value="">
                                    
                                    <button type="submit" class="place-btn">Place Order</button>
                                </form>
                                <button class="place-btn-1">Make Reservation</button>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h3><b>Customer Information</b></h3>
                            <h4 class="orderinfo" id="displayCustName"><b>Customer Name</b></h4>
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
                                <th>Particulars</th>
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
<!-- Modal for Receipt -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Temporary Receipt</h5>
                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h3 class="orderReceiptTitle text-center">#20 Pag-Asa Street, S.I.R. Matina, Phase 2, Barangay Bucana, Davao City 8000 Philippines</h3><br><hr>
                <h4 class="orderReceipt"><b>Grand Total: ₱<span id="modalOrderTotal">0.00</span></b></h4>
                <h5 class="orderReceipt"><b>Customer Name:</b> <span id="modalCustomerName"></span></h5>
                <h5 class="orderReceipt"><b>Delivery Method:</b> <span id="modalDeliveryMethod"></span></h5>
                <h5 class="orderReceipt"><b>Payment Method:</b> <span id="modalPaymentMethod"></span></h5>
                <h5 class="orderReceipt"><b>Delivery Date:</b> <span id="modalDeliveryDate"></span></h5>
                <hr>
                <h4>Order Summary</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="modalOrderSummary">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmOrderBtn">Print Receipt</button>
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
<!-- display here the information -->
<script>
    function toProperCase(str) {
        return str
            .toLowerCase()
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const customerInfo = JSON.parse(localStorage.getItem('customerInfo'));

        if (!customerInfo) {
            return; // Exit if customerInfo is not found
        }

        // Displaying the existing customer information
        document.getElementById('displayCustName').textContent = toProperCase(String(customerInfo.custName || "N/A"));
        document.getElementById('displayAddress').textContent = toProperCase(customerInfo.address || "N/A");

        const deliveryMethod = customerInfo.deliveryMethod === 'deliver' ? 'Home Delivery' : 'In-store Pickup';
        document.getElementById('displayDeliveryMethod').textContent = toProperCase(deliveryMethod);

        const currentDate = new Date().toISOString().split('T')[0];

        if (customerInfo.deliveryMethod === 'deliver') {
            document.getElementById('displayDeliveryDate').textContent = toProperCase(customerInfo.deliveryDate || "N/A");
            document.getElementById('delivery_date').value = customerInfo.deliveryDate || currentDate;
        } else {
            document.getElementById('displayDeliveryDate').textContent = toProperCase(currentDate);
            document.getElementById('delivery_date').value = currentDate;
        }

        document.getElementById('displayBillingDate').textContent = toProperCase(customerInfo.orderDate || currentDate);
        document.getElementById('order_date').value = currentDate;

        const paymentMethod = toProperCase(customerInfo.paymentMethod || "N/A");
        document.getElementById('displayPaymentMethod').textContent = paymentMethod;

        let paymentDetails = "";
        if (paymentMethod === 'Cash') {
            paymentDetails = `Cash Payment: ₱${customerInfo.paymentDetails?.cashPayment ? toProperCase(String(customerInfo.paymentDetails.cashPayment)) : 'N/A'}`;
        } else if (paymentMethod === 'Gcash') {
            paymentDetails = `Sender Name: ${customerInfo.paymentDetails?.senderName ? toProperCase(customerInfo.paymentDetails.senderName) : 'N/A'}<br><br>
                            GCash Amount: ₱${customerInfo.paymentDetails?.gcashAmount ? toProperCase(String(customerInfo.paymentDetails.gcashAmount)) : 'N/A'}<br><br>
                            Ref. No.: ${customerInfo.paymentDetails?.gcashReferenceNum ? toProperCase(customerInfo.paymentDetails.gcashReferenceNum) : 'N/A'}`;
        } else if (paymentMethod === 'BankTransfer') {
            paymentDetails = `Bank: ${customerInfo.paymentDetails?.bankName ? toProperCase(customerInfo.paymentDetails.bankName) : 'N/A'}<br><br>
                            Account Holder: ${customerInfo.paymentDetails?.accHold ? toProperCase(customerInfo.paymentDetails.accHold) : 'N/A'}<br><br>
                            Amount: ₱${customerInfo.paymentDetails?.bankPayment ? toProperCase(String(customerInfo.paymentDetails.bankPayment)) : 'N/A'}<br><br>
                            Transaction Date: ${customerInfo.paymentDetails?.bankTransactionDate ? toProperCase(customerInfo.paymentDetails.bankTransactionDate) : 'N/A'}<br><br>
                            Ref. No.: ${customerInfo.paymentDetails?.bankReferenceNum ? toProperCase(customerInfo.paymentDetails.bankReferenceNum) : 'N/A'}`;
        }

        document.getElementById('displayPaymentDetails').innerHTML = toProperCase(String(paymentDetails || "N/A"));
        document.getElementById('displayBillingAddress').textContent = toProperCase(String(customerInfo.address || "N/A"));

        document.getElementById('customer_id').value = customerInfo.customerId || '';
        document.getElementById('service_ID').value = customerInfo.serviceID || '';
        document.getElementById('payment_id').value = customerInfo.paymentMethod || '';
        document.getElementById('user_ID').value = customerInfo.userId || '';
        document.getElementById('qty_order').value = customerInfo.orderQuantity || '1';
        document.getElementById('total_price').value = customerInfo.totalPrice || '0';

        const orderTotal = localStorage.getItem('orderTotal') || "0.00";
        document.getElementById('orderTotal').textContent = `₱${parseFloat(orderTotal).toFixed(2)}`;

        // Now, we set the displayed data back to localStorage
        localStorage.setItem('customerInfo', JSON.stringify({
            custName: document.getElementById('displayCustName').textContent,
            address: document.getElementById('displayAddress').textContent,
            deliveryMethod: document.getElementById('displayDeliveryMethod').textContent.toLowerCase() === 'home delivery' ? 'deliver' : 'pickup',
            deliveryDate: document.getElementById('delivery_date').value,
            orderDate: document.getElementById('order_date').value,
            paymentMethod: document.getElementById('displayPaymentMethod').textContent,
            paymentDetails: customerInfo.paymentDetails,
            totalPrice: document.getElementById('total_price').value,
            customerId: document.getElementById('customer_id').value,
            serviceID: document.getElementById('service_ID').value,
            userId: document.getElementById('user_ID').value,
            orderQuantity: document.getElementById('qty_order').value
        }));
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
<!-- for receipt -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const placeOrderBtn = document.querySelector('.place-btn');
        
        placeOrderBtn.addEventListener('click', function (e) {
            e.preventDefault(); 

            Swal.fire({
                title: 'Confirm Order',
                text: "Are you sure you want to place this order?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, confirmed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, submit the form
                    this.closest('form').submit();
                }
            });
        });
    });
</script>
@endsection
