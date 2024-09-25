@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
<div class="main p-3">
    <div class="text">
        <h1 class="prod_title">Customer Information</h1>
    </div>
    <div class="container mt-5">
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

                                <div class="progress-step" data-route="{{ route('adminConfirm') }}" data-index="3">
                                    <div class="step-icon"><i class="bi bi-check-circle"></i></div>
                                    <div class="progress-label">Preview & Confirm</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container">               
                        <div class="container mt-5">
                        <form id="orderForm" action="{{ route('storeCustomer') }}" method="POST">
                            @csrf
                            <h3>Personal Details 
                                <button type="submit" class="btn btn-medium">Save</button>
                            </h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="custName" class="form-label">Customer Name</label>
                                        <input type="text" class="form-control" id="custName" name="custName" placeholder="(Optional)">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="deliveryMethod" class="form-label">Delivery Option</label>
                                        <select class="form-control" id="deliveryMethod" name="deliveryMethod" required>
                                            <option value="" disabled selected>Choose a delivery option</option>
                                            <option value="deliver">Home delivery</option>
                                            <option value="pick-up">In-store pickup</option>
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
                                        <input type="number" class="form-control" id="cashAmount" name="cashPayment" placeholder="Enter the total amount">
                                    </div>
                                    <!-- GCash Transfer -->
                                    <div id="gcashDetailsInput" style="display: none;">
                                        <label for="senderName" class="form-label">Sender Name</label>
                                        <input type="text" class="form-control" id="senderName" name="gcashCustomerName" placeholder="Enter the Sender Name">
                                        
                                        <label for="gcashAmount" class="form-label">Amount</label>
                                        <input type="number" class="form-control" id="gcashAmount" name="gcashPayment" placeholder="Enter the GCash amount">

                                        <label for="referenceNum" class="form-label">Reference Number</label>
                                        <input type="text" class="form-control" id="referenceNum" name="gcashReferenceNum" placeholder="Enter the GCash reference number">
                                    </div>
                                    <!-- Bank Transfer -->
                                    <div id="bankTransferDetails" style="display: none;">
                                        <label for="bankName" class="form-label">Bank Name</label>
                                        <input type="text" class="form-control" id="bankName" name="bankPaymentType" placeholder="Enter the Bank Name">
                                        
                                        <label for="accHold" class="form-label">Account Holder</label>
                                        <input type="text" class="form-control" id="accHold" name="bankCustomerName" placeholder="Enter the Account Name">
                                        
                                        <label for="amount" class="form-label">Transaction Amount</label>
                                        <input type="number" class="form-control" id="amount" name="bankPayment">
                                        
                                        <label for="transactDate" class="form-label">Transaction Date</label>
                                        <input type="date" class="form-control" id="transactDate" name="bankTransactionDate">

                                        <label for="transactRef" class="form-label">Transaction Reference</label>
                                        <input type="text" class="form-control" id="transactRef" name="bankReferenceNum" placeholder="Enter the Transaction Reference">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="modal-footer">
                            <a href="{{ route('adminOrder') }}" class="btn btn-secondary btn-medium" style="width:150px; margin-left: 10px;">Back to the Product</a>
                            <button id="placeCustomerButton" class="btn btn-success" style="width:100px;" disabled>Confirm Payment</button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- for the customer information -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const orderForm = document.getElementById('orderForm');
    const reviewConfirmButton = document.getElementById('placeCustomerButton');

    if (!orderForm) {
        console.error("Order form not found!");
        return;
    }

    console.log("Order Form retrieved:", orderForm);

    // Handle form submission
    orderForm.addEventListener('submit', function (event) {
        event.preventDefault();  // Prevent form from submitting immediately
        console.log("Inside submit event listener:", orderForm);

        // Retrieve values from form inputs
        const custName = document.getElementById('custName').value || null;
        const address = document.getElementById('address').value;
        const deliveryMethod = document.getElementById('deliveryMethod').value;
        const deliveryDate = document.getElementById('deliveryDate').value || null;
        const paymentMethod = document.getElementById('paymentMethod').value;

        // Log customer details for debugging
        console.log("Customer Name:", custName);
        console.log("Address:", address);
        console.log("Delivery Method:", deliveryMethod);
        console.log("Delivery Date:", deliveryDate);
        console.log("Payment Method:", paymentMethod);

        // Ensure that the required fields (address, delivery method, and payment method) are filled in
        if (!address || !deliveryMethod || !paymentMethod) {
            console.error("Required fields are missing!");
            alert("Please fill in all required fields (Address, Delivery Method, and Payment Method).");
            return;
        }

        // Payment details based on payment method
        let paymentDetails = {};
        if (paymentMethod === 'cash') {
            const cashPayment = document.getElementById('cashAmount').value || null;
            paymentDetails = { cashPayment };
            if (!cashPayment) {
                alert("Please enter the cash amount.");
                return;
            }
        } else if (paymentMethod === 'gcash') {
            const senderName = document.getElementById('senderName').value || null;
            const gcashAmount = document.getElementById('gcashAmount').value || null;
            const gcashReferenceNum = document.getElementById('referenceNum').value || null;
            paymentDetails = { senderName, gcashAmount, gcashReferenceNum };
            if (!senderName || !gcashAmount || !gcashReferenceNum) {
                alert("Please fill in all GCash payment details.");
                return;
            }
        } else if (paymentMethod === 'banktransfer') {
            const bankName = document.getElementById('bankName').value || null;
            const accHold = document.getElementById('accHold').value || null;
            const bankPayment = document.getElementById('amount').value || null;
            const bankTransactionDate = document.getElementById('transactDate').value || null;
            const bankReferenceNum = document.getElementById('transactRef').value || null;
            paymentDetails = { bankName, accHold, bankPayment, bankTransactionDate, bankReferenceNum };
            if (!bankName || !accHold || !bankPayment || !bankTransactionDate || !bankReferenceNum) {
                alert("Please fill in all Bank Transfer payment details.");
                return;
            }
        }

        const customerInfo = {
            custName,
            address,
            deliveryMethod,
            deliveryDate,
            paymentMethod,
            paymentDetails
        };

        // Store customer info in localStorage
        try {
            localStorage.setItem('customerInfo', JSON.stringify(customerInfo));
            console.log("Stored customer info:", localStorage.getItem('customerInfo')); // Log stored data
        } catch (error) {
            console.error("Error storing customer info:", error);
            return;
        }

        // Enable the review confirm button
        reviewConfirmButton.disabled = false;

        // Redirect to confirmation page after a successful save
        console.log("Redirecting to confirmation page");
        window.location.href = "{{ route('adminConfirm') }}"; // Adjust the URL as necessary
    });

    // Handle change event for delivery method
    document.getElementById('deliveryMethod').addEventListener('change', function () {
        const deliverDateContainer = document.getElementById('deliverDate');
        deliverDateContainer.style.display = (this.value === 'deliver') ? 'block' : 'none';
    });

    // Handle change event for payment method
    document.getElementById('paymentMethod').addEventListener('change', function () {
        const cashInput = document.getElementById('cashAmountInput');
        const gcashInput = document.getElementById('gcashDetailsInput');
        const bankTransferInput = document.getElementById('bankTransferDetails');

        // Hide all payment detail sections initially
        cashInput.style.display = 'none';
        gcashInput.style.display = 'none';
        bankTransferInput.style.display = 'none';

        // Show the appropriate section based on the selected payment method
        if (this.value === 'cash') {
            cashInput.style.display = 'block';
        } else if (this.value === 'gcash') {
            gcashInput.style.display = 'block';
        } else if (this.value === 'banktransfer') {
            bankTransferInput.style.display = 'block';
        }
    });
});
</script>
<!-- for progress -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const progressLine = document.getElementById('progressLine');
        const steps = document.querySelectorAll('.progress-step');

        function setProgressLine() {
            let activeCount = 0;

            steps.forEach((step) => {
                if (step.classList.contains('active')) {
                    activeCount++;
                }
            });
            progressLine.style.width = `${(activeCount / steps.length) * 100}%`;
            progressLine.style.left = `0%`;
        }

        window.navigateTo = function(page, index) {
            progressLine.style.width = `${((index + 1) / steps.length) * 100}%`;
            window.location.href = page;
        };

        steps.forEach((step, index) => {
            step.onclick = function() {
                if (!step.classList.contains('active')) {
                    navigateTo(step.dataset.route, index);
                }
            };
        });

        setProgressLine(); // Set initial progress line on page load
    });
</script>
@endsection
