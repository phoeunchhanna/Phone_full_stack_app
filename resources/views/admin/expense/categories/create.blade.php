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
                            <h3 class="page-title">បង្កើតប្រភេទការចំណាយ</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('expense_categories.index') }}">ប្រភេទការចំណាយ</a></li>
                                <li class="breadcrumb-item active">បង្កើតប្រភេទការចំណាយ</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">
                            <form action="{{ route('expense_categories.store') }}" method="POST" enctype="multipart/form-data" id="formcreate">
                                @csrf
                                <div class="Row">
                                    <div class="form-group">
                                        <h5 class="form-title student-info">បង្កើតប្រភេទការចំណាយ
                                             <span>
                                                <!-- Back Button -->
                                                <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                                                </a>
                                            </span>
                                        </h5>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">ឈ្មោះប្រភេទការចំណាយ<span class="login-danger">*</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="បញ្ចូលឈ្មោះប្រភេទការចំណាយ" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="description">ពណ៌នា</label>
                                        <textarea name="description" class="form-control" value="គ្មាន"></textarea>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary" id="createpurchase">
                                        បន្ថែម<i class="bi bi-check"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
