@extends('loyouts_user.app')
@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        /* Image zoom on hover */
        .img-zoom-container {
            position: relative;
            overflow: hidden;
        }

        .img-zoom {
            transition: transform 0.3s;
            cursor: zoom-in;
        }

        .img-zoom:hover {
            transform: scale(1.5);
            z-index: 10;
            position: relative;
        }

        /* Checkbox styling */
        .feature-checkbox {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .feature-checkbox input[type="checkbox"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
        }

        /* Modal for full image view */
        .modal-image {
            max-width: 100%;
            max-height: 80vh;
        }


        /*rotate  */
        .rotation-controls {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .rotate-btn,
        .flip-btn {
            white-space: nowrap;
        }

        #modalImage {
            transition: transform 0.3s ease;
        }
    </style>
    <section class="p-3">
        <div class="container mt-3  py-4 bg-light rounded">
            <div class="row  align-items-center">
                <!-- Product Image (Left Column) -->
                <div class="col-md-6 mb-4 mb-md-0 img-zoom-container">
                    <img src="{{ asset('storage/' . $pro_shows->image) }}" alt="{{ $pro_shows->name }}"
                        class="img-fluid rounded shadow-sm w-100 img-zoom" style="height: 400px; widows: 100px"
                        data-bs-toggle="modal" data-bs-target="#imageModal">
                </div>

                <!-- Product Details (Right Column) -->
                <div class="col-md-6">
                    <h2 class="mb-3">{{ $pro_shows->name }}</h2>
                    <p class="text-muted mb-2">Status: {{ $pro_shows->condition }}</p>
                    <div class="mb-3">
                        <span class="h4 text-primary">${{ number_format($pro_shows->selling_price, 2) }}</span>
                    </div>
                    <p class="mb-4">{{ $pro_shows->description }}</p>
                    <div class="mb-4 d-flex align-items-center" data-id="{{ $pro_shows->id }}"> <!-- Add this line -->
                        <label for="quantity" class="form-label me-3">Quantity:</label>
                        <div class="input-group" style="width: 150px;">
                            <button class="btn btn-outline-secondary decrease-quantity" type="button">-</button>
                            <input type="number" class="form-control text-center quantity-input" value="1"
                                min="1">
                            <button class="btn btn-outline-secondary increase-quantity" type="button">+</button>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <a href="{{ route('add.to.cart', $pro_shows->id) }}" id="add-to-cart-button"
                            class="btn btn-outline-primary btn-lg shadow-sm">
                            <i class="bi bi-cart-plus"></i> Add to Cart
                        </a>
                    </div>

                    <div class="mt-4">
                        <h5>Key Features:</h5>
                        <div class="mb-0">
                            <div class="feature-checkbox">
                                <label for="feature1">
                                    <i class="fab fa-facebook text-primary me-2"></i>Phone Shope
                                </label>
                            </div>
                            <div class="feature-checkbox">
                                <label for="feature2">
                                    <i class="fab fa-telegram text-info me-2"></i>Phone Shope
                                </label>
                            </div>
                            <div class="feature-checkbox">
                                <label for="feature3">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>Nimit Village, Nimit District,
                                    Piopet City, Cambodia Country
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $pro_shows->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ asset('storage/' . $pro_shows->image) }}" alt="{{ $pro_shows->name }}"
                            class="modal-image" id="modalImage" style="max-height: 70vh; height: 350px;">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <div class="rotation-controls">
                            <button type="button" class="btn btn-outline-primary rotate-btn" data-degrees="-90">
                                <i class="bi bi-arrow-counterclockwise"></i> Rotate Left
                            </button>
                            <button type="button" class="btn btn-outline-primary rotate-btn" data-degrees="90">
                                <i class="bi bi-arrow-clockwise"></i> Rotate Right
                            </button>
                            <button type="button" class="btn btn-outline-primary flip-btn" data-direction="vertical">
                                <i class="bi bi-arrow-down-up"></i> Flip Vertical
                            </button>
                            <button type="button" class="btn btn-outline-primary flip-btn" data-direction="horizontal">
                                <i class="bi bi-arrow-left-right"></i> Flip Horizontal
                            </button>
                            <button type="button" class="btn btn-outline-danger" id="resetRotation">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- include form --}}
        @include('loyouts_user.product_list.product_sale_list')
    </section>


    <script>
        function changeImage(event, src) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
            event.target.classList.add('active');
        }

        // 
        document.addEventListener('DOMContentLoaded', function() {
            const modalImage = document.getElementById('modalImage');
            let rotation = 0;
            let scaleX = 1;
            let scaleY = 1;

            // Rotate buttons
            document.querySelectorAll('.rotate-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const degrees = parseInt(this.getAttribute('data-degrees'));
                    rotation += degrees;
                    applyTransformation();
                });
            });

            // Flip buttons
            document.querySelectorAll('.flip-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const direction = this.getAttribute('data-direction');
                    if (direction === 'vertical') {
                        scaleY *= -1;
                    } else {
                        scaleX *= -1;
                    }
                    applyTransformation();
                });
            });

            // Reset button
            document.getElementById('resetRotation').addEventListener('click', function() {
                rotation = 0;
                scaleX = 1;
                scaleY = 1;
                applyTransformation();
            });

            function applyTransformation() {
                modalImage.style.transform = `rotate(${rotation}deg) scaleX(${scaleX}) scaleY(${scaleY})`;
            }

            // Reset transformations when modal is closed
            document.getElementById('imageModal').addEventListener('hidden.bs.modal', function() {
                rotation = 0;
                scaleX = 1;
                scaleY = 1;
                modalImage.style.transform = '';
            });
        });

        // Add to Cart" Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const addToCartButton = document.getElementById('add-to-cart-button');

            function updateCartLink() {
                const productId = "{{ $pro_shows->id }}";
                const quantity = quantityInput.value;
                // Assuming your 'add.to.cart' route can handle a quantity parameter
                // You might need to adjust the route's expected parameters on the backend
                addToCartButton.href = `/add-to-cart/${productId}?quantity=${quantity}`;
                // Or if you prefer to use the route helper directly (more robust in Laravel):
                // addToCartButton.href = `{{ route('add.to.cart', ['id' => $pro_shows->id]) }}&quantity=${quantity}`;
                // The above commented line might be tricky with Blade's escaping, so the direct URL construction is often easier for dynamic query params.
            }

            // Update the link when the quantity changes
            quantityInput.addEventListener('change', updateCartLink);
            quantityInput.addEventListener('keyup', updateCartLink); // For immediate updates as user types

            // Initial update in case the default quantity is changed before interaction
            updateCartLink();
        });

        $(document).ready(function() {
            // Handle increase quantity
            $(document).on('click', '.increase-quantity', function(e) {
                e.preventDefault();
                let input = $(this).siblings('.quantity-input');
                let currentVal = parseInt(input.val());
                let newVal = currentVal + 1;
                input.val(newVal).trigger('change');

                let productId = $(this).closest('[data-id]').data('id');
                updateCartQuantity(productId, newVal);
            });

            // Handle decrease quantity
            $(document).on('click', '.decrease-quantity', function(e) {
                e.preventDefault();
                let input = $(this).siblings('.quantity-input');
                let currentVal = parseInt(input.val());

                if (currentVal > 1) {
                    let newVal = currentVal - 1;
                    input.val(newVal).trigger('change');
                    let productId = $(this).closest('[data-id]').data('id');
                    updateCartQuantity(productId, newVal);
                }
            });

            // Handle direct input changes
            $(document).on('change', '.quantity-input', function() {
                let newQuantity = parseInt($(this).val());
                if (isNaN(newQuantity) || newQuantity < 1) {
                    newQuantity = 1;
                    $(this).val(1);
                }

                let productId = $(this).closest('[data-id]').data('id');
                updateCartQuantity(productId, newQuantity);
            });

            function updateCartQuantity(productId, newQuantity) {
                $.ajax({
                    url: '/cart/update/' + productId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        quantity: newQuantity
                    },
                    success: function(response) {
                        if (response.success) {
                            $('.cart-count').text(response.cartCount);
                            $('.cart-subtotal').text('R' + response.subtotal.toFixed(2));
                            toastr.success(response.message);

                            // Update the specific item's total if needed
                            $(`[data-id="${productId}"] .item-total`).text('R' + (response.itemPrice *
                                newQuantity).toFixed(2));
                        }
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON?.message || 'មានបញ្ហាកើតឡើង!');
                        location.reload(); // Reload to sync with server state
                    }
                });
            }
        });
    </script>
@endsection
