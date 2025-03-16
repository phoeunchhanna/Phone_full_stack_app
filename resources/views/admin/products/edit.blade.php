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
                                <li class="breadcrumb-item active">កែប្រែផលិតផល</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">
                            <form action="{{ route('products.update', $product->id) }}" method="POST"
                                enctype="multipart/form-data" id="formcreate">
                                @csrf
                                @method('PUT')
                                <div class="form-group d-flex align-items-center justify-content-between">
                                    <h3 class="text-primary font-weight-600 mb-0">កែប្រែផលិតផល</h3>
                                    <span>
                                        <!-- Back Button -->
                                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                                        </a>
                                    </span>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="name">ឈ្មោះផលិតផល<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="name" name="name"
                                                        value="{{ old('name', $product->name) }}"
                                                        placeholder="បញ្ចូលឈ្មោះផលិតផល" required>

                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <label for="code" class="form-label">លេខសម្គាល់:</label>
                                                <input type="text" name="code" id="code"
                                                    class="form-control @error('code') is-invalid @enderror"
                                                    value="{{ old('code', $product->code) }}"
                                                    placeholder="បញ្ចូលលេខសម្គាល់">
                                                @error('code')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="cost_price" class="form-label">ថ្លៃដើម<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" name="cost_price" id="cost_price"
                                                        class="form-control" required step="0.01" min="0"
                                                        value="{{ old('cost_price', $product->cost_price) }}"
                                                        placeholder="បញ្ចូលថ្លៃដើម">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="selling_price" class="form-label">តម្លៃលក់ចេញ<span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" name="selling_price" id="selling_price"
                                                        class="form-control" required step="0.01"
                                                        value="{{ old('selling_price', $product->selling_price) }}"
                                                        placeholder="បញ្ចូលតម្លៃលក់ចេញ">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="category_id" class="form-label">ប្រភេទទំនិញ <span
                                                            class="text-danger">*</span></label>
                                                    <select name="category_id" id="category_id"
                                                        class="form-select form-select-lg mb-3 fs-6" required>
                                                        <option value="">----ជ្រើសរើសប្រភេទទំនិញ----</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}"
                                                                {{ $category->id == old('category_id', $product->category_id) ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="mb-3">
                                                    <label for="brand_id" class="form-label">ម៉ាកយីហោ <span
                                                            class="text-danger">*</span></label>
                                                    <select name="brand_id" id="brand_id"
                                                        class="form-select form-select-lg mb-3 fs-6" required>
                                                        <option value="">----ជ្រើសរើសម៉ាកយីហោ----</option>
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand->id }}"
                                                                {{ $brand->id == old('brand_id', $product->brand_id) ? 'selected' : '' }}>
                                                                {{ $brand->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <label for="condition">លក្ខណៈ</label>
                                                <select class="form-control form-select" id="condition" name="condition">
                                                    <option value="ថ្មី"
                                                        {{ old('condition', $product->condition) == 'ថ្មី' ? 'selected' : '' }}>
                                                        ថ្មី</option>
                                                    <option value="មួយទឹក"
                                                        {{ old('condition', $product->condition) == 'មួយទឹក' ? 'selected' : '' }}>
                                                        មួយទឹក</option>
                                                    <option value="សម្រាប់ហ្វ្រីជូន"
                                                        {{ old('condition', $product->condition) == 'សម្រាប់ហ្វ្រីជូន' ? 'selected' : '' }}>
                                                        សម្រាប់ហ្វ្រីជូន</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <div class="form-group">
                                                    <br>
                                                    <label for="enable_stock">
                                                        <input class="form-check-input" type="checkbox" id="enable_stock"
                                                            name="enable_stock" value="1" checked>
                                                        <strong>គ្រប់គ្រងការជូនដំណឹងស្តុក?</strong>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6" id="stock_fields">
                                                <div class="mb-3">
                                                    <label for="stock_alert" class="form-label">ការជូនដំណឹងស្តុក</label>
                                                    <input type="number" name="stock_alert" id="stock_alert"
                                                        class="form-control"
                                                        value="{{ old('stock_alert', $product->stock_alert) }}"
                                                        placeholder="បញ្ចូលការជូនដំណឹងស្តុក">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <fieldset class="border p-3 rounded-2">
                                            <div class="form-group text-center">
                                                <label for="image">រូបភាពផលិតផល</label>
                                                <img style="width: 145px; height: 145px;"
                                                    class="d-block mx-auto img-thumbnail img-fluid mb-2"
                                                    src="{{ asset('storage/' . $stock->product->image) }}" alt="រូបភាពផលិតផល"
                                                    id="product-image-preview">
                                                <!-- Input ស្វ័យសម្គាល់ -->
                                                <input id="image" type="file" name="image" accept="image/*"
                                                    class="d-none" onchange="showPreview(event)">

                                                <!-- ប៊ូតុងបញ្ចូល -->
                                                <button type="button" class="btn btn-outline-primary"
                                                    onclick="document.getElementById('image').click()">
                                                    បញ្ចូលរូបភាព <i class="bi bi-upload"></i>
                                                </button>
                                                @error('image')
                                                    <p class="text-danger">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-sm-12">
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">ការពិពណ៌នា</label>
                                                    <textarea name="description" id="description" class="form-control" placeholder="បញ្ចូលការពិពណ៌នា">{{ old('description', $product->description) }}</textarea>
                                                </div>
                                            </div>
                                        </fieldset>
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
                            </div>
                        </div>
                    </div>
                </div>
                </form>
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
        document.addEventListener("DOMContentLoaded", function() {
            const enableStockCheckbox = document.getElementById("enable_stock");
            const stockFields = document.querySelectorAll("#stock_fields");

            function toggleStockFields() {
                if (enableStockCheckbox.checked) {
                    stockFields.forEach(field => {
                        field.style.display = "block";
                    });
                } else {
                    stockFields.forEach(field => {
                        field.style.display = "none";
                    });
                }
            }

            enableStockCheckbox.addEventListener("change", toggleStockFields);
            toggleStockFields(); // Call the function initially to set the correct state.
        });

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
