@extends('loyouts_user.app')

@section('content')
    <div class="container mt-5">
        <h2>Checkout</h2>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white ">Customer Information</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('checkout.process') }}">
                            @csrf

                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>

                            <div class="form-group">
                                <label for="address">Delivery Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="payment_method">Payment Method</label>
                                <select class="form-control" id="payment_method" name="payment_method" required>
                                    <option value="cash">Cash on Delivery</option>
                                    <option value="card">Credit/Debit Card</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Discount Options</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="discount_type" id="noDiscount"
                                        value="none" checked>
                                    <label class="form-check-label" for="noDiscount">
                                        No Discount
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="discount_type"
                                        id="percentageDiscount" value="percentage">
                                    <label class="form-check-label" for="percentageDiscount">
                                        Percentage Discount
                                    </label>
                                    <input type="number" class="form-control mt-2" id="discount" name="discount"
                                        min="0" max="100" placeholder="0-100%" disabled>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="discount_type" id="fixedDiscount"
                                        value="fixed">
                                    <label class="form-check-label" for="fixedDiscount">
                                        Fixed Amount Discount
                                    </label>
                                    <input type="number" class="form-control mt-2" id="discount_amount"
                                        name="discount_amount" min="0" placeholder="Amount" disabled>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">Order Summary</div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th class="text-end">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart as $id => $item)
                                    <tr class="align-middle">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('storage/' . $item['image']) }}"
                                                    alt="{{ $item['name'] }}" class="img-thumbnail me-2"
                                                    style="width: 50px; height: 50px;">
                                                <span>{{ $item['name'] }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span>{{ $item['quantity'] }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end">Subtotal:</td>
                                    <td class="text-end">${{ number_format($subtotal, 2) }}</td>
                                </tr>
                                <tr class="text-danger">
                                    <td colspan="2" class="text-end">Discount:</td>
                                    <td class="text-end" id="discountDisplay">$0.00</td>
                                </tr>
                                <tr class="fw-bold">
                                    <td colspan="2" class="text-end">Total:</td>
                                    <td class="text-end" id="totalDisplay">${{ number_format($subtotal, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

@section('scripts')
    <script>
        $(document).ready(function() {
            // Enable/disable discount inputs based on selection
            $('input[name="discount_type"]').change(function() {
                const type = $(this).val();
                $('#discount, #discount_amount').prop('disabled', true).val('');

                if (type === 'percentage') {
                    $('#discount').prop('disabled', false).focus();
                } else if (type === 'fixed') {
                    $('#discount_amount').prop('disabled', false).focus();
                }

                updateTotals();
            });

            // Update totals when discount values change
            $('#discount, #discount_amount').on('input', updateTotals);

            function updateTotals() {
                const subtotal = {{ $subtotal }};
                let discount = 0;
                const discountType = $('input[name="discount_type"]:checked').val();

                if (discountType === 'percentage') {
                    const percent = parseFloat($('#discount').val()) || 0;
                    discount = (subtotal * percent) / 100;
                } else if (discountType === 'fixed') {
                    discount = parseFloat($('#discount_amount').val()) || 0;
                    if (discount > subtotal) discount = subtotal;
                }

                const total = subtotal - discount;

                $('#discountDisplay').text('$' + discount.toFixed(2));
                $('#totalDisplay').text('$' + total.toFixed(2));
            }
        });
    </script>
@endsection
@endsection
