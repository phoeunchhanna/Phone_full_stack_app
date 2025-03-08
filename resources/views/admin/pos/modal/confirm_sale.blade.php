<div class="modal fade" id="confirmSaleModal" tabindex="-1" aria-labelledby="confirmSaleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">
                    <i class="bi bi-cart-check text-primary"></i> បញ្ជាក់ការលក់
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="checkout-form" action="{{ route('sales.store') }}" method="POST"
                onsubmit="return validateForm()">
                @csrf
                <div class="modal-body">
                    @if (session()->has('checkout_message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="alert-body">
                                <span>{{ session('checkout_message') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                    <input type="hidden" id="modalCustomerId" name="customer_id" />
                    <div class="row">
                        <div class="mb-3">
                            <label for="modalTotalPrice" class="form-label">កាលបរិច្ឆេទ</label>
                            <input type="date" class="form-control" name="date" required
                                value="{{ now()->format('Y-m-d') }}" max="{{ now()->format('Y-m-d') }}">
                        </div>
                        <!-- Total Amount -->
                        <div class="mb-3">

                            <input type="hidden" class="form-control" id="modalCustomerName" readonly />
                            <label for="modalTotalPrice" class="form-label">តម្លៃសរុប</label>
                            <input type="text" class="form-control" id="modalTotalPrice" readonly />
                        </div>

                        <!-- Discount -->
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="discount_type">ប្រភេទបញ្ចុះតម្លៃ</label>
                                <select name="discount_type" id="discount_type" class="form-control form-select"
                                    required>
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
                                    id="discount_amount" max="" class="form-control"
                                    value="0" required>
                            </div>
                        </div>

                        <!-- Paid Amount -->
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="paid_amount">ប្រាក់ដែលបានបង់ <span class="text-danger">*</span></label>
                                <input type="number" name="paid_amount" placeholder="$" id="paid_amount"
                                    class="form-control" value="" step="0.01" required>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="payment_method">វិធីសាស្ត្រទូទាត់</label>
                                <select name="payment_method" class="form-control form-select" required>
                                    <option value="សាច់ប្រាក់">សាច់ប្រាក់</option>
                                    <option value="អេស៊ីលីដា">អេស៊ីលីដា</option>
                                    <option value="ABA">ABA</option>
                                </select>
                            </div>
                        </div>

                        <!-- Note -->
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="note">ចំណាំ (បើមាន)</label>
                                <textarea name="note" id="note" rows="5" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="invoice-total-card">
                                <div class="invoice-total-box">
                                    <div class="invoice-total-inner">
                                        <p class="totalPrice">តម្លៃសរុប<span class="total_price">(-) 0.00 $</span></p>
                                        <p class="totalPrice">ចំនួនទឹកប្រាក់បញ្ចុះតម្លៃ<span id="display_due_amount">(-)
                                                0.00 $</span></p>
                                    </div>
                                    <div class="invoice-total-footer">
                                        <h4 class="grandTotal">ទឹកប្រាក់សរុប <span
                                                id="show_grandTotal">0.00 $</span></h4>
                                    </div>
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
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" id="submit-button" class="btn btn-primary"><i class="bi bi-printer"></i>
                        បញ្ចប់ការទូទាត់</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
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
        $("#show_grandTotal").text(`${grandTotal.toFixed(2)} $`);
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
        let totalAmount = parseFloat($("#show_grandTotal").text()) || 0;

        let discountAmount = parseFloat($("#discount_amount").val()) || 0;

        // Warning if paid amount exceeds total
        if (paidAmount > totalAmount) {
            toastr.warning("ចំនួនទឹកប្រាក់ដែលបានបង់មិនអាចលើសពីតម្លៃសរុបទេ");
            $("#paid_amount").val(totalAmount);
            paidAmount = totalAmount;
        }
        calculateDiscount();
    });
</script>
