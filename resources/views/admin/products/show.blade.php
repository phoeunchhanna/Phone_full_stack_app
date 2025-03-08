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
                    <div class="invoice-item invoice-item-bg">

                        <div class="row">
                            <div class="col-lg-4 col-md-12">
                                <div class="invoice-info invoice-info-one border-1">
                                    <strong class="customer-text-one">លក់ជូន៖</strong>
                                    <p>ឈ្មោះផលិតផល: {{ $product->name }}</p>
                                    <p>ប្រភេទផលិតផល: {{ $product->category->name }}</p>
                                    <p>ប្រភេទម៉ាកយីហោ: {{ $product->brand->name }}</p>
                                    <p>លក្ខណៈ: {{ $product->condition }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="invoice-info invoice-info-one border-1">
                                    <p>ស្ថានភាព: {{ $product->status }}</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="invoice-info invoice-info-one border-0">
                                    <fieldset class="border p-3 rounded-2">
                                        <div class="form-group text-center">
                                            <label for="image" style="color:white">រូបភាពផលិតផល</label>
                                            <img style="width: 145px; height: 145px;"
                                                class="d-block mx-auto img-thumbnail img-fluid mb-2"
                                                src="{{ asset($product->image) }}" alt="រូបភាពផលិតផល {{ $product->name }}">
                                        </div>
                                        @if ($product->description)
                                            <div class="col-12 col-sm-12">
                                                <div class="mb-3">
                                                    <label for="description text-light" style="color:white" class="form-label">ការពិពណ៌នា</label>
                                                    <p class="form-control-static" >{{ $product->description }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </fieldset>
                                </div>
                            </div>
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
