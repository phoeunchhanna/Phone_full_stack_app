@extends('layouts.master')
@section('content')
    {{-- សារ --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                                <li class="breadcrumb-item active">ព៌ត័មានផ្ទាល់ខ្លួន</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <h3>សួរស្តី, <span class="text-primary">{{ auth()->user()->name }}</span></h3>
                        <p class="font-italic">អ្នកអាចផ្លាស់ប្តូរព៌ត័មានផ្ទាល់ខ្លួន និងពាក្យសម្ងាត់របស់អ្នកពីទីនេះ...</p>
                        <div class="text-end mb-2">
                            <!-- Back Button -->
                            <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('patch')

                                    <div class="form-group text-center">
                                        <label for="image">រូបភាពប្រវត្តិរូប <span class="text-danger">*</span></label>
                                        <img id="product-image-preview" src="{{ asset('storage/' . $user->avatar) }}"
                                            class="img-thumbnail rounded-circle mb-2" style="width: 150px; height: 150px;">
                                        <input id="avatar" type="file" name="avatar" accept="image/*"
                                            class="form-control d-none" onchange="showPreview(event)">
                                        <button type="button" class="btn btn-outline-primary"
                                            onclick="document.getElementById('avatar').click()">
                                            បញ្ចូលរូបភាព <i class="bi bi-upload"></i>
                                        </button>
                                        @error('avatar')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="name">ឈ្មោះ <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" required
                                            value="{{ auth()->user()->name }}">
                                        @error('name')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email">អ៊ីមែល <span class="text-danger">*</span></label>
                                        <input class="form-control" type="email" name="email" required
                                            value="{{ auth()->user()->email }}">
                                        @error('email')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" {{-- @if (auth()->user()->name == 'Admin' && auth()->user()->email == 'admin@gmail.com') 
                                                style="display:none;"
                                            @endif --}}>
                                            អាប់ដេតប្រវត្តិរូប <i class="bi bi-check"></i>
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('profile.update.password') }}" method="POST">
                                    @csrf
                                    @method('patch')
                                    <div class="form-group">
                                        <label for="current_password">ពាក្យសម្ងាត់បច្ចុប្បន្ន <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="current_password" required>
                                        @error('current_password')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password">ពាក្យសម្ងាត់ថ្មី <span class="text-danger">*</span></label>
                                        <input class="form-control" type="password" name="password" required>
                                        @error('password')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">បញ្ជាក់ពាក្យសម្ងាត់ <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="password" name="password_confirmation" required>
                                        @error('password_confirmation')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">អាប់ដេតពាក្យសម្ងាត់ <i
                                                class="bi bi-check"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showPreview(event) {
            const fileInput = event.target;
            const file = fileInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.querySelector('.img-thumbnail');
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
