@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">បញ្ជីការលក់</a></li>
                            <li class="breadcrumb-item active">ព័ត៌មានលម្អិតអំពីការលក់</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="col-12">
                        <div class="invoice-info d-flex justify-content-between align-items-center">
                            <div class="invoice-head">
                                <h2 class="text-primary">ព័ត៌មានលម្អិតអំពីការលក់</h2>
                                <p>លេខយោង(លេខវិក័យបត្រ) : {{ $sale->reference }}</p>
                            </div>
                            <span>
                                <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left text-primary"></i> ត្រឡប់ក្រោយ
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="invoice-item invoice-item-bg">
                        <div class="row">
                            <div class="col-lg-4 col-md-12">
                                <div class="invoice-info invoice-info-one border-1">
                                    <strong class="customer-text-one">លក់ជូន៖</strong>
                                    <p >កាលបរិច្ឆេទ(ឆ្នាំ-ខែ-ថ្ងៃទី) : {{ $sale->date }}</p>
                                    <p>ឈ្មោះអតិថិជន : {{ $sale->customer->name }}</p>
                                    <p>លេខទូរស័ព្ទ : {{ $sale->customer->phone }}</p>
                                    <p>អាសយដ្ឋាន : {{ $sale->customer->address }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="invoice-info invoice-info-one border-1">
                                    <p>អ្នកគិតលុយ : {{ $sale->user->name}}</p>
                                    <p>តម្លៃសរុប : {{ number_format($sale->total_amount, 2) }} $</p>
                                    <p>បញ្ចុះតម្លៃ : {{ number_format($sale->discount, 2) }} $</p>
                                    <p>ចំនួនទឹកប្រាក់បានបង់ : {{ number_format($sale->paid_amount, 2) }} $</p>
                                    <p>ចំនួនទឹកប្រាក់នៅសល់ : {{ number_format($sale->due_amount, 2) }} $</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="invoice-info invoice-info-one border-0">

                                    <p>ស្ថានភាព : {{ $sale->status }}</p>
                                    <p class="mb-0">ការពិពណ៌នា : {{ $sale->description }}</p>
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
                                @foreach ($sale->saleDetails as $detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}
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
