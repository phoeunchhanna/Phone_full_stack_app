$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    
    // បូកសរុបទឹកប្រាក់
    function calculateTotalPrice() {
        let totalPrice = 0;
        $("#tablebody tr").each(function () {
            const quantity = parseInt($(this).find(".qty").val()) || 0;
            const price = parseFloat($(this).find(".price").val()) || 0;
            const rowTotal = quantity * price;
            $(this).find(".priceDisplay").text(rowTotal.toFixed(2));
            totalPrice += rowTotal; // Add to total price
        });
        $("#totalPrice").text(totalPrice.toFixed(2));
    }
    getCarts();
    // change quantity
    $(document).on("change", ".qty", function () {
        const qty = $(this).val();
        const cartId = $(this).closest("td").find(".cartId").val();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "PUT", 
            url: `/carts/${cartId}`, 
            data: {
                qty,
            },
            dataType: "json",
            success: function (response) {
                if (response.status === 400) {
                } else if (response.status === 200) {
                    if (typeof getCarts === "function") {
                        getCarts();
                    }
                }
            },
            error: function (xhr, status, error) {
                console.log("Error:", error);
                alert("An error occurred while updating the quantity.");
            }
        });
    });
    // click card to insert to cart
    $(document).on("click", ".item", function () {
        const productId = $(this).val();
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "post",
            url: `carts`,
            data: {
                productId,
            },
            dataType: "json",
            success: function (response) {
                if (response.status === 400) {
                    alert(response.message);
                }
                getCarts();
            },
        });
    });
    // delete cart
    $(document).on("click", ".delete", function () {
        const cartId = $(this).val();

        $.ajax({
            type: "DELETE",
            url: `/carts/${cartId}`,
            success: function (response) {
                if (response.status === 200) {
                    toastr.success(response.message);
                    getCarts();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr) {
                toastr.error("Error deleting cart item. Please try again.");
                console.log(xhr.responseText);
            },
        });
    });
    
});
