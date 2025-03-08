$(document).ready(function() {
    function getCarts() {
        $.ajax({
            type: 'get',
            url: "carts",
            dataType: "json",
            success: function(response) {
                let total = 0;
                $('tbody').html("");
                $.each(response.carts, function(key, product) {
                    total += product.price * product.quantity
                    $('tbody').append(`
                   <tr>
                       <td>${product.name}</td>
                       <td class="class="col-sm-3"">
                           <input type="number" class="form-control form-control-sm qty" min="1" max="${product.stock}" value="${product.quantity}"/>
                           <input type="hidden" class="cartId" value="${product.id}" />
                       </td>
                       <td class="text-right">
                       ${ product.quantity * product.price}
                       </td>
                       <td>
                       <button type="button" class="btn btn-danger btn-sm delete" value="${product.id}">
                           <i class="fas fa-trash"></i>
                       </button>
                       </td>
                       
                   </tr>`)
                });
                const test = $('.total').attr('value', `${total}`);
            }
        })
    }
    getCarts()
    // Barcode input handler
    $('#barcodeInput').on('input', function(e) {
        let barcode = $(this).val();

        if (barcode.length >= 8) { // Adjust length to typical barcode length
            $.ajax({
                type: 'post',
                url: "/carts/add",
                data: {
                    barcode
                },
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 200) {
                        getCarts(); // Update cart display after adding
                    } else {
                        alert(response.message); // Error handling if product not found
                    }
                }
            });
            $(this).val(''); // Clear the input after processing
        }
    });

    // Quantity update
    $(document).on('change', '.qty', function() {
        const qty = $(this).val();
        const cartId = $(this).closest('td').find('.cartId').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'put',
            url: `carts/${cartId}`,
            data: {
                qty
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 400) {
                    alert(response.message);
                }
                getCarts()
            }
        })
    })
    function formatPrice(value) {
       return value.toLocaleString('en-US', {
           minimumFractionDigits: 0,
           maximumFractionDigits: 0
       });
   }

    $(document).on('keyup', '.search', function() {
        const search = $(this).val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: `products/search`,
            data: {
                search
            },
            dataType: 'json',
            success: function(response) {
                $('#show').html("");
                $.each(response, function(key, product) {
                    console.log('value is ', product);
                    $('#show').append(`

                                <div class="col" >
                                    <button type="button" class="item custom-btn  fs-7 px-1 w-100"
                                        id="addToCartBtn"
                                        style="cursor: pointer; border: none; background: transparent;"
                                        value="${product.id}">
                                        <div
                                            class="order-product product-search d-flex justify-content-center align-items-center">
                                            <div class="card border shadow-sm rounded mb-2"
                                                style="height: 100%; width: 100%;">
                                                <!-- Product Info -->
                                                <div class="first"
                                                    style="position: absolute;width: 100%;padding-left: 4px;padding-top: 0px;">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="discount"
                                                            style="background-color: #3d5ee1;padding-top: 1px;padding-bottom: 1px;padding-left: 5px;padding-right: 5px;font-size: 10px;border-radius: 4px;color: #fff;">
                                                            ${product.quantity}</span>
                                                        <span class="wishlist"><i class="fa fa-heart-o"></i></span>
                                                    </div>
                                                </div>
                                                <!-- Product Image -->
                                                @if ($product->image)
                                                    <img src="${product.image}" class="card-img-top"alt="${product.name}"style="object-fit: cover; height: 100%; width: 100%;" />
                                                @endif
                                                <!-- Product Details -->
                                                <div class="card-body d-flex flex-column justify-content-center"
                                                    style="padding: 10px;">
                                                    <div class="card-body d-flex flex-column justify-content-center"
                                                        style="padding: 10px;">
                                                        <h5 class="card-title text-truncate text-start"
                                                            style="font-size: 14px;">${product.name}</h5>
                                                        <h5 class="card-title text-truncate text-start"
                                                            style="font-size: 14px;">
                                                           ${formatPrice(product.selling_price * 4000)}៛
                                                        </h5>
                                                        <h5 class="card-title text-truncate text-start"
                                                            style="font-size: 14px; color: #3d5ee1;">
                                                            ${product.selling_price}$
                                                            </h5>
                                                    </div>
                                                    {{-- <p class="card-text" style="font-size: 14px; color: #333;">{{ number_format($product->selling_price, 2) }}$</p> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </div>
       `);

                    // Option 2: Store a product attribute in a custom attribute (e.g., 'data-show') of #show
                    $('#show').attr('data-show', product
                        .name); // Change 'product.name' as needed
                });
            }
        });
    });

    // delete
    $(document).on('click', '.delete', function() {
        const cartId = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $.ajax({
            type: 'delete',
            url: `/carts/${cartId}`,
            success: function(response) {
                if (response.status === 400) {
                    alert(response.message);
                } else if (response.status === 200) {
                    getCarts();
                }
            },
            error: function(xhr) {
                alert('Error deleting cart item. Please try again.');
                console.log(xhr.responseText);
            }
        });
    });

    $('.scan').click(function(e) {
        e.preventDefault();
        const barcode = $(this).closest('form').find('.barcode').val();
        console.log('Barcode:', barcode); // Log the barcode value
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: `carts`,
            data: {
                barcode
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 400 || response.status === 500) {
                    alert(response.message);
                }
                getCarts()
            }
        })
    });
    $(document).on('click', '.category-btn', function() {
        const categoryId = $(this).val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'GET',
            url: `/carts/filter/${categoryId}`,
            dataType: 'json',
            success: function(response) {
                $('.product-search').html("");
                $.each(response.products, function(key, product) {
                    $('.product-search').append(`
                       <div class="col">
                           <button type="button" class="item custom-btn fs-7 px-1 w-100" id="addToCartBtn" style="cursor: pointer; border: none; background: transparent;" value="${product.id}">
                               <div class="order-product d-flex justify-content-center align-items-center">
                                   <div class="card border shadow-sm rounded mb-2" style="height: 100%; width: 100%;">
                                       <div class="first" style="position: absolute;width: 100%;padding-left: 4px;padding-top: 0px;">
                                           <div class="d-flex justify-content-between align-items-center">
                                               <span class="discount" style="background-color: #3d5ee1;padding: 1px 5px;font-size: 10px;border-radius: 4px;color: #fff;">
                                                   ${product.quantity}
                                               </span>
                                               <span class="wishlist"><i class="fa fa-heart-o"></i></span>
                                           </div>
                                       </div>
                                       ${product.image ? `<img src="${product.image}" class="card-img-top" alt="${product.name}" style="object-fit: cover; height: 100%; width: 100%;" />` : ''}
                                       <div class="card-body d-flex flex-column justify-content-center" style="padding: 10px;">
                                           <h5 class="card-title text-truncate text-start" style="font-size: 14px;">${product.name}</h5>
                                           <h5 class="card-title text-truncate text-start" style="font-size: 14px;">${(product.selling_price * 4100).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')}៛</h5>
                                           <h5 class="card-title text-truncate text-start" style="font-size: 14px; color: #3d5ee1;">${product.selling_price.toFixed(2)}</h5>
                                       </div>
                                   </div>
                               </div>
                           </button>
                       </div>
                   `);
                });
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Error fetching products. Please try again.');
            }
        });
    });
    $(document).on('click', '.item', function() {
        const productId = $(this).val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'post',
            url: `carts`,
            data: {
                productId
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 400) {
                    alert(response.message);
                }
                getCarts()
            }
        })
    })
})