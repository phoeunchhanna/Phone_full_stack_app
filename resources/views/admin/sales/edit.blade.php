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
                                                            step="0.01" required placeholder="$">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="description">ចំណាំ</label>
                                                        <textarea name="description" id="description" class="form-control" placeholder="ចំណាំ" rows="3">{{ $sale->description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="invoice-total-card rounded-lg p-4 bg-white">
                                                <div class="invoice-total-box">
                                                    <div class="invoice-total-inner border-bottom pb-3">
                                                        <p class="totalPrice d-flex justify-content-between">
                                                            <span>តម្លៃសរុប (Total Price)</span>
                                                            <span class="total_price fw-bold">$ 0.00</span>
                                                        </p>
                                                        <p class="totalPrice d-flex justify-content-between">
                                                            <span>បញ្ចុះតម្លៃ (Discount)</span>
                                                            <span id="display_due_amount" class="fw-bold">$ 0.00</span>
                                                        </p>
                                                    </div>
                                                    <div class="invoice-total-footer py-3 border-bottom">
                                                        <h4
                                                            class="grandTotal d-flex justify-content-between fw-bold text-primary">
                                                            <span>ទឹកប្រាក់សរុប (Grand Total)</span>
                                                            <span id="display_grandTotal">$ 0.00</span>
                                                        </h4>
                                                    </div>
                                                    <div class="invoice-total-inner pt-3">
                                                        <p class="due_amount d-flex justify-content-between">
                                                            <span>ចំនួនទឹកប្រាក់នៅខ្វះ (Due Amount)</span>
                                                            <span class="fw-bold" id="due_amount">$ 0.00</span>
                                                        </p>
                                                        <input class="form-control change_return input_number" required
                                                            name="due_amount" id="due_amount" type="hidden"
                                                            value="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Submit and Cancel Buttons --}}
                                    <div class="d-flex justify-content-end">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg"
                                                id="saveButton">រក្សាទុក<i class="bi bi-check-lg"></i></button>
                                            <button class="btn btn-primary btn-lg" type="button" disabled=""
                                                id="savingButton" style="display: none;">
                                                <span class="spinner-border spinner-border-sm me-1" role="status"
                                                    aria-hidden="true"></span>
                                                កំពុងរក្សាទុក...
                                            </button>
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
        document.getElementById('formcreate').addEventListener('submit', function(event) {
            event.preventDefault();
            document.getElementById('saveButton').style.display = 'none';
            document.getElementById('savingButton').style.display = 'inline-block';
            setTimeout(() => {
                document.getElementById('formcreate').submit();
            }, 500);
        });
    </script>
    <script>
        $(document).ready(function() {

            // CSRF Token setup for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Function to format currency
            function formatCurrency(amount) {
                return '$ ' + parseFloat(amount).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function calculateDiscount() {
                let totalAmount = parseFloat($(".total_price").text().replace(/[^0-9.]/g, '')) || 0;
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
                        $("#discount_amount").val(discountAmount);
                    }
                    totalDiscount = discountAmount;
                }

                let grandTotal = totalAmount - totalDiscount;
                let dueAmount = grandTotal - paidAmount;

                if (paidAmount === 0) {
                    dueAmount = 0.00;
                }

                $("#display_due_amount").text(formatCurrency(totalDiscount));
                $("#display_grandTotal").text(formatCurrency(grandTotal));
                $("#due_amount").text(formatCurrency(dueAmount));
            }

            // Attach event listeners
            $("#discount_type, #discount_amount").on("change keyup", function() {
                $("#paid_amount").val("");
                calculateDiscount();
            });

            $("#paid_amount").on("keyup change", function() {
                let paidAmount = parseFloat($("#paid_amount").val()) || 0;
                let totalAmount = parseFloat($("#display_grandTotal").text().replace(/[^0-9.]/g, '')) || 0;

                if (paidAmount > totalAmount) {
                    toastr.warning("ចំនួនទឹកប្រាក់ដែលបានបង់មិនអាចលើសពីតម្លៃសរុបទេ");
                    $("#paid_amount").val(totalAmount);
                }
                calculateDiscount();
            });
            // Fetch and display cart items
            function getCarts() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('sales.cart.items') }}",
                    dataType: "json",
                    success: function(response) {
                        let total = 0;
                        const cartBody = $("#cart_table");
                        cartBody.empty();

                        if (response.success && response.cartItems.length > 0) {
                            $("#saveButton").prop("disabled", false);
                            $.each(response.cartItems, function(index, item) {
                                let itemTotal = item.quantity * item.price;
                                total += itemTotal;
                                cartBody.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.name}</td>
                                    <td>${formatCurrency(item.price)}</td>
                                    <td>
                                        <input type="hidden" value="${item.id}">
                                        <input type="number" class="form-control cart-quantity" min="1" data-product-id="${item.id}" value="${item.quantity}" style="width: 100px; text-align: center;">
                                    </td>
                                    <td class="priceDisplay">${formatCurrency(itemTotal)}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-item" data-product-id="${item.id}">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                            `);
                            });
                        } else {
                            $("#paid_amount").val("");
                            $("#saveButton").prop("disabled", true);
                            cartBody.append(`
                                <tr>
                                    <td colspan="6" class="text-center"><h5 class="text-danger">គ្មានទិន្នន័យ</h5></td>
                                </tr>
                            `);
                            calculateDiscount();
                        }
                        $(".total_price").text(formatCurrency(total));
                        $("#display_grandTotal").text(formatCurrency(total));
                        calculateDiscount();
                    },
                    error: function() {
                        toastr.error("បរាជ័យក្នុងការទាញយកទិន្នន័យកន្ត្រក។");
                    }
                });
            }
            getCarts();

            // Product search using select2
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
                            toastr.warning(response.message);
                            $('#product-search').val(null).trigger('change');
                        }
                    },
                    error: function() {
                        toastr.error("Error adding product to cart.");
                    }
                });
            });

            // Delete item from cart
            $(document).on("click", ".remove-item", function() {
                const productId = $(this).data("product-id");
                deleteCartItem(productId);
            });

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

            // Update cart item quantity
            $(document).on("change", ".cart-quantity", function() {
                const productId = $(this).data("product-id");
                const quantity = $(this).val();
                updateCartItemQuantity(productId, quantity);
            });

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
