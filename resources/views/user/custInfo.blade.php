@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
<div class="main p-3">
    <div class="text">
        <h1>Customer Information</h1>
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

                                <div class="progress-step active" data-route="{{ route('custInfo') }}" data-index="1">
                                    <div class="step-icon"><i class="bi bi-person"></i></div>
                                    <div class="progress-label">Customer Information</div>
                                </div>

                                <div class="progress-step" data-route="{{ route('confirm') }}" data-index="3">
                                    <div class="step-icon"><i class="bi bi-check-circle"></i></div>
                                    <div class="progress-label">Preview & Confirm</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <h3>Personal Details
                            <button type="button" class="btn btn-medium">Save</button>
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="custName" class="form-label">Customer Name</label>
                                    <input type="text" class="form-control" id="custName" name="custName" placeholder="(Optional)">

                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address">
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="form-group">
                                    <label for="deliveryMethod" class="form-label">Delivery Option</label>
                                    <select class="form-control" id="deliveryMethod" name="deliveryMethod">
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
                                    <select class="form-control" id="paymentMethod" name="paymentMethod"> 
                                        <option value="" disabled selected>Choose a payment method</option>
                                        <option value="cash">Cash</option>
                                        <option value="gcash">GCash</option>
                                        <option value="banktransfer">Bank Transfer</option>
                                    </select>
                                </div>
                                <div id="cashAmountInput" style="display: none;">
                                    <label for="cashAmount" class="form-label">Cash Amount</label>
                                    <input type="number" class="form-control" id="cashAmount" name="cashAmount" placeholder="Enter the total amount">
                                </div>
                                <div id="gcashDetailsInput" style="display: none;">
                                    <label for="senderName" class="form-label">Sender Name</label>
                                    <input type="text" class="form-control" id="senderName" name="senderName" placeholder="Enter the Sender Name">
                                    
                                    <label for="receiverName" class="form-label">Receiver Name</label>
                                    <input type="text" class="form-control" id="receiverName" name="receiverName" placeholder="Enter the Receiver Name">
                                    
                                    <label for="gcashAmount" class="form-label">Amount</label>
                                    <input type="number" class="form-control" id="gcashAmount" name="gcashAmount" placeholder="Enter the GCash amount">

                                    <label for="referenceNum" class="form-label">Reference Number</label>
                                    <input type="text" class="form-control" id="referenceNum" name="referenceNum" placeholder="Enter the GCash reference number">
                                </div>
                                <div id="bankTransferDetails" style="display: none;">
                                    <label for="bankName" class="form-label">Bank Name</label>
                                    <input type="text" class="form-control" id="bankName" name="bankName" placeholder="Enter the Bank Name">

                                    <label for="accHold" class="form-label">Account Holder</label>
                                    <input type="text" class="form-control" id="accHold" name="accHold" placeholder="Enter the Account Name">
                                    
                                    <label for="receiverName" class="form-label">Receiver Name</label>
                                    <input type="text" class="form-control" id="receiverName" name="receiverName" placeholder="Enter the Receiver Name">

                                    <label for="amount" class="form-label">Transaction Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount">
                                    
                                    <label for="transactDate" class="form-label">Transaction Date</label>
                                    <input type="date" class="form-control" id="transactDate" name="transactDate" >

                                    <label for="transactRef" class="form-label">Transaction Reference</label>
                                    <input type="text" class="form-control" id="transactRef" name="transactRef" placeholder="Enter the Transaction Reference">
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('confirm') }}" class="btn btn-success btn-medium" style="float: right; width:150px; margin-left: 10px;">Review and Confirm Payment</a>
                        <a href="{{ route('adminOrder') }}" class="btn btn-secondary btn-medium" style="float: right; width:150px; margin-left: 10px;">Back to the Product</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
