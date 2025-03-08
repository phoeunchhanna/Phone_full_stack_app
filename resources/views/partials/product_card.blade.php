
<div class="col">
    <button type="button" class="item custom-btn fs-7 px-1 w-100" id="addToCartBtn"
        style="cursor: pointer; border: none; background: transparent;" value="{{ $product->id }}">
        <div class="order-product product-search d-flex justify-content-center align-items-center">
            <div class="card border mb-2"
                style="height: 200px; width: 100%; overflow: hidden; box-shadow: 0 4px 8px rgba(34, 34, 34, 0.2); border-radius: 10px;">
                <div class="first"
                    style="position: absolute; width: 100%; padding-left: 4px; padding-top: 0px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="discount"
                            style="background-color: #3d5ee1; padding: 2px 5px; font-size: 10px; border-radius: 4px; color: #fff;">ស្តុក:
                            {{ $product->quantity }}</span>
                        <span class="wishlist"><i class="fa fa-heart-o"></i></span>
                    </div>
                </div>
                <div>
                    <img src="{{ asset($product->image) }}" class="card-img-top" alt="${product.name}"
                        style="width: 120px; height: 120px; object-fit: cover;" />
                </div>
                <div class="card-body d-flex flex-column justify-content-center" style="padding: 10px;">
                    <h5 class="card-title text-truncate" style="font-size: 14px;">{{ $product->name }}</h5>
                    <h5 class="card-title text-truncate" style="font-size: 14px; color: #3d5ee1;">
                        ${{ number_format($product->selling_price, 2) }}
                    </h5>
                </div>
            </div>
        </div>
    </button>
</div>
