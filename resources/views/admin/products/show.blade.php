@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                                <li class="breadcrumb-item active">ព័ត៌មានលម្អិតផលិតផល</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="col-12">
                        <div class="invoice-info d-flex justify-content-between align-items-center">
                            <div class="invoice-head">
                                <h2 class="text-primary">ព័ត៌មានផលិតផល</h2>
                                <p>លេខកូដ: {{ $product->code }}</p>
                            </div>
                            <span>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left text-primary"></i> ត្រឡប់ក្រោយ
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Product Image -->
                        <div class="col-md-4 text-center">
                            <img src="{{ asset('storage/' . $stock->product->image) }}" alt="{{ $product->name }}" 
                                class="img-fluid img-thumbnail rounded" 
                                style="max-width: 200px; height: auto;">
                        </div>
            
                        <!-- Product Details -->
                        <div class="col-md-8">
                            <h3 class="text-primary">{{ $product->name }}</h3>
                            <p><strong>ប្រភេទផលិតផល:</strong> {{ $product->category->name }}</p>
                            <p><strong>ម៉ាកយីហោ:</strong> {{ $product->brand->name }}</p>
                            <p><strong>លក្ខណៈ:</strong> {{ $product->condition }}</p>
                            <p class="mt-3">
                                <strong>ការពិពណ៌នា:</strong> <br>
                                <span class="text-muted">{{ $product->description }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="invoice-table table table-center mb-0">
                            <thead>
                                <tr>
                                    <th>ថ្លៃដើម</th>
                                    <th>តម្លៃលក់ចេញ</th>
                                    <th>បរិមាណក្នុងស្តុក</th>
                                    <th>ការជូនដំណឹងស្តុក</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $product->cost_price }}$</td>
                                    <td>{{ $product->selling_price }}$</td>
                                    <td>{{ $stock->current }}</td>
                                    <td>{{ $product->stock_alert }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
    @endsection
