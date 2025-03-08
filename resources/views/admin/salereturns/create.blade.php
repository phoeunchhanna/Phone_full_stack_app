@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">ការលក់</a></li>
                            <li class="breadcrumb-item active">បង្កើតការលក់</li>
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
                                        <h2 class="text-primary">បង្កើតការបង្វែរចូលទំនិញ</h2>
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
                                            <p>លេខយោង(លេខវិក័យបត្រ) : {{ $sale->reference }}</p>
                                            <p>កាលបរិច្ឆេទ(ឆ្នាំ-ខែ-ថ្ងៃទី) : {{ $sale->date }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-12">
                                        <div class="invoice-info invoice-info-one border-0">
                                            <p name="customer_name">ឈ្មោះអតិថិជន:
                                                {{ $sale->customer ? $sale->customer->name : 'មិនមានព័ត៌មានអតិថិជន' }}</p>
                                            <p>លេខទូរស័ព្ទ : {{ $sale->customer->phone }}</p>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <form action="{{ route('salereturns.store') }}" method="POST" id="formcreate">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <input name="customer_id" type="hidden" value="{{ $sale->customer->id }}">
                                        <div class="form-group">
                                            <input type="hidden" class="form-control" name="sale_id" required
                                                id="sale_id" value="{{ $sale->id }}">
                                            <input type="hidden" class="form-control" name="sale_reference" required
                                                readonly value="{{ $sale->reference }}">
                                            @error('sale_reference')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="date">កាលបរិច្ឆេទបង្វែចូល </span></label>
                                            <input type="date" class="form-control" name="date" required
                                                value="{{ now()->format('Y-m-d') }}" max="{{ now()->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table-hover table-center mb-4 table table-stripped">
                                            <thead class="" style="background-color: #0d6efd;color: white;">
                                                <tr>
                                                    <th>ល.រ</th>
                                                    <th>ឈ្មោះទំនិញ</th>
                                                    <th>តម្លៃឯកតាលក់ចេញ (បន្ទាប់ពីការបញ្ចុះតម្លៃ)</th>
                                                    <th>បរិមាណលក់ចេញ</th>
                                                    {{-- <th>តម្លៃបញ្ចុះតម្លៃពេលលក់ចេញ
                                                    </th> --}}
                                                    <th>បរិមាណបង្វែចូល</th>
                                                    <th>សរុបរង</th>
                                                    <th>សកម្មភាព</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbl_sale_return">

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
                                                            <option value="none">គ្មានបញ្ចុះតម្លៃ</option>
                                                            <option value="percentage">ជាភាគរយ (%)</option>
                                                            <option value="fixed">ជាទឹកប្រាក់ ($)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="discount_amount">ចំនួនបញ្ចុះតម្លៃ</label>
                                                        <input type="number" step="0.01" name="discount_amount"
                                                            id="discount_amount" max="100" class="form-control"
                                                            value="0.00" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group ">
                                                        <label for="payment_method">វិធីសាស្ត្រទូទាត់</label>
                                                        <select name="payment_method" class="form-control form-select"
                                                            required>
                                                            <option value="សាច់ប្រាក់">សាច់ប្រាក់</option>
                                                            <option value="អេស៊ីលីដា">អេស៊ីលីដា</option>
                                                            <option value="ABA">ABA</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="paid_amount">ចំនួនទឹកប្រាក់ដែលត្រូវប្រគល់ជូន</label>
                                                        <input type="number" name="paid_amount" placeholder="$"
                                                            id="paid_amount" class="form-control" value=""
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
                                                                id="display_due_amount">0.00 $</span></p>
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
                                                        <span id="due_amount">0.00$</span>
                                                    </h5>
                                                    <input class="form-control change_return input_number" required
                                                        name="due_amount" id="due_amount" type="hidden" value="0.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 d-flex justify-content-end">
                                        {{-- <a href="" class="btn btn-secondary btn-lg">បោះបង់</a> --}}
                                        <button type="submit" class="btn btn-lg btn-primary ms-2"
                                            id="btnsave">រក្សារទុក <i class="bi bi-check"></i></button>
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
            // Set up CSRF token for AJAX requests
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

                // Discount calculations based on the selected type
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
                $("#paid_amount").val("");
                calculateDiscount();
            });

            // Event listener for paid amount change
            $("#paid_amount").on("keyup change", function() {
                let paidAmount = parseFloat($("#paid_amount").val()) || 0;
                let totalAmount = parseFloat($("#display_grandTotal").text()) || 0;

                let discountAmount = parseFloat($("#discount_amount").val()) || 0;

                // Warning if paid amount exceeds total
                if (paidAmount > totalAmount) {
                    toastr.warning("ចំនួនទឹកប្រាក់ដែលបានបង់មិនអាចលើសពីតម្លៃសរុបទេ");
                    $("#paid_amount").val(totalAmount);
                    paidAmount = totalAmount;
                }
                calculateDiscount();
            });
    
            // Function to update product quantity in the cart
            function updateQuantity(productId, quantity) {
                $.ajax({
                    type: "POST",
                    url: "/sale-returns/updateQuantity", // Adjust the URL if needed
                    data: {
                        productId,
                        quantity
                    },
                    success: function(response) {
                        if (response.success) {
                            getCarts(); // Refresh cart items
                            toastr.success(response.message);
                            calculateDiscount(); // Recalculate after quantity update
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error("Failed to update product quantity.");
                    }
                });
            }
    
            // Function to get cart items and update the cart display
            function getCarts() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('sale-returns.cart.items') }}",
                    dataType: "json",
                    success: function(response) {
                        const cartBody = $("#tbl_sale_return");
                        let total = 0;
                        cartBody.empty();
    
                        if (response.success && response.cartItems.length > 0) {
                            $("#btnsave").prop("disabled", false);
    
                            response.cartItems.forEach((item, index) => {
                                let formattedPrice = item.price.toFixed(2);
                                cartBody.append(`
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td name="product_id">${item.name}</td>
                                        <td class="unit-price">${formattedPrice} $</td>
                                        <td>${item.quantity}</td>
                                        <td>
                                            <input name="stock" type="number" class="form-control cart-quantity"
                                                min="0" max="${item.quantity}"
                                                data-product-id="${item.id}"
                                                value="${item.stock}"
                                                style="width: 100px; text-align: center;">
                                        </td>
                                        <td class="priceDisplay">0.00</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm remove-item" data-product-id="${item.id}">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `);
                            });
    
                            // Handle return quantity change
                            $(".cart-quantity").on("input change", function() {
                                let row = $(this).closest("tr");
                                let returnQuantity = parseFloat($(this).val()) || 0;
                                let maxQuantity = parseFloat($(this).attr("max"));
    
                                // Prevent returnQuantity from exceeding the max allowed value
                                if (returnQuantity > maxQuantity) {
                                    returnQuantity = maxQuantity; // Reset to maxQuantity if input exceeds
                                    $(this).val(returnQuantity); // Update the input field with the max value
                                }
    
                                let unitPrice = parseFloat(row.find(".unit-price").text());
                                let newTotal = (returnQuantity * unitPrice).toFixed(2);
    
                                row.find(".priceDisplay").text(newTotal);
                                updateTotalPrice(); // Update the total price when return quantity changes
                                calculateDiscount(); // Recalculate the discount and totals
                            });
    
                            // Handle remove item from cart
                            $(".remove-item").click(function() {
                                let productId = $(this).data("product-id");
                                $.ajax({
                                    type: "DELETE",
                                    url: "{{ route('sale-returns.cart.delete') }}",
                                    data: {
                                        product_id: productId,
                                        _token: "{{ csrf_token() }}",
                                    },
                                    dataType: "json",
                                    success: function(response) {
                                        if (response.success) {
                                            getCarts(); // Refresh cart items
                                            toastr.success(response.message);
                                        } else {
                                            toastr.error(response.message);
                                        }
                                    },
                                    error: function() {
                                        toastr.error("Error removing item from cart.");
                                    }
                                });
                            });
    
                            // Function to update total price dynamically
                            function updateTotalPrice() {
                                let total = 0;
                                $(".priceDisplay").each(function() {
                                    total += parseFloat($(this).text()) || 0;
                                });
                                $(".total_price").text(`${total.toFixed(2)} $`);
                                calculateDiscount(); // Recalculate discount when total price is updated
                            }
    
                        } else {
                            $("#btnsave").prop("disabled", true);
                            cartBody.append(`
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No data found</td>
                                </tr>
                            `);
                        }
    
                        $(".total_price").text(`${total.toFixed(2)} $`);
                        $("#display_grandTotal").text(total.toFixed(2));
                        calculateDiscount();
                    },
                    error: function() {
                        toastr.error("Error retrieving cart data.");
                    }
                });
            }
    
            // Initial call to load cart data
            getCarts();
        });
    </script>
    
    
@endsection
