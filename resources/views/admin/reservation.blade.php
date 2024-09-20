@extends('admin.side')
@section('title', 'Double-K Computer')
@section('content')
<div class="main p-3">
    <div class="text">
        <h1>Reservation</h1>
    </div>
    <div class="container mt-5">
        <div class="row mb-4 align-items-center">
            <div class="col-md-8">
                <div class="input-group search-bar">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search..." aria-label="Search">
                    <button class="btn custom-btn" type="button" onclick="filterTable()">Search</button>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <button type="button" class="btn btn-custom" id="plus-button" style="border-radius: 7px; height: 2.3rem; border: none;" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <i class="bi bi-plus"></i> Reserve
                </button>
            </div>
        </div>

        <!-- Reservation Table -->
        <div class="table-responsive">
            <table class="table table-striped custom-table">
                <thead>
                    <tr>
                        <th>Reference #</th>
                        <th>Customer Name</th>
                        <th>Product/Service</th>
                        <th>Initial Payment</th>
                        <th>Reservation Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>45435439052</td>
                        <td>John Doe</td>
                        <td>Monitor</td>
                        <td>Php 2,000.00</td>
                        <td>09/10/2024</td>
                        <td>
                            <button class="btn btn-warning" onclick="reserveStat(this)">Pending</button>
                        </td>
                    </tr>
                    <tr>
                        <td>67545647547</td>
                        <td>John Kim</td>
                        <td>Laptop</td>
                        <td>Php 2,000.00</td>
                        <td>09/10/2024</td>
                        <td>
                            <button class="btn btn-warning" onclick="reserveStat(this)">Pending</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Reservation Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Reservation Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="inventoryForm">
                    <!-- Customer Name -->
                    <div class="mb-3">
                        <label for="customerName" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="customerName" placeholder="Enter Customer name">
                    </div>
                    <div class="row mb-3">
                        <!-- Reserve Product -->
                        <div class="col-md-6">
                            <label for="reserveProd" class="form-label">Reserve Product</label>
                            <input type="text" class="form-control" id="reserveProd" placeholder="Enter Product name">
                        </div>
                        <!-- Initial Payment -->
                        <div class="col-md-6">
                            <label for="initPay" class="form-label">Initial Payment</label>
                            <input type="text" class="form-control" id="initPay" placeholder="Payment">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <!-- Reservation Date -->
                        <div class="col-md-6">
                            <label for="reserveDate" class="form-label">Reservation Date</label>
                            <input type="date" class="form-control me-2" id="reserveDate">
                        </div>
                        <!-- Customer Type -->
                        <div class="col-md-6">
                            <label for="custType" class="form-label">Customer Type</label>
                            <select class="form-select" id="custType">
                                <option value="walkin">Walk-In</option>
                                <option value="regular">Regular</option>
                                <option value="reseller">Reseller</option>
                            </select>
                        </div>
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
@endsection
