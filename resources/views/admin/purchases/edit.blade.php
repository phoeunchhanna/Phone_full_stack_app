@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <ul class="breadcrumb pre-breadcrumb text-muted">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">បញ្ជីការបញ្ជាទិញ</a></li>
                            <li class="breadcrumb-item active">កែប្រែការបញ្ជាទិញ</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="purchase-form" action="{{ route('purchases.update', $purchase->id) }}" method="POST">
                                @csrf
                                @method('PUT') <!-- Specifies that this is an update request -->
                                <div class="form-group d-flex align-items-center justify-content-between">
                                    <h3 class="text-primary font-weight-600 mb-0">កែប្រែការបញ្ជាទិញ</h3>
                                    <span>
                                        <!-- Back Button -->
                                        <a href="{{route('purchases.index')}}" class="btn btn-outline-primary">
                                            <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                                        </a>
                                    </span>
                                </div>
                                <div class="row">
                                        {{-- Supplier --}}
                                        <div class="col-lg-3 col-md-6">
                                            <label for="supplier_id">អ្នកផ្គត់ផ្គង់ <span
                                                    class="text-danger">*</span></label>
                                            <select
                                                class="form-control form-select @error('supplier_id') is-invalid @enderror"
                                                name="supplier_id" id="supplier_id" required>
                                                <option value="" disabled>----ជ្រើសរើសអ្នកផ្គត់ផ្គង់----</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('supplier_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        {{-- referent --}}
                                        <div class="col-lg-3 col-md-6">
                                            <div class="form-group">
                                                <label for="reference">លេខយោង</label>
                                                <input type="text" class="form-control" name="reference"
                                                    value="{{ $purchase->reference }}">
                                            </div>
                                        </div>
                                        {{-- Date --}}
                                        <div class="col-lg-3 col-md-6">
                                            <label for="date">កាលបរិច្ឆេទ <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="date" required
                                                value="{{ $purchase->date }}" max="{{ now()->format('Y-m-d') }}">
                                            @error('date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        {{-- Status --}}
                                        <div class="col-lg-3 col-md-6">
                                            <div class="form-group">
                                                <label for="status">ស្ថានភាព <span class="text-danger">*</span></label>
                                                <select class="form-control form-select" name="status" id="status"
                                                    required>
                                                    <option value="កំពុងរង់ចាំ"
                                                        {{ $purchase->status == 'កំពុងរង់ចាំ' ? 'selected' : '' }}>
                                                        កំពុងរង់ចាំ
                                                    </option>
                                                    <option value="បញ្ចប់"
                                                        {{ $purchase->status == 'បញ្ចប់' ? 'selected' : '' }}>បញ្ចប់
                                                    </option>
                                                    <option value="បោះបង់"
                                                        {{ $purchase->status == 'បោះបង់' ? 'selected' : '' }}>
                                                        បោះបង់</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                {{-- Product Search --}}
                                <div class="mt-2">
                                    <div class="row d-flex justify-content-center align-items-center">
                                        <div class="col-lg-8 col-md-6">
                                            <div class="form-group">
                                                <select id="product-search" class="form-select select2"
                                                    aria-label="ស្វែងរកតាមឈ្មោះផលិតផល ឬលេខសម្គាល់...">
                                                    <option value="" disabled>ស្វែងរកតាមឈ្មោះផលិតផល ឬលេខសម្គាល់...</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Table for Products --}}
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
                                        <tbody id="tbl_purchase">

                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                {{-- Payment and Notes --}}
                                <div class="row">
                                    <div class="col-lg-8 col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="discount_type">ប្រភេទបញ្ចុះតម្លៃ</label>
                                                    <select name="discount_type" id="discount_type"
                                                        class="form-control form-select" required>

                                                        <option value="none"
                                                            {{ $purchase->discount_type == 'none' ? 'selected' : '' }}>
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
                                                        value="{{ $purchase->discount }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group ">
                                                    <label for="payment_method">វិធីសាស្ត្របង់ប្រាក់</label>
                                                    <select name="payment_method" class="form-control form-select"
                                                        required>
                                                        <option value="សាច់ប្រាក់"
                                                            {{ $purchase->payment_method == 'សាច់ប្រាក់' ? 'selected' : '' }}>
                                                            សាច់ប្រាក់</option>
                                                        <option value="អេស៊ីលីដា"
                                                            {{ $purchase->payment_method == 'អេស៊ីលីដា' ? 'selected' : '' }}>
                                                            អេស៊ីលីដា
                                                        </option>
                                                        <option value="ABA"
                                                            {{ $purchase->payment_method == 'ABA' ? 'selected' : '' }}>ABA
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {{-- <input type="hidden" id="total_price" value="{{ $totalPrice ?? 0 }} $"> --}}
                                                <div class="form-group">
                                                    <label for="paid_amount">ចំនួនប្រាក់ដែលបានបង់</label>
                                                    <input type="number" name="paid_amount" id="paid_amount"
                                                        placeholder="$" class="form-control"
                                                        value="{{ $purchase->paid_amount ?? 0 }}" step="0.01"
                                                        required>
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function calculateDiscount() {
                let totalAmount = parseFloat($(".total_price").text()) || 0; // Total cart price
                let discountType = $("#discount_type").val();
                let discountAmount = parseFloat($("#discount_amount").val()) || 0;
                let paidAmount = parseFloat($("#paid_amount").val()) || 0;
                let totalDiscount = 0;

                if (discountType === "percentage") {
                    if (discountAmount > 100) {
                        discountAmount = 0;
                        toastr.warning("Percentage discount cannot exceed 100%.");
                        $("#discount_amount").val(discountAmount);
                    }
                    totalDiscount = (totalAmount * discountAmount) / 100;
                } else if (discountType === "fixed") {
                    if (discountAmount > totalAmount) {
                        discountAmount = 0;
                        toastr.warning("ការបញ្ចុះតម្លៃជាទឹកប្រាក់មិនអាចលើសពីចំនួនទឹកប្រាក់សរុបបានទេ។");
                        $("#discount_amount").val(discountAmount);
                    }
                    totalDiscount = discountAmount;
                } else if(discountType === "none"){
                    discountAmount = 0;
                }

                let grandTotal = totalAmount - totalDiscount;
                let dueAmount = grandTotal - paidAmount;
                if (paidAmount === 0) {
                    dueAmount = 0.00;
                }

                $("#display_due_amount").text(`${totalDiscount.toFixed(2)} $`);
                $("#display_grandTotal").text(`${grandTotal.toFixed(2)} $`);
                $("#due_amount").text(`${dueAmount.toFixed(2)} $`);
            }
            $("#discount_type, #discount_amount").on("change keyup", function() {
                calculateDiscount();
            });
            $("#paid_amount").on("keyup change", function() {
                calculateDiscount();
            });
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
                        results: data.map(product => ({
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
                    url: "{{ route('purchases.cart.add') }}",
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
            //discount
            function getCarts() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('purchases.cart.items') }}",
                    dataType: "json",
                    success: function(response) {
                        let total = 0;
                        if (response.success) {
                            const cartBody = $("#tbl_purchase");
                            cartBody.empty(); // Clear the existing rows
                            $("#totalPrice").text(response.totalPrice || "0.00");

                            if (response.cartItems && response.cartItems.length > 0) {
                                $("#btnsave").prop("disabled", false);
                                $.each(response.cartItems, function(index, item) {
                                    const itemTotal = (item.quantity * item.price).toFixed(2);
                                    total += parseFloat(itemTotal);
                                    cartBody.append(`
                                        <tr data-product-id="${item.id}">
                                            <td>${index + 1}</td>
                                            <td>${item.name}</td>
                                            <td>${item.price}</td>
                                            <td>
                                                <input type="hidden" value="${item.id}">
                                                <input type="number" class="form-control cart-quantity" min="1" data-product-id="${item.id}" value="${item.quantity}" style="width: 100px; text-align: center;">
                                            </td>
                                            <td class="priceDisplay">${itemTotal}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-item" data-product-id="${item.id}"><i class="bi bi-trash3"></i></button>
                                            </td>
                                        </tr>
                                    `);
                                });
                            } else {
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
                            toastr.error("បរាជ័យក្នុងការទាញយកទិន្នន័យកន្ត្រក។");
                        }
                    },
                    error: function() {
                        toastr.error("កំហុសក្នុងការទាញយកទិន្នន័យកន្ត្រក។");
                    }
                });
            }
            getCarts();
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
                    url: "{{ route('purchases.cart.delete') }}",
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
            $(document).on("click", ".btn-back", function(event) {
                event.preventDefault();
                clearCart();
                setTimeout(function() {
                    window.location.href =
                        "{{ route('sales.index') }}";
                }, 1500);
            });
            //function clear cart
            function clearCart() {
                $.ajax({
                    type: "POST",
                    url: "{{ route('purchases.cart.clear') }}",
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
            //function updateCartItem
            function updateCartItemQuantity(productId, quantity) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('purchases.cart.updateQuantity') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId,
                        quantity: quantity
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $("#paid_amount").val("");
                            getCarts();
                        } else {
                            toastr.error(response.message || "Failed to update quantity.");
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = "Error updating quantity.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message; // Use the message from the server
                        }
                        toastr.error(errorMessage);
                        getCarts(); // Refresh the cart even after error
                    }
                });
            }
        });
    </script>
@endsection
