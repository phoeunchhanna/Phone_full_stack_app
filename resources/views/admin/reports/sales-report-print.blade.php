<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Sales Report</title>
    <style>
        /* Add any print-specific styles here */
        body {
            font-family: battambong, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>របាយការណ៍ការលក់</h1>
    
    @if ($request->filled('date_range'))
        <p><strong>កាលបរិច្ឆេទដែលបានត្រួតពិនិត្យ:</strong> {{ $request->date_range }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>ឈ្មោះផលិតផល</th>
                <th>កាលបរិច្ឆេទ</th>
                <th>លេខយោង</th>
                <th>ឈ្មោះអតិថិជន</th>
                <th>បរិមាណ</th>
                <th>តម្លៃឯកតា</th>
                <th>បញ្ចុះតម្លៃ</th>
                <th>តម្លៃសរុប</th>
                <th>វិធីសាស្ត្រទូទាត់</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sale->product->name }}</td>
                <td>{{ $sale->sale->date }}</td>
                <td>{{ $sale->sale->reference }}</td>
                <td>{{ $sale->sale->customer->name ?? 'N/A' }}</td>
                <td>{{ $sale->quantity }}</td>
                <td>${{ number_format($sale->unit_price, 2) }}</td>
                <td>{{ $sale->discount }}</td>
                <td>${{ number_format($sale->quantity * $sale->unit_price, 2) }}</td>
                <td>{{ ucfirst($sale->sale->payment_method) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
