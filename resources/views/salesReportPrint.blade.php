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
        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            text-align: center;
            font-size: 20px;
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
            <div class="title-section">
                <h1>{{$title}}</h1>
                <h4><b>Reporting Period:</b> From: {{ $fromDate ? $fromDate : 'N/A' }} To: {{ $toDate ? $toDate : 'N/A' }}</h4>
                <h4><b>Total Sales:</b> Php {{$total_sales}}</h4>
                <h5>{{$order_id}}</h5>
                <h5>{{$date}}</h5>
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
                    <th>Customer Name</th>
                    <th>Particulars</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Amount Paid</th>
                    <th>Payment Method</th>
                    <th>Reference Number</th>
                    <th>Transaction Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderItems as $orderItem)
                    <tr>
                        <td>{{ ucwords(strtolower($orderItem['customer_name'])) }}</td>
                        <td>{{ $orderItem['particulars'] }}</td>
                        <td style="text-align:center">{{ $orderItem['quantity_ordered'] }}</td>
                        <td>Php {{ $orderItem['unit_price'] }}</td>
                        <td>Php {{ $orderItem['payment'] }}</td>
                        <td>Php {{ $orderItem['amount'] }}</td>
                        <td>{{ $orderItem['payment_type'] }}</td>
                        <td>{{ $orderItem['reference_num'] }}</td>
                        <td>{{ $orderItem['order_date'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p><b>Validated by:</b> <u>{{$representative}}</u></p>
        </div>
    </div>
</body>
</html>
