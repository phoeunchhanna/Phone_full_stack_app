// public/js/purchase.js

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
    
        // CSRF Token setup for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Function to calculate and display discount, grand total, and due amount
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
                    toastr.warning("Percentage discount cannot exceed 100%.");
                    $("#discount_amount").val(discountAmount);
                }
                totalDiscount = (totalAmount * discountAmount) / 100;
            } else if (discountType === "fixed") {
                if (discountAmount > totalAmount) {
                    discountAmount = 0;
                    toastr.warning("Fixed discount cannot exceed the total amount.");
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
                data: params => ({ search: params.term }),
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
});





// $(document).ready(function () {
//     $.ajaxSetup({
//         headers: {
//             "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//         },
//     });
//     function calculateTotalPrice() {
//         let totalPrice = 0;
//         $("#tablebody tr").each(function () {
//             const quantity = parseInt($(this).find(".qty").val()) || 0;
//             const price = parseFloat($(this).find(".price").val()) || 0;
//             const rowTotal = quantity * price;
//             $(this).find(".priceDisplay").text(rowTotal.toFixed(2));
//             totalPrice += rowTotal;
//         });
//         $("#totalPrice").text(totalPrice.toFixed(2));
//     }
//     $(document).on("keyup", ".searchp", function () {
//         const search = $(this).val().trim();
//         if (search !== "") {
//             $.ajax({
//                 type: "POST",
//                 url: "/cart/search",
//                 data: {
//                     search,
//                 },
//                 dataType: "json",
//                 success: function (response) {
//                     $("#product-list").empty();
//                     if (response.length > 0) {
//                         // Slice response to get only the first 10 items
//                         const limitedResults = response.slice(0, 2);
//                         $.each(limitedResults, function (key, product) {
//                             $("#product-list").append(`
//                                 <li class="list-group-item list-group-item-action">
//                                     <a href="#" onclick="event.preventDefault(); selectProduct(${product.id}, '${product.name}', '${product.barcode}');">
//                                         ${product.name} | ${product.barcode}
//                                     </a>
//                                 </li>
//                             `);
//                         });
//                     } else {
//                         $("#product-list").html(
//                             '<li class="list-group-item text-danger">No products found in this category.</li>'
//                         );
//                     }
//                     $("#product-list").show();
//                 },
//                 error: function () {
//                     $("#product-list").html(
//                         '<li class="list-group-item text-danger">Error fetching products. Please try again.</li>'
//                     );
//                     toastr.error("Error fetching products.");
//                 },
//             });
//         } else {
//             $("#product-list").empty();
//         }
//     });

//     window.selectProduct = function (id, name, barcode) {
//         const data = {
//             product_id: id,
//             name: name,
//             barcode: barcode,
//             quantity: 1,
//         };

//         $.ajax({
//             type: "POST",
//             url: "/cart/add",
//             data: data,
//             dataType: "json",
//             success: function (response) {
//                 if (response.success) {
//                     toastr.success(response.message);
//                     getCarts();
//                 } else {
//                     toastr.error(response.message || "Failed to add product.");
//                 }
//             },
//             error: function () {
//                 toastr.error("Error adding product to cart.");
//             },
//         });

//         $("#product-list").empty();
//         $(".searchp").val("");
//     };

//     function getCarts() {
//         $.ajax({
//             type: "GET",
//             url: "/carts",
//             data: {
//                 limit: 10,
//             },
//             dataType: "json",
//             success: function (response) {
//                 let total = 0;
//                 $("#tablebody").html("");

//                 if (response.carts && response.carts.length > 0) {
//                     $("#createpurchase, #clearcart").prop("disabled", false);
//                     $.each(response.carts, function (key, product) {
//                         total += product.price * product.quantity;
//                         $("#tablebody").append(`
//                             <tr>
//                                 <td>
//                                     ${product.name}
//                                     <a type="button" class="btn btn-sm ms-2" 
//                                     data-bs-toggle="modal" 
//                                     onclick="openDiscountModal(${
//                                         product.id
//                                     }, '${product.name}', ${product.price})">
//                                         <i class="bi bi-pencil-square text-primary"></i>
//                                     </a>
//                                 </td>
//                                 <td> ${product.stock}</td>
//                                 <td> ${product.price}</td>
//                                 <td class="col-sm-3">
//                                     <input type="number" class="form-control form-control-sm qty" min="1" value="${product.quantity}" />
//                                     <input type="hidden" class="price" value="${
//                                         product.price
//                                     }" />
//                                     <input type="hidden" class="cartId" value="${
//                                         product.id
//                                     }" />
//                                 </td>
//                                 <td class="text-right priceDisplay">${(
//                                     product.quantity * product.price
//                                 ).toFixed(2)}</td>
//                                 <td class"text-right">
//                                     <button type="button" class="btn btn-danger btn-sm delete" value="${
//                                         product.id
//                                     }">
//                                         <i class="bi bi-trash3"></i>
//                                     </button>
//                                 </td>
//                             </tr>
//                         `);
//                     });
//                     $(".total").val(total.toFixed(2));
//                 } else {
//                     $("#tablebody").append(
//                         `<tr><td colspan="6" class="text-center text-danger">--រកមិនឃើញផលិតផល!--</td></tr>`
//                     );
//                     $(".total").val("0.00");
//                     $("#createpurchase, #clearcart").prop("disabled", true);
//                 }
//                 calculateTotalPrice();
//             },
//             error: function () {
//                 toastr.error("Error retrieving cart items.");
//             },
//         });
//     }

//     getCarts();
//     $(document).on("change", ".qty", function() {
//         const qty = $(this).val();
//         const cartId = $(this).closest("td").find(".cartId").val();

//         $.ajax({
//             type: "PUT",
//             url: `/purchases/carts/${cartId}`,
//             data: {
//                 qty
//             },
//             dataType: "json",
//             success: function(response) {
//                 if (response.status === 200) {
//                     toastr.success("Quantity updated successfully");
//                     getCarts();
//                 } else {
//                     toastr.error("Error updating quantity.");
//                 }
//             },
//             error: function() {
//                 toastr.error("An error occurred while updating the quantity.");
//             }
//         });
//     });
//     $(document).on("click", ".delete", function() {
//         const cartId = $(this).val();

//         $.ajax({
//             type: "DELETE",
//             url: `/carts/${cartId}`,
//             success: function(response) {
//                 if (response.status === 200) {
//                     toastr.success(response.message);
//                     getCarts();
//                 } else {
//                     toastr.error(response.message);
//                 }
//             },
//             error: function() {
//                 toastr.error("Error deleting cart item.");
//             }
//         });
//     });

// });
