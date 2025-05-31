    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <body class="mt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-success text-white">Order Confirmation</div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <h4 class="mb-4">Thank you for your order!</h4>

                            <div class="mb-4">
                                <h5>Order Details</h5>
                                <p><strong>Order Number:</strong> {{ $order->reference }}</p>
                                <p><strong>Date:</strong> {{ $order->date->format('d-m-Y') }}</p>
                                <p><strong>Status:</strong> <span
                                        class="badge badge-success">{{ ucfirst($order->status) }}</span></p>
                            </div>

                            <div class="mb-4">
                                <h5>Customer Information</h5>
                                <p><strong>Name:</strong> {{ $order->customer->name }}</p>
                                <p><strong>Phone:</strong> {{ $order->customer->phone }}</p>
                                <p><strong>Address:</strong> {{ $order->customer->address }}</p>
                            </div>

                            <div class="table-responsive mb-4">
                                <h5>Order Items</h5>
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
                                        @foreach ($order->saleDetails as $item)
                                            <tr>
                                                <td>{{ $item->product->name }}</td>
                                                <td>${{ number_format($item->unit_price, 2) }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>${{ number_format($item->total_price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Total</th>
                                            <th>${{ number_format($order->total_amount, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('client.home') }}" class="btn btn-primary">Continue Shopping</a>
                                <a href="{{ route('order.print') }}" class=" ml-2">
                                    <button class="btn btn-secondary ml-2"><i class="fa fa-print"></i>Print</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
