<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>វិក័យប័ត្របង្វែចូលទំនិញ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .invoice-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1, .footer p {
            margin: 0;
        }
        .customer-info, .product-table {
            margin-bottom: 20px;
        }
        .product-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .product-table th, .product-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .product-table th {
            background-color: #f2f2f2;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .total-section table {
            width: 40%;
            margin: 0 auto;
        }
        .total-section td {
            padding: 8px;
        }
    </style>
</head>
<body>

<div class="invoice-container">

    <h2 style="text-align: center;">វិក័យប័ត្រប្តូរទំនិញ</h2>

    <div class="customer-info">
        <p><strong>ឈ្មោះអតិថិជន:</strong> {{ $customer->name }}</p>
        <p><strong>ទូរស័ព្ទ:</strong> {{ $customer->phone }}</p>
        <p><strong>យោងវិក័យប័ត្រដើម:</strong> {{ $saleReturn->sale->reference }}</p>
        <p><strong>កាលបរិច្ឆេទ:</strong> {{ $saleReturn->created_at->format('Y-m-d') }}</p>
    </div>

    <div class="product-table">
        <table>
            <thead>
                <tr>
                    <th>ឈ្មោះទំនិញ</th>
                    <th>បរិមាណ</th>
                    <th>តម្លៃមុខងារ</th>
                    <th>តម្លៃសរុប</th>
                </tr>
            </thead>
            <tbody>
                @foreach($saleReturnDetails as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->return_quantity }}</td>
                    <td>${{ number_format($detail->unit_price, 2) }}</td>
                    <td>${{ number_format($detail->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="total-section">
        <table>
            <tr>
                <td><strong>ចំនួនប្រាក់បង្វែចូលទំនិញសរុប:</strong></td>
                <td>${{ number_format($saleReturn->total_return_amount, 2) }}</td>
            </tr>
            <tr>
                <td><strong>កាត់ថវិកា:</strong></td>
                <td>-${{ number_format($saleReturn->deduction, 2) }}</td>
            </tr>
            <tr>
                <td><strong>ចំនួនប្រាក់បង្វែចូលទំនិញសរុប:</strong></td>
                <td>${{ number_format($saleReturn->net_return_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="payment-info">
        <p><strong>វិធីសាស្ត្រទូទាត់:</strong> {{ $saleReturn->payment_method }}</p>
        <p><strong>ចំនួនប្រាក់បានបង់:</strong> ${{ number_format($saleReturn->amount_paid, 2) }}</p>
        <p><strong>ចំនួនប្រាក់នៅសល់:</strong> ${{ number_format($saleReturn->remaining_amount, 2) }}</p>
    </div>

    <div class="footer">
        <p>អរគុណសម្រាប់ការជាវជាមួយយើង!</p>
        <p>លក្ខខណ្ឌនិងកំណត់: ការបង្វែចូលត្រូវបានអនុញ្ញាតឱ្យធ្វើក្នុងរយៈពេល 7 ថ្ងៃពីការជាវ។</p>
    </div>
</div>

</body>
<script>
    window.onload = function() {
        window.print();
    };

    window.onafterprint = function() {
        window.location.href = "{{route('sale-returns.index')}}";
    };

    let body = document.body;
    let html = document.documentElement;
    let height = Math.max(
        body.scrollHeight, body.offsetHeight,
        html.clientHeight, html.scrollHeight, html.offsetHeight
    );

    document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    document.cookie = "innerHeight=" + ((height + 50) * 0.264583);
</script>
</html>
