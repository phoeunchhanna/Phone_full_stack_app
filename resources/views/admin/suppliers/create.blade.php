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
                                <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">អ្នកផ្គត់ផ្គង់</a></li>
                                <li class="breadcrumb-item active">បង្កើតអ្នកផ្គត់ផ្គង់</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">
                            <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data" id="formcreate">
                                @csrf
                                <div class="form-group d-flex align-items-center justify-content-between">
                                    <h2 class="text-primary font-weight-600 mb-0">បង្កើតអ្នកផ្គត់ផ្គង់</h2>
                                    <span>
                                        <!-- Back Button -->
                                        <a href="{{route('suppliers.index')}}" class="btn btn-outline-primary">
                                            <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                                        </a>
                                    </span>
                                </div>
                                <div class="Row">
                                    <div class="form-group">
                                        <label for="name">ឈ្មោះអ្នកផ្គត់ផ្គង់<span class="login-danger">*</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                            id="name" name="name" value="" 
                                            placeholder="បញ្ចូលឈ្មោះអតិថិជន" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">លេខទូរស័ព្ទ<span class="login-danger">*</label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                            id="phone" name="phone" value="0" 
                                            placeholder="បញ្ចូលលេខទូរស័ព្ទ" required>
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="address">អាសយដ្ឋាន<span class="login-danger">*</label>
                                            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                            id="address" name="address" value="" 
                                            placeholder="បញ្ចូលអាសយដ្ឋាន" required>
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                </div>
                                {{-- Submit and Cancel Buttons --}}
                                <div class="mt-3 d-flex justify-content-end">
                                    <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-lg btn-primary ms-2"
                                        id="btnsave">រក្សារទុក <i class="bi bi-check-lg"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
