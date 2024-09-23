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

                                <div class="progress-step active" data-route="{{ route('custInfo') }}" data-index="1">
                                    <div class="step-icon"><i class="bi bi-person"></i></div>
                                    <div class="progress-label">Customer Information</div>
                                </div>

                                <div class="progress-step active" data-route="{{ route('confirm') }}" data-index="3">
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
                                <span class="order-peso">$3137.85</span>
                                <button class="place-btn">Place Order</button>
                                <button class="place-btn">Make Reservation</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h3><b>Customer Information</b></h3>
                            <h4 class="orderinfo"><b>customer name</b></h4>
                        </div> <div class="col-md-6">
                            <h3><b>Delivery Address</b></h3>
                            <h4 class="orderinfo"><b>customer name</b></h4>
                            <h4 class="orderinfo">Delivery Address</h4>
                            <h4 class="orderinfo">Delivery Date</h4>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h3><b>Payment Method</b></h3>
                            <h4 class="orderinfo"><b>Type of Payment</b></h4>
                            <h4 class="orderinfo">Sender Name</h4>
                            <h4 class="orderinfo">Transaction Amount</h4>
                            <h4 class="orderinfo">Transaction Reference</h4>
                            <h4 class="orderinfo">Transaction Date</h4>
                        </div>
                        <div class="col-md-6">
                            <h3><b>Billing Address</b></h3>
                            <h4 class="orderinfo"><b>customer name</b></h4>
                            <h4 class="orderinfo">Billing Address</h4>
                            <h4 class="orderinfo">Transaction Date</h4>
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <a href="{{ route('custInfo') }}" class="btn btn-secondary" style="width:100px;">Back</a>
                    </div>
                </div>
                <h2>Order Summary</h2>
                <div class="card card-cstm p-3">
                    <table class="table table-striped custom-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Services</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total</td>
                                <td>40</td>
                                <td></td>
                                <td></td>
                                <td>11,911</td>
                            </tr>
                        </tbody>
                    </table>
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
@endsection
