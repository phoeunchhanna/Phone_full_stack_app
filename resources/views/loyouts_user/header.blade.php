<style>
    /* Dropdown Hover Effects */
    /* Enhanced Dropdown Hover Effects */
    @media (min-width: 992px) {

        /* Main dropdown hover effect */
        .navbar .nav-item.dropdown:hover .dropdown-menu {
            display: block;
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        /* Dropdown menu styling */
        .dropdown-menu {
            display: block;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
            margin-top: 0;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 15px;
            z-index: 1055 !important;
            /* above navbar (z-index: 1030) */
            display: block !important;
            /* for testing only */
        }

        /* Dropdown item hover effect */
        .dropdown-item {
            transition: all 0.2s ease;
            padding: 8px 15px;
            border-radius: 4px;
        }

        .dropdown-item:hover {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd !important;
            transform: translateX(5px);
        }

        /* Multi-column dropdowns */
        .dropdown-menu.multi-column {
            width: 600px;
            columns: 2;
            column-gap: 20px;
            border: none;
        }

        /* Prevent breaking within items */
        .dropdown-menu.multi-column li {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        /* Cart and wishlist dropdowns */
        .dropdown:not(.nav-item) .dropdown-menu {
            right: 0;
            left: auto;
            min-width: 300px;
            padding: 15px;
        }

        /* Search dropdown */
        #mobileSearch.collapse:not(.show) {
            display: none;
        }
    }

    /* Mobile specific styles */
    @media (max-width: 991.98px) {

        /* Ensure dropdowns work with click on mobile */
        .dropdown-menu {
            box-shadow: none;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        /* Mobile search */
        #mobileSearch.collapse {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgb(0, 0, 0);
            padding: 10px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }
    }

    /* General dropdown improvements */
    .dropdown-toggle::after {
        display: none;
    }

    /* Cart and wishlist icons */
    .nav-link .fa-heart,
    .nav-link .fa-shopping-cart {
        transition: transform 0.2s ease;
    }

    .nav-link:hover .fa-heart,
    .nav-link:hover .fa-shopping-cart {
        transform: scale(1.1);
    }

    /* Search button hover */
    .btn-primary:hover {
        background-color: #e6d705;
        border-color: #0a58ca;
    }

    .navbar {
        transition: all 0.3s ease;
    }

    .navbar.sticky {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        animation: slideDown 0.3s ease-out;
    }

    body.sticky-nav {
        padding-top: 56px;
        /* Adjust this if navbar height changes */
    }


    @keyframes slideDown {
        from {
            transform: translateY(-100%);
        }

        to {
            transform: translateY(0);
        }
    }
</style>
<header>
    {{-- message cart alert --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert"
            style="z-index: 1055;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="bg-secondary border-bottom py-2 text-white">
        <div class="container">
            <div class="row align-items-center">
                <!-- Contact Info - Hidden on mobile -->
                <div class="col-md-6 d-none d-md-block">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-phone-alt me-2"></i>
                            <span>+885 883140333</span>
                        </div>
                        <div>
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:phoeun.chhanna32@gmail.com"
                                class="text-decoration-none text-white">phoeun.chhanna32@gmail.com</a>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Full on desktop, simplified on mobile -->
                <div class="col-12 col-md-6">
                    <div class="d-flex justify-content-between justify-content-md-end">
                        <!-- Language dropdown - Hidden on mobile -->
                        <div class="dropdown me-3 d-none d-md-block">
                            <a class="dropdown-toggle text-decoration-none text-white" href="#"
                                id="languageDropdown" data-bs-toggle="dropdown">
                                English <i class="fas fa-chevron-down ms-1"></i>
                            </a>
                            <ul class="dropdown-menu bg-secondary">
                                <li><a class="dropdown-item text-white" href="#">Italian</a></li>
                                <li><a class="dropdown-item text-white" href="#">Spanish</a></li>
                                <li><a class="dropdown-item text-white" href="#">Japanese</a></li>
                            </ul>
                        </div>

                        <!-- Login/Register - Always visible -->
                        <div class="d-flex">
                            <div class="me-3">
                                <a href="#" class="text-decoration-none text-white">Register</a>
                            </div>
                            <div>
                                <a href="#" class="text-decoration-none text-white">Sign in</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-warning py-2">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('images/phone_shopicon.png') }}" alt="Logo" class="rounded-circle p-1 bg-dark"
                    style="width: 50px; height: 50px; object-fit: contain;">
            </a>

            <!-- Toggler -->
            <button class="navbar-toggler text-black" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse justify-content-center" id="navbarMain">
                <!-- Centered Nav Links -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-black" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-black" href="#" data-bs-toggle="dropdown">
                            All Product
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($products as $product)
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('product_client.show', ['id' => $product->id]) }}">
                                        {{ $product->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-black" href="#" data-bs-toggle="dropdown">
                            All Brands
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($brands as $brand)
                                <li>
                                    <a href="{{ route('brand.products', $brand->id) }}" class="text-decoration-none">
                                        {{ $brand->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-black" href="#" data-bs-toggle="dropdown">
                            All Category
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($categories as $category)
                                <li>
                                    <a class="dropdown-item" href="{{ route('category.products', $category->id) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-black" href="{{ route('contact.form') }}">Contact</a>
                    </li>
                </ul>

                <!-- Right Search and Cart -->
                <div class="d-flex align-items-center">
                    <!-- Search Bar -->
                    <form class="input-group me-3 border border-primary rounded">
                        <input type="search" class="form-control border-0" placeholder="Search for products...">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    @php
                        $cart = session('cart', []);
                        $cartCount = array_sum(array_column($cart, 'quantity'));
                        $cartTotal = array_reduce(
                            $cart,
                            function ($carry, $item) {
                                return $carry + $item['quantity'] * $item['price'];
                            },
                            0,
                        );
                    @endphp
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link text-black position-relative" href="#" id="cartDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count">
                                {{ $cartCount }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-3 shadow" aria-labelledby="cartDropdown"
                            style="min-width: 300px; max-width: 350px; width: auto;">
                            @if (!empty($cart))
                                @foreach ($cart as $id => $item)
                                    <li class="d-flex align-items-start mb-3 border-bottom pb-2">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                            class="rounded me-2"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <p class="mb-0 fw-bold">{{ $item['name'] }}</p>
                                            <small>{{ $item['quantity'] }} × R{{ number_format($item['price'], 2) }}</small>
                                        </div>
                                        <a href="#" class="text-danger ms-2 remove-from-cart"
                                            data-id="{{ $id }}">
                                            <i class="fas fa-times-circle"></i>
                                        </a>
                                    </li>
                                @endforeach
                                <li class="d-flex justify-content-between fw-bold mt-2">
                                    <span>សរុបរង:</span>
                                    <span>R{{ number_format($cartTotal, 2) }}</span>
                                </li>
                                <li class="text-center mt-3">
                                    <a href="{{ route('checkout') }}" class="btn btn-primary w-100">ពិនិត្យលុយ</a>
                                </li>
                            @else
                                <li class="text-center text-muted">រទេះទំនេរ។</li>
                            @endif
                        </ul>
                    </li>
                </div>
            </div>
        </div>
    </nav>

</header>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.querySelector('.navbar');
        const body = document.body;

        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('sticky');
                body.classList.add('sticky-nav');
            } else {
                navbar.classList.remove('sticky');
                body.classList.remove('sticky-nav');
            }
        });

        $(document).on('click', '.add-to-cart-btn', function(e) {
            e.preventDefault();

            const productId = $(this).data('id');

            $.ajax({
                url: '/add-to-cart/' + productId,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#cart-alert-msg').text(response.message);
                        $('#cart-alert').removeClass('d-none').addClass('show');

                        // Optionally update cart count badge
                        $('.cart-count').text(response.cart_count);

                        // Auto-hide alert after 3 seconds
                        setTimeout(() => {
                            $('#cart-alert').removeClass('show').addClass('d-none');
                        }, 3000);
                    }
                },
                error: function() {
                    alert('Something went wrong. Please try again.');
                }
            });
        });

        // Existing jQuery logic...
        $(document).ready(function() {
            // Handle click on remove button
            $(document).on('click', '.remove-from-cart', function(e) {
                e.preventDefault();
                let id = $(this).data('id');

                if (confirm('តើអ្នកពិតជាចង់លុបធាតុនេះចេញពីរទេះមែនទេ?')) {
                    $.ajax({
                        url: '{{ route('cart.remove') }}',
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(response) {
                            if (response.success) {
                                // Remove item from DOM
                                // $('[data-id="' + id + '"]').closest('li').remove();
                                 $(`li.cart-item[data-item-id="${id}"]`).remove();

                                // Update cart count
                                $('.cart-count').text(response.cartCount);

                                // Update subtotal
                                // $('.cart-subtotal').text('R' + response.subtotal
                                //     .toFixed(2));
                                $('.cart-subtotal span:last').text('R' + response.subtotal.toFixed(2));

                                // If cart is empty, show empty message
                                if (response.cartCount === 0) {
                                    $('.dropdown-menu').html(
                                        '<li class="text-center text-muted">រទេះទំនេរ។</li>'
                                    );
                                }

                                toastr.success(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON.message ||
                                'មានបញ្ហាកើតឡើង!');
                        }
                    });
                }
            });
        });

    });
</script>

{{-- @include('loyouts_user.mobile_menu') --}}
