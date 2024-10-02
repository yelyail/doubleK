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
                                            <option value="pick-up">Walk-In</option>
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
                            <div class="modal-footer">
                                <a href="{{ route('adminOrder') }}" class="btn btn-secondary btn-medium" style="width:100px; margin-left: 10px;">Back Product</a>
                                <button type="submit" class="btn btn-success btn-medium" style="width:100px; margin-left: 10px;">Confirm Payment</button>
                            </div>
                        </form>
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

        if (!orderForm) {
            console.error("Order form not found!");
            return;
        }

        // Populate form fields from localStorage on page load
        function populateForm() {
            const fields = [
                'custName', 
                'address', 
                'deliveryMethod', 
                'deliveryDate', 
                'paymentMethod',
                'cashPayment',
                'gcashCustomerName',
                'gcashPayment',
                'gcashReferenceNum',
                'bankPaymentType',
                'bankCustomerName',
                'bankPayment',
                'bankTransactionDate',
                'bankReferenceNum'
            ];

            fields.forEach(field => {
                const value = localStorage.getItem(field);
                if (value) {
                    const element = document.getElementById(field);
                    if (element) {
                        element.value = value;
                    }
                }
            });

            // Show delivery date if applicable
            if (localStorage.getItem('deliveryMethod') === 'deliver') {
                document.getElementById('deliverDate').style.display = 'block';
            }

            // Show payment details based on payment method
            const paymentMethod = localStorage.getItem('paymentMethod');
            if (paymentMethod) {
                document.getElementById('paymentMethod').value = paymentMethod;
                showPaymentDetails(paymentMethod);
            }
        }

        function showPaymentDetails(paymentMethod) {
            document.getElementById('cashAmountInput').style.display = 'none';
            document.getElementById('gcashDetailsInput').style.display = 'none';
            document.getElementById('bankTransferDetails').style.display = 'none';

            if (paymentMethod === 'cash') {
                document.getElementById('cashAmountInput').style.display = 'block';
            } else if (paymentMethod === 'gcash') {
                document.getElementById('gcashDetailsInput').style.display = 'block';
            } else if (paymentMethod === 'banktransfer') {
                document.getElementById('bankTransferDetails').style.display = 'block';
            }
        }

        orderForm.addEventListener('submit', function (event) {
            // Prevent default form submission
            event.preventDefault();

            // Save form data to localStorage
            localStorage.setItem('custName', document.getElementById('custName').value);
            localStorage.setItem('address', document.getElementById('address').value);
            localStorage.setItem('deliveryMethod', document.getElementById('deliveryMethod').value);
            localStorage.setItem('deliveryDate', document.getElementById('deliveryDate').value || '');

            // Get and store the payment method
            const paymentMethod = document.getElementById('paymentMethod').value;
            localStorage.setItem('paymentMethod', paymentMethod); // Ensure payment method is saved here

            // Store payment details based on selected payment method
            if (paymentMethod === 'cash') {
                localStorage.setItem('cashPayment', document.getElementById('cashAmount').value || '');
            } else if (paymentMethod === 'gcash') {
                localStorage.setItem('gcashCustomerName', document.getElementById('senderName').value || '');
                localStorage.setItem('gcashPayment', document.getElementById('gcashAmount').value || '');
                localStorage.setItem('gcashReferenceNum', document.getElementById('referenceNum').value || '');
            } else if (paymentMethod === 'banktransfer') {
                localStorage.setItem('bankPaymentType', document.getElementById('bankName').value || '');
                localStorage.setItem('bankCustomerName', document.getElementById('accHold').value || '');
                localStorage.setItem('bankPayment', document.getElementById('amount').value || '');
                localStorage.setItem('bankTransactionDate', document.getElementById('transactDate').value || '');
                localStorage.setItem('bankReferenceNum', document.getElementById('transactRef').value || '');
            }
            window.location.href = "{{ route('adminConfirm') }}";
        });

        document.getElementById('deliveryMethod').addEventListener('change', function () {
            const deliverDateContainer = document.getElementById('deliverDate');
            deliverDateContainer.style.display = (this.value === 'deliver') ? 'block' : 'none';
        });

        document.getElementById('paymentMethod').addEventListener('change', function () {
            showPaymentDetails(this.value);
        });

        populateForm(); 
    });
</script>

@endsection
