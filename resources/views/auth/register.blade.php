{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}

@extends('layouts.app')
@section('content')
    <div class="login-right">
        <div class="login-right-wrap">
            <h1>Sign Up</h1>
            <p class="account-subtitle">Enter details to create your account</p>
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Full Name <span class="login-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name">
                    <span class="profile-views"><i class="fas fa-user-circle"></i></span>
                </div> <div class="form-group">
                    <label>Useranme <span class="login-danger">*</span></label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username">
                    <span class="profile-views"><i class="fas fa-user-circle"></i></span>
                </div>
                <div class="form-group">
                    <label>Email <span class="login-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email">
                    <span class="profile-views"><i class="fas fa-envelope"></i></span>
                </div>
                <input type="hidden" name="image" value="avatar-01.jpg">
                <div class="form-group">
                    <label>Password <span class="login-danger">*</span></label>
                    <input type="password" class="form-control pass-input  @error('password') is-invalid @enderror" name="password">
                    <span class="profile-views feather-eye toggle-password"></span>
                </div>
                <div class="form-group">
                    <label>Confirm password <span class="login-danger">*</span></label>
                    <input type="password" class="form-control pass-confirm @error('password_confirmation') is-invalid @enderror" name="password_confirmation">
                    <span class="profile-views feather-eye reg-toggle-password"></span>
                </div>
                <div class=" dont-have">Already Registered? <a href="{{ route('login') }}">Login</a></div>
                <div class="form-group mb-0">
                    <button class="btn btn-primary btn-block" type="submit">Register</button>
                </div>
            </form>
        </div>
    </div>
@endsection
