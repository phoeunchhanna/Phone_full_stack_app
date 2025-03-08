{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/font/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/img.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <style>
        @font-face {
            font-family: 'Battambang';
            src: url('/fonts/Battambang-Regular.ttf') format('truetype');
            font-weight: 400;
        }

        @font-face {
            font-family: 'Battambang';
            src: url('/fonts/Battambang-Bold.ttf') format('truetype');
            font-weight: 700;
        }

        body {
            font-family: 'Battambang', sans-serif;
        }

        /* Header styling */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007bff;
            padding: 10px;
            color: white;
        }

        .header-container img {
            width: 50px;
            height: 50px;
        }

        .header-container .button-group {
            display: flex;
            gap: 10px;
        }

        .header-container button {
            background-color: #28a745;
            border: none;
            color: white;
            padding: 10px;
            cursor: pointer;
            font-size: 14px;
        }

        .header-container button:hover {
            background-color: #218838;
        }

        /* Styling the card */
        .card-body {
            padding: 15px;
        }

        .btn {
            font-size: 14px;
        }

        /* Responsive form and table */
        .form-select, .btn, .table-responsive {
            margin-bottom: 15px;
        }

        @media (max-width: 767px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .button-group {
                flex-direction: column;
                gap: 5px;
            }

            .header-container img {
                margin-bottom: 10px;
            }

            .card-body {
                padding: 10px;
            }

            .table-responsive {
                max-height: 300px;
            }

            .invoice-total-card {
                position: absolute;
                bottom: 10px;
                left: 15px;
                width: 100%;
            }
        }

        /* Table styling */
        .table-hover tbody tr:hover {
            background-color: #f0f0f0;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .invoice-total-card {
            background-color: #f9f9f9;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
        }

        .invoice-total-footer {
            display: flex;
            justify-content: space-between;
        }

        .btn-clear {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            width: 100%;
        }

        .btn-clear:hover {
            background-color: #c82333;
        }

        .btn-lg {
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <!-- Header Section -->
        <div class="header-container">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
            <div class="button-group">
                <button id="returnButton">Return</button>
                <button id="backButton">Back</button>
                <button id="closeRegisterButton">Close Register</button>
                <button id="registerDetailsButton">Register Details</button>
                <button id="dateButton">Date</button>
                <button id="addExpenseButton">Add Expense</button>
            </div>
        </div>

        <!-- Content -->
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">ផ្ទាំងលក់ផលិតផល</h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-5 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-5">
                                <div class="input-group">
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createcustomers">
                                        បន្ថែម <i class="fas fa-plus"></i>
                                    </button>
                                    <select class="form-select form-control" id="customerSelect" name="customer_id" required>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="table-responsive mt-2" style="height: 520px; overflow-y: auto;">
                                    <table class="table-hover table-center mb-4 table table-stripped">
                                        <thead class="" style="background-color: #0d6efd;color: white;">
                                            <tr>
                                                <th>ល.រ</th>
                                                <th>ឈ្មោះផលិតផល</th>
                                                <th>ចំនួន</th>
                                                <th>សរុបរង</th>
                                                <th class="text-end">សកម្មភាព</th>
                                            </tr>
                                        </thead>
                                        <tbody id="poscart_table"></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="invoice-total-card">
                                <div class="invoice-total-footer">
                                    <h4 class="grandTotal">ទឹកប្រាក់សរុប <span id="display_grandTotal">0.00 $</span></h4>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <a type="button" class="btn btn-danger btn-block btn-clear btn-lg" id="btn_remove">
                                        <i class="bi bi-x-circle"></i> បោះបង់
                                    </a>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-primary btn-block btn-lg" id="checkoutButton" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                                        <i class="bi bi-credit-card"></i> បង់ប្រាក់
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-12 col-sm-10">
                                    <div class="form-group">
                                        <select id="product-search" class="form-select select2" aria-label="ស្វែងរកតាមឈ្មោះផលិតផល ឬបាកូដ...">
                                            <option value="" disabled selected>ស្វែងរកតាមឈ្មោះផលិតផល ឬបាកូដ...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-2">
                                    <div class="form-group">
                                        <select id="product-quantity" class="form-select form-control" aria-label="Select number of products">
                                            <option value="15" selected>15</option>
                                            <option value="30">30</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="d-flex align-items-center">
                                    <!-- Scroll Left Button -->
                                    <button class="btn btn-light border me-2" id="scroll-left">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>

                                    <div class="col overflow-auto">
                                        <div id="category-buttons" class="d-flex" style="overflow: hidden; white-space: nowrap;">
                                            <!-- Horizontal scrolling for category buttons -->
                                            <button class="btn btn-warning category-button me-2" style="scroll-behavior: smooth;" data-category="All">ទាំងអស់</button>
                                            @foreach ($categories as $category)
                                                <button class="btn btn-primary category-button me-2" data-category="{{ $category->name }}">{{ $category->name }}</button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- Scroll Right Button -->
                                    <button class="btn btn-light border ms-2" id="scroll-right">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card">
                                <hr>
                                <div class="card-body" style="height: 545px; overflow-y: auto;">
                                    @if ($products->isEmpty())
                                        <div class="alert alert-danger text-center fs-3">គ្មានទិន្នន័យ!</div>
                                    @else
                                        <div class="mt-0">
                                            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 g-1" id="show">
                                                @foreach ($products->take(15) as $product)
                                                    @if ($product->quantity > 0)
                                                        @include('partials.product_card', [
                                                            'product' => $product,
                                                        ])
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Scripts -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simple-calendar/jquery.simple-calendar.js') }}"></script>
    <script src="{{ asset('assets/js/calander.js') }}"></script>
    <script src="{{ asset('assets/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>

    <script>
        // Initialize Select2
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
</body>
</html> --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/font/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/img.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
    <style>
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">POS System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="wrapper-content m-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="row" style="height: 100%;">
                        <div class="card mt-2" style="height: calc(90vh - 90px); overflow: hidden; display: flex; flex-direction: column;">
                            <div class="card-body d-flex flex-column" style="flex-grow: 1;">
                                <div class="mb-5">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createcustomers">
                                                    បន្ថែម <i class="fas fa-plus"></i>
                                                </button>
                                                <select class="form-select form-control" id="customerSelect" name="customer_id" required>
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="table-responsive mt-2">
                                                <table class="table-hover table-center mb-4 table table-stripped">
                                                    <thead class="" style="background-color: #0d6efd;color: white;">
                                                        <tr>
                                                            <th>ល.រ</th>
                                                            <th>ឈ្មោះផលិតផល</th>
                                                            <th>ចំនួន</th>
                                                            <th>សរុបរង</th>
                                                            <th class="text-end">សកម្មភាព</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="poscart_table"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-auto">
                                            <div class="invoice-total-card">
                                                <div class="invoice-total-footer">
                                                    <h4 class="grandTotal">ទឹកប្រាក់សរុប <span id="display_grandTotal">0.00 $</span></h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <a type="button" class="btn btn-danger btn-block btn-clear btn-lg" id="btn_remove">
                                                        <i class="bi bi-x-circle"></i> បោះបង់
                                                    </a>
                                                </div>
                                                <div class="col">
                                                    <button type="button" class="btn btn-primary btn-block btn-lg" id="checkoutButton"
                                                        data-bs-toggle="modal" data-bs-target="#checkoutModal">
                                                        <i class="bi bi-credit-card"></i> បង់ប្រាក់
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
                <div class="col-md-8">
                    <div class="row" style="height: 100%;">
                        <div class="card m-2" style="height: calc(90vh - 90px);overflow: hidden;">
                            <div class="row">
                                <div class="col-12 col-sm-10">
                                    <div class="form-group">
                                        <select id="product-search" class="form-select select2"
                                            aria-label="ស្វែងរកតាមឈ្មោះផលិតផល ឬបាកូដ...">
                                            <option value="" disabled selected>ស្វែងរកតាមឈ្មោះផលិតផល ឬបាកូដ...
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-2">
                                    <div class="form-group">
                                        <select id="product-quantity" class="form-select form-control"
                                            aria-label="Select number of products">
                                            <option value="15" selected>15</option>
                                            <option value="30">30</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="d-flex align-items-center">
                                    <!-- Scroll Left Button -->
                                    <button class="btn btn-light border me-2" id="scroll-left">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>

                                    <div class="col overflow-auto">
                                        <div id="category-buttons" class="d-flex"
                                            style="overflow: hidden; white-space: nowrap;">
                                            <!-- Horizontal scrolling for category buttons -->
                                            <button class="btn btn-warning category-button me-2"
                                                style="scroll-behavior: smooth;" data-category="All">ទាំងអស់</button>
                                            @foreach ($categories as $category)
                                                <button class="btn btn-primary category-button me-2"
                                                    data-category="{{ $category->name }}">{{ $category->name }}</button>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- Scroll Right Button -->
                                    <button class="btn btn-light border ms-2" id="scroll-right">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                @foreach ($products->take(15) as $product)
                                    @if ($product->quantity > 0)
                                        <div style="width: 12rem;">
                                            @include('partials.product_card', [
                                                'product' => $product,
                                            ])
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="row">
                <div class="col" style="height: 100%;">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                      <div class="col">
                                        <button type="button" class="btn btn-primary ">Save</button>
                                        <button type="button" class="btn btn-primary ">Print</button>
                                        <button type="button" class="btn btn-primary ">Reset</button>
                                        </div>                  <!-- First row of buttons -->

                            </div>
                        </div>

                    </div>

                </div>
            </div> --}}
        </div>            
    </div>
    
    
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simple-calendar/jquery.simple-calendar.js') }}"></script>
    <script src="{{ asset('assets/js/calander.js') }}"></script>
    <script src="{{ asset('assets/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
</body>


</html>
