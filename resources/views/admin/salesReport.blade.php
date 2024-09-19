@extends('admin.side')

@section('title', 'Inventory Reports')

@section('content')
            <div class="main p-3">
                <div class="row mb-4 align-items-center">
                    <div class="col-md-9">
                        <h1>Sales Reports</h1>
                    </div>
                    <div class="col-md-3 text-end">
                        <button type="button" class="btn btn-custom" id="plus-button" style="border-radius: 7px; height: 2.3rem; border: none;">
                            <i class="bi bi-printer"></i> Generate Reports
                        </button>
                    </div>
                </div>
                <div class="container mt-4">
                    <div class="row mb-2 align-items-center">
                        <div class="col-md-6 mt-4">
                            <div class="input-group search-bar">
                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search..." aria-label="Search">
                                <button class="btn custom-btn" type="button" onclick="filterTable()">Search</button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group ">
                                <label for="date_filter"><b>Filter by Date:</b></label>
                                <form method="get" action="employee">
                                    <div class="input-group md-3">
                                        <span class="input-group-text"><b>From</b></span>
                                        <input type="date" name="from_date" class="form-control me-2" placeholder="From Date">
                                        <span class="input-group-text"><b>To</b></span>
                                        <input type="date" name="to_date" class="form-control" placeholder="To Date">
                                        <button type="submit" class="btn btn-custom">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
                    <table class="table table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Purchase Date</th>
                                    <th>Category Name</th>
                                    <th>Helper Name</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>Print</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Kim Doe</td>
                                    <td>Laptops</td>
                                    <td>Hp</td>
                                    <td>1</td>
                                    <td>09/09/2024</td>
                                    <td>Jane Cruz</td>
                                    <td>Php 20,000.00</td>
                                    <td><button type="submit" class="btn btn-success"><i class="bi bi-printer"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection
