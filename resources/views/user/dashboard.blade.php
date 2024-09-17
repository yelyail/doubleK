@extends('user.side')

@section('title', 'Dashboard')

@section('content')
<div class="main p-3">
    <div class="row mb-4 align-items-center">
        <div class="col-md-9">
            <h1>Dashboard</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Daily Sales</h5>
                    <h2 class="card-text" id="dailySales">₱0.00</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Sales</h5>
                    <h2 class="card-text" id="totalSales">₱0.00</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Product Sold</h5>
                    <h2 class="card-text" id="productSold">0</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-5 col-md-8">
            <div class="card bg-light mb-4">
                <div class="card-body btn-custom">
                    <h3 class="card-title"><b>Inventory Details</b></h3>
                    <div class="order-det">
                        <h5 class="order-item">Available Stocks</h5>
                        <h6>800</h6>
                    </div>
                    <div class="order-det">
                        <h5 class="order-item">Low Stocks Item</h5>
                        <h6>800</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped cstm-tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Services Name</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>John Doe</td>
                    <td>john@example.com</td>
                    <td>Admin</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Jane Smith</td>
                    <td>jane@example.com</td>
                    <td>User</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Michael Johnson</td>
                    <td>michael@example.com</td>
                    <td>Moderator</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    
</div>
@endsection
