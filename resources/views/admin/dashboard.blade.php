@extends('admin.side')

@section('title', 'Double-K Computer')

@section('content')
<div class="main p-3">
    <div class="row mb-4 align-items-center">
        <div class="col-md-9">
            <h1 class="prod_title">Dashboard</h1>
        </div>
    </div>
    
    <div class="row">
    <!-- Inventory Status Column -->
    <div class="col-lg-6 col-md-6">
        <div class="card mb-4">
            <div class="card-body btn-custom">
                <h3 class="card-title"><b>Inventory Status</b></h3>
                <table class="table cstm-tbl">
                    <tbody>
                        <tr>
                            <td>Total Available Stock</td>
                            <td>800</td>
                        </tr>
                        <tr>
                            <td>Low Stock Items</td>
                            <td>3 Items</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Product</th>
                            <th scope="col">Stock Left</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Product X</td>
                            <td>50</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Best Sellers Column -->
    <div class="col-lg-6 col-md-6">
        <div class="card1 mb-4">
            <h1 class="card-title1">Best Sellers</h1>
            <div class="table-responsive">
                <table class="table table-striped cstm-tbl">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Product</th>
                            <th>Units Sold</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Product A</td>
                            <td>500</td>
                            <td>Php 10,000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
