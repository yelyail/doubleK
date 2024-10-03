@extends('user.side')

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
                    <div class="inventory-status">
                        
                        <!-- Display Low Stock Products -->
                        <h3>Low Stock Products</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Stock Left</th>
                                </tr>
                            </thead>
                           
                        </table>
                    </div>
            </div>
        </div>
    </div>

</div>
@endsection
