<div class="invoice">
    <h3>🧾 វិក័យប័ត្រត្រឡប់ទំនិញ</h3>
    <p><strong>លេខវិក័យប័ត្រ:</strong> {{ $saleReturn->reference }}</p>
    <p><strong>អតិថិជន:</strong> {{ $saleReturn->customer->name }}</p>
    <p><strong>កាលបរិច្ឆេទ:</strong> {{ $saleReturn->date }}</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ផលិតផល</th>
                <th>ចំនួន</th>
                <th>តម្លៃរាយ ($)</th>
                <th>សរុប ($)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($saleReturn->details as $detail)
                <tr>
                    <td>{{ $detail->product->name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>${{ number_format($detail->unit_price, 2) }}</td>
                    <td>${{ number_format($detail->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>ចំនួនទឹកប្រាក់សរុប:</strong> ${{ number_format($saleReturn->total_amount, 2) }}</p>
    <p><strong>បញ្ចុះតម្លៃ:</strong> ${{ number_format($saleReturn->discount, 2) }}</p>
    <p><strong>ចំនួនទឹកប្រាក់បានបង់:</strong> ${{ number_format($saleReturn->paid_amount, 2) }}</p>
    <p><strong>ចំនួនទឹកប្រាក់ដែលនៅសល់:</strong> ${{ number_format($saleReturn->due_amount, 2) }}</p>
    <p><strong>ស្ថានភាព:</strong> {{ $saleReturn->status }}</p>
    <p><strong>ស្ថានភាពបង់ប្រាក់:</strong> {{ $saleReturn->payment_status }}</p>
</div>
