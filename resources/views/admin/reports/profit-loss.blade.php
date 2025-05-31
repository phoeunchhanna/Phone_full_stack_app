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
                                <li class="breadcrumb-item active">របាយការណ៍ប្រាក់ចំណេញ និងខាត</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="text-primary font-weight-600">របាយការណ៍ប្រាក់ចំណេញ និងខាត</h3>
                            <form action="{{ route('reports.profit-loss') }}" method="GET">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="date_range" class="form-label">ជ្រើសរើស ថ្ងៃខែឆ្នាំ <span class="text-danger">*</span></label>
                                            <input type="text" name="date_range" id="date_range" 
                                                class="form-control date_range_picker" 
                                                value="{{ request('date_range', now()->subDays(7)->format('d/m/Y') . ' to ' . now()->format('d/m/Y')) }}" readonly>
                                            @error('date_range')
                                                <span class="text-danger mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-shuffle"></i> ពិនិត្យមើល
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-white shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-info p-3 rounded d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cash-stack fs-2 text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1">ចំណូលសរុប</h5>
                                    <h3>${{ number_format($totalRevenue, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-info p-3 rounded d-flex align-items-center justify-content-center">
                                    <i class="bi bi-arrow-return-left fs-2 text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1">បង្វែចូលទំនិញ</h5>
                                    <h3>${{ number_format($totalReturns, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-info p-3 rounded d-flex align-items-center justify-content-center">
                                    <i class="bi bi-coin fs-2 text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1">ចំណូលសុទ្ធ</h5>
                                    <h3>${{ number_format($netRevenue, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="card text-white shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-info p-3 rounded d-flex align-items-center justify-content-center">
                                    <i class="bi bi-basket fs-2 text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1">ការទិញសរុប</h5>
                                    <h3>${{ number_format($totalPurchases, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-info p-3 rounded d-flex align-items-center justify-content-center">
                                    <i class="bi bi-credit-card fs-2 text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1">ចំណាយសរុប</h5>
                                <h3>${{ number_format($totalExpenses, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="bg-info p-3 rounded d-flex align-items-center justify-content-center">
                                    <i class="bi bi-receipt fs-2 text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1">ចំណេញ / ខាត</h5>
                                    <h3 class="{{ $profitLoss >= 0 ? 'text-primary' : 'text-danger' }}">
                                        {{ $profitLoss >= 0 ? 'ចំណេញ' : 'ខាត' }} ${{ number_format(abs($profitLoss), 2) }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
