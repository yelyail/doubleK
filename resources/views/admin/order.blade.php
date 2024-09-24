@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
<div class="main p-3">
  <div class="text">
    <h1 class="prod_title">Sales Transaction</h1>
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
                            <option value="product" selected>Product</option>
                            <option value="services">Services</option>
                            <option value="custDebt">Customer Debt</option>
                        </select>
                    </div>
                    <div class="container mt-3">
                        <div class="row">
                             <!-- wapani nahuman ugma napud di na kaya huhuhuh  -->
                            <div class="col-md-6">
                                <div class="card card-cstm-bt mt-4">
                                    <div id="productTable">
                                        <table class="table table-striped custom-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product Name</th>
                                                    <th>Category Name</th>
                                                    <th>Description</th>
                                                    <th>Price</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                    <tr>
                                                        <td>{{ $product->product_id }}</td>
                                                        <td>{{ $product->product_name }}</td>
                                                        <td>{{ $product->category_name }}</td>
                                                        <td>{{ $product->product_desc }}</td>
                                                        <td>₱ {{ $product->unit_price }}</td>
                                                        <td>
                                                            <div style="display: flex; align-items: center; gap: 5px;">
                                                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#productModal"
                                                                    data-id="{{ $product->product_id }}"
                                                                    data-name="{{ $product->product_name }}"
                                                                    data-category="{{ $product->category_name }}"
                                                                    data-desc="{{ $product->product_desc }}"
                                                                    data-price="{{ $product->unit_price }}">
                                                                    <i class="bi bi-plus"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
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
                            </div>
                            <div class="col-md-6">
                                <div class="card card-cstm-bt mt-4">
                                    <div class="card-header">
                                        <h5 class="card-title-text">Order Summary</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody id="orderSummaryBody">
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <div class="card-title-text text-end mt-4">
                                        <strong>Total Amount:</strong> <span id="totalAmount">₱ 0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="text-end mt-4">
                    <a href="{{ route('custInfo') }}" class="btn btn-success" style="width:100px;">Place Order</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- purchase  product -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Product Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> 
            </div>
            <form id="productForm" action="{{ route('addProduct') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <h1 class="prod_name"><span id="modalProductName"></span></h1>
                    <h5 class="prod_cat"><span id="modalProductCategory"></span></h5>
                    <h5 class="prod_desc"><span id="modalProductDesc"></span></h5>
                    <h3 class="mt-5"><strong>Price:</strong> ₱ <span id="modalProductPrice"></span></h3>
                    <input type="hidden" id="product_id" name="product_id">
                    <div class="quantity">
                        <label for="quantity">Quantity:</label>
                        <input type="number" class="format-control-prod" id="quantity" name="qty_order" value="1" min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- for the filtering -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById("categoryfilter").addEventListener("change", function() {
            var productTable = document.getElementById("productTable");
            var serviceInput = document.getElementById("serviceInput");
            var custDebtInput = document.getElementById("custDebtInput");

            productTable.style.display = "none";
            serviceInput.style.display = "none";
            custDebtInput.style.display="none";

            if (this.value === "product") {
                productTable.style.display = "block";
            } else if (this.value === "services") {
                serviceInput.style.display = "block";
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
<!-- for adding -->
<script>
    $('#productModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var productId = button.data('id'); 
        var productName = button.data('name');
        var productCategory = button.data('category');
        var productDesc = button.data('desc');
        var productPrice = button.data('price');

        // Update the modal's content.
        var modal = $(this);
        modal.find('#modalProductName').text(productName);
        modal.find('#modalProductCategory').text(productCategory);
        modal.find('#modalProductDesc').text(productDesc);
        modal.find('#modalProductPrice').text(productPrice);
        modal.find('#product_id').val(productId);
    });

</script>
@endsection

