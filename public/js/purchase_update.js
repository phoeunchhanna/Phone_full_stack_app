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

//     // Select product and add to cart
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
//     function getCarts() {
//         $.ajax({
//             type: "GET",
//             url: "/carts",
//             data: {
//                 limit: 10,
//             },
//             dataType: "json",
//             success: function(response) {
//                 let total = 0;
//                 $("#tablebody").html("");

//                 if (response.carts && response.carts.length > 0) {
//                     $("#createpurchase, #clearcart").prop("disabled", false);
//                     $.each(response.carts, function(key, product) {
//                         total += product.price * product.quantity;
//                         $("#tablebody").append(`
//                         <tr>
//                             <td>
//                                 ${product.name}
                               
//                             </td>
//                             <td> ${product.stock}</td>
//                             <td> ${product.price}</td>
//                             <td class="col-sm-3">
//                                 <input type="number" class="form-control form-control-sm qty" min="1" value="${product.quantity}" />
//                                 <input type="hidden" class="price" value="${
//                                     product.price
//                                 }" />
//                                 <input type="hidden" class="cartId" value="${
//                                     product.id
//                                 }" />
//                             </td>
//                             <td class="text-right priceDisplay">${(
//                                 product.quantity * product.price
//                             ).toFixed(2)}</td>
//                             <td class"text-right">
//                                 <button type="button" class="btn btn-danger btn-sm delete" value="${
//                                     product.id
//                                 }">
//                                     <i class="bi bi-trash3"></i>
//                                 </button>
//                             </td>
//                         </tr>
//                     `);
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
//             error: function() {
//                 toastr.error("Error retrieving cart items.");
//             },
//         });
//     }
//     getCarts();
// });
