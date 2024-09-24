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
                        <!-- wala pani nahuman yawa balikan ko rani unya kay boshit di ma update ang table pota -->
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->category_name }}</td>
                                <td>{{ $product->product_name }}</td> 
                                <td>{{ $product->supplier_name ?? 'N/A' }}</td> 
                                <td>{{ $product->product_desc }}</td>
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
                                <td>{{ $product->unit_price }}</td>
                                <td>{{ $product->stock_qty ?? 'N/A' }}</td>
                                <td>{{ $product->prod_add ?? 'N/A' }}</td>
                                <td>{{ $product->updatedQty ?? 'N/A' }}</td>
                                <td>{{ $product->inventory->nextRestockDate ?? 'N/A' }}</td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                    <button class="btn btn-success btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editInventoryModal" 
                                        data-inventory="{{ json_encode([
                                            'id' => $product->product_id, // Change to product_id
                                            'category_name' => $product->category_name,
                                            'product_name' => $product->product_name,
                                            'product_description' => $product->product_desc,
                                            'price_per_unit' => $product->unit_price,
                                            'stocks' => $product->updatedQty, 
                                            'restock_date' => $product->prod_add, 
                                            'warranty_period' => $product->warranty, 
                                            'warranty_unit' => 'days',
                                            'supplier_id' => $product->supplierName // Access the supplier ID
                                        ]) }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                        <button type="button"class="btn btn-danger btn-sm archive-btn" data-productId="{{ $product->product_id }}">
                                            <i class="bi bi-archive"></i>
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

<!-- Add HERE-->
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
                        <input type="number" class="form-control" id="stocks" name="Stocks" placeholder="Enter how many stocks">
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
                            <select class="form-select" id="warrantyUnit">
                                <option value="days">Days</option>
                                <option value="weeks">Weeks</option>
                                <option value="months">Months</option>
                            </select>
                        </div>
                    </div>
                <div class="row mb-3">
                    <label for="suppName" class="form-label">Supplier Name</label>
                        <div class="col-md-8">
                            <select class="form-select" id="suppName" name="supplierName">
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
<!-- Edit Inventory -->
<div class="modal fade" id="editInventoryModal" tabindex="-1" aria-labelledby="editInventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Edit Inventory</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editInventoryForm" action="{{ route('updateInventory') }}" method="POST">
                    @csrf
                    <input type="hidden" id="editInventoryID" name="id">
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="editCategoryName" name="editCategoryName" placeholder="Enter category name">
                    </div>
                    <div class="mb-3">
                        <label for="editProductName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="editProductName" name="editProductName" placeholder="Enter Product name">
                    </div>
                    <div class="mb-3">
                        <label for="editProductDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editProductDescription" rows="3" name="editProductDescription" placeholder="Enter a description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editUpdatedStocks" class="form-label">Updated Stocks</label>
                    <input type="number" class="form-control" id="editUpdatedStocks" name="editUpdatedStocks" placeholder="Enter how many stocks">                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editPricePerUnit" class="form-label">Price</label>
                            <input type="text" class="form-control" id="editPricePerUnit" name="editPricePerUnit" placeholder="Enter price">
                        </div>
                        <div class="col-md-6">
                        <label for="editRestockDate" class="form-label">Restock Date</label>
                        <input type="date" class="form-control" id="editRestockDate" name="editRestockDate">                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="warranty" class="form-label">Warranty</label>
                        <div class="col-md-6">
                            <input type="number" class="form-control me-2" id="editWarrantyPeriod" name="warrantyPeriod" placeholder="Enter warranty period">
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="warrantyUnit">
                                <option value="days">Days</option>
                                <option value="weeks">Weeks</option>
                                <option value="months">Months</option>
                            </select>
                        </div>
                    </div>
                <div class="row mb-3">
                    <label for="suppName" class="form-label">Supplier Name</label>
                        <div class="col-md-8">
                            <select class="form-select" id="suppName" name="editSupplierName">
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_ID }}">{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
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
<!-- converting days for warranty -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const warrantyPeriodInput = document.getElementById('warrantyPeriod');
        const warrantyUnitSelect = document.getElementById('warrantyUnit');

        function convertToDays() {
            const period = parseInt(warrantyPeriodInput.value) || 0;
            const unit = warrantyUnitSelect.value;

            if (unit === 'weeks') {
                return Math.round(period * 7);
            } else if (unit === 'months') {
                return Math.round(period * 30);
            }
            return period; // Default is days
        }

        const form = document.getElementById('inventoryForm');
        form.addEventListener('submit', function(event) {
            // Convert the warranty period before form submission
            const days = convertToDays();
            warrantyPeriodInput.value = days; // Update input value for submission
            // Optionally, prevent default submission for testing
            // event.preventDefault(); 
        });
    });
</script>
<input type="hidden" id="productsData" value='@json($products)'>
<!-- Edit Inventory -->
<script>
    $('#editInventoryModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var inventoryData = button.data('inventory'); // Extract inventory data from data-* attributes

        var modal = $(this);
        modal.find('#editInventoryID').val(inventoryData.id);
        modal.find('#editCategoryName').val(inventoryData.category_name);
        modal.find('#editProductName').val(inventoryData.product_name);
        modal.find('#editProductDescription').val(inventoryData.product_description);
        modal.find('#editUpdatedStocks').val(inventoryData.stocks);
        modal.find('#editPricePerUnit').val(inventoryData.price_per_unit);
        modal.find('#editRestockDate').val(inventoryData.restock_date);
        modal.find('#editWarrantyPeriod').val(inventoryData.warranty_period);
        modal.find('#warrantyUnit').val(inventoryData.warranty_unit);
        modal.find('#suppName').val(inventoryData.supplier_id);
    });
</script>
<!-- Archive Inventory -->
<script>
    $(document).ready(function() {
        $('.archive-btn').click(function() {
            var productId = $(this).data('productId'); 
            var row = $(this).closest('tr'); 

            Swal.fire({
                title: 'Inventory Archiving',
                text: 'Are you sure to archive this product??',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Archive',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: `{{ url('/admin/inventory/') }}/${productId}/archive`,
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