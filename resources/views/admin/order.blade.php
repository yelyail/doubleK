@extends('admin.side')

@section('title', 'Order')

@section('content')
<div class="main p-3">
    <div class="text">
        <h1>Orders</h1>
    </div>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card p-3">
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6">
                            <div class="input-group search-bar">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search..." aria-label="Search">
                                <button class="btn custom-btn" type="button" onclick="filterTable()">Search</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Filters</h6>
                            <select class="form-select" id="categoryfilter">
                                <option value="">Select Category</option>
                                <option value="Monitor">Monitor</option>
                                <option value="System Unit">System Unit</option>
                                <option value="Cables">Cables</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category Name</th>
                                    <th>Product Name</th>
                                    <th>Stocks</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody id="inventoryTableBody">
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td:</td>
                                    <td>
                                        <div style="display: inline-flex; align-items: center; justify-content: center;">
                                            <button class="btn btn-secondary me-1" onclick="changeQuantity(this, -1)">-</button>
                                            <input type="number" class="form-control text-center" value="0" min="0" style="width: 50px; text-align: center; margin: 0;" readonly>
                                            <button class="btn btn-secondary ms-1" onclick="changeQuantity(this, 1)">+</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3">
                    <div class="row mb-4 align-items-center">
                        <h4 style="font-weight:bold">Checkout</h4>
                        <div class="col-md-8">
                            <h6>Customer name</h6>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-custom" id="plus-button" style="border-radius: 7px; height: 2.3rem; border: none;" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                        <div class="order-det">
                            <h5 class="order-item">Order * 1</h5>
                            <h6>Php 23.00</h6>
                        </div>
                        <div class="order-det">
                            <h5 class="order-item">Order * 1</h5>
                            <h6>Php 23.00</h6>
                        </div>
                        <div class="order-det">
                            <h5 class="order-item">Order * 1</h5>
                            <h6>Php 23.00</h6>
                        </div>
                    </div>
                    <div class="card p-2">
                        <div class="row mb-4 align-items-center">
                            <div class="order-det">
                                <h5 class="order-item">Subtotal</h5>
                                <h6>Php 23.00</h6>
                            </div>
                            <div class="order-det">
                                <h5 class="order-item">TOTAL</h5>
                                <h6>Php 23.00</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-8">
                            <button type="button" class="btn btn-success" id="plus-button" style="border-radius: 7px; height: 2.3rem; border: none;" data-bs-toggle="modal" data-bs-target="#checkOutBackdrop">
                                <i class="bi bi-cart"></i> Checkout
                            </button>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-danger" onclick="resetQuantities()">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FOR CUSTOMER INFORMATION -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Customer Information</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="inventoryForm">
                    <div class="mb-3">
                        <label for="custName" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="custName" placeholder="Enter Customer name">
                    </div>
                    <div class="row mb-3">
                        <label for="ttlPayment" class="form-label">Total Payment</label>
                        <div class="col-md-6">
                            <input type="number" class="form-control me-2" id="ttlPayment" placeholder="Enter Total Payment">
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="payMeth">
                                <option value="cash">Cash</option>
                                <option value="ecash">E-Cash</option>
                                <option value="bankTransfer">Bank Transfer</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="refNum" class="form-label">Reference Number</label>
                        <input type="text" class="form-control" id="refNum" placeholder="Reference #">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="saveInventory">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FOR CHECKOUT -->
<div class="modal fade" id="checkOutBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="checkOutBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="checkOutBackdropLabel">Temporary Receipt</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ URL('assets/images/logo.jpg') }}" alt="Company Logo" class="img-fluid mb-3" style="max-width: 100px; border-radius: 50%;">
                <h3 class="title_bod">Double-K Computer Parts</h3>
                <p>#20 Pag-Asa Street, S.I.R. Matina, Phase 2, Barangay Bucana, Talomo District<br>8000 Davao City, Davao del Sur, Philippines</p>
            </div>
            <div class="modal-body">
                <h3 class="title_bod">Customer Information</h3>
                <p><strong>Sold to:</strong> <span id="receiptCustName">John Doe</span></p>
                <p><strong>Date:</strong> <span id="receiptDate">09/12/2024</span></p>
                <hr>
                <h3 class="title_bod">Order Details</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="receiptTableBody">
                        <tr>
                            <td>Monitor</td>
                            <td>1</td>
                            <td>Php 23,000.00</td>
                            <td>Php 23,000.00</td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <p><strong>Payment Method:</strong> <span id="payMeth"> Cash</span></p>
                <p><strong>Total Amount:</strong> <span id="receiptTotal"> Php 23,000.00</span></p>
                <p><strong>Cashier/Authorized Representative:</strong> <span id="cashierrep"> Helper Name</span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="printReceipt">Print Receipt</button>
            </div>
        </div>
    </div>
</div>
@endsection
