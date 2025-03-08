@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">កែប្រែ ការទិញផលិតផល</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="SaleReturns.html">ការទិញ</a></li>
                            <li class="breadcrumb-item active">កែប្រែ ការទិញផលិតផល</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="student-group-form">
                <div class="row">
                    <!-- Barcode Search Field -->
                    <div class="col-lg-6 col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control barcode" placeholder="ស្កែនបាកូដ ....">
                        </div>
                    </div>

                    <!-- Search List with Overlay Effect -->
                    <div class="col-lg-6 col-md-6 position-relative">
                        <div class="form-group position-relative" style="z-index: 2; border: 0;">
                            <input type="text" class="form-control search"
                                placeholder="ស្វែងរកតាមរយៈឈ្មោះផលិតផល ឬបាកូដ .......">
                            <ul id="product-list" class="list-group list-group-flush mt-1 position-absolute"
                                style="width: 100%;">
                                <!-- Search Results -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('salesreturn.update', $salereturns->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col-12">
                                    <h5 class="form-title"><span>ព័ត៌មានលម្អិតអំពីការទិញ</span></h5>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="reference">លេខយោង <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="reference" required
                                                value="{{ $salereturns->reference }}" readonly>
                                            @error('reference')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <label for="customer_id">អ្នកផ្គត់ផ្គង់ <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select
                                                class="form-control form-select @error('customer_id') is-invalid @enderror"
                                                name="customer_id" id="customer_id" required>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        {{ $salereturns->customer_id == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="date">កាលបរិច្ឆេទ<span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="date" required
                                                value="{{ $salereturns->date }}" max="{{ now()->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table-hover table-center mb-4 table table-stripped">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="form-check check-tables">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="something">
                                                        </div>
                                                    </th>
                                                    <th>ឈ្មោះផលិតផល</th>
                                                    <th>តម្លៃ</th>
                                                    <th>បរិមាណក្នុងស្តុក</th>
                                                    <th>បរិមាណដែលត្រូវបញ្ជាទិញ</th>
                                                    <th>តម្លៃសរុប</th>
                                                    <th class="text-center">សកម្មភាព</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td colspan="6" class="text-center">រកមិនឃើញ</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <hr class="hr" />
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="status">ស្ថានភាព <span class="text-danger">*</span></label>
                                            <select class="form-control form-select" name="status" id="status" required>
                                                <option value="រង់ចាំ"
                                                    {{ $salereturns->status == 'រង់ចាំ' ? 'selected' : '' }}>រង់ចាំ</option>
                                                <option value="បានបញ្ជាទិញ"
                                                    {{ $salereturns->status == 'បានបញ្ជាទិញ' ? 'selected' : '' }}>
                                                    បានបញ្ជាទិញ</option>
                                                <option value="បានបញ្ចប់"
                                                    {{ $salereturns->status == 'បានបញ្ចប់' ? 'selected' : '' }}>បានបញ្ចប់
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="payment_method">វិធីសាស្ត្របង់ប្រាក់<span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control form-select" name="payment_method"
                                                id="payment_method" required>
                                                <option value="សាច់ប្រាក់"
                                                    {{ $salereturns->payment_method == 'សាច់ប្រាក់' ? 'selected' : '' }}>
                                                    សាច់ប្រាក់
                                                </option>
                                                <option value="ABA"
                                                    {{ $salereturns->payment_method == 'ABA' ? 'selected' : '' }}>
                                                    ABA</option>
                                                <option value="អេស៊ីលីដា"
                                                    {{ $salereturns->payment_method == 'អេស៊ីលីដា' ? 'selected' : '' }}>
                                                    អេស៊ីលីដា</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="total_amount">សរុប <span
                                                    class="text-danger">*</span></label>
                                           
                                        </div>
                                    </div> --}}
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="paid_amount">ចំនួនទឹកប្រាក់ដែលត្រូវបង់ <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input id="total_amount" type="hidden" class="form-control total"
                                                    name="total_amount" required readonly
                                                    value="{{ $salereturns->total_amount }}">
                                                <input id="paid_amount" type="text" class="form-control"
                                                    name="paid_amount" value="{{ $salereturns->paid_amount }}" required>
                                                <div class="input-group-append">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="note">ចំណាំ(បើមាន)</label>
                                    <textarea name="note" id="note" rows="5" class="form-control">{{ $salereturns->note }}</textarea>
                                </div>
                                <div class="mt-3">
                                    <a type="button" href="{{ route('salesreturn.index') }}" class="btn btn-secondary"
                                        id="exit">ចាកចេញ</a>
                                    <button type="submit" class="btn btn-primary" id="updateSaleReturn">
                                        កែប្រែការបញ្ជាទិញ<i class="bi bi-check"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.suppliers.modal.create')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            window.addEventListener('beforeunload', function() {
                fetch('{{ route('cart.clear') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
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
                            $('#createSaleReturn').prop('disabled', false);
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
                                    </tr>
                                `);
                            });
                            $('.total').val(total.toFixed(2)); // Update total input
                        } else {
                            // If there are no products, display a message
                            $('tbody').append(`
                                <tr>
                                    <td colspan="6" class="text-center text-danger">No products found in the cart.</td>
                                </tr>`);
                            $('.total').val('0.00');
                            $('#createSaleReturn').prop('disabled', true);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            }

            getCarts(); // Fetch cart items on page load

            // Handle barcode input
            $(document).on('input', '.barcode', function() {
                const barcode = $(this).val().trim();

                $.ajax({
                    type: 'POST',
                    url: '/SaleReturns/barcode',
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
                    url: `/SaleReturns/Carts/${cartId}`,
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

            // Function to clear the cart
            function clearCart() {
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('cart.clear') }}", // Ensure the route is correct
                    success: function(response) {
                        if (response.status === 200) {
                            toastr.success("Cart cleared successfully");
                            getCarts(); // Refresh the cart items after clearing
                        } else {
                            toastr.error("Error clearing cart.");
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Error clearing cart. Please try again.");
                        console.log(xhr.responseText); // Log the error to the console
                    }
                });
            }

            $(document).on('click', '#exit', function() {
                clearCart(); 
            });

            // Quantity update
            $(document).on('change', '.qty', function() {
                const qty = $(this).val();
                const cartId = $(this).closest('td').find('.cartId').val();

                $.ajax({
                    type: 'PUT',
                    url: `/SaleReturns/carts/${cartId}`,
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
            $(document).on('keyup', '.search', function() {
                const search = $(this).val();

                if (search.trim() !== "") {
                    $.ajax({
                        type: 'POST',
                        url: '/SaleReturns/search',
                        data: {
                            search
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#product-list').html(""); // Clear the list each time

                            if (response.length > 0) {
                                // Loop through each result and add to the list
                                $.each(response, function(key, product) {
                                    $('#product-list').append(`
                                        <li class="list-group-item list-group-item-action">
                                            <a href="#" onclick="event.preventDefault(); selectProduct(${product.id});">
                                                ${product.name} | ${product.barcode}
                                            </a>
                                        </li>`);
                                });
                            } else {
                                // Display a message if no products are found
                                $('#product-list').append(
                                    '<li class="list-group-item text-danger">No products found in this category.</li>'
                                );
                            }
                        },
                        error: function() {
                            // Display an error message if there's an issue with the AJAX request
                            $('#product-list').html(
                                '<li class="list-group-item text-danger">Error fetching products. Please try again.</li>'
                            );
                        }
                    });
                } else {
                    // Clear the list if search input is empty
                    $('#product-list').html("");
                }
            });

            // Handle product selection
            $(document).on('click', '.productId', function() {
                const productId = $(this).data('id');

                $.ajax({
                    type: 'POST',
                    url: `/SaleReturns/barcode`, // Ensure this URL matches your route
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
        });
    </script>
@endsection
