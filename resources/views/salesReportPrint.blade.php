<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            border: 1px solid black;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between; /* Space between items */
            align-items: center; /* Center vertically */
            margin-bottom: 20px;
            font-weight: bold;
        }
        .header h6 {
            margin: 0; /* Remove default margin */
        }
        .company-info {
            text-align: right;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        .footer {
            display: flex;
            justify-content: space-between; /* Space between items */
            margin-top: 20px;
            text-align: center;
        }
        h5{
            margin: 0;
            font-weight: normal;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Sales Report</h1>
                <h5>Reporting Period: from date to date</h5>
                <h5>Total Sales: Php 900.20</h5>
                <h5>Transaction Date: date and time</h5>
            </div>
            <div class="company-info">
                <h2>Your Company Name</h2>
                <p>1234 Your Address St.<br>Your City, State, ZIP</p>
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
                <!-- Data rows go here -->
            </tbody>
        </table>

        <div class="footer">
            <p><b>Prepared by:__________________________</b></p>
            <p><b>Validated by:__________________________</b></p>
        </div>
    </div>
</body>
</html>
