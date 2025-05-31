@extends('loyouts_user.app')

@section('content')
    @include('loyouts_user.slidebar')

    <div class="container py-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Brands</h5>
                    </div>
                    <div class="alert alert-info d-none">
                        Debug Info:<br>
                        Brand ID: {{ $brand->id }}<br>
                        Brand Name: {{ $brand->name }}<br>
                        Product Count: {{ $products->count() }}<br>
                        First Product Brand: {{ $products->first() ? $products->first()->brand_id : 'N/A' }}
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            @foreach ($brands as $brandItem)
                                <li class="mb-2">
                                    <a href="{{ route('brand.products', $brandItem->id) }}"
                                        class="text-decoration-none {{ $brandItem->id == $brand->id ? 'fw-bold text-primary' : '' }}">
                                        {{ $brandItem->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Products in Brand: {{ $brand->name }}</h3>
                    <div class="text-muted">{{ $products->count() }} products found</div>
                </div>

                @if ($products->count() > 0)
                    <div class="row g-4">
                        @foreach ($products as $product)
                            <div class="col-md-4 col-lg-3">
                                <div class="card h-100 shadow-sm">
                                    <div class="position-relative" style="height: 180px; overflow: hidden;">
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            class="card-img-top h-100 w-100 object-fit-cover" alt="{{ $product->name }}">
                                        @if ($product->stock_alert > 0)
                                            <span
                                                class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 small">
                                                In Stock: {{ $product->stock_alert }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ Str::limit($product->name, 30) }}</h5>
                                        <span class="h5 text-primary mb-0">
                                            ${{ number_format($product->selling_price, 2) }}
                                        </span>
                                        <div class="d-flex justify-content-between align-items-center mt-3">

                                            <a href="{{ route('product_client.show', $product->id) }}"
                                                class="btn btn-sm btn-outline-dark">
                                                View Details
                                            </a>
                                            <a href="{{ route('add.to.cart', $product->id) }}"
                                                class="btn btn-sm btn-outline-primary mt-auto">
                                                <i class="fas fa-cart-plus"></i> AddCart
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        No products found for this brand.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
