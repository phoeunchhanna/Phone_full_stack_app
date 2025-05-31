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

    <div class="container mt-5 py-4 bg-light rounded">
        <div class="row align-items-center">
            <!-- Product Image (Left Column) -->
            <div class="col-md-6 mb-4 mb-md-0 img-zoom-container">
                <img src="{{ asset('storage/' . $pro_shows->image) }}" alt="{{ $pro_shows->name }}"
                    class="img-fluid rounded shadow-sm w-100 img-zoom" style="height: 400px; widows: 100px"
                    data-bs-toggle="modal" data-bs-target="#imageModal">
            </div>

            <!-- Product Details (Right Column) -->
            <div class="col-md-6">
                <h2 class="mb-3">{{ $pro_shows->name }}</h2>
                <p class="text-muted mb-2">SKU: {{ $pro_shows->sku }}</p>
                <div class="mb-3">
                    <span class="h4 text-primary">${{ number_format($pro_shows->selling_price, 2) }}</span>
                </div>
                <p class="mb-4">{{ $pro_shows->description }}</p>

                <div class="mb-4">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="number" class="form-control" id="quantity" value="1" min="1"
                        style="width: 100px;">
                </div>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button class="btn btn-primary btn-lg">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                    <button class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-heart"></i> Add to Wishlist
                    </button>
                </div>

                <div class="mt-4">
                    <h5>Key Features:</h5>
                    <div class="mb-0">
                        <div class="feature-checkbox">
                            <input type="checkbox" id="feature1" checked>
                            <label for="feature1">Rem 8 128 G</label>
                        </div>
                        <div class="feature-checkbox">
                            <input type="checkbox" id="feature2">
                            <label for="feature2">Rem 8 256 G</label>
                        </div>
                        <div class="feature-checkbox">
                            <input type="checkbox" id="feature3">
                            <label for="feature3">Rem 8 512 G</label>
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
                    <img src="{{ asset('storage/' . $pro_shows->image) }}" alt="{{ $pro_shows->name }}" class="modal-image"
                        id="modalImage" style="max-height: 70vh; height: 350px;">
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
    </script>
@endsection
