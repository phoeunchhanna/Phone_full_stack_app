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
                            <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">ការលក់</a></li>
                            <li class="breadcrumb-item active">បង្កើតការលក់</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- ផ្នែកផលិតផល និង កន្រ្តក -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="col-12">
                                    <h5 class="form-title student-info">បង្កើតការទូទាត់ការលក់

                                        <span>
                                            <a href="{{ route('sales.index') }}" class="btn btn-outline-primary">
                                                <i class="fas fa-arrow-left text-primary"></i> ត្រឡប់ក្រោយ
                                            </a>
                                        </span>
                                    </h5>
                                </div>
                            </div>
                            <form action="{{ route('sale_payments.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="sale_id">យោងការលក់</label>
                                    <input type="text" class="form-control" name="reference" required readonly value="INV/{{ $sale->reference }}">
                                </div>
                                <div class="form-group">
                                    <label for="due_amount">ចំនួនទឹកប្រាក់នៅខ្វះ</label>
                                    <input type="number" name="due_amount" id="due_amount" class="form-control" step="0.01" value="{{ ($sale->due_amount) }}" required readonly >
                                </div>
                                <div class="form-group">
                                    <label for="amount">ចំនួនប្រាក់ <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ old('amount') }}" required >
                                </div>
                                <div class="form-group">
                                    <label for="date">ថ្ងៃខែឆ្នាំ(ខែ/ថ្ងៃទី/ឆ្នាំ)</label>
                                    <input type="date" name="date" id="date" value="{{ now()->format('Y-m-d') }}" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <div class="form-group ">
                                        <label for="payment_method">វិធីសាស្ត្រទូទាត់</label>
                                        <select name="payment_method" class="form-control form-select"
                                            required>
                                            <option value="សាច់ប្រាក់">សាច់ប្រាក់</option>
                                            <option value="អេស៊ីលីដា">អេស៊ីលីដា</option>
                                            <option value="ABA">ABA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="note">កត់សម្គាល់</label>
                                    <textarea name="note" id="note" class="form-control" >{{ old('note') }}</textarea>
                                </div>
                                <input type="hidden" value="{{ $sale->id }}" name="sale_id">
                                <button type="submit" class="btn btn-success">រក្សាទុក</button>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
