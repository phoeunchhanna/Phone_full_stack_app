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
                                <li class="breadcrumb-item active">របាយការណ៍ការទិញ</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <form submit="generateReport">
                                <h3 class="text-primary font-weight-600">
                                    របាយការណ៍ការទិញ
                                </h3>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label>ជ្រើសរើស ថ្ងៃខែឆ្នាំ <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control date_range_picker" name="date_range"
                                                value="{{ old('date_range', now()->subDays(7)->format('Y-m-d') . ' to ' . now()->format('Y-m-d')) }}">
                                            @error('date_range')
                                                <span class="text-danger mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label>អ្នកផ្គត់ផ្គង់</label>
                                            <select name="supplier_id" class="form-control">
                                                <option value="">អ្នកផ្គត់ផ្គង់ទាំងអស់</option>
                                                @foreach ($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}"
                                                        {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <span target="generateReport" role="status" aria-hidden="true"></span>
                                            <i class="bi bi-funnel"></i> ពិនិត្យមើល
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="printReport()"><i
                                                class="bi bi-eye"></i> ពិនិត្យ</button>
                                        <a href="{{ route('purchases-report.export', ['export' => 'excel']) }}"
                                            class="btn btn-success"><i class="bi bi-download"></i>ទាញយកជាExcel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="datatable table-hover table-center mb-0 table table-stripped">
                                    <thead>
                                        <tr>
                                            <th>កាលបរិច្ឆេទ</th>
                                            <th>លេខយោង</th>
                                            <th>ឈ្មោះផលិតផល</th>
                                            <th>កូដ</th>
                                            <th>អ្នកផ្គត់ផ្គង់</th>
                                            <th>តម្លៃទិញចូល</th>
                                            <th>បញ្ចុះតម្លៃ</th>
                                            <th>បរិមាណទិញចូល</th>
                                            <th>សរុប</th>
                                            <th>ស្ថានភាព</th>
                                            <th>ស្ថានភាពការទូទាត់</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchasesDetails as $purchase)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($purchase->purchase->date)->format('d-m-Y') }}
                                                </td>
                                                <td class="text-primary">{{ $purchase->purchase->reference }}</td>
                                                <td>{{ $purchase->product ? $purchase->product->name : 'N/A' }}</td>
                                                <td>{{ $purchase->product ? $purchase->product->code : 'N/A' }}</td>
                                                <td>{{ $purchase->purchase->supplier->name }}</td>
                                                <td>{{ $purchase->unit_price }} $</td>
                                                <td>{{ $purchase->purchase->discount }} $</td>
                                                <td class="text-center">{{ $purchase->quantity }}</td>
                                                <td>{{ $purchase->total_price }} $</td>
                                                <td>
                                                    <h6>
                                                        <span
                                                            class="badge
                                                        {{ $purchase->purchase->status === 'បញ្ចប់' ? 'bg-info' : ($purchase->purchase->status === 'Pending' ? 'bg-warning' : 'bg-danger') }}">
                                                            {{ $purchase->purchase->status }}
                                                        </span>
                                                    </h6>
                                                </td>
                                                <td>
                                                    <h6>
                                                        <span
                                                            class="badge
                                                        {{ $purchase->purchase->payment_status === 'បានទូទាត់រួច' ? 'bg-info' : ($purchase->purchase->payment_status === 'បានទូទាត់ខ្លះ' ? 'bg-warning' : 'bg-danger') }}">
                                                            {{ $purchase->purchase->payment_status }}
                                                        </span>
                                                    </h6>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function printReport() {
            const dateRange = document.querySelector('input[name="date_range"]').value;

            // Extract start and end date from the date range
            const dates = dateRange.split(' to ');
            const startDate = dates[0];
            const endDate = dates[1];

            const printContents = document.querySelector('.datatable').outerHTML; // Select the content to print
            const originalContents = document.body.innerHTML; // Save the current page content

            // Prepare the page for printing with dynamic title
            document.body.innerHTML = `
    <html>
    <head>
        <title>របាយការណ៍ផលិតផល</title>
        <style>
            body { font-family: 'battambang', sans-serif; text-align: center; }
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
        }
    </script>
    <!-- Include jQuery and Date Range Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.js"></script>
    <script>
        $(document).ready(function() {
            $('.date_range_picker').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: 'អនុវត្ត',
                    cancelLabel: 'បោះបង់',
                    customRangeLabel: 'Custom Range'
                },
                maxDate: moment(),
                ranges: {
                    'ថ្ងៃនេះ': [moment(), moment()],
                    'ម្សិលមិញ': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '៧ថ្ងៃមុន': [moment().subtract(7, 'days'), moment()],
                    '៣០ថ្ងៃមុន': [moment().subtract(30, 'days'), moment()],
                    'ក្នុងខែនេះ': [moment().startOf('month'), moment().endOf('month')],
                    'ខែមុន': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                        .endOf('month')
                    ],
                    'ទាំងអស់': [moment().subtract(10, 'years'), moment()] // Adjust based on your needs
                }
            });

            $('.date_range_picker').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });
        });
    </script>
@endsection
