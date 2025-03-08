$(document).ready(function () {
    // CSRF Token setup for AJAX requests
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
    // Product search functionality using select2
    $("#product-search").select2({
        placeholder: "ស្វែងរកតាមឈ្មោះផលិតផល ឬលេខសម្គាល់...",
        minimumInputLength: 1,
        ajax: {
            url: "/products/search",
            type: "POST",
            dataType: "json",
            delay: 250,
            data: (params) => ({
                search: params.term,
            }),
            processResults: (data) => ({
                results: data
                    .filter((product) => product.quantity > 0)
                    .map((product) => ({
                        id: product.id,
                        text: `${product.name} (${product.code})`,
                        product: product,
                    })),
            }),
        },
    });

    // Add Product to Cart
    $("#product-search").on("select2:select", function (e) {
        const product = e.params.data.product;
        $.ajax({
            type: "POST",
            url: "{{ route('sales.cart.add') }}",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: product.id,
                quantity: 1,
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    getCarts();
                    $("#product-search").val(null).trigger("change");
                } else {
                    toastr.error(response.message || "Failed to add product.");
                    $("#product-search").val(null).trigger("change");
                }
            },
            error: function (xhr, status, error) {
                let errorMessage = "Error adding product to cart.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                toastr.error(errorMessage);
            },
        });
    });
});
