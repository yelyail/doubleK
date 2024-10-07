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
                        <table class="table cstm-tbl">
                            <tbody>
                                <tr>
                                    <td class="ttl">Total Available Stock</td>
                                    <td>{{ $totalStock ?? 0 }} Stock/s</td>
                                </tr>
                                <tr>
                                    <td class="ttl">Low Stock Items</td>
                                    <td>{{ $lowStockItems }} Item/s</td>
                                </tr>
                                <tr>
                                    <td class="ttl">Out of Stock Items</td>
                                    <td>{{ $outOfStockItems }} Item/s</td>
                                </tr>
                            </tbody>
                        </table>
                        <h3>Low Stock Products</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Stock Left</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($lowStockProducts) > 0)
                                    @foreach ($lowStockProducts as $productName)
                                        @php
                                            // Find the product by name
                                            $product = $products->where('product_name', $productName)->first();
                                        @endphp

                                        <tr>
                                            <td>{{ $productName }}</td>  
                                            <td>{{ $product->inventory->stock_qty ?? 'N/A' }}</td> <!-- Display stock_qty from the associated inventory -->
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2">No low stock items.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <h3>Out of Stock Products</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Stock Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($outOfStockProducts) > 0)
                                    @foreach ($outOfStockProducts as $product)
                                        <tr>
                                            <td>{{ $product }}</td> 
                                            <td>Out of Stock</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2">No out of stock items.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
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
                        @foreach ($bestSellers as $index => $bestSeller)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $bestSeller['product_name'] }}</td>
                                <td>{{ $bestSeller['units_sold'] }}</td>
                                <td>₱ {{ number_format($bestSeller['revenue'], 2) }}</td> <!-- Format revenue -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
