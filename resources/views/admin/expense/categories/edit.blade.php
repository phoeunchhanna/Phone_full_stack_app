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
                            <h3 class="page-title">កែប្រែប្រភេទការចំណាយ</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('expense_categories.index') }}">ប្រភេទការចំណាយ</a></li>
                                <li class="breadcrumb-item active">កែប្រែប្រភេទការចំណាយ</li>
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
                                <div class="form-group">
                                    <h5 class="form-title student-info">កែប្រែប្រភេទការចំណាយ
                                        <span>
                                            <!-- Back Button -->
                                            <a href="{{route('expense_categories.index')}}" class="btn btn-outline-primary">
                                                <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                                            </a>
                                        </span>
                                    </h5>
                                </div>
                                <form action="{{ route('expense_categories.update', $expense_category->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="name">ឈ្មោះប្រភេទការចំណាយ<span
                                                    class="login-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name', $expense_category->name) }}"
                                                placeholder="បញ្ចូលឈ្មោះម៉ាកយីហោ" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description">ពណ៌នា</label>
                                            <textarea name="description" class="form-control">{{ old('description', $expense_category->description) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="student-submit mt-3">
                                            <button type="submit" class="btn btn-primary w-100">រក្សាទុក</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
