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
                                <li class="breadcrumb-item active">ព័ត៌មានអ្នកប្រើប្រាស់</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title student-info">ព័ត៌មានអ្នកប្រើប្រាស់
                                        <span>
                                            <!-- Back Button -->
                                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                                            </a>
                                        </span>
                                    </h5>
                                </div>

                                <!-- Name Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">ឈ្មោះ</label>
                                    <p>{{ $user->name }}</p>
                                </div>

                                <!-- Email Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">អ៊ីមែល</label>
                                    <p>{{ $user->email }}</p>
                                </div>

                                <!-- Avatar Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="avatar" class="form-label">រូបភាពប្រវត្តិរូប</label>
                                    <div class="text-center">
                                        <img id="product-image-preview" src="{{ asset('storage/' . ($user->avatar ?? 'avatar-01.jpg')) }}"
                                            class="img-thumbnail rounded-circle mb-2"
                                            style="width: 150px; height: 150px;">
                                    </div>
                                </div>

                                <!-- User Type Field -->
                                <div class="col-md-6 mb-3">
                                    <label for="user_type" class="form-label">ប្រភេទអ្នកប្រើប្រាស់</label>
                                    <p>{{ ucfirst($user->user_type) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
