
@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
<div class="main p-3">
    <div class="text">
        <h1 class="prod_title">Supplier</h1>
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
                    @foreach ($suppliers as $supplier)
                        @if($supplier->archived == 0) 
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucwords(strtolower($supplier->supplier_name)) }}</td>
                                <td>{{ $supplier->supplier_email }}</td>
                                <td>{{ '+63 ' . substr($supplier->supplier_contact, 0, 3) . ' ' . substr($supplier->supplier_contact, 3, 3) . ' ' . substr($supplier->supplier_contact, 6) }}</td>
                                <td>{{ substr($supplier->supplier_landline, 0, 3) . ' ' . substr($supplier->supplier_landline, 3, 3) . ' ' . substr($supplier->supplier_landline, 6) }}</td>
                                <td>{{ $supplier->supplier_address }}</td>
                                <td>{{ ucwords(strtolower($supplier->user->fullname ?? 'N/A')) }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 5px;">
                                        <button class="btn btn-success btn-sm" onclick="editSupplier('{{ $supplier->supplier_ID }}')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm archive-btn" data-supplierID="{{ $supplier->supplier_ID }}">
                                            <i class="bi bi-archive"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
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
                        <input type="text" class="form-control" id="supplierName" name="supplier_name" placeholder="Juan Dela Cruz">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="supplier_email" placeholder="juandelacruz@gmail.com">
                    </div>
                    <div class="mb-3">
                        <label for="pNum" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="pNum" name="supplier_contact" placeholder="9123456789">
                    </div>
                    <div class="mb-3">
                        <label for="landline" class="form-label">Landline</label>
                        <input type="text" class="form-control" id="landline" name="supplier_landline" placeholder="0821234567">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="supplier_address" placeholder="Davao City">
                    </div>
                    <div class="mb-3">
                        <label for="representative" class="form-label">Representative</label>
                        <select class="form-select" id="representative" name="representative" required>
                            <option value="">Select Inventory Recipient</option>
                            @foreach($users as $user)
                                <option value="{{ $user->user_ID }}">{{ ucwords(strtolower($user->fullname)) }}</option>
                            @endforeach
                        </select>
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
<!-- Edit a Supplier -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Edit Supplier</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editSupplierform" method="POST" action="{{ route('updateSupplier', ['id' => 'supplier_id']) }}" enctype="multipart/form-data">
                @csrf
                    <input type="hidden" id="editSupplierId" name="id">
                    <div class="mb-3">
                        <label for="editSupplierName" class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" id="editSupplierName" name="supplier_name" required> 
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="supplier_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPhoneNumber" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="editPhoneNumber" name="supplier_contact" required maxlength="10">
                    </div>
                    <div class="mb-3">
                        <label for="editLandline" class="form-label">Landline</label>
                        <input type="text" class="form-control" id="editLandline" name="supplier_landline" placeholder="0821234567" maxlength="10">
                    </div>
                    <div class="mb-3">
                        <label for="editAddress" class="form-label">Address</label>
                        <input type="text" class="form-control" id="editAddress" name="supplier_address" placeholder="Davao City">
                    </div>
                    <div class="mb-3">
                        <label for="editRepresentative" class="form-label">Representative</label>
                        <select class="form-select" id="editRepresentative" name="representative" required> 
                            <option value="">Select Inventory Recipient</option>
                            @foreach($users as $user)
                                <option value="{{ $user->user_ID }}">{{ ucwords(strtolower($user->fullname)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="supplierData" value='@json($suppliers)'>
<script>
    function editSupplier(supplierId) {
        $.ajax({
            url: '/admin/supplier/' + supplierId + '/edit', 
            type: 'GET',
            success: function (data) {
                $('#editSupplierId').val(data.supplier_ID);
                $('#editSupplierName').val(data.supplier_name);
                $('#editEmail').val(data.supplier_email);
                $('#editPhoneNumber').val(data.supplier_contact);
                $('#editLandline').val(data.supplier_landline);
                $('#editAddress').val(data.supplier_address);
                $('#editRepresentative').val(data.representative);

                $('#editSupplierModal').modal('show');
            },
            error: function () {
                alert('Error fetching supplier data');
            }
        });
    }
</script>
<script>
    $(document).ready(function() {
        $('.archive-btn').click(function() {
            var supplierID = $(this).data('supplierid'); 
            var row = $(this).closest('tr');

            Swal.fire({
                title: 'Supplier Archiving',
                text: 'Are you sure to archive this supplier?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Archive',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: `{{ url('/admin/supplier/') }}/${supplierID}/archive`, 
                        data: {
                            '_token': '{{ csrf_token() }}',
                        },
                        success: function(data) {
                            row.remove();
                            Swal.fire('Archived', 'Archived successfully', 'success');
                        },
                        error: function(data) {
                            console.error(data);
                            Swal.fire('Error!', data.responseJSON.message || 'There was an error archiving.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>

@endsection
