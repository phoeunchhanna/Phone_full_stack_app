<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #eee;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .info p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h2>PC&Code</h2>
            <p>Phone Shop</p>
            <p>Date: {{ $order->date->format('Y-m-d') }}</p>
        </div>

        <div class="info">
            <h4>Order Details</h4>
            <p><strong>Order Number:</strong> {{ $order->reference }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>

        <div class="info">
            <h4>Customer Information</h4>
            <p><strong>Name:</strong> {{ $order->customer->name }}</p>
            <p><strong>Phone:</strong> {{ $order->customer->phone }}</p>
            <p><strong>Address:</strong> {{ $order->customer->address }}</p>
        </div>

        <h4>Order Items</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->saleDetails as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>${{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                    <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <p>Thank you for your purchase!</p>
        </div>
    </div>

    <script>
        window.onload = function () {
            window.print();
        }
    </script>
</body>
</html>
