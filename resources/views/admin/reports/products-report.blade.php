@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">របាយការណ៍ផលិតផល</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                                <li class="breadcrumb-item active">របាយការណ៍ផលិតផល</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <form submit="generateReport">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label>ជ្រើសរើស ថ្ងៃខែឆ្នាំ <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control date_range_picker" name="date_range"
                                                value="{{ old('date_range', now()->subDays(7)->format('Y-m-d') . ' to ' . now()->format('Y-m-d')) }}">
                                            @error('date_range')
                                                <span class="text-danger mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label>ប្រភេទផលិតផល</label>
                                            <select name="category_id" class="form-control form-select">
                                                <option value="">ទាំងអស់</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label>ម៉ាកយីហោ</label>
                                            <select name="brand_id" class="form-control form-select">
                                                <option value="">ទាំងអស់</option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <span target="generateReport" role="status" aria-hidden="true"></span>
                                            <i class="bi bi-funnel"></i> ច្រោះទិន្នន័យ
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="printReport()"><i
                                                class="bi bi-eye"></i> ពិនិត្យ</button>
                                        <a href="{{ route('products-report.export', ['export' => 'excel']) }}"
                                            class="btn btn-success"><i class="bi bi-download"></i>ទាញយកជាExcel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <table class="datatable table table-bordered table-striped text-center mb-0">
                                <thead>
                                    <tr>
                                        <th>ល.រ</th>
                                        <th>ឈ្មោះផលិតផល</th>
                                        <th>បាកូដ</th>
                                        <th>ថ្លៃដើម</th>
                                        <th>តម្លៃលក់ចេញ</th>
                                        <th>បរិមាណក្នងស្តុក</th>
                                        <th>ប្រភេទផលិតផល</th>
                                        <th>ម៉ាកយីហោ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->code }}</td>
                                            <td class="text-end">{{ $product->cost_price }} $</td>
                                            <td class="text-end">{{ $product->selling_price }} $</td>
                                            <td class="text-end">{{ $product->quantity }}</td>
                                            <td>{{ $product->category ? $product->category->name : 'N/A' }}</td>
                                            <td>{{ $product->brand ? $product->brand->name : 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">
                                                <h4 class="text-danger">គ្មានទិន្នន័យ</h4>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function printReport() {
            const printContents = document.querySelector('.datatable').outerHTML; // Select the content to print
            const originalContents = document.body.innerHTML; // Save the current page content

            // Prepare the page for printing
            document.body.innerHTML = `
            <html>
            <head>
                <title>របាយការណ៍ផលិតផល</title>
                <style>
                    body { font-family: 'battambang', sans-serif; text-align: center; }
                    table { border-collapse: collapse; width: 100%; margin: 0 auto; }
                    th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                    th { background-color: #f4f4f4; }
                    h4 { margin: 20px 0; }
                </style>
            </head>
            <body>
                <h4>របាយការណ៍ផលិតផល</h4>
                ${printContents}
            </body>
            </html>
        `;

            // // Trigger print
            // window.print();

            // // Restore original content after printing
            // document.body.innerHTML = originalContents;
            // window.location.reload(); // Reload the page to restore scripts and bindings
        }
    </script>
    <script>
        function printReport() {
            const dateRange = document.querySelector('input[name="date_range"]').value;

            // Extract start and end date from the date range
            const dates = dateRange.split(' to ');
            const startDate = dates[0];
            const endDate = dates[1];

            const printContents = document.querySelector('.datatable').outerHTML; // Select the content to print
            const originalContents = document.body.innerHTML; // Save the current page content

            // Prepare the page for printing with dynamic title
            document.body.innerHTML = `
    <html>
    <head>
        <title>របាយការណ៍ផលិតផល</title>
        <style>
            body { font-family: 'battambang', sans-serif; text-align: center; }
            table { border-collapse: collapse; width: 100%; margin: 0 auto; }
            th, td { border: 1px solid #000; padding: 8px; text-align: center; }
            th { background-color: #f4f4f4; }
            h4 { margin: 20px 0; }
        </style>
    </head>
    <body>
        <h4>របាយការណ៍ផលិតផល: ${startDate} - ${endDate}</h4>
        ${printContents}
    </body>
    </html>
    `;
        }
    </script>
@endsection
