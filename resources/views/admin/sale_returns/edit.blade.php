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
                            <li class="breadcrumb-item"><a href="{{ route('sale-returns.index') }}">បញ្ជីការបង្វិលចូលទំនិញ</a></li>
                            <li class="breadcrumb-item active">កែប្រែការបង្វិលចូលទំនិញ</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Product and Cart Section -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="invoice-info d-flex justify-content-between align-items-center">
                                    <div class="invoice-head">
                                        <h2 class="text-primary">កែប្រែការបង្វិលចូលទំនិញ</h2>
                                    </div>
                                    <span>
                                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                                            <i class="fas fa-arrow-left text-primary"></i> ត្រឡប់ក្រោយ
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <div class="invoice-item invoice-item-bg">
                                <div class="row">
                                    <div class="col-lg-4 col-md-12">
                                        <div class="invoice-info invoice-info-one border-1">
                                            <p>លេខយោង(លេខវិក័យបត្រ) : {{ $saleReturn->reference }}</p>
                                            <p>កាលបរិច្ឆេទ(ឆ្នាំ-ខែ-ថ្ងៃទី) : {{ $saleReturn->date }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-12">
                                        <div class="invoice-info invoice-info-one border-0">
                                            <p>ឈ្មោះអតិថិជន: {{ $saleReturn->customer ? $saleReturn->customer->name : 'មិនមានព័ត៌មានអតិថិជន' }}</p>
                                            <p>លេខទូរស័ព្ទ : {{ $saleReturn->customer->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('sale-returns.update', $saleReturn->id) }}" method="POST" id="formcreate">
                                @csrf
                                @method('PUT') <!-- Since we're updating, we need to use PUT method -->
                                <div class="row">
                                    <input name="customer_id" type="hidden" value="{{ $saleReturn->customer->id }}">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="form-group">
                                            <label for="reference">លេខយោង</label>
                                            <input type="text" class="form-control" name="reference" required readonly
                                                value="{{ $saleReturn->reference }}">
                                            @error('reference')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="form-group">
                                            <label for="date">កាលបរិច្ឆេទ </span></label>
                                            <input type="date" class="form-control" name="date" required
                                                value="{{ $saleReturn->date }}" max="{{ now()->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="form-group">
                                            <label for="status">ស្ថានភាព <span class="text-danger">*</span></label>
                                            <select class="form-control form-select" name="status" id="status" required>
                                                <option value="កំពុងរង់ចាំ" {{ $saleReturn->status == 'កំពុងរង់ចាំ' ? 'selected' : '' }}>កំពុងរង់ចាំ</option>
                                                <option value="បញ្ចប់" {{ $saleReturn->status == 'បញ្ចប់' ? 'selected' : '' }}>បញ្ចប់</option>
                                                <option value="បោះបង់" {{ $saleReturn->status == 'បោះបង់' ? 'selected' : '' }}>បោះបង់</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table-hover table-center mb-4 table table-stripped">
                                            <thead class="" style="background-color: #0d6efd;color: white;">
                                                <tr>
                                                    <th>ល.រ</th>
                                                    <th>ឈ្មោះទំនិញ</th>
                                                    <th>តម្លៃឯកតា</th>
                                                    <th>បរិមាណលក់</th>
                                                    <th>បរិមាណបង្វែចូល</th>
                                                    <th>សរុបរង</th>
                                                    <th class="text-center">សកម្មភាព</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbl_purchase">
                                                
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
                                                            <option value="none" {{ $saleReturn->discount_type == 'none' ? 'selected' : '' }}>គ្មានបញ្ចុះតម្លៃ</option>
                                                            <option value="percentage" {{ $saleReturn->discount_type == 'percentage' ? 'selected' : '' }}>ជាភាគរយ (%)</option>
                                                            <option value="fixed" {{ $saleReturn->discount_type == 'fixed' ? 'selected' : '' }}>ជាទឹកប្រាក់ ($)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="discount_amount">ចំនួនបញ្ចុះតម្លៃ</label>
                                                        <input type="number" step="0.01" name="discount_amount"
                                                            id="discount_amount" max="" class="form-control"
                                                            value="{{ $saleReturn->discount }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="payment_method">វិធីសាស្ត្រទូទាត់</label>
                                                        <select name="payment_method" class="form-control form-select"
                                                            required>
                                                            <option value="សាច់ប្រាក់" {{ $saleReturn->payment_method == 'សាច់ប្រាក់' ? 'selected' : '' }}>សាច់ប្រាក់</option>
                                                            <option value="អេស៊ីលីដា" {{ $saleReturn->payment_method == 'អេស៊ីលីដា' ? 'selected' : '' }}>អេស៊ីលីដា</option>
                                                            <option value="ABA" {{ $saleReturn->payment_method == 'ABA' ? 'selected' : '' }}>ABA</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="paid_amount">ចំនួនប្រាក់ដែលបានបង់</label>
                                                        <input type="number" name="paid_amount" placeholder="$"
                                                            id="paid_amount" class="form-control" value="{{ $saleReturn->paid_amount }}"
                                                            step="0.01" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="invoice-total-card">
                                                <div class="invoice-total-box">
                                                    <div class="invoice-total-inner">
                                                        <p class="totalPrice">តម្លៃសរុប<span class="total_price">(-) 0.00
                                                                $</span></p>

                                                        <p class="totalPrice">ចំនួនទឹកប្រាក់បញ្ចុះតម្លៃ<span
                                                                id="display_due_amount">(-) 0.00 $</span></p>
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
                                                        <span>ចំនួនទឹកប្រាក់មិនទាន់ទូទាត់</span>
                                                        <span id="due_amount">{{ $saleReturn->due_amount }} </span>
                                                    </h5>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 d-flex justify-content-end">
                                        <div class="form-group mt-4">
                                            <button type="submit" class="btn btn-primary btn-lg" id="saveButton">រក្សាទុក<i
                                                    class="bi bi-check-lg"></i></button>
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

                let grandTotal = totalAmount - totalDiscount;
                let dueAmount = grandTotal - paidAmount;

                if (paidAmount === 0) {
                    dueAmount = 0.00;
                }

                $("#display_due_amount").text(`${totalDiscount.toFixed(2)} $`);
                $("#display_grandTotal").text(`${grandTotal.toFixed(2)} $`);
                $("#due_amount").text(`${dueAmount.toFixed(2)} $`);
            }

            // Attach event listeners
            $("#discount_type, #discount_amount").on("change keyup", function() {
                $("#paid_amount").val("");
                calculateDiscount();
            });

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
                            getCarts();

                            $('#product-search').val(null).trigger('change');
                        } else {
                            toastr.error(response.message ||
                                "បរាជ័យក្នុងការបញ្ចូលផលិតផលទៅកាន់កន្ត្រក។");
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = "កំហុសក្នុងការបញ្ចូលផលិតផលទៅកាន់កន្ត្រក។";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
                    }
                });
            });

            // Discount
            function getCarts() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('sale_returns.cart.items') }}",
                    dataType: "json",
                    success: function(response) {
                        let total = 0;
                        if (response.success) {
                            const cartBody = $("#tbl_purchase");
                            cartBody.empty();
                            $("#totalPrice").text(response.totalPrice || "0.00");

                            if (response.cartItems && response.cartItems.length > 0) {
                                $("#formcreate").prop("disabled", false);
                                $.each(response.cartItems, function(index, item) {
                                    const itemTotal = (item.quantity * item.price).toFixed(2);
                                    total += parseFloat(itemTotal);
                                    cartBody.append(`
                                        <tr data-product-id="${item.id}">
                                            <td>${index + 1}</td>
                                            <td>${item.name}</td>
                                            <td>${item.price}</td>
                                            <td>${item.sale_quantity}</td>
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
                                $("#formcreate").prop("disabled", true);
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

            // Calculate Total Price
            function calculateTotalPrice() {
                let totalPrice = 0;

                $("#tablebody tr").each(function() {
                    const quantity = parseInt($(this).find(".cart-quantity").val()) || 0;
                    const price = parseFloat($(this).find("td:nth-child(3)").text()) || 0;
                    const rowTotal = quantity * price;

                    // Update row total
                    $(this).find(".priceDisplay").text(rowTotal.toFixed(2));
                    totalPrice += rowTotal;
                });

                $("#totalPrice").text(totalPrice.toFixed(2));
            }

            $(document).on("change", ".cart-quantity", function() {
                calculateTotalPrice();
            });

            getCarts();

            // Delete cart
            $(document).on("click", ".remove-item", function() {
                const productId = $(this).data("product-id");
                deleteCartItem(productId);
            });

            // Update Qty
            $(document).on("change", ".cart-quantity", function() {
                const productId = $(this).data("product-id");
                const quantity = $(this).val();
                updateCartItemQuantity(productId, quantity);
            });

            // Function Delete
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
                            toastr.success("បានលុបផលិតផលចេញពីកន្ត្រកជោគជ័យ។");
                            $("#paid_amount").val("");
                            getCarts();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error("កំហុសក្នុងការលុបផលិតផលចេញពីកន្ត្រក។");
                    }
                });
            }

            $(document).on("click", ".btn-back", function(event) {
                event.preventDefault();
                clearCart();
                setTimeout(function() {
                    window.location.href =
                        "{{ route('purchases.index') }}";
                }, 1500);
            });

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
                            toastr.success("បានសម្អាតកន្ត្រកជោគជ័យ។");
                            getCarts();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error("កំហុសក្នុងការសម្អាតកន្ត្រក។");
                    }
                });
            }

            function updateCartItemQuantity(productId, quantity) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('sale_returns.cart.updateQuantity') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId,
                        quantity: quantity
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $("#paid_amount").val("");
                            getCarts();
                        } else {
                            toastr.error(response.message || "បរាជ័យក្នុងការកំណត់បរិមាណផលិតផល។");
                        }
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = "កំហុសក្នុងការកំណត់បរិមាណ។";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.warning(errorMessage);
                        getCarts();
                    }
                });
            }
        });
    </script>
@endsection
