@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
<div class="main">
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
                                    <div class="progress-step " data-route="{{ route('adminCustInfo') }}">
                                        <div class="step-icon"><i class="bi bi-person"></i></div>
                                        <div class="progress-label">Customer Information</div>
                                    </div>
                                    <div class="progress-step " data-route="{{ route('adminConfirm') }}">
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
                            <div class="col-md-7">
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
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ ucwords(strtolower($product->product_name)) }}</td>
                                                        <td>{{ ucwords(strtolower($product->category->categoryName)) }}</td>
                                                        <td style="text-align: justify;">{{ucwords(strtolower( $product->product_desc)) }}</td>
                                                        <td>₱ {{ $product->unit_price }}</td>
                                                        <td>
                                                            <div style="display: flex; align-items: center; gap: 5px;">
                                                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#productModal"
                                                                    data-id="{{ $product->product_id }}"
                                                                    data-name="{{ ucwords(strtolower($product->product_name)) }}"
                                                                    data-category="{{ ucwords(strtolower($product->category->categoryName ))}}"
                                                                    data-desc="{{ ucwords(strtolower($product->product_desc)) }}"
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
                                    <div id="custDebtInput" style="display: none;">
                                        <table class="table table-striped custom-table">
                                            <thead>
                                                <tr>
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
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $service->service_name }}</td>
                                                        <td>{{ $service->description }}</td>
                                                        <td>₱ {{ $service->service_fee }}</td>
                                                        <td>
                                                            <div style="display: flex; align-items: center; gap: 5px;">
                                                                <button class="btn btn-success btn-sm add-service" 
                                                                        data-name="{{ $service->service_name }}" 
                                                                        data-fee="{{ $service->service_fee }}">
                                                                    <i class="bi bi-plus"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- Order Summary-->
                            <div class="col-md-5">
                                <div class="card card-cstm-btn mt-4">
                                    <div class="card-header">
                                        <h5 class="card-title-text">Order Summary</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table">
                                            <thead class="orderSummaryHead">
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                            <tbody id="orderSummaryBody">
                                            </tbody>
                                        </table>
                                        <div class="card-title-text text-end mt-4">
                                            <strong>Total Amount:</strong> 
                                            <span id="totalAmount"></span>
                                        </div>
                                        <div class="text-end mt-4">
                                            <button id="placeOrderButton" class="btn btn-success" style="width:100px;" disabled>Place Order</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                <button type="button" class="btn btn-success" id="addProductButton">Add Product</button>
            </div>
        </div>
    </div>
</div>
</script>
<!-- for adding product/services-->
<script>
    $('#productModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var productId = button.data('id'); 
        var productName = button.data('name');
        var productCategory = button.data('category');
        var productDesc = button.data('desc');
        var productPrice = button.data('price');

        var modal = $(this);
        modal.find('#modalProductName').text(productName);
        modal.find('#modalProductCategory').text(productCategory);
        modal.find('#modalProductDesc').text(productDesc);
        modal.find('#modalProductPrice').text(productPrice);
        modal.find('#product_id').val(productId);
        
        $('#addProductButton').off('click').on('click', function() {
            const quantity = parseInt($('#quantity').val());
            if (isNaN(quantity) || quantity < 1) {
                alert("Please enter a valid quantity."); // Validation for quantity
                return;
            }
            
            const totalPrice = parseFloat(productPrice) * quantity;
            const orderSummaryBody = document.getElementById('orderSummaryBody');

            // Check if an existing row matches productName, productCategory, and productPrice
            let existingRow = Array.from(orderSummaryBody.rows).find(row => 
                row.cells[0].innerText === productName &&
                row.cells[2].innerText.replace('₱ ', '').replace(',', '') === parseFloat(productPrice).toFixed(2) &&
                row.cells[4].innerText === productCategory
            );

            if (existingRow) {
                // If the row exists, update the quantity and total
                let existingQuantity = parseInt(existingRow.cells[1].innerText);
                existingRow.cells[1].innerText = existingQuantity + quantity;
                existingRow.cells[3].innerText = `₱ ${(parseFloat(existingRow.cells[3].innerText.replace('₱ ', '').replace(',', '')) + totalPrice).toFixed(2)}`;
            } else {
                // Create a new row
                const newRow = orderSummaryBody.insertRow();
                newRow.innerHTML = `
                    <td>${productName}</td>
                    <td class="text-center">${quantity}</td>
                    <td>₱ ${parseFloat(productPrice).toFixed(2)}</td>
                    <td>₱ ${totalPrice.toFixed(2)}</td>
                    <td><button class="btn btn-danger btn-sm remove-product"><i class="bi bi-x-circle"></i></button></td>
                `;

                newRow.querySelector('.remove-product').addEventListener('click', function() {
                    orderSummaryBody.deleteRow(newRow.rowIndex - 1); 
                    updateTotalAmount(); 
                });
            }

            updateTotalAmount();
            $('#productModal').modal('hide');
        });
    });


    // Function to update total amount and toggle button state
    function updateTotalAmount() {
        const orderSummaryBody = document.getElementById('orderSummaryBody');
        let overallTotal = 0;
        for (let row of orderSummaryBody.rows) {
            overallTotal += parseFloat(row.cells[3].innerText.replace('₱ ', '').replace(',', ''));
        }
        document.getElementById('totalAmount').innerText = `₱ ${overallTotal.toFixed(2)}`;
        togglePlaceOrderButton(); // Ensure this is called after the total amount is updated
    }

    function togglePlaceOrderButton() {
        const orderSummaryBody = document.getElementById('orderSummaryBody');
        const placeOrderButton = document.getElementById('placeOrderButton');
        placeOrderButton.disabled = orderSummaryBody.rows.length === 0;
    }
    document.getElementById('placeOrderButton').addEventListener('click', function() {
        window.location.href = "{{ route('adminCustInfo') }}";
    });

    togglePlaceOrderButton();
</script>
<script>
    function saveOrderSummary() {
        const orderSummaryBody = document.getElementById('orderSummaryBody');
        let orderItems = [];
        
        for (let row of orderSummaryBody.rows) {
            orderItems.push({
                name: row.cells[0].innerText,
                quantity: parseInt(row.cells[1].innerText),
                price: parseFloat(row.cells[2].innerText.replace('₱ ', '').replace(',', '')),
                total: parseFloat(row.cells[3].innerText.replace('₱ ', '').replace(',', ''))
            });
        }
        localStorage.setItem('orderSummary', JSON.stringify(orderItems));
    }

    document.getElementById('placeOrderButton').addEventListener('click', function() {
        saveOrderSummary();
        window.location.href = "{{ route('adminCustInfo') }}";
    });

    function loadOrderSummary() {
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
                    <td><button class="btn btn-danger btn-sm remove-product"><i class="bi bi-x-circle"></i></button></td>
                `;
                newRow.querySelector('.remove-product').addEventListener('click', function() {
                    orderSummaryBody.deleteRow(newRow.rowIndex - 1);
                    updateTotalAmount();
                    saveOrderSummary(); 
                });
            });
            updateTotalAmount();
        }
    }

    function updateTotalAmount() {
        const orderSummaryBody = document.getElementById('orderSummaryBody');
        let overallTotal = 0;

        for (let row of orderSummaryBody.rows) {
            overallTotal += parseFloat(row.cells[3].innerText.replace('₱ ', '').replace(',', ''));
        }

        document.getElementById('totalAmount').innerText = `₱ ${overallTotal.toFixed(2)}`;

        localStorage.setItem('orderTotal', overallTotal.toFixed(2)); 

        togglePlaceOrderButton(); 
        saveOrderSummary(); 
    }
    function togglePlaceOrderButton() {
        const orderSummaryBody = document.getElementById('orderSummaryBody');
        const placeOrderButton = document.getElementById('placeOrderButton');
        
        placeOrderButton.disabled = orderSummaryBody.rows.length === 0;
    }
    document.addEventListener('DOMContentLoaded', saveOrderSummary);
</script>
<script src="{{ asset('assets/js/order.js') }}"></script>
@endsection