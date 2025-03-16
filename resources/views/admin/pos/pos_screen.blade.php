@extends('layouts.master')
@section('content')
    {{-- Message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">ផ្ទាំងលក់ផលិតផល</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-12 col-sm-10">
                                    <div class="form-group">
                                        <select id="product-search" class="form-select select2"
                                            aria-label="ស្វែងរកតាមឈ្មោះផលិតផល ឬបាកូដ...">
                                            <option value="" disabled selected>ស្វែងរកតាមឈ្មោះផលិតផល ឬបាកូដ...
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-2">
                                    <div class="form-group">
                                        <select id="product-quantity" class="form-select form-control"
                                            aria-label="Select number of products">
                                            <option value="15" selected>15</option>
                                            <option value="30">30</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2 ">
                                <div class="d-flex align-items-center">
                                    <!-- Scroll Left Button -->
                                    <button class="btn btn-light border me-2" id="scroll-left">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>

                                    <div class="col overflow-auto">
                                        <div id="category-buttons" class="d-flex"
                                            style="overflow: hidden; white-space: nowrap;">
                                            <!-- Horizontal scrolling for category buttons -->
                                            <button class="btn btn-warning category-button me-2"
                                                style="scroll-behavior: smooth;" data-category="All">ទាំងអស់</button>
                                            @foreach ($categories as $category)
                                                <button class="btn btn-primary category-button me-2"
                                                    data-category="{{ $category->name }}">{{ $category->name }}</button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- Scroll Right Button -->
                                    <button class="btn btn-light border ms-2" id="scroll-right">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card">
                                <hr>
                                <div class="card-body" style="height: 545px; overflow-y: auto;">
                                    @if ($products->isEmpty())
                                        <div class="alert alert-danger text-center fs-3">គ្មានទិន្នន័យ!</div>
                                    @else
                                        <div class="mt-0">
                                            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 g-1" id="show">
                                                @foreach ($products->take(15) as $product)
                                                    @if ($product->quantity > 0)
                                                        @include('partials.product_card', [
                                                            'product' => $product,
                                                        ])
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-5">
                                <div class="input-group">
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#createcustomers">
                                        បន្ថែម <i class="fas fa-plus"></i>
                                    </button>
                                    <select class="form-select form-control" id="customerSelect" name="customer_id"
                                        required>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="table-responsive mt-2" style="height:  520px; overflow-y: auto;">
                                    <table class="table-hover table-center mb-4 table table-stripped">
                                        <thead class="" style="background-color: #0d6efd;color: white;">
                                            <tr>
                                                <th>ល.រ</th>
                                                <th>ឈ្មោះផលិតផល</th>
                                                <th>ចំនួន</th>
                                                <th>សរុបរង</th>
                                                <th class="text-end">សកម្មភាព</th>
                                            </tr>
                                        </thead>
                                        <tbody id="poscart_table"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="invoice-total-card align-self-end">
                                <div class="invoice-total-box">
                                    <div class="invoice-total-footer">
                                        <h4 class="grandTotal">ទឹកប្រាក់សរុប <span id="display_grandTotal">0.00 $</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <a type="button" class="btn btn-danger btn-block btn-clear btn-lg" id="btn_remove">
                                        <i class="bi bi-x-circle"></i> បោះបង់
                                    </a>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-primary btn-block btn-lg" id="checkoutButton"
                                        data-bs-toggle="modal" data-bs-target="#checkoutModal">
                                        <i class="bi bi-credit-card"></i> បង់ប្រាក់
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.pos.modal.confirm_sale')
    @include('admin.customers.modal.create')
    <script>
        document.getElementById('scroll-left').addEventListener('click', function() {
            const container = document.getElementById('category-buttons');
            container.scrollBy({
                left: -150,
                behavior: 'smooth'
            }); // Scroll left by 150px
        });

        document.getElementById('scroll-right').addEventListener('click', function() {
            const container = document.getElementById('category-buttons');
            container.scrollBy({
                left: 150,
                behavior: 'smooth'
            }); // Scroll right by 150px
        });
    </script>
    <script>
        $(document).ready(function() {
            function calculateTotalPrice() {
                let totalPrice = 0;
                $('#productTableBody tr').each(function() {
                    const quantity = parseInt($(this).find('.qty').val()) || 0;
                    const price = parseFloat($(this).find('.price').val()) ||
                        0;
                    const rowTotal = quantity * price;
                    $(this).find('.priceDisplay').text(rowTotal.toFixed(2));
                    totalPrice += rowTotal;
                });
                $("#totalPrice").text(`${totalPrice.toFixed(2)} $`);
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#checkoutButton').on('click', function() {
                const customerId = $('#customerSelect').val();
                const customerName = $('#customerSelect option:selected').text();
                const totalPrice = $('#display_grandTotal').text().trim();

                if (!customerId) {
                    toastr.error('សូមជ្រើសរើសអតិថិជន!');
                    return;
                }

                // Set modal content
                $('#modalCustomerId').val(customerId); // Set Customer ID in hidden input
                $('#modalCustomerName').val(customerName); // Set Customer Name in text input
                $('#modalTotalPrice').val(totalPrice); // Set Total Price in text input

                // Show the modal
                $('#confirmSaleModal').modal('show');
            });

            // Confirm Sale
            $('#confirmSaleButton').on('click', function() {
                const customerId = $('#modalCustomerId').val(); // Get customer ID from input
                const totalPrice = $('#modalTotalPrice').val(); // Get total price from input

                $.ajax({
                    type: 'POST',
                    url: `{{ route('sales.confirm') }}`,
                    data: {
                        _token: "{{ csrf_token() }}",
                        customer_id: customerId,
                        total_price: totalPrice
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('ការលក់បានជោគជ័យ!');
                            $('#confirmSaleModal').modal('hide');
                            // Clear the cart or refresh the POS view
                        } else {
                            toastr.error(response.message || 'មានបញ្ហាក្នុងការលក់!');
                        }
                    },
                    error: function() {
                        toastr.error('កំហុសក្នុងការលក់!');
                    }
                });
            });
            $(document).on('click', '.item', function() {
                const productId = $(this)
                    .val();
                $.ajax({
                    type: 'POST',
                    url: `{{ route('sales.cart.add') }}`,
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId,
                        quantity: 1
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            getCarts();

                            // Clear the Select2 dropdown
                            $('#product-search').val(null).trigger('change');
                        } else {
                            toastr.warning(response.message || "បរាជ័យក្នុងការបន្ថែមផលិតផល។");
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = "កំហុសក្នុងការបន្ថែមផលិតផលទៅក្នុងការទិញ។";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
                    }
                });
            });

            function getCarts() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('sales.cart.items') }}", // Ensure this is the correct route for your cart items
                    dataType: "json",
                    success: function(response) {
                        let total = 0;
                        if (response.success) {
                            const cartBody = $("#poscart_table");
                            cartBody.empty();
                            if (response.cartItems && response.cartItems.length > 0) {
                                $("#btn_remove").prop("disabled", false);
                                $("#checkoutButton").prop("disabled", false);
                                $.each(response.cartItems, function(index, item) {
                                    const itemTotal = (item.quantity * item.price).toFixed(
                                        2);
                                    total += parseFloat(
                                        itemTotal);
                                    cartBody.append(`
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>
                                                 <p>
                                                ${item.name} <br>
                                                <span style="font-size: 14px; color: #3d5ee1;">${item.price} $</span>
                                                </p>
                                            <td >
                                                <input type="hidden" value="${item.id}">
                                                <input type="number" class="form-control cart-quantity" min="1" data-product-id="${item.id}" value="${item.quantity}" style="width: 100px; text-align: center;">
                                            </td>
                                            <td class="priceDisplay">${itemTotal} $</td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-item" data-product-id="${item.id}">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    `);
                                });
                            } else {
                                $("#checkoutButton").prop("disabled", true);
                                $("#btn_remove").prop("disabled", true);
                                cartBody.append(`
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">គ្មានទិន្នន័យ</td>
                                    </tr>
                                `);
                            }
                            $(".total_price").text(`${total.toFixed(2)} $`);
                            $("#display_grandTotal").text(`${total.toFixed(2)} $`);
                            calculateDiscount();
                        } else {
                            $(".total_price").text("0.00");
                            $("#display_grandTotal").text("0.00");
                            $("#paid_amount").text("");
                            toastr.error("បរាជ័យក្នុងការទាញយកទិន្នន័យកន្ត្រក។");
                        }
                    },
                    error: function() {
                        toastr.error("កំហុសក្នុងការទាញយកទិន្នន័យកន្ត្រក។");
                    }
                });
            }

            getCarts();

            $('#product-search').select2({
                minimumInputLength: 1,
                ajax: {
                    url: '/products/search',
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        search: params.term
                    }),
                    processResults: data => ({
                        results: data.map(product => ({
                            id: product.id,
                            text: `${product.name} (${product.barcode})`,
                            product: product
                        }))
                    }),
                }
            });

            // Add Product to Cart
            $('#product-search').on('select2:select', function(e) {
                const product = e.params.data.product;
                $.ajax({
                    type: "POST",
                    url: "{{ route('sales.cart.add') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: product.id,
                        quantity: 1
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            getCarts();

                            // Clear the Select2 dropdown
                            $('#product-search').val(null).trigger('change');
                        } else {
                            toastr.error(response.message || "បរាជ័យក្នុងការបន្ថែមផលិតផល។");
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = "កំហុសក្នុងការបន្ថែមផលិតផលទៅក្នុងការទិញ។";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
                    }
                });
            });

            //delete cart
            $(document).on("click", ".remove-item", function() {
                const productId = $(this).data("product-id");
                deleteCartItem(productId);
            });
            //update Qty
            $(document).on("change", ".cart-quantity", function() {
                const productId = $(this).data("product-id");
                const quantity = $(this).val();
                updateCartItemQuantity(productId, quantity);
            });
            //function Delete
            function deleteCartItem(productId) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('sales.cart.delete') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            getCarts();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.warning("កំហុសក្នុងការលុបផលិតផលចេញពីការទិញ។");
                    }
                });
            }

            $(document).on("click", ".btn-clear", function(event) {
                clearCart();
            });
            //function clear cart
            function clearCart() {
                $.ajax({
                    type: "POST",
                    url: "{{ route('sales.cart.clear') }}",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            getCarts();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error("កំហុសក្នុងការលុបការទិញ។");
                    }
                });
            }
            //function updateCartItem
            function updateCartItemQuantity(productId, quantity) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('sales.cart.updateQuantity') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId,
                        quantity: quantity
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            getCarts(); // Refresh the cart
                        } else {
                            toastr.error(response.message || "បរាជ័យក្នុងការអាប់ដេតបរិមាណ។");
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = "កំហុសក្នុងការអាប់ដេតបរិមាណ។";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message; // Use the message from the server
                        }
                        toastr.error(errorMessage);
                        getCarts(); // Refresh the cart even after error
                    }
                });
            }
            $(document).on('click', '.category-button', function() {
                const categoryName = $(this).data(
                    'category'); // Get the category name from the button's data-category attribute
                $.ajax({
                    url: `/products/category/${categoryName}`,
                    method: 'GET',
                    success: function(response) {
                        $('#show').empty(); // Clear previous results
                        if (response.length > 0) {
                            $.each(response, function(key, product) {
                                if (product.quantity >
                                    0) { // Check if stock is greater than 0
                                    $('#show').append(`
                            <div class="col">
                                <button type="button" class="item custom-btn fs-7 px-1 w-100" id="addToCartBtn" style="cursor: pointer; border: none; background: transparent;" value="${product.id}">
                                    <div class="order-product product-search d-flex justify-content-center align-items-center">
                                        <div class="card border mb-2" style="height: 200px; width: 100%; overflow: hidden; box-shadow: 0 4px 8px rgba(34, 34, 34, 0.2); border-radius: 10px;">
                                            <div class="first" style="position: absolute; width: 100%; padding-left: 4px; padding-top: 0px;">
                                                <div class="d-flex justify-content-between align-items-center">
                                                   <span class="discount" style="background-color: #3d5ee1; padding: 2px 5px; font-size: 10px; border-radius: 4px; color: #fff;">ស្តុក: ${product.quantity}</span>
                                                    <span class="wishlist"><i class="fa fa-heart-o"></i></span>
                                                </div>
                                            </div>
                                            <div>
                                                ${product.image ? `<img src="/storage/${product.image}" class="card-img-top" alt="${product.name}" style="width: 120px; height: 120px; object-fit: cover;" />` : ''}
                                            </div>
                                            <div class="card-body d-flex flex-column justify-content-center" style="padding: 10px;">
                                                <h5 class="card-title text-truncate" style="font-size: 14px;">${product.name}</h5>
                                                <h5 class="card-title text-truncate" style="font-size: 14px; color: #3d5ee1;">$${product.selling_price}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </div>
                        `);
                                }
                            });
                        } else {
                            // If no products are found, display a message
                            $('#show').append('<p>--គ្មានទិន្នន័យ--</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        $('#show').empty().append('<p>ជ្រើសរើសប្រភេទផលិតផល.</p>');
                    }
                });
            });

            $(document).on('click', '.category-button', function() {
                $('.category-button').removeClass('btn-warning').addClass('btn-primary');
                $(this).removeClass('btn-primary').addClass('btn-warning');
            });
        });
    </script>

@endsection
