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
                                <li class="breadcrumb-item active">របាយការណ៍ប្រាក់ចំណេញ និងខាត</li>
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
                                របាយការណ៍ប្រាក់ចំណេញ និងខាត
                            </h3>
                            <form action="{{ route('profit.loss.report') }}" method="GET">
                                @csrf
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
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-funnel"></i> ពិនិត្យមើល
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Sales --}}
                <div class="col-12 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="bg-info p-3 mfe-3 m-2 rounded">
                                <i class="bi bi-receipt font-2xl"></i>
                            </div>
                            <div>
                                <div class="text-value text-primary">{{ number_format($sales_amount, 2) }} $</div>
                                <div class="text-uppercase font-weight-bold small">{{ $total_sales }} ការលក់</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Profit --}}
                <div class="col-12 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="bg-info p-3 mfe-3 m-2 rounded">
                                <i class="bi bi-trophy font-2xl"></i>
                            </div>
                            <div>
                                <div class="text-value text-primary">{{ number_format($profit_amount, 2) }} $</div>
                                <div class="text-uppercase font-weight-bold small">ចំណេញ</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Purchases --}}
                <div class="col-12 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="bg-info p-3 mfe-3 m-2 rounded">
                                <i class="bi bi-bag font-2xl"></i>
                            </div>
                            <div>
                                <div class="text-value text-primary">{{ number_format($purchases_amount, 2) }} $</div>
                                <div class="text-uppercase font-weight-bold small">{{ $total_purchases }} ការទិញ</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Expenses --}}
                <div class="col-12 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="bg-info p-3 mfe-3 m-2 rounded">
                                <i class="bi bi-wallet2 font-2xl"></i>
                            </div>
                            <div>
                                <div class="text-value text-primary">{{ number_format($expenses_amount, 2) }} $</div>
                                <div class="text-uppercase font-weight-bold small">ចំណាយ</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payments Sent --}}
                <div class="col-12 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="bg-info p-3 mfe-3 m-2 rounded">
                                <i class="bi bi-cash-stack font-2xl"></i>
                            </div>
                            <div>
                                <div class="text-value text-primary">{{ number_format($payments_sent_amount, 2) }} $</div>
                                <div class="text-uppercase font-weight-bold small">ប្រាក់ដែលចំណាយ</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payments Received --}}
                <div class="col-12 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="bg-info p-3 mfe-3 m-2 rounded">
                                <i class="bi bi-cash-stack font-2xl"></i>
                            </div>
                            <div>
                                <div class="text-value text-primary">{{ number_format($payments_received_amount, 2) }} $</div>
                                <div class="text-uppercase font-weight-bold small">ប្រាក់បានទទួល</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Net Payments --}}
                <div class="col-12 col-lg-4 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="bg-info p-3 mfe-3 m-2 rounded">
                                <i class="bi bi-cash-stack font-2xl"></i>
                            </div>
                            <div>
                                <div class="text-value text-primary">{{ number_format($payments_net_amount, 2) }} $</div>
                                <div class="text-uppercase font-weight-bold small">ប្រាក់សុទ្ធ</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
