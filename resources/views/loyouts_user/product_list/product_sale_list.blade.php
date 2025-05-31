{{-- product_sale_list.blade.php --}}
@if (isset($products) && count($products))
    <div class="container py-4 px-0">
        <h2 class="my-4 text-white">All Products</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
            @foreach ($products as $product)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <a href="{{ route('product_client.show', ['id' => $product->id]) }}">
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top p-4"
                                alt="{{ $product->name }}"
                                style="min-height: 250px; max-height: 250px; object-fit: contain;">
                        </a>
                        <div class="card-body bg-light">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="badge text-black bg-warning">
                                    Stock: {{ $product->quantity }} | Alert: {{ $product->stock_alert }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="badge rounded-pill text-primary fw-bold" >
                                    ${{ number_format($product->selling_price, 2) }}
                                </span>
                                <a href="{{ route('product_client.show', ['id' => $product->id]) }}"
                                    class="small text-muted text-uppercase fw-bold text-decoration-none">REVIEWS</a>
                            </div>
                            <h3 class="card-title fs-6 fw-light" style="height: 56px; overflow: hidden;">
                                <p class="text-dark text-bold fw-bold">{{ $product->name }}</p>
                                <p class="text-dark">{{ Str::limit($product->description, 30, '...') }}</p>
                            </h3>
                            <div class="d-grid gap-2 my-4">
                                <a href="{{ route('add.to.cart', $product->id) }}" class="btn  text-dark btn-outline-primary ">
                                   <i class="fa fa-cart-plus m-2">BUY NOW</i> 
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
