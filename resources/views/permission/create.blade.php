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
                            <h3 class="page-title">បង្កើតការអនុញ្ញាត</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('brands.index') }}">ការអនុញ្ញាត</a></li>
                                <li class="breadcrumb-item active">បង្កើតការអនុញ្ញាត</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">
                            <form action="{{route('permissions.store')}}" method="POST" enctype="multipart/form-data" id="formcreate">
                                @csrf
                                <div class="Row">
                                    <div class="form-group d-flex align-items-center justify-content-between">
                                        <h3 class="text-primary font-weight-600 mb-0">បង្កើតការអនុញ្ញាត</h3>
                                        <span>
                                            <!-- Back Button -->
                                            <a href="{{route('permissions.index')}}" class="btn btn-outline-primary">
                                                <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                                            </a>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">ឈ្មោះការអនុញ្ញាត<span class="login-danger">*</label>
                                            <input disabled type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="បញ្ចូលឈ្មោះការអនុញ្ញាត" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $error }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-3 d-flex justify-content-end">
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg" id="saveButton">រក្សាទុក<i
                                                class="bi bi-check-lg"></i></button>
                                        <button type="button" class="btn btn-primary btn-lg" id="savingButton"
                                            style="display: none;" disabled>
                                            កំពុងរក្សាទុក...
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
@endsection
