@extends('loyouts_user.app')
@section('content')
    @include('loyouts_user.slidebar')

    <section class="py-2 bg-secondary">
        <div class="container">
            {{-- Products by Brand --}}
            <div class="row g-4">
                @foreach ($brands as $brand)
                    @if ($brand->products->count())
                        <div class="d-flex justify-content-between align-items-center mt-5 mb-4">
                            <h2 class="text-white mb-0">
                                Brands: <span class="text-warning">{{ $brand->name }}</span>
                            </h2>
                            <a href="{{ route('brand.products', $brand->id) }}" class="btn btn-outline-warning">
                                View All Products in {{ $brand->name }}
                            </a>
                        </div>

                        <div class="row g-4">
                            @foreach ($brand->products->take(8) as $product)
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <a href="{{ route('product_client.show', $product->id) }}"
                                        class="text-decoration-none text-dark">
                                        <div
                                            class="bg-white rounded-3 shadow-sm h-100 d-flex flex-column overflow-hidden transition-all">
                                            <div class="position-relative overflow-hidden p-3"
                                                style="height: 250px; background-image: url('{{ asset('storage/' . $product->image) }}'); background-size: cover; background-position: center;">
                                                <div class="position-absolute bottom-0 start-0 p-2">
                                                    <span class="badge text-black bg-warning">
                                                        Stock: {{ $product->quantity }} | Alert: {{ $product->stock_alert }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="p-3 d-flex flex-column flex-grow-1">
                                                <h3 class="h5 fw-semibold mb-2">{{ $product->name }}</h3>
                                                <div class="text-primary fw-bold mb-3">
                                                    ${{ number_format($product->selling_price, 2) }}
                                                </div>
                                                <a href="{{ route('add.to.cart', $product->id) }}"
                                                    class="btn btn-sm btn-outline-primary mt-auto">
                                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                                </a>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>



            @include('loyouts_user.product_list.product_sale_list')
        </div>
    </section>
@endsection
