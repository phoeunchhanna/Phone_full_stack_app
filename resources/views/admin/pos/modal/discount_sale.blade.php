<!-- Discount Modal -->
<div class="modal fade" id="dicountcart" tabindex="-1" role="dialog" aria-labelledby="dicountcartLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dicountcartLabel">{{$product->name}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="discountForm">
                    <div class="form-group mt-3" id="priceGroup">
                        <label for="priceInput">តម្លៃឯកតា:</label>
                        <input type="text" class="form-control" id="priceInput" value="{{$product->selling_price}}">
                    </div>                    
                    <!-- Discount Type Dropdown -->
                    <div class="form-group">
                        <label for="discountType">ប្រភេទការបញ្ចុះតម្លៃ:</label>
                        <select class="form-control" id="discountType" onchange="toggleDiscountInput()">
                            <option value="amount">បញ្ចុះតម្លៃជាទឹកប្រាក់</option>
                            <option value="percentage">បញ្ចុះតម្លៃជាភាគរយ</option>                            
                        </select>
                    </div>

                    <!-- Discount Amount Input (initially visible) -->
                    <div class="form-group mt-3" id="discountAmountGroup">
                        <label for="discountAmount">បញ្ចុះតម្លៃជាទឹកប្រាក់:</label>
                        <input type="number" class="form-control" id="discountAmount" placeholder="Discount Amount" oninput="validateDiscount()" >
                    </div>

                    <!-- Discount Percentage Input (initially hidden) -->
                    <div class="form-group mt-3" id="discountPercentGroup" style="display: none;">
                        <label for="discountPercent">បញ្ចុះតម្លៃជាភាគរយ:</label>
                        <input type="number" class="form-control" id="discountPercent" min="1" max="100" placeholder="Percentage" oninput="validateDiscount()">
                        {{-- <input type="number" class="form-control" id="discountPercent" min="1" max="100" placeholder="Percentage"> --}}
                    </div>

                    <!-- Hidden input to store cart ID -->
                    <input type="hidden" id="modalCartId">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បិទ</button>
                <button type="button" class="btn btn-primary" id="applyDiscount">រក្សារទុក</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openDiscountModal(cartId, productName, price) {
    document.getElementById('productName').textContent = productName;
    document.getElementById('priceInput').value = price;
    document.getElementById('modalCartId').value = cartId;

    document.getElementById('discountAmount').value = '';
    document.getElementById('discountPercent').value = '';
    toggleDiscountInput();
}

function toggleDiscountInput() {
    const discountType = document.getElementById('discountType').value;
    const amountGroup = document.getElementById('discountAmountGroup');
    const percentGroup = document.getElementById('discountPercentGroup');

    if (discountType === 'amount') {
        amountGroup.style.display = 'block';
        percentGroup.style.display = 'none';
    } else {
        amountGroup.style.display = 'none';
        percentGroup.style.display = 'block';
    }
}
function validateDiscount() {
    const discountInput = document.getElementById("discountPercent");
    if (discountInput.value > 100) {
        discountInput.value = 100;
    }
}
</script>
