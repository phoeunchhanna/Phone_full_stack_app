@extends('layouts.master')

@section('content')
    {{-- Message --}}
    {!! Toastr::message() !!}

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                                <li class="breadcrumb-item active">កែប្រែអ្នកប្រើប្រាស់</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">
                            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data"
                                id="formupdate">
                                @csrf
                                @method('PUT') <!-- PUT method for update -->
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
                                        <label for="name" class="form-label">ឈ្មោះ<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $user->name) }}"
                                            placeholder="បញ្ចូលឈ្មោះរបស់អ្នក" required>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Email Field -->
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">អ៊ីមែល<span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $user->email) }}"
                                            placeholder="បញ្ចូលអ៊ីមែល" required>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Password Field -->
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">លេខសម្ងាត់</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="លេខសម្ងាត់">
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3" style="display: none">
                                        <label for="user_type" class="form-label">ប្រភេទអ្នកប្រើប្រាស់</label>
                                        <input type="user_type" class="form-control" id="user_type" name="user_type"
                                            value="{{ $user->user_type }}" readonly>
                                    </div>
                                    <!-- Confirm Password Field -->
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">បញ្ជាក់លេខសម្ងាត់</label>
                                        <input type="password"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            id="password_confirmation" name="password_confirmation"
                                            placeholder="បញ្ជាក់លេខសម្ងាត់">
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Avatar Field -->
                                    <div class="col-md-6 mb-3">
                                        <label for="avatar" class="form-label">រូបភាពប្រវត្តិរូប</label>
                                        <div class="text-center">
                                            <img id="product-image-preview" src="{{ asset('storage/' . $user->avatar) }}"
                                                class="img-thumbnail rounded-circle mb-2"
                                                style="width: 150px; height: 150px;">
                                            <input id="avatar" type="file" name="avatar" accept="image/*"
                                                class="form-control d-none" onchange="showPreview(event)">
                                            <button type="button" class="btn btn-outline-primary"
                                                onclick="document.getElementById('avatar').click()">
                                                បញ្ចូលរូបភាព <i class="bi bi-upload"></i>
                                            </button>
                                        </div>
                                        @error('avatar')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Role Field -->
                                    <div class="col-md-6 mb-3">
                                        <label for="role" class="form-label">ជ្រើសរើសតួរនាទីរបស់អ្នកប្រើប្រាស់</label>
                                        <select name="role" id="role" class="form-select form-select-lg mb-3 fs-6" required>
                                            <option value="">----ជ្រើសរើសតួរនាទី----</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mt-3 d-flex justify-content-end">
                                        <div class="form-group mt-4">
                                            <button type="submit" class="btn btn-primary btn-lg"
                                                id="saveButton">រក្សាទុក<i class="bi bi-check-lg"></i></button>
                                            <button class="btn btn-primary btn-lg" type="button" disabled=""
                                                id="savingButton" style="display: none;">
                                                <span class="spinner-border spinner-border-sm me-1" role="status"
                                                    aria-hidden="true"></span>
                                                កំពុងរក្សាទុក...
                                            </button>
                                        </div>
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
    <script>
        function showPreview(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('product-image-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
