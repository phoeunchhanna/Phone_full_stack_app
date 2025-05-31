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
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                                <li class="breadcrumb-item active">របាយការណ៍ការស្តុក</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="text-primary font-weight-600">
                                របាយការណ៍ការស្តុក
                            </h3>

                            <!-- Filter Form -->
                            <form method="GET" action="{{ route('stock.report.index') }}">
                                <div class="row mb-3">
                                    <!-- Filter by Product -->
                                    <div class="col-md-3">
                                        <label for="product_id">ជ្រើសរើសផលិតផល</label>
                                        <select name="product_id" id="product_id" class="form-control form-select">
                                            <option value="">ផលិតផលទាំងអស់</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Filter by Category -->
                                    <div class="col-md-3">
                                        <label for="category_id">ប្រភេទផលិតផល</label>
                                        <select name="category_id" id="category_id" class="form-control form-select">
                                            <option value="">ប្រភេទទាំងអស់</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Filter by Brand -->
                                    <div class="col-md-3">
                                        <label for="brand_id">ម៉ាកផលិតផល</label>
                                        <select name="brand_id" id="brand_id" class="form-control form-select">
                                            <option value="">ម៉ាកទាំងអស់</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}"
                                                    {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Filter Buttons -->
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-shuffle"></i>
                                            ច្រោះទិន្នន័យ</button>
                                        </a>
                                    </div>
                                </div>
                            </form>

                            <!-- Stock Report Table -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        @isset($stocks)
                                            <table class="datatable table table-hover table-center mb-0 table-striped">
                                                <thead class="bg-primary text-white">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>ឈ្មោះផលិតផល</th>
                                                        <th>កូដ</th>
                                                        <th>ម៉ាកយីហោ</th>
                                                        <th>ប្រភេទ</th>
                                                        <th>កាលបរិច្ឆេទ</th>
                                                        <th>ស្តុកចាស់</th>
                                                        <th>បរិមាណបន្ថែម</th>
                                                        <th>ស្តុកបច្ចុប្បន្ន</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($stocks as $key => $stock)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $stock->product->name ?? 'N/A' }}</td>
                                                            <td class="text-primary">{{ $stock->product->code ?? 'N/A' }}</td>
                                                            <td>{{ $stock->product->brand->name ?? 'N/A' }}</td>
                                                            <td>{{ $stock->product->category->name ?? 'N/A' }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($stock->date)->translatedFormat('d/m/Y') }}</td>
                                                            <td>{{ $stock->last_stock }}</td>
                                                            <td>{{ $stock->purchase }}</td>
                                                            <td>{{ $stock->current }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            {{ $stocks->links() }}
                                        @endisset
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
