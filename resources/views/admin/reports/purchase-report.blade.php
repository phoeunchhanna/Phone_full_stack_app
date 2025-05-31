@extends('layouts.master')

@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                                <li class="breadcrumb-item active">របាយការណ៍ការបញ្ជាទិញ</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h3 class="text-primary font-weight-600">
                                របាយការណ៍ការបញ្ជាទិញ
                            </h3>
                            <form method="POST" action="{{ route('purchases.report.filter') }}">
                                @csrf
                                <div class="row">
                                    <!-- Date Range -->
                                    <div class="col-md-4">
                                        <label for="date_range">ជ្រើសរើស ថ្ងៃខែឆ្នាំ</label>
                                        <input type="text" name="date_range" id="date_range"
                                            class="form-control date_range_picker"
                                            value="{{ request('date_range', now()->subDays(7)->format('d/m/Y') . ' to ' . now()->format('d/m/Y')) }}" readonly>
                                    </div>
                                    <!-- supplier -->
                                    <div class="col-md-4">
                                        <label for="supplier_id">ជ្រើសរើសអ្នកផ្គត់ផ្គង់</label>
                                        <select name="supplier_id" class="form-control form-select">
                                            <option value="">អ្នកផ្គត់ផ្គង់ទាំងអស់</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Payment Method -->
                                    <div class="col-md-4">
                                        <label for="payment_method">វិធីសាស្ត្រទូទាត់</label>
                                        <select name="payment_method" class="form-control form-select">
                                            <option value="">វិធីសាស្ត្រទូទាត់ទាំងអស់</option>
                                            <option value="សាច់ប្រាក់"
                                                {{ request('payment_method') == 'សាច់ប្រាក់' ? 'selected' : '' }}>សាច់ប្រាក់
                                            </option>
                                            <option value="អេស៊ីលីដា"
                                                {{ request('payment_method') == 'អេស៊ីលីដា' ? 'selected' : '' }}>អេស៊ីលីដា
                                            </option>
                                            <option value="ABA"
                                                {{ request('payment_method') == 'ABA' ? 'selected' : '' }}>ABA</option>
                                        </select>
                                    </div>
                                    <!-- Submit Button -->
                                    <div class="col-md-12 mt-3">
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-shuffle"></i>
                                            ច្រោះទិន្នន័យ</button>
                                        @if (count($purchases) > 0)
                                        <button class="btn btn-light" onclick="window.open('{{ route('purchases.report.print', ['date_range' => request('date_range')]) }}', '_blank')"><i class="bi bi-eye"></i>ពិនិត្យមើល</button>
                                        <a href="{{ route('purchases.report.export', ['date_range' => request()->input('date_range')]) }}" class="btn btn-success">
                                            <i class="bi bi-download"></i> ទាញយកជា Excel
                                        </a>
                                            <a href="{{ route('purchases.report.index') }}" class="btn btn-danger"> <i
                                                    class="bi bi-arrow-clockwise"></i> ជម្រះ</a>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            <!-- purchases Report Table -->
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="datatable table table-hover table-center mb-0 table table-stripped">
                                            <thead class="bg-primary text-white">
                                                <tr>
                                                    <th>#</th>
                                                    <th>ឈ្មោះផលិតផល</th>
                                                    <th>កាលបរិច្ឆេទ</th>
                                                    <th>លេខយោង</th>
                                                    <th>ឈ្មោះអតិថិជន</th>
                                                    <th>បរិមាណ</th>
                                                    <th>តម្លៃឯកតា</th>
                                                    <th>បញ្ចុះតម្លៃ</th>
                                                    <th>តម្លៃសរុប</th>
                                                    <th>វិធីសាស្ត្រទូទាត់</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($purchases as $purchase)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td> <!-- Corrected loop reference -->
                                                        <td>{{ $purchase->product->name }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($purchase->purchase->date)->translatedFormat('d-F-Y') }}
                                                        </td> 
                                                        <td class="text-primary">{{ $purchase->purchase->reference }}</td>
                                                        <td>{{ $purchase->purchase->supplier->name ?? 'N/A' }}</td>
                                                        <td>{{ $purchase->quantity }}</td>
                                                        <td>${{ number_format($purchase->unit_price, 2) }}</td>
                                                        <td>{{ $purchase->discount }}</td>
                                                        <td>${{ number_format($purchase->quantity * $purchase->unit_price, 2) }}
                                                        </td>
                                                        <td>{{ ucfirst($purchase->purchase->payment_method) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
