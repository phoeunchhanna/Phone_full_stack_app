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
                                <li class="breadcrumb-item active">បង្កើតម៉ាកយីហោ</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">
                            <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data"
                                id="formcreate">
                                @csrf
                                <div class="Row">
                                    <div class="form-group d-flex align-items-center justify-content-between">
                                        <h3 class="text-primary font-weight-600 mb-0">បង្កើតម៉ាកយីហោ</h3>
                                        <span>
                                            <!-- Back Button -->
                                            <a href="{{ route('brands.index') }}" class="btn btn-outline-primary">
                                                <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                                            </a>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">ឈ្មោះម៉ាកយីហោ<span class="login-danger">*</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="បញ្ចូលឈ្មោះម៉ាកយីហោ" required 
                                            oninput="check_nuber(this)">
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

        // Note allow number 
        function check_nuber(input) {
            const khmerDigitsOnly = /^[\u17E0-\u17E9]+$/; // only Khmer digits
            const allowedPattern = /^[\u1780-\u17FFa-zA-Z0-9\s]+$/; // valid characters

            const containsLetter = /[\u1780-\u17A2a-zA-Z]/.test(input.value); // at least one Khmer or English letter

            if (
                khmerDigitsOnly.test(input.value) || // only Khmer digits
                !allowedPattern.test(input.value) || // contains invalid characters
                !containsLetter // no letters at all
            ) {
                input.setCustomValidity(
                    "សូមបញ្ចូលអក្សរខ្មែរឬអង់គ្លេស។ អាចមានលេខអង់គ្លេស ឬខ្មែរបាន ប៉ុន្តែហាមប្រើលេខតែឯង។");
            } else {
                input.setCustomValidity("");
            }
        }
    </script>
@endsection
