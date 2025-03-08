$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    function calculateTotalPrice() {
        let totalPrice = 0;
        $("#tablebody tr").each(function() {
            const quantity = parseInt($(this).find(".qty").val()) || 0;
            const price = parseFloat($(this).find(".price").val()) || 0;
            const rowTotal = quantity * price;
            $(this).find(".priceDisplay").text(rowTotal.toFixed(2));
            totalPrice += rowTotal;
        });
        $("#totalPrice").text(totalPrice.toFixed(2));
    }
    $(document).on('change', '#category_id', function() {
        const categoryName = $(this).val(); // Get the category name from the button value
        $.ajax({
            url: `/products/category/${categoryName}`,
            method: 'GET',
            success: function(response) {
                $('#show').empty();
                if (response.length > 0) {
                    
                    $.each(response, function(key, product) {
                        $('#show').append(`
                            
                   <div class="col">
                       <button type="button" class="item custom-btn fs-7 px-1 w-100"
                                    id="addToCartBtn"
                                    style="cursor: pointer; border: none; background: transparent;"
                                    value="${product.id}">
                                <div class="order-product product-search d-flex justify-content-center align-items-center">
                                    <div class="card border mb-2"
                                         style="height: 200px; width: 100%; overflow: hidden; 
                                                box-shadow: 0 4px 8px rgba(34, 34, 34, 0.2); 
                                                border-radius: 10px;">
                                        <!-- Product Info -->
                                        <div class="first"
                                             style="position: absolute; width: 100%; padding-left: 4px; padding-top: 0px;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="discount"
                                                      style="background-color: #3d5ee1; padding: 2px 5px; font-size: 10px; border-radius: 4px; color: #fff;">
                                                    ស្តុក: ${product.quantity}</span>
                                                <span class="wishlist"><i class="fa fa-heart-o"></i></span>
                                            </div>
                                        </div>
                                        <!-- Product Image -->
                                        <div>
                                            ${product.image ? `<img src="${product.image}" class="card-img-top" alt="${product.name}" style="width: 120px; height: 120px; object-fit: cover;" />` : ''}
                                        </div>
                                        <!-- Product Details -->
                                        <div class="card-body d-flex flex-column justify-content-center" style="padding: 10px;">
                                            <h5 class="card-title text-truncate" style="font-size: 14px;">${product.name}</h5>
                                            <h5 class="card-title text-truncate" style="font-size: 14px;">(${product.name})</h5>
                                            <h5 class="card-title text-truncate" style="font-size: 14px; color: #3d5ee1;">$${product.selling_price}</h5>
                                        </div>
                                    </div>
                                </div>
                            </button>
                   </div>
               `);
                    });
                } else {
                    // Display a message if no products are found
                    $('#show').append('<p>--គ្មានទិន្នន័យ--</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#show').empty().append(
                    '<p>ជ្រើសរើសប្រភេទផលិតផល.</p>');
            }
        });
    });
    $(document).on('click', '.category-button', function() {
        $('.category-button').removeClass('btn-warning').addClass('btn-primary');
        $(this).removeClass('btn-primary').addClass('btn-warning');
    });

    function setCustomerId() {
        $('#customer_id_input').val($('#customer_id').val());
    }

    function getCarts() {
        
        $.ajax({
            type: "GET",
            url: "/carts",
            data: {
                limit: 10
            },
            dataType: "json",
            success: function(response) {
                let total = 0;
                $("#tablebody").html("");

                if (response.carts && response.carts.length > 0) {
                    $("#checkoutButton, #clearcart").prop("disabled", false);
                    $.each(response.carts, function(key, product) {
                        total += product.price * product.quantity;
                        $("#tablebody").append(`
                            <tr>
                                <td>
                                    ${product.name}
                                    <a type="button" class="btn btn-sm ms-2" 
                                    data-bs-toggle="modal" 
                                    onclick="openDiscountModal(${product.id}, '${product.name}', ${product.price})">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </a>
                                </td>
                                <td class="col-sm-3">
                                    <input type="number" class="form-control form-control-sm qty" min="1" max="${product.stock}" value="${product.quantity}" />
                                    <input type="hidden" class="price" value="${product.price}" />
                                    <input type="hidden" class="cartId" value="${product.id}" />
                                </td>
                                <td class="text-right priceDisplay">${(product.quantity * product.price).toFixed(2)}</td>
                                <td class"text-right">
                                    <button type="button" class="btn btn-danger btn-sm delete" value="${product.id}">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                    $(".total").val(total.toFixed(2));
                } else {
                    $("#tablebody").append(
                        `<tr><td colspan="6" class="text-center text-danger">--គ្មានទិន្នន័យ!--</td></tr>`
                        );
                    $(".total").val("0.00");
                    $("#checkoutButton, #clearcart").prop("disabled", true);
                }
                calculateTotalPrice();
            },
            error: function() {
                toastr.error("Error retrieving cart items.");
            }
        });
    }

    getCarts();
    
    $(document).on("click", ".item", function() {
        const productId = $(this).val();
        $.ajax({
            type: "POST",
            url: "carts",
            data: {
                productId
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 400) {
                    toastr.error(response.message);
                }
                getCarts();
            },
            error: function() {
                toastr.error("Error adding product.");
            }
        });
    });

    $(document).on("click", ".delete", function() {
        const cartId = $(this).val();

        $.ajax({
            type: "DELETE",
            url: `/carts/${cartId}`,
            success: function(response) {
                if (response.status === 200) {
                    toastr.success(response.message);
                    getCarts();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error("Error deleting cart item.");
            }
        });
    });

    $(document).on("change", ".qty", function() {
        const qty = $(this).val();
        const cartId = $(this).closest("td").find(".cartId").val();

        $.ajax({
            type: "PUT",
            url: `/carts/${cartId}`,
            data: {
                qty
            },
            dataType: "json",
            success: function(response) {
                if (response.status === 200) {
                    toastr.success("Quantity updated successfully");
                    getCarts();
                } else {
                    toastr.error("Error updating quantity.");
                }
            },
            error: function() {
                toastr.error("An error occurred while updating the quantity.");
            }
        });
    });
    document.getElementById('applyDiscount').addEventListener('click', function () {
        const cartId = document.getElementById('modalCartId').value;
        const discountType = document.getElementById('discountType').value;
        const discountAmount = document.getElementById('discountAmount').value;
        const discountPercent = document.getElementById('discountPercent').value;
    
        $.ajax({
            type: 'PUT',
            url: `/carts/${cartId}/discount`,
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                discount_type: discountType,
                discount_amount: discountAmount,
                discount_percent: discountPercent
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#dicountcart').modal('hide');
                    // Optionally refresh cart display
                    getCarts();
                }
            },
            error: function () {
                toastr.error('Failed to update discount.');
            }
        });
    });
});