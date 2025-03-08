{{-- <div class="table-responsive">
    <table class="table">
        <thead class="table-primary">
            <tr>
                <th>ឈ្មោះផលិតផល</th>
                <th>បរិមាណក្នុងស្តុក</th>
                <th>តម្លៃឯកត្តា</th>
                <th>ចំនួន</th>
                <th class="text-right">សរុបរង</th>
                <th class="text-right">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPrice = 0;
            @endphp
            @if (!empty($cart))
                @foreach($cart as $item)
                    @php
                        $product = \App\Models\Product::find($item['product_id']);
                    @endphp
                    @if ($product)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $product->quantity ?? 'N/A' }}</td>
                            <td>{{ number_format($item['price'], 2) }}</td>
                            <td>
                                <input type="number" name="quantity[{{ $item['product_id'] }}]" value="{{ $item['quantity'] }}" min="1" class="form-control">
                            </td>
                            <td class="text-right">
                                @php
                                    $itemTotal = $item['quantity'] * $item['price'];
                                    $totalPrice += $itemTotal;
                                @endphp
                                {{ number_format($itemTotal, 2) }}
                            </td>
                            <td class="text-right">
                                <a href="{{ route('cart.remove', $item['product_id']) }}" class="btn btn-danger btn-sm">លុប</a>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="6" class="text-center">Product not found</td>
                        </tr>
                    @endif
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">Your cart is empty.</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><strong>តម្លៃសរុប:</strong></td>
                <td class="text-right">{{ number_format($totalPrice, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</div> --}}
< @if (!empty($cart) && count($cart) > 0)
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Barcode</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cart as $item)
                                                <tr>
                                                    <td>{{ $item['name'] }}</td>
                                                    <td>{{ $item['barcode'] }}</td>
                                                    <td>{{ $item['quantity'] }}</td>
                                                    <td>{{ $item['price'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>No items in the cart.</p>
                                @endif