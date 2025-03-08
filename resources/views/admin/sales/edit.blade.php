@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">បញ្ជីការលក់</a></li>
                            <li class="breadcrumb-item active">កែរប្រែការលក់</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Product and Cart Section -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">

                            <form action="{{ route('sales.update', $sale->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group d-flex align-items-center justify-content-between">
                                    <h3 class="text-primary font-weight-600 mb-0">កែរប្រែការលក់</h3>
                                    <span>
                                        <!-- Back Button -->
                                        <a href="{{ route('sales.index') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                                        </a>
                                    </span>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="reference">លេខយោង</label>
                                            <input type="text" class="form-control" name="reference" required readonly
                                                value="{{ $sale->reference }}">
                                            @error('reference')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="customer_id">អតិថិជន <span class="text-danger">*</span></label>
                                            <select
                                                class="form-control form-select @error('customer_id') is-invalid @enderror"
                                                name="customer_id" id="customer_id" required>
                                                <option value="" selected>----ជ្រើសរើសអ្នកអតិថិជន----</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="date">កាលបរិច្ឆេទ </span></label>
                                            <input type="date" class="form-control" name="date" required
                                                value="{{ $sale->date }}" max="{{ now()->format('Y-m-d') }}">
                                        </div>
                                    </div>

                                    {{-- Product Search --}}
                                    <div class="mt-2">
                                        <div class="row d-flex justify-content-center align-items-center">
                                            <div class="col-lg-8 col-md-6">
                                                <div class="form-group">
                                                    <select id="product-search" class="form-select select2"
                                                        aria-label="ស្វែងរកតាមឈ្មោះផលិតផល ឬលេខសម្គាល់...">
                                                        <option value="" disabled selected>ស្វែងរកតាមឈ្មោះផលិតផល
                                                            ឬលេខសម្គាល់...</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table-hover table-center mb-4 table table-stripped">
                                            <thead class="" style="background-color: #0d6efd;color: white;">
                                                <tr>
                                                    <th>ល.រ</th>
                                                    <th>ឈ្មោះទំនិញ</th>
                                                    <th>តម្លៃឯកតា</th>
                                                    <th>បរិមាណ</th>
                                                    <th>សរុបរង</th>
                                                    <th>សកម្មភាព</th>
                                                </tr>
                                            </thead>
                                            <tbody id="cart_table">
                                                <!-- Populate cart items here -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-8 col-md-6">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="discount_type">ប្រភេទបញ្ចុះតម្លៃ</label>
                                                        <select name="discount_type" id="discount_type"
                                                            class="form-control form-select" required>

                                                            <option value="none"
                                                                {{ $sale->discount_type == 'none' ? 'selected' : '' }}>
                                                                គ្មានបញ្ចុះតម្លៃ
                                                            </option>
                                                            <option value="percentage"
                                                                {{ $discountType == 'percentage' ? 'selected' : '' }}>ភាគរយ
                                                                (%)
                                                            </option>
                                                            <option value="fixed"
                                                                {{ $discountType == 'fixed' ? 'selected' : '' }}>
                                                                ជាទឹកប្រាក់ ($)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="discount_amount">ចំនួនបញ្ចុះតម្លៃ</label>
                                                        <input type="number" step="0.01" name="discount_amount"
                                                            id="discount_amount" max="" class="form-control"
                                                            value="{{ $sale->discount }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group ">
                                                        <label for="payment_method">វិធីសាស្ត្របង់ប្រាក់</label>
                                                        <select name="payment_method" class="form-control form-select"
                                                            required>
                                                            <option value="សាច់ប្រាក់"
                                                                {{ $sale->payment_method == 'សាច់ប្រាក់' ? 'selected' : '' }}>
                                                                សាច់ប្រាក់</option>
                                                            <option value="អេស៊ីលីដា"
                                                                {{ $sale->payment_method == 'អេស៊ីលីដា' ? 'selected' : '' }}>
                                                                អេស៊ីលីដា
                                                            </option>
                                                            <option value="ABA"
                                                                {{ $sale->payment_method == 'ABA' ? 'selected' : '' }}>ABA
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    {{-- <input type="hidden" id="total_price" value="{{ $totalPrice ?? 0 }} $"> --}}
                                                    <div class="form-group">
                                                        <label for="paid_amount">ចំនួនប្រាក់ដែលបានបង់</label>
                                                        <input type="number" name="paid_amount" id="paid_amount"
                                                            class="form-control" value="{{ $sale->paid_amount }}"
                                                            step="0.01" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="invoice-total-card">
                                                <div class="invoice-total-box">
                                                    <div class="invoice-total-inner">
                                                        <p class="totalPrice">តម្លៃសរុប<span class="total_price">0.00
                                                                $</span></p>

                                                        <p class="totalPrice">ចំនួនទឹកប្រាក់បញ្ចុះតម្លៃ<span
                                                                id="display_due_amount">{{ $sale->discount ?? '0.00 $' }}
                                                                $</span></p>
                                                    </div>
                                                    <div class="invoice-total-footer">
                                                        <h4 class="grandTotal">ទឹកប្រាក់សរុប <span
                                                                id="display_grandTotal">0.00 $</span></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-md-end">
                                        <div class="col-md-4">

                                            <div class="invoice-total-card">

                                                <div class="invoice-total-inner">
                                                    <h5 class="change_return_span d-flex justify-content-between">
                                                        <span>ចំនួនទឹកប្រាក់នៅសល់</span>
                                                        <span id="due_amount">{{ $sale->due_amount ?? '0.00 $' }}</span>
                                                    </h5>
                                                    <input class="form-control change_return input_number" required
                                                        name="due_amount" id="due_amount" type="hidden" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Submit and Cancel Buttons --}}
                                    <div class="mt-3 d-flex justify-content-end">
                                        <div class="form-group mt-4">
                                            <button type="submit" class="btn btn-lg btn-primary ms-2"
                                                id="btnsave">រក្សារទុក <i class="bi bi-check-lg"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // CSRF Token setup for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function calculateDiscount() {
                let totalAmount = parseFloat($(".total_price").text()) || 0;
                let discountType = $("#discount_type").val();
                let discountAmount = parseFloat($("#discount_amount").val()) || 0;
                let paidAmount = parseFloat($("#paid_amount").val()) || 0;
                let totalDiscount = 0;

                if (discountType === "percentage") {
                    if (discountAmount > 100) {
                        discountAmount = 0;
                        toastr.warning("ការបញ្ចុះតម្លៃជាចំនួនភាគរយ មិនអាចលើសពី 100% បានទេ");
                        $("#discount_amount").val(discountAmount);
                    }
                    totalDiscount = (totalAmount * discountAmount) / 100;
                } else if (discountType === "fixed") {
                    if (discountAmount > totalAmount) {
                        discountAmount = 0;
                        toastr.warning("ការបញ្ចុះតម្លៃជាទឹកប្រាក់ មិនអាចលើសពីចំនួនទឹកប្រាក់សរុបបានទេ");
                        $("#discount_amount").val(discountAmount);
                    }
                    totalDiscount = discountAmount;
                }

                // Calculate grand total and due amount
                let grandTotal = totalAmount - totalDiscount;
                let dueAmount = grandTotal - paidAmount;

                if (paidAmount === 0) {
                    dueAmount = 0;
                }

                // Update UI with calculated amounts
                $("#display_due_amount").text(`${totalDiscount.toFixed(2)} $`);
                $("#display_grandTotal").text(`${grandTotal.toFixed(2)} $`);
                $("#due_amount").text(`${dueAmount.toFixed(2)} $`);
            }

            // Event listeners for discount amount and type change
            $("#discount_type, #discount_amount").on("change keyup", function() {
                calculateDiscount();
                $("#paid_amount").val("");

            });

            // Event listener for paid amount change
            $("#paid_amount").on("keyup change", function() {
                let paidAmount = parseFloat($("#paid_amount").val()) || 0;
                let totalAmount = parseFloat($("#display_grandTotal").text()) || 0;

                let discountAmount = parseFloat($("#discount_amount").val()) || 0;

                if (paidAmount > totalAmount) {
                    toastr.warning("ចំនួនទឹកប្រាក់ដែលបានបង់មិនអាចលើសពីតម្លៃសរុបទេ");
                    $("#paid_amount").val(totalAmount);
                    paidAmount = totalAmount;
                }
                calculateDiscount();
            });

            // Function to fetch and display cart items
            function getCarts() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('sales.cart.items') }}",
                    dataType: "json",
                    success: function(response) {
                        let total = 0;
                        if (response.success) {
                            const cartBody = $("#cart_table");
                            cartBody.empty();
                            if (response.cartItems && response.cartItems.length > 0) {
                                $("#btnsave").prop("disabled", false);
                                $.each(response.cartItems, function(index, item) {
                                    const itemTotal = (item.quantity * item.price).toFixed(2);
                                    total += parseFloat(itemTotal);
                                    // Add each cart item to the table
                                    cartBody.append(`
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${item.name}</td>
                                            <td>${item.price}</td>
                                            <td>
                                                <input type="hidden" value="${item.id}">
                                                <input type="number" class="form-control cart-quantity" min="1" data-product-id="${item.id}" value="${item.quantity}" style="width: 100px; text-align: center;">
                                            </td>
                                            <td class="priceDisplay">${itemTotal}</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-item" data-product-id="${item.id}">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    `);
                                });
                            } else {
                                // If cart is empty
                                $("#btnsave").prop("disabled", true);
                                cartBody.append(`
                                    <tr>
                                        <td colspan="6" class="text-center "><h5 class="text-danger">គ្មានទិន្នន័យ</h5></td>
                                    </tr>
                                `);
                            }
                            $(".total_price").text(`${total.toFixed(2)} $`);
                            $("#display_grandTotal").text(total.toFixed(2));
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

            // Product search functionality using select2
            $('#product-search').select2({
                placeholder: "ស្វែងរកតាមឈ្មោះផលិតផល ឬលេខសម្គាល់...",
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
                        results: data.filter(product => product.quantity > 0).map(product => ({
                            id: product.id,
                            text: `${product.name} (${product.code})`,
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
                            $('#product-search').val(null).trigger('change');
                        } else {
                            toastr.error(response.message || "Failed to add product.");
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = "Error adding product to cart.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
                    }
                });
            });

            // Delete item from cart
            $(document).on("click", ".remove-item", function() {
                const productId = $(this).data("product-id");
                deleteCartItem(productId);
            });

            // Update cart quantity
            $(document).on("change", ".cart-quantity", function() {
                const productId = $(this).data("product-id");
                const quantity = $(this).val();
                updateCartItemQuantity(productId, quantity);
            });

            // Function to delete item from cart
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
                        toastr.error("Error deleting product from cart.");
                    }
                });
            }

            // Clear the entire cart
            $(document).on("click", ".btn-back", function(event) {
                event.preventDefault();
                clearCart();
                setTimeout(function() {
                    window.location.href = "{{ route('sales.index') }}";
                }, 1500);
            });

            // Function to clear the cart
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
                        toastr.error("Error clearing cart.");
                    }
                });
            }

            // Function to update cart item quantity
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
                            getCarts();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error("ចំនួនក្នុងស្តុកមិនគ្រប់គ្រាន់.");
                        getCarts();
                    }
                });
            }
        });
    </script>
@endsection
