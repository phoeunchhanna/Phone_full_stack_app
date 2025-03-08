@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            {{-- <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">nake</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">nh</li>
                        </ul>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="student-group-form">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="form-group">
                            <input type="text" class="form-control search" placeholder="Search Product..." />
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="card card-table">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">ផ្ទាំងលក់ផលិតផល</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Left Column: User Cart -->
                        <div class="col-12 col-md-6 col-lg-8 mb-4"> <!-- Add mb-4 here -->
                            <div class="row g-2 align-items-start mb-2">
                                <!-- Search Inputs -->
                                <div class="col-6 col-md-6">
                                    <input type="text" class="form-control search"
                                        placeholder="ស្វែងរកតាមរយៈឈ្មោះផលិតផល...">
                                </div>
                                <div class="col-6 col-md-6">
                                    <select id="showCount" class="form-control form-select">
                                        <option value="10">10 ផលិតផល</option>
                                        <option value="15">15 ផលិតផល</option>
                                        <option value="20">21 ផលិតផល</option>
                                        <option value="30">30 ផលិតផល</option>
                                        <option value="">បង្ហាញទាំងអស់</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-0">
                                <h5>ផលិតផល:</h5>
                            </div>
                            <div class="mt-3 " data-bs-spy="scroll"
                                style="max-height: 600px; overflow-y: auto;padding-bottom: 20px;">
                                <!-- Products Loop -->
                                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 g-1" id="show">
                                    @foreach ($products->take(10) as $product)
                                        <div class="col">
                                            <a href="{{ route('add.to.cart', $product->id) }}" type="button" class="item custom-btn fs-7 px-1 w-100 "
                                                id="addToCartBtn"
                                                style="cursor: pointer; border: none; background: transparent; "
                                                value="{{ $product->id }}">
                                                <div
                                                    class="order-product product-search d-flex justify-content-center align-items-center">
                                                    <div class="card border shadow-sm rounded mb-2"
                                                        style="height: 250px; width: 100%; overflow: hidden; ">
                                                        <!-- Product Info -->
                                                        <div class="first"
                                                            style="position: absolute;width: 100%;padding-left: 4px;padding-top: 0px;">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="discount"
                                                                    style="background-color: #3d5ee1;padding: 2px 5px;font-size: 10px;border-radius: 4px;color: #fff;">
                                                                    ស្តុក: {{ $product->quantity }}</span>
                                                                <span class="wishlist"><i class="fa fa-heart-o"></i></span>
                                                            </div>
                                                        </div>
                                                        <!-- Product Image -->
                                                        <div style="height: 150px; width: 100%; overflow: hidden; ">
                                                            @if ($product->image)
                                                                <img src="{{ asset($product->image) }}"
                                                                    class="card-img-top" alt="{{ $product->name }}"
                                                                    style="object-fit: cover; height: 100%; width: 100%;" />
                                                            @endif
                                                        </div>
                                                        <!-- Product Details -->
                                                        <div class="card-body d-flex flex-column justify-content-center"
                                                            style="padding: 10px;">
                                                            <h5 class="card-title text-truncate text-start"
                                                                style="font-size: 14px;">{{ $product->name }}</h5>
                                                            <h5 class="card-title text-truncate text-start"
                                                                style="font-size: 14px;">
                                                                {{ number_format($product->selling_price * 4100, 2, ',', ',') }}៛
                                                            </h5>
                                                            <h5 class="card-title text-truncate text-start"
                                                                style="font-size: 14px; color: #3d5ee1;">
                                                                ${{ number_format($product->selling_price, 2) }}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <div class="mb-3">
                                <div class="row mb-2">
                                    <div class="col">
                                        <div class="input-group">
                                            <input type="text" class="form-control barcode"
                                                placeholder="ស្កែនបាកូដ..." />
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row mb-2">
                                    <div class="col">
                                        <div class="input-group">
                                            <button class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#createcustomers">បន្ថែម <i
                                                    class="fas fa-plus"></i></button>
                                            <select class="form-control form-select" name="customer_id" id="customer_id"
                                                required>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="user-cart"style="max-height: 600px; overflow-y: auto;">
                                    <div class="card">
                                        <div class="table-responsive"> <!-- Added responsive wrapper -->
                                            <table id="cart" class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ឈ្មោះផលិតផល</th>
                                                        <th>ចំំនួន</th>
                                                        <th class="text-right">សរុបរង</th>
                                                        <th class="text-right">សកម្មភាព</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="productTableBody">
                                                    @php $total = 0 @endphp
                                                    @if(session('cart'))
                                                        @foreach(session('cart') as $id => $details)
                                                            @php $total += $details['selling_price'] * $details['quantity'] @endphp
                                                            <tr data-id="{{ $id }}">
                                                                <td><a class="fs-6" href="">{{ $details['name'] }}</a></td>
                                                                <td data-th="selling_price">${{ number_format($details['selling_price'], 2) }}</td>
                                                                <td data-th="Quantity">
                                                                    <input type="number" value="{{ $details['quantity'] }}" class="form-control quantity update-cart" />
                                                                </td>
                                                                <td data-th="Subtotal" class="text-center">
                                                                    ${{ number_format($details['selling_price'] * $details['quantity'], 2) }}
                                                                </td>
                                                                <td>
                                                                    <button type="button" class="btn btn-danger btn-sm remove-from-cart" data-id="{{ $id }}">
                                                                        <i class="bi bi-trash3"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="6" class="text-center text-danger">រកមិនឃើញផលិតផល!</td>
                                                        </tr>
                                                    @endif                                                    
                                                <tr>
                                                    <td colspan="6" class="text-center text-danger">រកមិនឃើញផផលិតផល!</td>
                                                </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td></td>
                                                        <td class="text-right"><strong>តម្លៃសរុប:</strong></td>
                                                        <td class="text-right" id="totalPrice">{{ $total }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button type="button" class="btn btn-danger btn-block" id="clearcart"
                                            value="">បោះបង់</button>
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-pill btn-primary btn-block"
                                            id="checkoutButton" data-bs-toggle="modal" data-bs-target="#checkoutModal"
                                            onclick="setCustomerId()">
                                            លក់
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <script type="text/javascript">
  
  $(".update-cart").change(function (e) {
    e.preventDefault();
    var ele = $(this);

    $.ajax({
        url: '{{ route('update.cart') }}',
        method: "patch",
        data: {
            _token: '{{ csrf_token() }}', 
            id: ele.parents("tr").attr("data-id"), 
            quantity: ele.parents("tr").find(".quantity").val()
        },
        success: function (response) {
            console.log(response); // Check the response structure
            if (response.html && response.total) {
                $('#productTableBody').html(response.html);
                $('#totalPrice').text(response.total);
            } else {
                alert("Failed to update cart content.");
            }
        }
    });
});


$(".remove-from-cart").click(function (e) {
    e.preventDefault();
    var ele = $(this);

    if (confirm("Are you sure want to remove?")) {
        $.ajax({
            url: '{{ route('remove.from.cart') }}',
            method: "DELETE",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele.parents("tr").attr("data-id")
            },
            success: function (response) {
                // Update only the cart table and total price
                $('#productTableBody').html(response.html);
                $('#totalPrice').text(response.total);
            }
        });
    }
});

      
    </script>
@endsection
