@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
<div class="main p-3">
  <div class="text">
    <h1>Sales Transaction</h1>
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
                                    <div class="progress-step active" data-route="{{ route('adminOrder') }}">
                                        <div class="step-icon"><i class="bi bi-cart"></i></div>
                                        <div class="progress-label">Order</div>
                                    </div>
                                    <div class="progress-step " data-route="{{ route('custInfo') }}">
                                        <div class="step-icon"><i class="bi bi-person"></i></div>
                                        <div class="progress-label">Customer Information</div>
                                    </div>
                                    <div class="progress-step " data-route="{{ route('confirm') }}">
                                        <div class="step-icon"><i class="bi bi-check-circle"></i></div>
                                        <div class="progress-label">Preview & Confirm</div>
                                    </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-4">
                        <div class="input-group search-bar mt-3">
                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Search..." aria-label="Search"> <button class="btn custom-btn" type="button" onclick="filterTable()">Search</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="categoryfilter" class="form-label">Filter</label>
                        <select class="form-control" id="categoryfilter" name="categoryfilter">
                            <option value="product" selected>Product</option> <!-- Set default to 'product' -->
                            <option value="services">Services</option>
                            <option value="reservation">Reservation</option>
                            <option value="custDebt">Customer Debt</option>
                        </select>
                    </div>
                    <div id="productTable">
                        <table class="table table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Category Name</th>
                                    <th>Description</th>
                                    <th>Variant</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="serviceInput" style="display: none;">
                        <table class="table table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Service Name</th>
                                    <th>Description</th>
                                    <th>Service Fee</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($services as $service)
                                    <tr>
                                        <td>{{ $service->service_ID }}</td>
                                        <td>{{ $service->service_name }}</td>
                                        <td>{{ $service->description }}</td>
                                        <td>₱ {{ $service->service_fee }}</td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 5px;">
                                                <button class="btn btn-success btn-sm"><i class="bi bi-plus"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="reservationInput" style="display: none;">
                        <table class="table table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Reference Number</th>
                                    <th>Customer Name</th>
                                    <th>Particulars</th>
                                    <th>Initial Payment</th>
                                    <th>Reservation Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="custDebtInput" style="display: none;">
                        <table class="table table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer Name</th>
                                    <th>Particulars</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Amount</th>
                                    <th>Date of Product Debt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <a href="{{ route('custInfo') }}" class="btn btn-success" style="width:100px;">Place Order</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- for the filtering -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById("categoryfilter").addEventListener("change", function() {
            var productTable = document.getElementById("productTable");
            var serviceInput = document.getElementById("serviceInput");
            var reservationInput = document.getElementById("reservationInput");
            var custDebtInput = document.getElementById("custDebtInput");

            productTable.style.display = "none";
            serviceInput.style.display = "none";
            reservationInput.style.display = "none";
            custDebtInput.style.display="none";

            if (this.value === "product") {
                productTable.style.display = "block";
            } else if (this.value === "services") {
                serviceInput.style.display = "block";
            } else if (this.value === "reservation") {
                reservationInput.style.display = "block";
            }else if (this.value === "reservation") {
                reservationInput.style.display = "block";
            }else if (this.value === "custDebt") {
                custDebtInput.style.display = "block";
            }
        });

        document.getElementById("categoryfilter").dispatchEvent(new Event("change"));
    });
</script>
<!-- for the progress bar -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const progressLine = document.getElementById('progressLine');
        const steps = document.querySelectorAll('.progress-step');

        function setInitialProgress() {
            steps.forEach((step) => {
                if (step.classList.contains('active')) {
                    progressLine.style.width = `20%`;
                }
            });
        }

        window.navigateTo = function(page, index) {
            steps.forEach((step, idx) => {
                if (idx <= index) {
                    step.classList.add('active');
                } else {
                    step.classList.remove('active');
                }
            });

            progressLine.style.width = `${((index + 1) / steps.length) * 100}%`;
            window.location.href = page;
        };

        steps.forEach((step, index) => {
            step.onclick = function() {
                if (index === 0 || steps[index - 1].classList.contains('active')) {
                    navigateTo(step.dataset.route, index);
                }
            };
        });

        setInitialProgress();
    });
</script>
@endsection

