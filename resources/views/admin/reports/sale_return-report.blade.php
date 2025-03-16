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
                                <li class="breadcrumb-item active">របាយការណ៍ការបង្វិលទំនិញចូល</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <form method="GET" action="{{ route('reports.sale-return') }}" class="mb-4">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="form-group">
                                            <label>ជ្រើសរើស ថ្ងៃខែឆ្នាំ <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control date_range_picker" name="date_range"
                                                value="{{ old('date_range', now()->subDays(7)->format('Y-m-d') . ' to ' . now()->format('Y-m-d')) }}">
                                            @error('date_range')
                                                <span class="text-danger mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="form-group">
                                            <label>អតិថិជន</label>
                                            <select name="customer_id" class="form-control form-select">
                                                <option value="">អតិថិជនទាំងអស់</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="form-group">
                                            <label>វិធីសាស្រ្តទូទាត់</label>
                                            <select name="payment_method" class="form-control form-select">
                                                <option value="">វិធីសាស្រ្តទាំងអស់</option>
                                                <option value="សាច់ប្រាក់" {{ request('payment_method') == 'សាច់ប្រាក់' ? 'selected' : '' }}>សាច់ប្រាក់</option>
                                                <option value="អេស៊ីលីដា" {{ request('payment_method') == 'អេស៊ីលីដា' ? 'selected' : '' }}>អេស៊ីលីដា</option>
                                                <option value="ABA" {{ request('payment_method') == 'ABA' ? 'selected' : '' }}>ABA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="form-group">
                                            <label>ស្ថានភាពទូទាត់</label>
                                            <select name="payment_status" class="form-controlc form-select">
                                                <option value="">ស្ថានភាពទាំងអស់</option>
                                                <option value="បានបង់" {{ request('payment_status') == 'បានបង់' ? 'selected' : '' }}>បានបង់</option>
                                                <option value="បានបង់ខ្លះ" {{ request('payment_status') == 'បានបង់ខ្លះ' ? 'selected' : '' }}>បានបង់ខ្លះ</option>
                                                <option value="មិនទាន់បង់" {{ request('payment_status') == 'មិនទាន់បង់' ? 'selected' : '' }}>មិនទាន់បង់</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> ពិនិត្យមើល</button>
                                        <button type="button" class="btn btn-secondary" onclick="printReport()"><i class="bi bi-eye"></i> ពិនិត្យ</button>
                                        <a href="{{ route('purchases-report.export', ['export' => 'excel']) }}" class="btn btn-success">
                                            <i class="bi bi-download"></i> ទាញយកជា Excel
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sales Report Table --}}
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <table class="datatable table table-bordered table-striped text-center mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>លេខវិក័យប័ត្រ</th>
                                        <th>អតិថិជន</th>
                                        <th>ផលិតផល</th>
                                        <th>ចំនួន</th>
                                        <th>តម្លៃ</th>
                                        <th>ស្ថានភាពទូទាត់</th>
                                        <th>កាលបរិច្ឆេទ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salesDetails as $key => $sale)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $sale->saleReturn->reference ?? 'N/A' }}</td>
                                            <td>{{ $sale->saleReturn->customer->name ?? 'N/A' }}</td>
                                            <td>{{ $sale->product->name ?? 'N/A' }}</td>
                                            <td>{{ $sale->quantity }}</td>
                                            <td>${{ number_format($sale->price, 2) }}</td>
                                            <td>
                                                @if ($sale->saleReturn->payment_status == 'paid')
                                                    <span class="badge bg-success">បានបង់</span>
                                                @elseif ($sale->saleReturn->payment_status == 'pending')
                                                    <span class="badge bg-warning">កំពុងរងចាំ</span>
                                                @else
                                                    <span class="badge bg-danger">មិនទាន់បង់</span>
                                                @endif
                                            </td>
                                            <td>{{ $sale->saleReturn->date ?? 'N/A' }}</td>
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

    {{-- Print Script --}}
    <script>
        function printReport() {
            const dateRange = document.querySelector('input[name="date_range"]').value;
            const dates = dateRange.split(' to ');
            const startDate = dates[0];
            const endDate = dates[1];

            const printContents = document.querySelector('.datatable').outerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = `
                <html>
                <head>
                    <title>របាយការណ៍ផលិតផល</title>
                    <style>
                        body { font-family: 'Battambang', sans-serif; text-align: center; }
                        table { border-collapse: collapse; width: 100%; margin: 0 auto; }
                        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                        th { background-color: #f4f4f4; }
                        h4 { margin: 20px 0; }
                    </style>
                </head>
                <body>
                    <h4>របាយការណ៍ផលិតផល: ${startDate} - ${endDate}</h4>
                    ${printContents}
                </body>
                </html>
            `;

            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
