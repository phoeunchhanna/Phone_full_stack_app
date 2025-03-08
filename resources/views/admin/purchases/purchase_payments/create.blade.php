@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">បង្កើតការលក់</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">ការលក់</a></li>
                            <li class="breadcrumb-item active">បង្កើតការលក់</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Product and Cart Section -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            {{-- <div class="col-12">
                                <div class="col-12">
                                    <h5 class="form-title student-info">ព័ត៌មានលម្អិតអំពីការទិញ
                                        <span>
                                            <a href="{{ route('purchases.index') }}" class="btn btn-outline-primary">
                                                <i class="fas fa-arrow-left text-primary"></i> ត្រឡប់ក្រោយ
                                            </a>
                                        </span>
                                    </h5>
                                </div>
                            </div> --}}
                            <form action="{{ route('purchase_payments.store') }}" method="POST">
                                @csrf
                                <div class="form-group d-flex align-items-center justify-content-between">
                                    <h2 class="text-primary font-weight-600 mb-0">បង្កើតម៉ាកយីហោ</h2>
                                    <span>
                                        <!-- Back Button -->
                                        <a href="{{route('purchase_payments.index')}}" class="btn btn-outline-primary">
                                            <i class="fas fa-arrow-left"></i> បង្កើតម៉ាកយីហោ
                                        </a>
                                    </span>
                                </div>
                                
                                <div class="form-group">
                                    <label for="purchase_id">purchase Reference</label>
                                    <select name="purchase_id" id="purchase_id" class="form-control" required>
                                        <option value="">Select purchase</option>
                                        @foreach ($purchases as $purchase)
                                            <option value="{{ $purchase->id }}">{{ $purchase->reference }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" name="amount" id="amount" class="form-control" step="0.01"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" id="date" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="reference">Reference</label>
                                    <input type="text" name="reference" id="reference" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="payment_method">Payment Method</label>
                                    <input type="text" name="payment_method" id="payment_method" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="note">Note</label>
                                    <textarea name="note" id="note" class="form-control"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
