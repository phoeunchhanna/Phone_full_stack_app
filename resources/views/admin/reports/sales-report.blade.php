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
                                <li class="breadcrumb-item active">របាយការណ៍ការលក់ទំនិញ</li>
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
                                <div class="row">
                                    <h3 class="text-primary font-weight-600">
                                        របាយការណ៍ការលក់ទំនិញ
                                    </h3>
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
                                            <label>អតិថិជន</label>
                                            <select model="customer_id" class="form-control" name="customer_id">
                                                <option value="">----ជ្រើសរើសអតិថិជន-----</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6">
                                        <div class="form-group">
                                            <label>ស្ថានភាពទូទាត់</label>
                                            <select model="payment_status" class="form-control" name="payment_status">
                                                <option value="">----ជ្រើសរើសស្ថានភាពទូទាត់-----</option>
                                                <option value="បានបង់ប្រាក់">បានបង់ប្រាក់</option>
                                                <option value="បានទូទាត់ខ្លះ">បានទូទាត់ខ្លះ</option>
                                                <option value="មិនទាន់ទូទាត់">មិនទាន់ទូទាត់</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <span target="generateReport" role="status" aria-hidden="true"></span>
                                            <i class="bi bi-funnel"></i> ច្រោះទិន្នន័យ
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="printReport()"><i
                                                class="bi bi-eye"></i> ពិនិត្យ</button>
                                        <a href="{{ route('sales-report.export', ['export' => 'excel']) }}"
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
                            <table class="datatable table table-bordered table-striped text-center mb-0">
                                <thead>
                                    <tr>
                                        <th>កាលបរិច្ឆេទ</th>
                                        <th>លេខយោង</th>
                                        <th>ឈ្មោះផលិតផល</th>
                                        <th>កូដ</th>
                                        <th>អតិថិជន</th>
                                        <th>តម្លៃឯកតា</th>
                                        <th>បញ្ចុះតម្លៃ</th>
                                        <th>បរិមាណលក់ចេញ</th>
                                        <th>សរុប</th>
                                        <th>ស្ថានភាព</th>
                                        <th>ស្ថានភាពការទូទាត់</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($salesDetails as $saleDetail)
                                        <tr data-entry-id="">
                                            <td>{{ \Carbon\Carbon::parse($saleDetail->sale->date)->format('d-m-Y') }}</td>
                                            <td class="text-primary">{{ $saleDetail->sale->reference }}</td>
                                            <td>{{ $saleDetail->product ? $saleDetail->product->name : 'N/A' }}</td>
                                            <td>{{ $saleDetail->product ? $saleDetail->product->code : 'N/A' }}</td>
                                            <td>{{ $saleDetail->sale->customer ? $saleDetail->sale->customer->name : 'N/A' }}
                                            </td>
                                            <td>{{ $saleDetail->unit_price }} $</td>
                                            <td>{{ $saleDetail->discount }} $</td>
                                            <td class="text-center">{{ $saleDetail->quantity }}</td>
                                            <td>{{ $saleDetail->total_price }} $</td>
                                            <td>
                                                <h6>
                                                    <span
                                                        class="badge
                                                    {{ $saleDetail->sale->status === 'បញ្ចប់' ? 'bg-info' : ($saleDetail->sale->status === 'Pending' ? 'bg-warning' : 'bg-danger') }}">
                                                        {{ $saleDetail->sale->status }}
                                                    </span>
                                                </h6>
                                            </td>
                                            <td>
                                                <h6>
                                                    <span
                                                        class="badge
                                                    {{ $saleDetail->sale->payment_status === 'បានទូទាត់រួច' ? 'bg-info' : ($saleDetail->sale->payment_status === 'បានទូទាត់ខ្លះ' ? 'bg-warning' : 'bg-danger') }}">
                                                        {{ $saleDetail->sale->payment_status }}
                                                    </span>
                                                </h6>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10">
                                                <h4 class="text-danger">គ្មានទិន្នន័យ</h4>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        < script >
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
    </>
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
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            });
        });
    </script>
@endsection
