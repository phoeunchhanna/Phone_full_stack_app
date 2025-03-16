@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">បង្កើត និងបោះពុម្ព Barcode</h4>
            </div>
            <div class="card-body">
                <!-- Form for Barcode -->
                <form action="{{ route('barcode.generate') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <label for="code" class="form-label">លេខកូដផលិតផល</label>
                            <input type="text" name="code" id="code" class="form-control"
                                value="{{ old('code') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">ចំនួន Barcode</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" min="1"
                                value="1" required>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-success"><i class="fas fa-barcode"></i> បង្កើត
                            Barcode</button>
                    </div>
                </form>
            </div>
        </div>

        @if (session('barcode'))
            <div class="card mt-4">
                <div class="card-body text-center">
                    <h5>Barcode សម្រាប់ផលិតផល</h5>
                    <div id="barcode-section">
                        @for ($i = 0; $i < session('quantity'); $i++)
                            <div class="mb-2">
                                <img src="data:image/png;base64,{{ session('barcode') }}" class="img-fluid">
                                <p class="text-center">{{ session('code') }}</p>
                            </div>
                        @endfor
                    </div>
                    <button onclick="printBarcode()" class="btn btn-primary mt-3">
                        <i class="fas fa-print"></i> បោះពុម្ព Barcode
                    </button>
                </div>
            </div>
        @endif
    </div>

    <script>
        function printBarcode() {
            var printContent = document.getElementById('barcode-section').innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>
@endsection
