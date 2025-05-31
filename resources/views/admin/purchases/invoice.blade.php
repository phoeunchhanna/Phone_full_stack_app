<div class="invoice-content">
    <h4 class="text-center">Invoice #{{ $purchase->reference }}</h4>
    <p>Date: {{ $purchase->date }}</p>
    <p>Supplier: {{ $purchase->supplier->name }}</p>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart as $productId => $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h5>Total Amount: ${{ number_format($purchase->total_amount, 2) }}</h5>
    <h5>Discount: ${{ number_format($purchase->discount, 2) }}</h5>
    <h5>Paid Amount: ${{ number_format($purchase->paid_amount, 2) }}</h5>
</div>
