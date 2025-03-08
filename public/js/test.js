$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    $('#search-input').on('input', function () {
            let query = $(this).val();
            if (query.length > 1) {
                $.ajax({
                    url: "{{ route('add.to.cart') }}",
                    method: "POST",
                    data: {
                        query: query,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('#product-list').empty();
                        if (response.status === 'success') {
                            $('#product-list').append(
                                `<li class="list-group-item">${response.product.name} - ${response.product.barcode}</li>`
                            );
                        } else {
                            $('#product-list').append(
                                `<li class="list-group-item text-danger">Product not found</li>`
                            );
                        }
                    }
                });
            } else {
                $('#product-list').empty();
            }
        });
    $(document).on('keyup', '.searchp', function() {
            const search = $(this).val().trim();
            if (search !== "") {
                $.ajax({
                    type: 'POST',
                    url: '/products/search',
                    data: {
                        search
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#product-list').empty();
                        if (response.length > 0) {
                            // Slice response to get only the first 10 items
                            const limitedResults = response.slice(0, 2);
                            $.each(limitedResults, function(key, product) {
                                $('#product-list').append(`
                                <li class="list-group-item list-group-item-action">
                                    <a href="#" onclick="event.preventDefault(); selectProduct(${product.id}, '${product.name}', '${product.barcode}');">
                                        ${product.name} | ${product.barcode}
                                    </a>
                                </li>
                            `);
                            });
                        } else {
                            $('#product-list').html(
                                '<li class="list-group-item text-danger">No products found in this category.</li>'
                            );
                        }
                        $('#product-list').show();
                    },
                    error: function() {
                        $('#product-list').html(
                            '<li class="list-group-item text-danger">Error fetching products. Please try again.</li>'
                        );
                        toastr.error("Error fetching products.");
                    }
                });
            } else {
                $('#product-list').empty();
            }
        });
        window.selectProduct = function(id, name, barcode) {
            const data = {
                product_id: id,
                name: name,
                barcode: barcode,
                quantity: 1
            };

            $.ajax({
                type: 'POST',
                url: "/cart/add",
                data: data,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        getCarts();
                    } else {
                        toastr.error(response.message || "Failed to add product.");
                    }
                },
                error: function() {
                    toastr.error("Error adding product to cart.");
                }
            });

            $('#product-list').empty();
            $('.searchp').val("");
        };
});







$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // Clear the cart on page load
    $.ajax({
        type: 'DELETE',
        url: "{{ route('cart.clear') }}",
        success: function(response) {
            if (response.status === 200) {
                console.log(response.message); // Successfully cleared
                getCarts(); // Refresh the cart display
            } else {
                console.error(response.message); // Display error message
            }
        },
        error: function(xhr) {
            console.error('Error clearing cart on page load:', xhr.responseText);
        }
    });

    // Function to fetch and display the cart items
    function getCarts() {
        $.ajax({
            type: 'GET',
            url: "{{ route('cart.index') }}",
            dataType: "json",
            success: function(response) {
                let total = 0;
                $('tbody').html(""); // Clear existing cart items

                // Check if there are any carts
                if (response.carts && response.carts.length > 0) {
                    $('#createpurchase').prop('disabled', false);
                    $.each(response.carts, function(key, product) {
                        total += product.price * product.quantity;
                        $('tbody').append(`
                            <tr>
                                <td>
                                    <div class="form-check check-tables">
                                        <input class="form-check-input" type="checkbox" value="${product.id}">
                                    </div>
                                </td>
                                <td>${product.name}</td>
                                <td>${product.price}</td> 
                                <td>${product.stock}</td>
                                <td class="col-sm-2">
                                    <input type="number" class="form-control form-control qty" min="1" value="${product.quantity}" style="width: 150px"/>
                                    <input type="hidden" class="cartId" value="${product.id}" />
                                </td>
                                <td class="text-right">${(product.quantity * product.price).toFixed(2)}</td>
                                <td class="text-center mb-5">
                                    <button type="button" class="btn btn-danger btn-sm delete" value="${product.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`);
                    });
                    $('.total').val(total.toFixed(2)); // Update total input
                } else {
                    // If there are no products, display a message
                    $('tbody').append(`
                        <tr>
                            <td colspan="6" class="text-center text-danger">រកមិនឃើញ!</td>
                        </tr>`);
                    $('.total').val('0.00');
                    $('#createpurchase').prop('disabled', true);
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }
    getCarts();
    // Handle barcode input
    $(document).on('input', '.barcode', function() {
        const barcode = $(this).val().trim();

        $.ajax({
            type: 'POST',
            url: '/purchases/barcode',
            data: {
                productCode: barcode
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {
                    $('tbody').html(""); // Clear existing items
                    let total = 0;

                    $.each(response.carts, function(key, product) {
                        total += product.price * product.quantity;
                        $('tbody').append(`
                            <tr>
                                <td>
                                    <div class="form-check check-tables">
                                        <input class="form-check-input" type="checkbox" value="something">
                                    </div>
                                </td>
                                <td>${product.name}</td>
                                <td>${product.price}</td> 
                                <td>${product.stock}</td>
                                <td class="col-sm-2">
                                    <input type="number" class="form-control form-control qty" min="1" value="${product.quantity}" style="width: 150px"/>
                                    <input type="hidden" class="cartId" value="${product.id}" />
                                </td>
                                <td class="text-right">${(product.quantity * product.price).toFixed(2)}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm delete" value="${product.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>`);
                    });
                    $('.total').val(total.toFixed(2)); // Update total input
                    $('.barcode').val(""); // Clear the barcode input
                    toastr.success(response.message);
                    getCarts(); // Refresh cart items
                } else {
                    toastr.error(response.message);
                    $('.barcode').val("");
                }
            },
        });
    });

    // Delete cart item
    $(document).on('click', '.delete', function() {
        const cartId = $(this).val();

        $.ajax({
            type: 'DELETE',
            url: `/purchases/Carts/${cartId}`,
            success: function(response) {
                if (response.status === 200) {
                    toastr.success(response.message);
                    getCarts();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error('Error deleting cart item. Please try again.');
                console.log(xhr.responseText);
            }
        });
    });

    // Quantity update
    $(document).on('change', '.qty', function() {
        const qty = $(this).val();
        const cartId = $(this).closest('td').find('.cartId').val();

        $.ajax({
            type: 'PUT',
            url: `/purchases/carts/${cartId}`,
            data: {
                qty
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 400) {
                    alert(response.message);
                }
                getCarts(); // Refresh cart items
            }
        });
    });

    // Search products
    // Search products
    // $(document).on('keyup', '.search', function() {
    //     const search = $(this).val();

    //     if (search.trim() !== "") {
    //         $.ajax({
    //             type: 'POST',
    //             url: '/purchases/search',
    //             data: {
    //                 search
    //             },
    //             dataType: 'json',
    //             success: function(response) {
    //                 $('#product-list').html(""); // Clear the list each time

    //                 if (response.length > 0) {
    //                     // Loop through each result and add to the list
    //                     $.each(response, function(key, product) {
    //                         $('#product-list').append(`
    //                 <li class="list-group-item list-group-item-action">
    //                     <a href="#" onclick="event.preventDefault(); selectProduct(${product.id}, '${product.name}', '${product.barcode}');">
    //                         ${product.name} | ${product.barcode}
    //                     </a>
    //                 </li>`);
    //                     });
    //                 } else {
    //                     // Display a message if no products are found
    //                     $('#product-list').append(
    //                         '<li class="list-group-item text-danger">No products found in this category.</li>'
    //                     );
    //                 }
    //             },
    //             error: function() {
    //                 $('#product-list').html(
    //                     '<li class="list-group-item text-danger">Error fetching products. Please try again.</li>'
    //                 );
    //             }
    //         });
    //     } else {
    //         $('#product-list').html("");
    //     }
    // });

    // // Function to add selected product to cart
    // function selectProduct(productId, productName, productBarcode) {
    //     $.ajax({
    //         type: 'POST',
    //         url: '/cart/add', // Adjust to your route for adding to cart
    //         data: {
    //             product_id: productId,
    //             name: productName,
    //             barcode: productBarcode
    //         },
    //         dataType: 'json',
    //         success: function(response) {
    //             if (response.success) {
    //                 alert('Product added to cart successfully!');
    //                 $('#product-list').html(""); // Clear the search list
    //             } else {
    //                 alert('Failed to add product to cart.');
    //             }
    //         },
    //         error: function() {
    //             alert('Error adding product to cart. Please try again.');
    //         }
    //     });
    // }


    // Handle product selection
    $(document).on('click', '.productId', function() {
        const productId = $(this).data('id');

        $.ajax({
            type: 'POST',
            url: `/purchases/barcode`, // Ensure this URL matches your route
            data: {
                productCode: barcode
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {
                    toastr.success(response.message); // Notify success
                    getCarts(); // Refresh cart items
                } else {
                    toastr.error(response.message); // Notify error if any
                }
            },
            error: function(xhr) {
                toastr.error('Error adding product to cart. Please try again.');
                console.log(xhr.responseText); // Log the error response for debugging
            }
        });
    });
    $('#supplierForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Get the form data
        var formData = $(this).serialize();

        $.ajax({
            url: "{{ route('suppliers.store') }}", // Your route to store the supplier
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Append the new supplier to the supplier dropdown
                    $('#supplier_id').append('<option value="' + response.supplier.id +
                        '">' + response.supplier.name + '</option>');

                    // Optionally, select the new supplier in the dropdown
                    $('#supplier_id').val(response.supplier.id);

                    // Close the modal
                    $('#createsuppliers').modal('hide');

                    // Show success message
                    toastr.success('Supplier added successfully!');
                } else {
                    // Handle validation errors if any
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                // Handle errors
                toastr.error('Error adding supplier. Please try again.');
            }
        });
    });
});
// ===============================================================================================
$(document).ready(function() {
    function calculateTotalPrice() {
        let totalPrice = 0;
        $('#productTableBody tr').each(function() {
            const quantity = parseInt($(this).find('.qty').val()) || 0;
            const price = parseFloat($(this).find('.price').val()) ||
                0; // Assuming you store the price in a hidden input
            const rowTotal = quantity * price;
            $(this).find('.priceDisplay').text(rowTotal.toFixed(2)); // Update row total display
            totalPrice += rowTotal; // Add to total price
        });
        $('#totalPrice').text(totalPrice.toFixed(2)); // Update total price in footer
    }
    //reset
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'DELETE',
        url: "{{ route('cart.clear') }}",
        success: function(response) {
            if (response.status === 200) {
                console.log(response.message); // Successfully cleared
                getCarts(); // Refresh the cart display
            } else {
                console.error(response.message); // Display error message
            }
        },
        error: function(xhr) {
            console.error('Error clearing cart on page load:', xhr.responseText);
        }
    });
    //get cart
    function getCarts() {
        $.ajax({
            type: 'get',
            url: "carts",
            data: {
                limit: 10
            },
            dataType: "json",
            success: function(response) {
                let total = 0;
                $('tbody').html("");

                // Check if there are products in the cart
                if (response.carts && response.carts.length > 0) {
                    $('#checkoutButton').prop('disabled', false);
                    $('#clearcart').prop('disabled', false);
                    $.each(response.carts, function(key, product) {
                        total += product.price * product.quantity;
                        $('tbody').append(`
               <tr>
                   <td>${product.name}</td>
                   <td class="col-sm-3">
                       <input type="number" class="form-control form-control-sm qty" min="1" max="${product.stock}" value="${product.quantity}" />
                       <input type="hidden" class="price" value="${product.price}" />
                       <input type="hidden" class="cartId" value="${product.id}" />
                   </td>
                   <td class="text-right priceDisplay">
                       ${product.quantity * product.price}
                   </td>
                   <td>
                       <button type="button" class="btn btn-danger btn-sm delete" value="${product.id}">
                           <i class="bi bi-trash3"></i>
                       </button>
                   </td>
               </tr>
                   `);
                    });
                    $('.total').val(total.toFixed(2)); // Update total if cart has items
                } else {
                    $('tbody').append(`
           <tr>
               <td colspan="6" class="text-center text-danger">រកមិនឃើញផផលិតផល!</td>
           </tr>`);
                    $('.total').val('0.00');
                    $('#checkoutButton').prop('disabled', true);
                    $('#clearcart').prop('disabled', true);
                }
                calculateTotalPrice();
            }
        });
    }
    getCarts()
    // Quantity update
    $(document).on('change', '.qty', function() {
        const qty = $(this).val();
        const cartId = $(this).closest('td').find('.cartId').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'put',
            url: `carts/${cartId}`,
            data: {
                qty
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 400) {
                    alert(response.message);
                }
                getCarts();
            }
        });
    });
    //
    function formatPrice(value) {
        return value.toLocaleString('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }
    // Event listener for showCount dropdown change
    // $(document).on('change', '#showCount', function() {
    //     const showCount = $('#showCount').val();

    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });

    //     $.ajax({
    //         type: 'post',
    //         url: '{{ route('filter.products') }}', // Use Laravel route helper
    //         data: {
    //             showCount
    //         },
    //         dataType: 'json',
    //         success: function(response) {
    //             $('#show').html("");
    //             if (response.length > 0) {
    //                 $.each(response, function(key, product) {
    //                     $('#show').append(`
    //            <div class="col">
    //                <button type="button" class="item custom-btn fs-7 px-1 w-100"
    //                        id="addToCartBtn"
    //                        style="cursor: pointer; border: none; background: transparent;"
    //                        value="${product.id}">
    //                    <div class="order-product product-search d-flex justify-content-center align-items-center">
    //                        <div class="card border shadow-sm rounded mb-2"
    //                             style="height: 100%; width: 100%;">
    //                            <div class="first"
    //                                 style="position: absolute;width: 100%;padding-left: 4px;padding-top: 0px;">
    //                                <div class="d-flex justify-content-between align-items-center">
    //                                    <span class="discount"
    //                                          style="background-color: #3d5ee1;padding: 1px 5px;font-size: 10px;border-radius: 4px;color: #fff;">
    //                                        ${product.quantity}</span>
    //                                    <span class="wishlist"><i class="fa fa-heart-o"></i></span>
    //                                </div>
    //                            </div>
    //                            <div style="height: 150px; width: 100%; overflow: hidden;">
    //                                ${product.image ? `<img src="${product.image}" class="card-img-top" alt="${product.name}" style="object-fit: cover; height: 100%; width: 100%;" />` : ''}
    //                            </div>
    //                            <div class="card-body d-flex flex-column justify-content-center" style="padding: 10px;">
    //                                <h5 class="card-title text-truncate text-start" style="font-size: 14px;">${product.name}</h5>
    //                                <h5 class="card-title text-truncate text-start" style="font-size: 14px;">
    //                                    ${formatPrice(product.selling_price * 4000)}៛
    //                                </h5>
    //                                <h5 class="card-title text-truncate text-start" style="font-size: 14px; color: #3d5ee1;">
    //                                    ${product.selling_price}$
    //                                </h5>
    //                            </div>
    //                        </div>
    //                    </div>
    //                </button>
    //            </div>
    //        `);
    //                 });
    //             } else {
    //                 $('#show').append(
    //                     '<p class="text-danger">No products found in this category.</p>'
    //                 );
    //             }
    //         }
    //     });
    // });
    // Format price function
    function formatPrice(price) {
        return new Intl.NumberFormat().format(price); // Formats price with commas
    }
    $(document).on('keyup', '.search', function() {
        const search = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: `/pos/search`,
            data: {
                search
            },
            dataType: 'json',
            success: function(response) {
                $('#show').html("");
                if (response.length > 0) {
                    $.each(response, function(key, product) {
                        console.log('value is ', product);
                        $('#show').append(`
               <div class="col">
                   <button type="button" class="item custom-btn fs-7 px-1 w-100"
                           id="addToCartBtn"
                           style="cursor: pointer; border: none; background: transparent;"
                           value="${product.id}">
                       <div class="order-product product-search d-flex justify-content-center align-items-center">
                           <div class="card border shadow-sm rounded mb-2"
                                style="height: 100%; width: 100%;">
                               <!-- Product Info -->
                               <div class="first"
                                    style="position: absolute;width: 100%;padding-left: 4px;padding-top: 0px;">
                                   <div class="d-flex justify-content-between align-items-center">
                                       <span class="discount"
                                             style="background-color: #3d5ee1;padding: 1px 5px;font-size: 10px;border-radius: 4px;color: #fff;">
                                           ${product.quantity}</span>
                                       <span class="wishlist"><i class="fa fa-heart-o"></i></span>
                                   </div>
                               </div>
                               <!-- Product Image -->
                               <div style="height: 150px; width: 100%; overflow: hidden;">
                                   ${product.image ? `<img src="${product.image}" class="card-img-top" alt="${product.name}" style="object-fit: cover; height: 100%; width: 100%;" />` : ''}
                               </div>
                               <!-- Product Details -->
                               <div class="card-body d-flex flex-column justify-content-center" style="padding: 10px;">
                                   <h5 class="card-title text-truncate text-start" style="font-size: 14px;">${product.name}</h5>
                                   <h5 class="card-title text-truncate text-start" style="font-size: 14px;">
                                       ${formatPrice(product.selling_price * 4000)}៛
                                   </h5>
                                   <h5 class="card-title text-truncate text-start" style="font-size: 14px; color: #3d5ee1;">
                                       ${product.selling_price}$
                                   </h5>
                               </div>
                           </div>
                       </div>
                   </button>
               </div>
                   `);
                        $('#show').attr('data-show', product
                            .name); // Change 'product.name' as needed
                    });
                } else {
                    $('#show').append(
                        '<p class="text-danger">រកមិនឃើញផផលិតផល!</p>'
                    );
                }
            }
        });
    });
    //scanbarcode
    $(document).on('input', '.barcode', function() {
        const barcode = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '/pos/barcode',
            data: {
                productCode: barcode
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {
                    $('tbody').html("");
                    $.each(response.carts, function(key, product) {
                        let total = 0;
                        // let product = response.carts[0];
                        total += product.price * product.quantity;
                        $('tbody').append(`
                       <tr>
                           <td>${product.name}</td>
                           <td class="col-sm-3">
                               <input type="number" class="form-control form-control-sm qty" min="1" max="${product.stock}" value="${product.quantity}"/>
                               <input type="hidden" class="cartId" value="${product.id}" />
                           </td>
                           <td class="text-right">${product.quantity * product.price}</td>
                           <td>
                               <button type="button" class="btn btn-danger btn-sm delete" value="${product.id}">
                                   <i class="bi bi-trash3"></i>
                               </button>
                           </td>
                       </tr>
                   `);

                        $('.total').attr('value', total);
                        $('.barcode').val("");
                        toastr.success(response.message);
                    });

                } else {
                    toastr.error(response
                        .message); // Single toastr error message for other statuses
                }
            },
        });
    });
    //Clear Cart
    $(document).on('click', '#clearcart', function() {
        // Show a SweetAlert confirmation dialog
        Swal.fire({
            title: 'តើអ្នកប្រាកដទេ?',
            text: "តើអ្នកពិតជាចង់បោះបង់មែនទេ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'បាទ!',
            cancelButtonText: 'អត់ទេ'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, proceed with the AJAX request to clear the cart
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('cart.clear') }}",
                    success: function(response) {
                        if (response.status === 200) {
                            Swal.fire(
                                'បានលុបចោល!',
                                response.message,
                                'ដោយជោគជ័យ'
                            );
                            getCarts(); // Refresh the cart display
                        } else {
                            Swal.fire(
                                'មានបញ្ហា',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'មានបញ្ហា',
                            'មានបញ្ហា ពេលលុបកន្ត្រកនៅលើទំព័រទាញទិន្នន័យ.',
                            'error'
                        );
                        console.error(
                            'មានបញ្ហា ពេលលុបកន្ត្រកនៅលើទំព័រទាញទិន្នន័យ:',
                            xhr.responseText);
                    }
                });
            }
        });
    });
    // delete cart
    $(document).on('click', '.delete', function() {
        const cartId = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $.ajax({
            type: 'delete',
            url: `/carts/${cartId}`,
            success: function(response) {
                if (response.status === 400) {
                    alert(response.message);
                } else if (response.status === 200) {
                    getCarts();
                }
            },
            error: function(xhr) {
                alert('Error deleting cart item. Please try again.');
                console.log(xhr.responseText);
            }
        });
    });
    //
    $('.category-button').on('click', function() {
        const categoryName = $(this).val(); // Get the category name from the button value

        $.ajax({
            url: `/products/category/${categoryName}`, // Adjust URL as necessary
            method: 'GET',
            success: function(response) {
                $('#show').empty(); // Clear previous results

                // Check if products are found
                if (response.length > 0) {
                    $.each(response, function(key, product) {
                        $('#show').append(`
                   <div class="col">
                       <button type="button" class="item custom-btn fs-7 px-1 w-100"
                               id="addToCartBtn"
                               style="cursor: pointer; border: none; background: transparent;"
                               value="${product.id}">
                           <div class="order-product product-search d-flex justify-content-center align-items-center">
                               <div class="card border shadow-sm rounded mb-2"
                                    style="height: 100%; width: 100%;">
                                   <!-- Product Info -->
                                   <div class="first"
                                        style="position: absolute;width: 100%;padding-left: 4px;padding-top: 0px;">
                                       <div class="d-flex justify-content-between align-items-center">
                                           <span class="discount"
                                                 style="background-color: #3d5ee1;padding: 1px 5px;font-size: 10px;border-radius: 4px;color: #fff;">
                                               ${product.quantity}</span>
                                           <span class="wishlist"><i class="fa fa-heart-o"></i></span>
                                       </div>
                                   </div>
                                   <!-- Product Image -->
                                   <div style="height: 150px; width: 100%; overflow: hidden;">
                                       ${product.image ? `<img src="${product.image}" class="card-img-top" alt="${product.name}" style="object-fit: cover; height: 100%; width: 100%;" />` : ''}
                                   </div>
                                   <!-- Product Details -->
                                   <div class="card-body d-flex flex-column justify-content-center" style="padding: 10px;">
                                       <h5 class="card-title text-truncate text-start" style="font-size: 14px;">${product.name}</h5>
                                       <h5 class="card-title text-truncate text-start" style="font-size: 14px;">
                                           ${formatPrice(product.selling_price * 4000)}៛
                                       </h5>
                                       <h5 class="card-title text-truncate text-start" style="font-size: 14px; color: #3d5ee1;">
                                           ${product.selling_price}$
                                       </h5>
                                   </div>
                               </div>
                           </div>
                       </button>
                   </div>
               `);
                    });
                } else {
                    // Display a message if no products are found
                    $('#show').append('<p>No products found in this category.</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#show').empty().append(
                    '<p>An error occurred while fetching products.</p>');
            }
        });
    });
    //
    $(document).on('click', '.item', function() {
        const productId = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'post',
            url: `carts`,
            data: {
                productId
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 400) {
                    alert(response.message);
                }
                getCarts()
            }
        })
    })
})
// ==========================================================================================




$(document).ready(function() {
    // Setup CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
         function getCarts() {
             $.ajax({
                 type: 'get',
                 url: "carts",
                 data: {
                     limit: 10
                 },
                 dataType: "json",
                 success: function(response) {
                     let total = 0;
                     $('tbody').html("");

                     // Check if there are products in the cart
                     if (response.carts && response.carts.length > 0) {
                         $('#checkoutButton').prop('disabled', false);
                         $('#clearcart').prop('disabled', false);
                         $.each(response.carts, function(key, product) {
                             total += product.price * product.quantity;
                             $('tbody').append(`
                    <tr>
                        <td>${product.name}</td>
                        <td class="col-sm-3">
                            <input type="number" class="form-control form-control-sm qty" min="1" max="${product.stock}" value="${product.quantity}" />
                            <input type="hidden" class="price" value="${product.price}" />
                            <input type="hidden" class="cartId" value="${product.id}" />
                        </td>
                        <td class="text-right priceDisplay">
                            ${product.quantity * product.price}
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm delete" value="${product.id}">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </td>
                    </tr>
                        `);
                         });
                         $('.total').val(total.toFixed(2)); // Update total if cart has items
                     } else {
                         $('tbody').append(`
                <tr>
                    <td colspan="6" class="text-center text-danger">រកមិនឃើញផផលិតផល!</td>
                </tr>`);
                         $('.total').val('0.00');
                         $('#checkoutButton').prop('disabled', true);
                         $('#clearcart').prop('disabled', true);
                     }
                     calculateTotalPrice();
                 }
             });
         }
         getCarts()
    // Handle search input
    $(document).on('keyup', '.search', function() {
        const search = $(this).val().trim();

        if (search !== "") {
            $.ajax({
                type: 'POST',
                url: '/cart/search',
                data: { search: search },
                dataType: 'json',
                success: function(response) {
                    $('#product-list').empty(); // Clear the list each time

                    if (response.length > 0) {
                        // Loop through each result and add to the list
                        $.each(response, function(key, product) {
                            $('#product-list').append(`
                                <li class="list-group-item list-group-item-action">
                                    <a href="#" onclick="event.preventDefault(); selectProduct(${product.id}, '${product.name}', '${product.barcode}');">
                                        ${product.name} | ${product.barcode}
                                    </a>
                                </li>`);
                        });
                    } else {
                        $('#product-list').html(
                            '<li class="list-group-item text-danger">No products found in this category.</li>'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    $('#product-list').html(
                        '<li class="list-group-item text-danger">Error fetching products. Please try again.</li>'
                    );
                    console.error("Search error:", xhr.responseText);
                }
            });
        } else {
            $('#product-list').empty(); // Clear the list if input is empty
        }
    });
});

function selectProduct(id, name, barcode) {
    const data = {
        product_id: id,
        name: name,
        barcode: barcode,
        quantity: 1
    };

    $.ajax({
        type: 'POST',
        url: "/cart/add",
        data: data,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                alert(`Product "${name}" (Barcode: ${barcode}) added to cart successfully!`);
                getCarts();
            } else {
                alert(response.message || 'Failed to add product to cart. Please try again.');
            }
        },
        error: function(xhr, status, error) {
            console.error(`Error adding product: ${xhr.responseText}`);
            alert('Error adding product to cart. Please try again.');
        }
    });

    $('#product-list').empty();
    $('.search').val("");
}

// =================================================================================================================
    $(document).ready(function() {
        // function calculateTotalPrice() {
        //     let totalPrice = 0;
        //     $('#productTableBody tr').each(function() {
        //         const quantity = parseInt($(this).find('.qty').val()) || 0;
        //         const price = parseFloat($(this).find('.price').val()) ||
        //             0; // Assuming you store the price in a hidden input
        //         const rowTotal = quantity * price;
        //         $(this).find('.priceDisplay').text(rowTotal.toFixed(2)); // Update row total display
        //         totalPrice += rowTotal; // Add to total price
        //     });
        //     $('#totalPrice').text(totalPrice.toFixed(2)); // Update total price in footer
        // }
        // //reset
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });
       //  $.ajax({
       //      type: 'DELETE',
       //      url: "{{ route('cart.clear') }}",
       //      success: function(response) {
       //          if (response.status === 200) {
       //              console.log(response.message); // Successfully cleared
       //              getCarts(); // Refresh the cart display
       //          } else {
       //              console.error(response.message); // Display error message
       //          }
       //      },
       //      error: function(xhr) {
       //          console.error('Error clearing cart on page load:', xhr.responseText);
       //      }
       //  });
        //get cart

        // Quantity update
        
        //
        function formatPrice(value) {
            return value.toLocaleString('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }
        // Event listener for showCount dropdown change
        $(document).on('change', '#showCount', function() {
            const showCount = $('#showCount').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '{{ route('filter.products') }}', // Use Laravel route helper
                data: {
                    showCount
                },
                dataType: 'json',
                success: function(response) {
                    $('#show').html("");
                    if (response.length > 0) {
                        $.each(response, function(key, product) {
                            $('#show').append(`
                   <div class="col">
                       <button type="button" class="item custom-btn fs-7 px-1 w-100"
                               id="addToCartBtn"
                               style="cursor: pointer; border: none; background: transparent;"
                               value="${product.id}">
                           <div class="order-product product-search d-flex justify-content-center align-items-center">
                               <div class="card border shadow-sm rounded mb-2"
                                    style="height: 100%; width: 100%;">
                                   <div class="first"
                                        style="position: absolute;width: 100%;padding-left: 4px;padding-top: 0px;">
                                       <div class="d-flex justify-content-between align-items-center">
                                           <span class="discount"
                                                 style="background-color: #3d5ee1;padding: 1px 5px;font-size: 10px;border-radius: 4px;color: #fff;">
                                               ${product.quantity}</span>
                                           <span class="wishlist"><i class="fa fa-heart-o"></i></span>
                                       </div>
                                   </div>
                                   <div style="height: 150px; width: 100%; overflow: hidden;">
                                       ${product.image ? `<img src="${product.image}" class="card-img-top" alt="${product.name}" style="object-fit: cover; height: 100%; width: 100%;" />` : ''}
                                   </div>
                                   <div class="card-body d-flex flex-column justify-content-center" style="padding: 10px;">
                                       <h5 class="card-title text-truncate text-start" style="font-size: 14px;">${product.name}</h5>
                                       <h5 class="card-title text-truncate text-start" style="font-size: 14px;">
                                           ${formatPrice(product.selling_price * 4000)}៛
                                       </h5>
                                       <h5 class="card-title text-truncate text-start" style="font-size: 14px; color: #3d5ee1;">
                                           ${product.selling_price}$
                                       </h5>
                                   </div>
                               </div>
                           </div>
                       </button>
                   </div>
               `);
                        });
                    } else {
                        $('#show').append(
                            '<p class="text-danger">No products found in this category.</p>'
                        );
                    }
                }
            });
        });
        // Format price function
        function formatPrice(price) {
            return new Intl.NumberFormat().format(price); // Formats price with commas
        }
        $(document).on('keyup', '.search', function() {
            const search = $(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: `/pos/search`,
                data: {
                    search
                },
                dataType: 'json',
                success: function(response) {
                    $('#show').html("");
                    if (response.length > 0) {
                        $.each(response, function(key, product) {
                            console.log('value is ', product);
                            $('#show').append(`
                   <div class="col">
                       <button type="button" class="item custom-btn fs-7 px-1 w-100"
                               id="addToCartBtn"
                               style="cursor: pointer; border: none; background: transparent;"
                               value="${product.id}">
                           <div class="order-product product-search d-flex justify-content-center align-items-center">
                               <div class="card border shadow-sm rounded mb-2"
                                    style="height: 100%; width: 100%;">
                                   <!-- Product Info -->
                                   <div class="first"
                                        style="position: absolute;width: 100%;padding-left: 4px;padding-top: 0px;">
                                       <div class="d-flex justify-content-between align-items-center">
                                           <span class="discount"
                                                 style="background-color: #3d5ee1;padding: 1px 5px;font-size: 10px;border-radius: 4px;color: #fff;">
                                               ${product.quantity}</span>
                                           <span class="wishlist"><i class="fa fa-heart-o"></i></span>
                                       </div>
                                   </div>
                                   <!-- Product Image -->
                                   <div style="height: 150px; width: 100%; overflow: hidden;">
                                       ${product.image ? `<img src="${product.image}" class="card-img-top" alt="${product.name}" style="object-fit: cover; height: 100%; width: 100%;" />` : ''}
                                   </div>
                                   <!-- Product Details -->
                                   <div class="card-body d-flex flex-column justify-content-center" style="padding: 10px;">
                                       <h5 class="card-title text-truncate text-start" style="font-size: 14px;">${product.name}</h5>
                                       <h5 class="card-title text-truncate text-start" style="font-size: 14px;">
                                           ${formatPrice(product.selling_price * 4000)}៛
                                       </h5>
                                       <h5 class="card-title text-truncate text-start" style="font-size: 14px; color: #3d5ee1;">
                                           ${product.selling_price}$
                                       </h5>
                                   </div>
                               </div>
                           </div>
                       </button>
                   </div>
                       `);
                            $('#show').attr('data-show', product
                                .name); // Change 'product.name' as needed
                        });
                    } else {
                        $('#show').append(
                            '<p class="text-danger">រកមិនឃើញផផលិតផល!</p>'
                        );
                    }
                }
            });
        });
        //scanbarcode
        $(document).on('input', '.barcode', function() {
            const barcode = $(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/pos/barcode',
                data: {
                    productCode: barcode
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 200) {
                        $('tbody').html("");
                        $.each(response.carts, function(key, product) {
                            let total = 0;
                            // let product = response.carts[0];
                            total += product.price * product.quantity;
                            $('tbody').append(`
                           <tr>
                               <td>${product.name}</td>
                               <td class="col-sm-3">
                                   <input type="number" class="form-control form-control-sm qty" min="1" max="${product.stock}" value="${product.quantity}"/>
                                   <input type="hidden" class="cartId" value="${product.id}" />
                               </td>
                               <td class="text-right">${product.quantity * product.price}</td>
                               <td>
                                   <button type="button" class="btn btn-danger btn-sm delete" value="${product.id}">
                                       <i class="bi bi-trash3"></i>
                                   </button>
                               </td>
                           </tr>
                       `);

                            $('.total').attr('value', total);
                            $('.barcode').val("");
                            toastr.success(response.message);
                        });

                    } else {
                        toastr.error(response
                            .message); // Single toastr error message for other statuses
                    }
                },
            });
        });
        //Clear Cart
        $(document).on('click', '#clearcart', function() {
            // Show a SweetAlert confirmation dialog
            Swal.fire({
                title: 'តើអ្នកប្រាកដទេ?',
                text: "តើអ្នកពិតជាចង់បោះបង់មែនទេ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'បាទ!',
                cancelButtonText: 'អត់ទេ'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, proceed with the AJAX request to clear the cart
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: 'DELETE',
                        url: "{{ route('cart.clear') }}",
                        success: function(response) {
                            if (response.status === 200) {
                                Swal.fire(
                                    'បានលុបចោល!',
                                    response.message,
                                    'ដោយជោគជ័យ'
                                );
                                getCarts(); // Refresh the cart display
                            } else {
                                Swal.fire(
                                    'មានបញ្ហា',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'មានបញ្ហា',
                                'មានបញ្ហា ពេលលុបកន្ត្រកនៅលើទំព័រទាញទិន្នន័យ.',
                                'error'
                            );
                            console.error(
                                'មានបញ្ហា ពេលលុបកន្ត្រកនៅលើទំព័រទាញទិន្នន័យ:',
                                xhr.responseText);
                        }
                    });
                }
            });
        });
        // delete cart
        $(document).on('click', '.delete', function() {
            const cartId = $(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $.ajax({
                type: 'delete',
                url: `/carts/${cartId}`,
                success: function(response) {
                    if (response.status === 400) {
                        alert(response.message);
                    } else if (response.status === 200) {
                        getCarts();
                    }
                },
                error: function(xhr) {
                    alert('Error deleting cart item. Please try again.');
                    console.log(xhr.responseText);
                }
            });
        });
        //
        $('.category-button').on('click', function() {
            const categoryName = $(this).val(); // Get the category name from the button value

            $.ajax({
                url: `/products/category/${categoryName}`, // Adjust URL as necessary
                method: 'GET',
                success: function(response) {
                    $('#show').empty(); // Clear previous results

                    // Check if products are found
                    if (response.length > 0) {
                        $.each(response, function(key, product) {
                            $('#show').append(`
                       <div class="col">
                           <button type="button" class="item custom-btn fs-7 px-1 w-100"
                                   id="addToCartBtn"
                                   style="cursor: pointer; border: none; background: transparent;"
                                   value="${product.id}">
                               <div class="order-product product-search d-flex justify-content-center align-items-center">
                                   <div class="card border shadow-sm rounded mb-2"
                                        style="height: 100%; width: 100%;">
                                       <!-- Product Info -->
                                       <div class="first"
                                            style="position: absolute;width: 100%;padding-left: 4px;padding-top: 0px;">
                                           <div class="d-flex justify-content-between align-items-center">
                                               <span class="discount"
                                                     style="background-color: #3d5ee1;padding: 1px 5px;font-size: 10px;border-radius: 4px;color: #fff;">
                                                   ${product.quantity}</span>
                                               <span class="wishlist"><i class="fa fa-heart-o"></i></span>
                                           </div>
                                       </div>
                                       <!-- Product Image -->
                                       <div style="height: 150px; width: 100%; overflow: hidden;">
                                           ${product.image ? `<img src="${product.image}" class="card-img-top" alt="${product.name}" style="object-fit: cover; height: 100%; width: 100%;" />` : ''}
                                       </div>
                                       <!-- Product Details -->
                                       <div class="card-body d-flex flex-column justify-content-center" style="padding: 10px;">
                                           <h5 class="card-title text-truncate text-start" style="font-size: 14px;">${product.name}</h5>
                                           <h5 class="card-title text-truncate text-start" style="font-size: 14px;">
                                               ${formatPrice(product.selling_price * 4000)}៛
                                           </h5>
                                           <h5 class="card-title text-truncate text-start" style="font-size: 14px; color: #3d5ee1;">
                                               ${product.selling_price}$
                                           </h5>
                                       </div>
                                   </div>
                               </div>
                           </button>
                       </div>
                   `);
                        });
                    } else {
                        // Display a message if no products are found
                        $('#show').append('<p>No products found in this category.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $('#show').empty().append(
                        '<p>An error occurred while fetching products.</p>');
                }
            });
        });
        //
        $(document).on('click', '.item', function() {
            const productId = $(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: `carts`,
                data: {
                    productId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 400) {
                        alert(response.message);
                    }
                    getCarts()
                }
            })
        })
    });

$.ajax({
        type: 'DELETE',
        url: "{{ route('cart.clear') }}",
        success: function(response) {
            if (response.status === 200) {
                console.log(response.message); // Successfully cleared
                getCarts(); // Refresh the cart display
            } else {
                console.error(response.message); // Display error message
            }
        },
        error: function(xhr) {
            console.error('Error clearing cart on page load:', xhr.responseText);
        }
    });
    $(document).on('click', '#clearcart', function() {
        // Show a SweetAlert confirmation dialog
        Swal.fire({
            title: 'តើអ្នកប្រាកដទេ?',
            text: "តើអ្នកពិតជាចង់បោះបង់មែនទេ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'បាទ!',
            cancelButtonText: 'អត់ទេ'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, proceed with the AJAX request to clear the cart
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('cart.clear') }}",
                    success: function(response) {
                        if (response.status === 200) {
                            Swal.fire(
                                'បានលុបចោល!',
                                response.message,
                                'ដោយជោគជ័យ'
                            );
                            getCarts(); // Refresh the cart display
                        } else {
                            Swal.fire(
                                'មានបញ្ហា',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'មានបញ្ហា',
                            'មានបញ្ហា ពេលលុបកន្ត្រកនៅលើទំព័រទាញទិន្នន័យ.',
                            'error'
                        );
                        console.error(
                            'មានបញ្ហា ពេលលុបកន្ត្រកនៅលើទំព័រទាញទិន្នន័យ:',
                            xhr.responseText);
                    }
                });
            }
        });
    });
    delete cart
    $(document).on('click', '.delete', function() {
        const cartId = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $.ajax({
            type: 'delete',
            url: `/carts/${cartId}`,
            success: function(response) {
                if (response.status === 400) {
                    alert(response.message);
                } else if (response.status === 200) {
                    getCarts();
                }
            },
            error: function(xhr) {
                alert('Error deleting cart item. Please try again.');
                console.log(xhr.responseText);
            }
        });
    });
    
    $(document).on('change', '#showCount', function() {
        const showCount = $('#showCount').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'post',
            url: '{{ route('filter.products') }}', // Use Laravel route helper
            data: {
                showCount
            },
            dataType: 'json',
            success: function(response) {
                $('#show').html("");
                if (response.length > 0) {
                    $.each(response, function(key, product) {
                        $('#show').append(`
               <div class="col">
                   <button type="button" class="item custom-btn fs-7 px-1 w-100"
                           id="addToCartBtn"
                           style="cursor: pointer; border: none; background: transparent;"
                           value="${product.id}">
                       <div class="order-product product-search d-flex justify-content-center align-items-center">
                           <div class="card border shadow-sm rounded mb-2"
                                style="height: 100%; width: 100%;">
                               <div class="first"
                                    style="position: absolute;width: 100%;padding-left: 4px;padding-top: 0px;">
                                   <div class="d-flex justify-content-between align-items-center">
                                       <span class="discount"
                                             style="background-color: #3d5ee1;padding: 1px 5px;font-size: 10px;border-radius: 4px;color: #fff;">
                                           ${product.quantity}</span>
                                       <span class="wishlist"><i class="fa fa-heart-o"></i></span>
                                   </div>
                               </div>
                               <div style="height: 150px; width: 100%; overflow: hidden;">
                                   ${product.image ? `<img src="${product.image}" class="card-img-top" alt="${product.name}" style="object-fit: cover; height: 100%; width: 100%;" />` : ''}
                               </div>
                               <div class="card-body d-flex flex-column justify-content-center" style="padding: 10px;">
                                   <h5 class="card-title text-truncate text-start" style="font-size: 14px;">${product.name}</h5>
                                   <h5 class="card-title text-truncate text-start" style="font-size: 14px;">
                                       ${formatPrice(product.selling_price * 4000)}៛
                                   </h5>
                                   <h5 class="card-title text-truncate text-start" style="font-size: 14px; color: #3d5ee1;">
                                       ${product.selling_price}$
                                   </h5>
                               </div>
                           </div>
                       </div>
                   </button>
               </div>
           `);
                    });
                } else {
                    $('#show').append(
                        '<p class="text-danger">No products found in this category.</p>'
                    );
                }
            }
        });
    });
            $(document).on('keyup', '.search', function() {
        const search = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: `/pos/search`,
            data: {
                search
            },
            dataType: 'json',
            success: function(response) {
                $('#show').html("");
                if (response.length > 0) {
                    $.each(response, function(key, product) {
                        console.log('value is ', product);
                        $('#show').append(`
               <div class="col">
                   <button type="button" class="item custom-btn fs-7 px-1 w-100"
                           id="addToCartBtn"
                           style="cursor: pointer; border: none; background: transparent;"
                           value="${product.id}">
                       <div class="order-product product-search d-flex justify-content-center align-items-center">
                           <div class="card border shadow-sm rounded mb-2"
                                style="height: 100%; width: 100%;">
                               <!-- Product Info -->
                               <div class="first"
                                    style="position: absolute;width: 100%;padding-left: 4px;padding-top: 0px;">
                                   <div class="d-flex justify-content-between align-items-center">
                                       <span class="discount"
                                             style="background-color: #3d5ee1;padding: 1px 5px;font-size: 10px;border-radius: 4px;color: #fff;">
                                           ${product.quantity}</span>
                                       <span class="wishlist"><i class="fa fa-heart-o"></i></span>
                                   </div>
                               </div>
                               <!-- Product Image -->
                               <div style="height: 150px; width: 100%; overflow: hidden;">
                                   ${product.image ? `<img src="${product.image}" class="card-img-top" alt="${product.name}" style="object-fit: cover; height: 100%; width: 100%;" />` : ''}
                               </div>
                               <!-- Product Details -->
                               <div class="card-body d-flex flex-column justify-content-center" style="padding: 10px;">
                                   <h5 class="card-title text-truncate text-start" style="font-size: 14px;">${product.name}</h5>
                                   <h5 class="card-title text-truncate text-start" style="font-size: 14px;">
                                       ${formatPrice(product.selling_price * 4000)}៛
                                   </h5>
                                   <h5 class="card-title text-truncate text-start" style="font-size: 14px; color: #3d5ee1;">
                                       ${product.selling_price}$
                                   </h5>
                               </div>
                           </div>
                       </div>
                   </button>
               </div>
                   `);
                        $('#show').attr('data-show', product
                            .name); // Change 'product.name' as needed
                    });
                } else {
                    $('#show').append(
                        '<p class="text-danger">រកមិនឃើញផផលិតផល!</p>'
                    );
                }
            }
        });
    });
    scanbarcode