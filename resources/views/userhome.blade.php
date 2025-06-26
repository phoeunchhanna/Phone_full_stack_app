@extends('loyouts_user.app')
@section('content')
    @include('loyouts_user.slidebar')
    <section class="p-3">
        <div class="container">
            @include('loyouts_user.product_list.brand_show')
            @include('loyouts_user.product_list.category_show')
            {{-- Products by Brand --}}
            @foreach ($brands as $brand)
                @if ($brand->products->count())
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 mb-4 px-3 px-md-0">
                        <h2 class="text-white mb-3 mb-md-0 text-center text-md-start">
                            Brands: <span class="text-warning">{{ $brand->name }}</span>
                        </h2>
                        <a href="{{ route('brand.products', $brand->id) }}" class="btn btn-outline-warning">
                            View All Products in {{ $brand->name }}
                        </a>
                    </div>

                    <div
                        class="row g-4  d-flex flex-column flex-md-row justify-content-between align-items-center ">
                        @foreach ($brand->products->take(4) as $product)
                            <div class="col-10 col-sm-6 col-md-4 col-lg-3  ">
                                <a href="{{ route('product_client.show', $product->id) }}"
                                    class="text-decoration-none text-dark ">
                                    <div class="bg-white rounded-3  h-130 d-flex flex-column overflow-hidden transition-all hover-effect"
                                        style="max-width: 300px;">
                                        <div class="position-relative overflow-hidden p-3"
                                            style="height: 200px; background-image: url('{{ asset('storage/' . $product->image) }}'); background-size: cover; background-position: center;">
                                            <div class="position-absolute bottom-0 start-0 p-2">
                                                <span class="badge text-black bg-warning">
                                                    Stock: {{ $product->quantity }} | Alert: {{ $product->stock_alert }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="p-3 d-flex flex-column flex-grow-1">
                                            <h3 class="h5 fw-semibold mb-2 text-center product-title">{{ $product->name }}
                                            </h3>
                                            <div class="text-primary fw-bold mb-3 text-center product-price">
                                                ${{ number_format($product->selling_price, 2) }}
                                            </div>
                                            <a href="{{ route('add.to.cart', $product->id) }}"
                                                class="btn btn-sm btn-outline-primary mt-auto mx-auto cart-button"
                                                style="width: fit-content;">
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
            @include('loyouts_user.product_list.product_sale_list')


        </div>
    </section>
    <style>
        .hover-effect {
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .hover-effect:hover {
            background-color: rgba(164, 206, 252, 0.4) !important;
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(251, 255, 15, 0.801);
            border-color: #ffc107 !important;
        }

        /* New hover styles */
        .hover-effect:hover .product-title,
        .hover-effect:hover .product-price {
            color: white !important;
        }

        .hover-effect:hover .cart-button {
            background-color: #ffc107 !important;
            border-color: #ffc107 !important;
            color: black !important;
        }
    </style>
@endsection
