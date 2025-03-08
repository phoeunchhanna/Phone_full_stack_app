@extends('layouts.master')
@section('content')
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">កែរប្រែការទូទាត់ការបញ្ជាទិញ</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('purchase_payments.index') }}">បញ្ជីការទូទាត់ការបញ្ជាទិញ</a></li>
                            <li class="breadcrumb-item active">កែប្រែការទូទាត់ការបញ្ជាទិញ</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Product and Details Section -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="col-12">
                                    <h5 class="form-title student-info">កែប្រែការទូទាត់ការបញ្ជាទិញ
                                        <span>
                                            <a href="{{ route('purchases.index') }}" class="btn btn-outline-primary">
                                                <i class="fas fa-arrow-left text-primary"></i> ត្រឡប់ក្រោយ
                                            </a>
                                        </span>
                                    </h5>
                                </div>
                            </div>
                            <form action="{{ route('purchase_payments.update', $purchasePayment->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="purchase_id">លេខយោង</label>
                                            <input type="text" class="form-control" name="reference" required readonly
                                                value="INV/{{ $purchase->reference }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="due_amount">ចំនួនទឹកប្រាក់នៅខ្វះ</label>
                                            <input type="number" name="due_amount" id="due_amount" class="form-control"
                                                step="0.01" value="{{ $purchase->due_amount }}" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="date">កាលបរិច្ឆេទ</label>
                                            <input type="date" name="date" id="date" class="form-control"
                                                value="{{ old('date', $purchasePayment->date) }}" required>
                                            @error('date')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="amount">ចំនួនទឹកប្រាក់<span class="text-danger">*</span></label>
                                            <input type="number" name="amount" id="amount" class="form-control"
                                                step="0.01" value="{{ old('amount', $purchasePayment->amount) }}" required>
                                            @error('amount')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="payment_method">វិធីសាស្ត្របង់ប្រាក់</label>
                                            <select name="payment_method" class="form-control form-select" required>
                                                <option value="សាច់ប្រាក់"
                                                    {{ old('payment_method', $purchasePayment->payment_method) == 'សាច់ប្រាក់' ? 'selected' : '' }}>
                                                    សាច់ប្រាក់
                                                </option>
                                                <option value="អេស៊ីលីដា"
                                                    {{ old('payment_method', $purchasePayment->payment_method) == 'អេស៊ីលីដា' ? 'selected' : '' }}>
                                                    អេស៊ីលីដា
                                                </option>
                                                <option value="ABA"
                                                    {{ old('payment_method', $purchasePayment->payment_method) == 'ABA' ? 'selected' : '' }}>
                                                    ABA
                                                </option>
                                            </select>
                                            @error('payment_method')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label for="note">ចំណាំ</label>
                                            <textarea name="note" id="note" class="form-control">{{ old('note', $purchasePayment->note) }}</textarea>
                                            @error('note')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
