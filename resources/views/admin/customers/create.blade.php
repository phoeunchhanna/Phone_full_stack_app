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
                                <li class="breadcrumb-item"><a href="{{ route('brands.index') }}">បង្កើតអតិថិជន</a></li>
                                <li class="breadcrumb-item active">បង្កើតអតិថិជន</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">
                            <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data" id="formcreate">
                                @csrf
                                <div class="Row">
                                    <div class="form-group d-flex align-items-center justify-content-between">
                                        <h3 class="text-primary font-weight-600 mb-0">បង្កើតអតិថិជន</h3>
                                        <span>
                                            <!-- Back Button -->
                                            <a href="{{route('customers.index')}}" class="btn btn-outline-primary">
                                                <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                                            </a>
                                        </span>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="name">ឈ្មោះអតិថិជន<span class="login-danger">*</label>
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
                                        <label for="phone">លេខទូរស័ព្ទ<span class="login-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone') }}" 
                                               placeholder="បញ្ចូលលេខទូរស័ព្ទ" 
                                               required 
                                               pattern="^[0-9]{9,10}$" 
                                               inputmode="numeric"
                                               maxlength="10"
                                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
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
                                <div class="mt-3 d-flex justify-content-end">
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg" id="saveButton">រក្សាទុក<i
                                                class="bi bi-check-lg"></i></button>
                                        <button class="btn btn-primary btn-lg" type="button" disabled=""
                                            id="savingButton" style="display: none;">
                                            <span class="spinner-border spinner-border-sm me-1" role="status"
                                                aria-hidden="true"></span>
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
    <script>
        document.getElementById('formcreate').addEventListener('submit', function(event) {
            event.preventDefault();
            document.getElementById('saveButton').style.display = 'none';
            document.getElementById('savingButton').style.display = 'inline-block';
            setTimeout(() => {
                document.getElementById('formcreate').submit();
            }, 500);
        });
    </script>
@endsection
