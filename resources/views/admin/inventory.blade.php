@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
    <div class="main p-3">
        <div class="text">
            <h1 class="prod_title">Inventory</h1>
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
                        <i class="bi bi-plus"></i> Add Product
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped custom-table">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Product Name</th>
                            <th>Supplier Name</th>
                            <th>Description</th>
                            <th>Warranty</th>
                            <th>Price</th>
                            <th>Current Stocks</th>
                            <th>Date Added</th>
                            <th>Updated Stocks</th>
                            <th>Restock Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($products->isEmpty())
                            <tr>
                                <td colspan="11" class="text-center">No Products Available.</td>
                            </tr>
                        @else
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ ucwords(strtolower($product->categoryName)) }}</td>
                                    <td>{{ ucwords(strtolower($product->product_name)) }}</td> 
                                    <td>{{ ucwords(strtolower($product->supplier_name ?? 'N/A')) }}</td>
                                    <td>{{ ucwords(strtolower($product->product_desc)) }}</td>
                                    <td>
                                        @php
                                            $warranty = $product->warranty;
                                            $warrantyUnit = 'days';
                                            
                                            if ($warranty >= 30) {
                                                $warranty = round($warranty / 30, 1);
                                                $warrantyUnit = 'months';
                                            } elseif ($warranty >= 7) {
                                                $warranty = round($warranty / 7, 1);
                                                $warrantyUnit = 'weeks';
                                            }
                                        @endphp
                                        {{ $warranty }} {{ $warrantyUnit }}
                                    </td>
                                    <td>₱ {{ number_format($product->unit_price, 2) }}</td>
                                    <td>{{ $product->stock_qty ?? 'N/A' }}</td>
                                    <td>{{ $product->prod_add ?? 'N/A' }}</td>
                                    <td>{{ $product->updatedQty ?? 'N/A' }}</td>
                                    <td>{{ $product->nextRestockDate ?? 'N/A' }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center;">
                                            @if($product->archived == 0)
                                                <!-- Active Product: Show Edit and Archive buttons -->
                                                <button class="btn btn-success btn-sm" onclick="editInventory('{{ $product->product_id }}')">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm archive-btn" data-product-id="{{ $product->product_id }}">
                                                    <i class="bi bi-archive"></i>
                                                </button>
                                            @else
                                                <span class="badge bg-light text-secondary">Inactive</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Add Inventory Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Inventory</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="inventoryForm" action="{{ route('storeProduct') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" name="categoryName" placeholder="Enter category name">
                    </div>
                    <div class="mb-3">
                        <label for="productName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="productName" name="productName" placeholder="Enter Product name">
                    </div>
                    <div class="mb-3">
                        <label for="itemDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="itemDescription" rows="3" name="productDescription" placeholder="Enter a description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="stocks" class="form-label">Stocks</label>
                        <input type="number" class="form-control" id="stocks" name="stocks" placeholder="Enter how many stocks">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="itemPrice" class="form-label">Price</label>
                            <input type="text" class="form-control" id="itemPrice" name="pricePerUnit" placeholder="Enter price">
                        </div>
                        <div class="col-md-6">
                            <label for="itemDate" class="form-label">Date Added</label>                                    
                            <input type="date" class="form-control" id="itemDate" name="dateAdded">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="warranty" class="form-label">Warranty</label>
                        <div class="col-md-6">
                            <input type="number" class="form-control me-2" id="warrantyPeriod" name="warrantyPeriod" placeholder="Enter warranty period">
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="warrantyUnit" name="warrantyUnit" required>
                                <option value="" disabled selected>Warranty Units</option>
                                <option value="days">Days</option>
                                <option value="weeks">Weeks</option>
                                <option value="months">Months</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="suppName" class="form-label">Supplier Name</label>
                        <div class="col-md-8">
                            <select class="form-select" id="suppName" name="supplierName" required>
                                <option value="" disabled selected>Supplier Name</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_ID }}">{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
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
<!-- Edit Inventory Modal -->
<div class="modal fade" id="editInventoryModal" tabindex="-1" aria-labelledby="editInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Edit Inventory</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editInventoryForm" method="POST" action="">
                @csrf
                    <input type="hidden" name="productID" id="editInventoryID">
                    <div class="mb-3">
                        <h4 class="prod_cat"><b>Product Name:</b> <span id="editProductNameDisplay"></span></h4>
                    </div>
                    <div class="mb-3">
                        <h4 class="prod_cat"><b>Category:</b> <span id="editCategoryNameDisplay"></span></h4>
                    </div>
                    <div class="mb-3">
                        <h4 class="prod_cat"><span id="editProductDescriptionDisplay"></span></h4>
                    </div>
                    <div class="mb-3">
                        <label for="editStocks" class="form-label">Updated Stocks</label>
                        <input type="number" class="form-control" name="editStocks" id="editStocks" required>
                    </div>
                        
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editPricePerUnit" class="form-label">Price</label>
                            <input type="number" class="form-control" name="editPricePerUnit" id="editPricePerUnit" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editItemDate" class="form-label">Restock Date</label>
                            <input type="date" class="form-control" id="editItemDate" name="editRestockAdded">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="updateInventory">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- for editing -->
<script>
    function editInventory(productID) {
        $.ajax({
            url: '/admin/inventory/' + productID + '/edit',
            type: 'GET',
            success: function (data) {
                $('#editInventoryID').val(data.product_id);  
                $('#editProductNameDisplay').text(data.product_name); 
                $('#editCategoryNameDisplay').text(data.categoryName);
                $('#editProductDescriptionDisplay').text(data.product_desc); 
                $('#editStocks').val(data.updatedQty); 
                $('#editPricePerUnit').val(data.unit_price); 
                $('#editItemDate').val(data.nextRestockDate);
                
                $('#editInventoryForm').attr('action', '/admin/inventory/' + productID + '/update');
                $('#editInventoryModal').modal('show');
            },
            error: function () {
                alert('Error fetching inventory data');
            }
        });
    }

    $('#editInventoryForm').on('submit', function (e) {
        e.preventDefault(); 

        $.ajax({
            url: $(this).attr('action'), // Use the action from the form
            type: 'POST',
            data: $(this).serialize(), 
            success: function (response) {
                // Show success alert
                Swal.fire({
                    icon: 'success',
                    title: response.message,
                    text: `New Stock Quantity`,
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload(); 
                });
            },
            error: function (xhr) {
                // Show error alert
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON.message || 'Failed to update inventory.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('.archive-btn').click(function() {
            var product_id = $(this).data('product-id'); 
            var row = $(this).closest('tr');

            Swal.fire({
                title: 'Inventory Archiving',
                text: 'Are you sure you want to archive this product?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Archive',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: `{{ url('/admin/inventory/') }}/${product_id}/archive`, 
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
<script>
    $('#warrantyUnit').on('click', function () {
        var warrantyPeriod = parseFloat($('#warrantyPeriod').val()); 
        var selectedUnit = $(this).val();

        if (isNaN(warrantyPeriod) || warrantyPeriod <= 0) {
            return;
        }
        switch (selectedUnit) {
            case 'weeks':
                 $('#warrantyPeriod').val((warrantyPeriod * 7));
                break;
            case 'months':
                $('#warrantyPeriod').val((warrantyPeriod * 30));
                break;
            case 'days':
                if ($('#warrantyUnit').data('prevUnit') === 'weeks') {
                    $('#warrantyPeriod').val((warrantyPeriod * 7));
                } else if ($('#warrantyUnit').data('prevUnit') === 'months') {
                    $('#warrantyPeriod').val((warrantyPeriod * 30)); 
                }
                break;
        }
        $('#warrantyUnit').data('prevUnit', selectedUnit);
    });

</script>

@endsection