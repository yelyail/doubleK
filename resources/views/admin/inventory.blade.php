@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
    <div class="main p-3">
        <div class="text">
            <h1>Inventory Management</h1>
        </div>
        <!-- first table -->
                <div class="container mt-3">
                    <div class="row">
                        <div class="col-md-4 mt-2">
                            <div class="card p-3">
                                <h4>Filter Inventory</h4>
                                <div class="input-group search-bar mb-3">
                                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                    <input type="text" id="searchInput" class="form-control" placeholder="Search..." aria-label="Search">
                                    <button class="btn custom-btn" type="button" onclick="filterTable()">Search</button>
                                </div>
                                <h5>Category</h5>
                                <select class="form-select mb-3" id="categoryfilter">
                                    <option value="">Select Category</option>
                                    <option value="Monitor">Monitor</option>
                                    <option value="System Unit">System Unit</option>
                                    <option value="Cables">Cables</option>
                                </select>
                                <h5>Brand/Model</h5>
                                <select class="form-select mb-3" id="brandFilter">
                                    <option value="">Select Brand/Model</option>
                                    <option value="Acer">Acer</option>
                                    <option value="Inplay">Inplay</option>
                                    <option value="Samsung">Samsung</option>
                                </select>
                                <h5>Price Range</h5>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" id="minPrice" placeholder="Min Price">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" class="form-control" id="maxPrice" placeholder="Max Price">
                                    </div>
                                </div>
                                <select class="form-select mb-3" id="sortFilter">
                                    <option value="">Sort By</option>
                                    <option value="nameAsc">Name: A to Z</option>
                                    <option value="nameDesc">Name: Z to A</option>
                                </select>
                                <button class="btn custom-btn mb-2" >Apply Filters</button>
                                <button class="btn btn-danger">Reset Filters</button>
                            </div>
                        </div> 
                        <!-- for the second table -->
                        <div class="col-md-8">
                            <div class="card p-3">
                                <div class="row mb-4 align-items-center">
                                    <div class="col-md-8">
                                        <h4>Inventory List</h4>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <button type="button" class="btn custom-btn" id="plus-button" style="border-radius: 7px; height: 2.3rem; border: none;" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            <i class="bi bi-plus"></i> Add Inventory
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped custom-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Category Name</th>
                                                <th>Product Name</th>
                                                <th>Supplier Name</th>
                                                <th>Description</th>
                                                <th>Price</th>
                                                <th>Warranty</th>
                                                <th>Current Stocks</th>
                                                <th>Date Added</th>
                                                <th>Updated Stocks</th>
                                                <th>Restock Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="inventoryTableBody">
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
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
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL HERE-->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Inventory</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="inventoryForm">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="categoryName" placeholder="Enter category name">
                            </div>
                            <div class="mb-3">
                                <label for="productName" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="productName" placeholder="Enter Product name">
                            </div>
                            <div class="mb-3">
                                <label for="itemDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="itemDescription" rows="3" placeholder="Enter a description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="stocks" class="form-label">Stocks</label>
                                <input type="number" class="form-control" id="stocks" placeholder="Enter how many stocks">
                            </div>
                            <div class="row mb-3">
                                <!-- Price Section -->
                                <div class="col-md-6">
                                    <label for="itemPrice" class="form-label">Price</label>
                                    <input type="text" class="form-control" id="itemPrice" placeholder="Enter price">
                                </div>
                                <!-- Date Section -->
                                <div class="col-md-6">
                                    <label for="itemDate" class="form-label">Date Added</label>
                                    <input type="date" class="form-control" id="itemDate">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="warranty" class="form-label">Warranty</label>
                                <div class="col-md-6">
                                    <input type="number" class="form-control me-2" id="warrantyPeriod" placeholder="Enter warranty period">
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
                                    <select class="form-select" id="suppName">
                                        <option value="john">John</option>
                                        <option value="jin">Jin</option>
                                        <option value="jam">Jam</option>
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