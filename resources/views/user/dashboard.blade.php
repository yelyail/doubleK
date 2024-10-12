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
            <a href="{{ route('adminInventory') }}" class="text-decoration-none">
                <div class="card mb-4">
                    <div class="card-body btn-custom">
                        <h3 class="card-title"><b>Inventory Status</b></h3>
                        <div class="inventory-status">
                            <table class="table cstm-tbl">
                                <tbody>
                                    <tr>
                                        <td class="ttl">Total Available Stock</td>
                                        <td>{{ $totalStock }} Stock/s</td>
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

                            <!-- Display Low Stock Products -->
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
                                                <td>{{ $product->inventory->stock_qty ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2">No low stock items.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                            <!-- Display Out of Stock Products -->
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
            </a>
        </div>

        <!-- Best Sellers Column -->
        <div class="col-lg-6 col-md-6">
            <div class="card1 mb-4">
                <h1 class="card-title1">Best Sellers</h1>
                <div class="table-responsive">
                    <table class="table table-striped cstm-tbl">
                        <thead>
                            <tr>
                                <th style="text-align: center;">Rank</th>
                                <th>Product</th>
                                <th>Units Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bestSellers as $index => $bestSeller)
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td>{{ $bestSeller['product_name'] }}</td>
                                    <td>{{ $bestSeller['units_sold'] }}</td>
                                    <td>Php {{ number_format($bestSeller['revenue'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Best Sellers by Customer -->
                    <h1 class="card-title1">Reserve and Debt Overview</h1>
                    <div class="table-responsive">
                        <table class="table table-striped cstm-tbl">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Product Name</th>
                                    <th>Total Price</th>
                                    <th>Reserve Date</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customerOrders as $customerOrder)
                                    <tr>
                                        <td>{{ ucwords(strtolower($customerOrder['customer_name'] ))}}</td>
                                        <td>{{ ucwords(strtolower($customerOrder['product_name'])) }}</td>
                                        <td>Php {{ number_format($customerOrder['total_price'], 2) }}</td>
                                        <td>{{ $customerOrder['reserve_date'] }}</td>
                                        <td>{{ucwords(strtolower( $customerOrder['type'])) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
