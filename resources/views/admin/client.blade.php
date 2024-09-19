
@extends('admin.side')

@section('title', 'Employee')

@section('content')
<div class="main p-3">
    <div class="text">
        <h1>Employee Information</h1>
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
                    <i class="bi bi-plus"></i> Add Employee
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Username</th>
                        <th>Job Title</th>
                        <th>Phone number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Service 1</td>
                        <td>Description 1</td>
                        <td>1000</td>
                        <td>2021-10-10</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <button class="btn btn-success btn-sm"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="bi bi-archive"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Service</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="inventoryForm">
                    <div class="mb-3">
                        <label for="servicesName" class="form-label">Service Name</label>
                        <input type="text" class="form-control" id="servicesName" placeholder="Enter service name">
                    </div>
                    <div class="mb-3">
                        <label for="serviceDesc" class="form-label">Description</label>
                        <textarea class="form-control" id="serviceDesc" rows="3" placeholder="Enter a description"></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="serviceFee" class="form-label">Service Fee</label>
                            <input type="text" class="form-control" id="serviceFee" placeholder="Enter price">
                        </div>
                        <div class="col-md-6">
                            <label for="deliveryDate" class="form-label">Delivery Date</label>
                            <input type="date" class="form-control" id="deliveryDate">
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
