@extends('layouts.app')
@section('content')
{{-- Flash Messages --}}
{!! Toastr::message() !!}

<div class="login-right">
    <div class="login-right-wrap">
        <h1 class="text-primary">សូមស្វាគមន៍!</h1>
        {{-- <p class="account-subtitle">ត្រូវការគណនីមែនទេ? <a href="{{ route('register') }}">ចុះឈ្មោះ</a></p> --}}
        <h2>ចូលគណនី</h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <label for="email">អ៊ីមែល <span class="login-danger">*</span></label>
            <div class="form-group">
                <input 
                    type="email" 
                    id="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    name="email" 
                    value="{{ old('email') }}" placeholder="បញ្ចូលអ៊ីមែល"
                    required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <label for="password">ពាក្យសម្ងាត់ <span class="login-danger">*</span></label>
            <div class="form-group">
                <input 
                    type="password" 
                    id="password" 
                    class="form-control pass-input @error('password') is-invalid @enderror" placeholder="លេខសម្ងាត់"
                    name="password" 
                    required>
                <span class="profile-views feather-eye toggle-password"></span>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="forgotpass d-flex justify-content-between align-items-center">
                <div class="remember-me">
                    <label class="custom_check mr-2 mb-0 d-inline-flex remember-me"> ចងចាំ
                        <input type="checkbox" name="remember">
                        <span class="checkmark"></span>
                    </label>
                </div>
                {{-- <a href="{{ route('password.request') }}" class="text-primary">ភ្លេចលេខសម្ងាត់?</a> --}}
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit">ចូល</button>
            </div>
        </form>
    </div>
</div>
@endsection
