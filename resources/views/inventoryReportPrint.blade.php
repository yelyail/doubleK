<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20px;
        }
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            justify-content: space-between;
        }
        .header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            align-items: center;
        }
        .header div:last-child {
            text-align: right;
        }
        .company-info {
            width: 100%;
            text-align: center;
            margin-bottom: 10px;
        }
        .title-section {
            display: flex;
            flex-direction: column; 
        }
        table {
            width: 100%;
            border: 1px;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid darkgray;
        }
        td{
            font-size: 12px;
        }
        thead {
            text-align: center;
            background-color: #f2f2f2;
        }
        h5{
            margin: 0;
            font-weight: normal;
        }
        p{
            margin: 0;
            font-weight: normal;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="title-selection">
                <h1>{{ $title }}</h1>
                <h4><b>Reporting Period:</b> From: {{ $fromDate ? $fromDate : 'N/A' }} To: {{ $toDate ? $toDate : 'N/A' }}</h4>
                <h5>{{ $date }}</h5>
            </div>
            <div class="company-info">
                <h2>Double-K Computers</h2>
                <p>#20 Pag-Asa Street, S.I.R.<br> Matina, Phase 2, Barangay Bucana<br>Davao City, Philippines 8000</p>
            </div>
        </div>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category Name</th>
                    <th>Supplier Name</th>
                    <th>Current Stocks</th>
                    <th>Price</th>
                    <th>Warranty</th>
                    <th>Description</th>
                    <th>Date Added</th>
                    <th>Updated Stocks</th>
                    <th>Restock Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product['product_name'] }}</td>
                        <td>{{ $product['categoryName'] }}</td>
                        <td>{{ $product['supplierName'] }}</td>
                        <td style="text-align:center">{{ $product['stock_qty'] }}</td>
                        <td>{{ number_format($product['unit_price'], 2) }}</td>
                        <td style="text-align:center">{{ $product['warranty'] }} days</td>
                        <td>{{ $product['product_desc'] }}</td>
                        <td>{{ $product['prod_add'] }}</td>
                        <td>{{ $product['updatedQty'] }}</td>
                        <td>{{ $product['nextRestockDate'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer" style="display: flex; justify-content: space-between; align-items: center;">
            <p style="margin-top: 10px;"><b>Prepared by:</b> <u>{{ $representative }}</u></p>
            <p style="margin: 0; text-align: right;"><b>Validated by:</b> <u>{{ $adminName }}</u></p> 
        </div>
    </div>
</body>
</html>
