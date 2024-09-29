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
                            <td colspan="11" class="text-center">No Inventory available.</td>
                        </tr>
                    @else
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ ucwords(strtolower($product->categoryName)) }}</td>
                                <td>{{ ucwords(strtolower($product->product_name)) }}</td> 
                                <td>{{ $product->supplier_name ?? 'N/A' }}</td> 
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
                                <td>₱ {{ number_format($product->unit_price,2) }}</td>
                                <td>{{ $product->stock_qty ?? 'N/A' }}</td>
                                <td>{{ $product->prod_add ?? 'N/A' }}</td>
                                <td>{{ $product->updatedQty ?? 'N/A' }}</td>
                                <td>{{ $product->nextRestockDate ?? 'N/A' }}</td>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <button class="btn btn-success btn-sm" 
                                                onclick="editInventory('{{ $product->product_id }}')">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <button type="button" class="btn btn-danger btn-sm archive-btn" data-product-id="{{ $product->product_id }}">
                                            <i class="bi bi-archive"></i>
                                        </button>

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
                <form id="editInventoryForm" method="POST">
                    @csrf
                    <input type="hidden" id="editInventoryID" name="product_id">
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="editCategoryName" name="editCategoryName" placeholder="Enter category name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="editProductName" name="editProductName" placeholder="Enter Product name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editItemDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editItemDescription" rows="3" name="editProductDescription" placeholder="Enter a description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editStocks" class="form-label">Stocks</label>
                        <input type="number" class="form-control" id="editStocks" name="editStocks" placeholder="Enter how many stocks" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="editItemPrice" class="form-label">Price</label>
                            <input type="text" class="form-control" id="editItemPrice" name="editPricePerUnit" placeholder="Enter price" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editItemDate" class="form-label">Date Added</label>                                    
                            <input type="date" class="form-control" id="editItemDate" name="editDateAdded">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="editWarranty" class="form-label">Warranty</label>
                        <div class="col-md-6">
                            <input type="number" class="form-control me-2" id="editWarrantyPeriod" name="editWarrantyPeriod" placeholder="Enter warranty period">
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="editWarrantyUnit" name="editWarrantyUnit" required>
                                <option value="" disabled selected>Warranty Units</option>
                                <option value="days">Days</option>
                                <option value="weeks">Weeks</option>
                                <option value="months">Months</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="editSuppName" class="form-label">Supplier Name</label>
                        <div class="col-md-8">
                            <select class="form-select" id="editSuppName" name="editSupplierName" required>
                                <option value="" disabled selected>Supplier Name</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_ID }}">{{ $supplier->supplier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="updateInventory">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- not done yawa -->
<script>
    function editInventory(productId) {
        // Fetch product details using AJAX
        fetch(`/admin/inventory/${productId}/edit`)
            .then(response => response.json())
            .then(data => {
                // Populate modal fields with product data
                document.getElementById('editInventoryID').value = data.product_id;
                document.getElementById('editCategoryName').value = data.categoryName;
                document.getElementById('editProductName').value = data.product_name;
                document.getElementById('editItemDescription').value = data.product_desc;
                document.getElementById('editStocks').value = data.stock_qty;
                document.getElementById('editItemPrice').value = data.unit_price;
                document.getElementById('editItemDate').value = data.prod_add;
                document.getElementById('editWarrantyPeriod').value = data.warranty;

                document.getElementById('editWarrantyUnit').value = data.warrantyUnit;
                
                var editModal = new bootstrap.Modal(document.getElementById('editInventoryModal'));
                editModal.show();
            })
            .catch(error => console.error('Error fetching product:', error));
    }

    // Archive product
    document.querySelectorAll('.archive-btn').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            const url = `/admin/inventory/${productId}/archive`;
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won’t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, archive it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            '_token': '{{ csrf_token() }}',
                        },
                        success: function (data) {
                            button.closest('tr').remove();
                            Swal.fire('Archived', 'Product archived successfully', 'success');
                        },
                        error: function (data) {
                            Swal.fire('Error!', data.responseJSON.message || 'There was an error archiving.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endsection