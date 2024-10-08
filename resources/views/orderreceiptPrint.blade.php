<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: monospace, sans-serif;
            margin: 0 auto;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .company-info{
            text-align: right;
            width: 45%;
        }
        .header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            align-items: center;
            margin-bottom: 20px;
        }
        .title-section {
            display: flex;
            flex-direction: column; 
        }

        .header div {
            width: 45%;
        }
        .header div:last-child {
            text-align: right;
        }
        .info {
            width: 100%;
            display: flex;
            justify-content: space-between; 
            align-items: flex-start; 
            margin-bottom: 20px; 
            flex-wrap: nowrap; 
        }
        .info {
            display: flex;
            justify-content: space-between; 
            align-items: flex-start; 
        }

        .left, .right {
            flex: 1;
            margin-right: 10px; 
        }

        p {
            margin: 5px 0; 
        }
        .full-line {
            width: 100%;
            height: 1px;
            background-color: black;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: center;
        }
        td:first-child {
            text-align: left;
        }
        td:last-child {
            text-align: center;
        }
        .footer {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }
        .summary {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            width: 100%;
        }
        .summary div {
            display: flex;
            width: 100%;
        }
        .summary div strong {
            margin-right: 10px;
        }
        h5 {
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
                <h5>{{$date}}</h5>
            </div>
            <div class="company-info">
                <h2>Double-K Computers</h2>
                <p>#20 Pag-Asa Street, S.I.R. Matina, Phase 2, Barangay Bucana<br>Davao City, Philippines 8000</p>
            </div>
        </div>

        <hr>

        <div class="info">
            <div class="left">
                <p><strong>Client:</strong>{{ ucwords(strtolower($customer_name))}}</p>
                <p><strong>Delivery Address: </strong>{{ucwords(strtolower($address))}}</p>
                <p><strong>Contact Person:</strong>{{ucwords(strtolower($customer_name))}}</p>
            </div>
            <div class="right">
                <p><strong>Cashier:</strong> {{ucwords(strtolower($representative))}}</p>
                <p><strong></strong>{{$date}}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Particulars</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderItems as $item)
                    <tr>
                        <td>
                            @if($item['product_name'])
                                {{ $item['product_name'] }}
                            @endif
                            @if($item['service_name'])
                                {{ $item['service_name'] }}
                            @endif
                        </td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>Php {{ $item['total_price'] }}</td>
                        <td>Php {{ number_format($total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            @php
                $totalQuantity = 0;
            @endphp
            @foreach($orderItems as $item)
                @php
                    $totalQuantity += $item['quantity']; // Use array syntax
                @endphp
            @endforeach

            <div>
                <p><strong>Total Quantity:</strong> #{{ $totalQuantity }}</p>
            </div>
            <div class="summary">
                <div>
                    <strong>Total Amount:</strong>
                    <span>Php {{ number_format($total_price, 2) }}</span>
                </div>
                <div>
                    <strong>Payment Method:</strong>
                    <span>{{$payment_type}}</span>
                </div>
                <div>
                    <strong>Amount Paid:</strong>
                    <span>{{ 'Php' . number_format($payment, 2) }}</span>
                </div>
                <div>
                    <strong>Change:</strong>
                    <span>Php {{ number_format($amount_deducted, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
