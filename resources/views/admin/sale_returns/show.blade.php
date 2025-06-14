@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('sale-returns.index') }}">បញ្ជីបង្វិលការលក់</a></li>
                            <li class="breadcrumb-item active">ព័ត៌មានលម្អិតអំពីការបង្វិលការលក់</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="col-12">
                        <div class="invoice-info d-flex justify-content-between align-items-center">
                            <div class="invoice-head">
                                <h2 class="text-primary">ព័ត៌មានលម្អិតអំពីការបង្វិលការលក់</h2>
                                <p>លេខយោង(លេខវិក័យបត្រ) : {{ $saleReturn->reference }}</p>
                            </div>
                            <span>
                                <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left text-primary"></i> ត្រឡប់ក្រោយ
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="invoice-item invoice-item-bg">
                        <div class="invoice-circle-img">
                            <img src="assets/img/invoice-circle1.png" alt="" class="invoice-circle1">
                            <img src="assets/img/invoice-circle2.png" alt="" class="invoice-circle2">
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-12">
                                <div class="invoice-info invoice-info-one border-1">
                                    <strong class="supplier-text-one text-light">បង្វិលលក់ជូន៖</strong>
                                    <p >កាលបរិច្ឆេទ(ឆ្នាំ-ខែ-ថ្ងៃទី) : {{ $saleReturn->date }}</p>
                                    <p>ឈ្មោះអតិថិជន : {{ $saleReturn->customer->name }}</p>
                                    <p>លេខទូរស័ព្ទ : {{ $saleReturn->customer->phone }}</p>
                                    <p>អាសយដ្ឋាន : {{ $saleReturn->customer->address }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="invoice-info invoice-info-one border-1">
                                    <p>តម្លៃសរុប : {{ number_format($saleReturn->total_amount, 2) }} $</p>
                                    <p>បញ្ចុះតម្លៃ : {{ number_format($saleReturn->discount, 2) }} $</p>
                                    <p>ចំនួនទឹកប្រាក់បានបង្វិល : {{ number_format($saleReturn->returned_amount, 2) }} $</p>
                                    <p>ចំនួនទឹកប្រាក់នៅសល់ : {{ number_format($saleReturn->due_amount, 2) }} $</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="invoice-info invoice-info-one border-0">
                                    <p>វិធីសាស្ត្រទូទាត់ : {{ $saleReturn->payment_method }}</p>
                                    <p>ស្ថានភាពការទូទាត់ : {{ $saleReturn->payment_status }}</p>
                                    <p>ស្ថានភាព : {{ $saleReturn->status }}</p>
                                    <p class="mb-0">ការពិពណ៌នា : {{ $saleReturn->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="invoice-table table table-center mb-0">
                            <thead>
                                <tr>
                                    <th>ល.រ</th>
                                    <th>ផលិតផល</th>
                                    <th>បរិមាណ</th>
                                    <th>តម្លៃឯកតា</th>
                                    <th>បញ្ចុះតម្លៃ($)</th>
                                    <th class="text-end">ទឹកប្រាក់សរុប</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($saleReturn->details as $detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $detail->product->name }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td>{{ number_format($detail->unit_price, 2) }}</td>
                                        <td>{{ number_format($detail->discount, 2) }}</td>
                                        <td class="text-end">{{ number_format($detail->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
