<!-- resources/views/print_report_sale.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <style>
        /* Add your custom styles here */
        body {
            font-family: DejaVu Sans, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Sales Report</h1>
    <p>From: {{ $start_date }} To: {{ $end_date }}</p>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Reference Number</th>
                <th>Customer Name</th>
                <th>Total Amount</th>
                <th>Discount</th>
                <th>Paid Amount</th>
                <th>Due Amount</th>
                <th>Status</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $index => $sale)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sale->reference }}</td>
                    <td>{{ $sale->customer->name }}</td>
                    <td>{{ $sale->total_amount }}</td>
                    <td>{{ $sale->discount }}</td>
                    <td>{{ $sale->paid_amount }}</td>
                    <td>{{ $sale->due_amount }}</td>
                    <td>{{ $sale->status }}</td>
                    <td>{{ $sale->payment_status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
