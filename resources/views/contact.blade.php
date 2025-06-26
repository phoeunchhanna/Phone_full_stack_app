@extends('loyouts_user.app')
@section('content')
<style>
    .contact-container {
        background-color: #f8f9fa;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    .card-header {
        background-color: #4e73df;
        color: white;
        font-weight: bold;
        font-size: 1.25rem;
        text-align: center;
        padding: 15px;
        border-radius: 8px 8px 0 0 !important;
    }
    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    .form-control {
        border-radius: 5px;
        border: 1px solid #ddd;
        padding: 12px 15px;
        transition: all 0.3s;
    }
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
        padding: 10px 25px;
        font-weight: 600;
        border-radius: 5px;
        transition: all 0.3s;
    }
    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
        transform: translateY(-2px);
    }
    .form-group label {
        font-weight: 600;
        color: #5a5c69;
    }
    .invalid-feedback {
        color: #e74a3b;
    }
    textarea {
        resize: none;
        min-height: 150px;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="contact-container">
                <div class="card">
                    <div class="card-header">{{ __('ទំនាក់ទំនងយើងខ្ញុំ') }}</div>

                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.submit') }}">
                            @csrf

                            <div class="form-group row mb-4">
                                <label for="full_name" class="col-md-4 col-form-label text-md-right">{{ __('ឈ្មោះពេញ') }} <span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input id="full_name" type="text" class="form-control @error('full_name') is-invalid @enderror" name="full_name" value="{{ old('full_name') }}" required autocomplete="name" autofocus>
                                    </div>
                                    @error('full_name')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('អ៊ីមែល') }} <span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('លេខទូរស័ព្ទ') }} <span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                                    </div>
                                    @error('phone')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label for="message" class="col-md-4 col-form-label text-md-right">{{ __('សារ') }} <span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-comment"></i></span>
                                        </div>
                                        <textarea id="message" class="form-control @error('message') is-invalid @enderror" name="message" required rows="5">{{ old('message') }}</textarea>
                                    </div>
                                    @error('message')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-paper-plane mr-2"></i> {{ __('ផ្ញើសារ') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

@endsection