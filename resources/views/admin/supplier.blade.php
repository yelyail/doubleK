
@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
<div class="main p-3">
    <div class="text">
        <h1>Supplier Information</h1>
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
                    <i class="bi bi-plus"></i> Add Supplier
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Supplier Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Landline</th>
                        <th>Address</th>
                        <th>Representative</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($suppliers as $supplier){
                            <td>{{ $supplier->user_ID }}</td>
                            <td>{{ $supplier->supplier_name }}</td>
                            <td>{{ $supplier->supplier_email }}</td>
                            <td>{{ '+63 ' . substr($supplier->supplier_contact, 1, 4) . ' ' . substr($supplier->supplier_contact, 5, 3) . ' ' . substr($supplier->supplier_contact, 8) }}</td>
                            <td>{{ substr($supplier->supplier_landline, 0, 3) }} {{ substr($supplier->supplier_landline, 3, 3) }} {{ substr($supplier->supplier_landline, 6) }}</td>
                            <td>{{ $supplier->supplier_address }}</td>
                            <td>{{ $supplier->orderDetails->user->fullname }}</td>
                            <td><div style="display: flex; align-items: center; gap: 5px;">
                                    <button class="btn btn-success btn-sm"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-danger btn-sm"><i class="bi bi-archive"></i></button>
                                </div>
                            </td>
                        }
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Adding a Supplier -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Supplier</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="inventoryForm" action="{{ route('storeSupplier') }}" method="POST">
                @csrf
                    <div class="mb-3">
                        <label for="supplierName" class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" id="supplierName" placeholder="Juan Dela Cruz">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="juandelacruz@gmail.com">
                    </div>
                    <div class="mb-3">
                        <label for="pNum" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="pNum" placeholder="9123456789">
                        format
                    </div>
                    <div class="mb-3">
                        <label for="landline" class="form-label">Landline</label>
                        <input type="text" class="form-control" id="landline" placeholder="0821234567">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" placeholder="Davao City">
                    </div>
                    <div class="mb-3">
                        <label for="representative" class="form-label">Representative</label>
                        <input type="text" class="form-control" id="representative" placeholder="John Ken">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="saveInventory">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
