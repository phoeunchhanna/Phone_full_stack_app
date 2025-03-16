@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            @php
                $timeInCambodia = \Carbon\Carbon::now('Asia/Phnom_Penh');
                $hour = $timeInCambodia->hour;

                if ($hour < 12) {
                    $greeting = 'អរុណសួស្តី';
                } elseif ($hour < 18) {
                    $greeting = 'ទិវាសួស្តី';
                } else {
                    $greeting = 'រាត្រីសួស្តី';
                }
            @endphp
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">{{ $greeting }}! {{ Auth::user()->name }}</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                                <li class="breadcrumb-item active">{{ Session::get('name') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Sales Statistics -->
    <div class="row">
        @php
            $dashboardStats = [
                ['title' => 'ចំណូលសរុប', 'value' => number_format($totalRevenue, 2), 'icon' => 'bi-cash-stack', 'bg' => 'bg-success'],
                ['title' => 'ចំនួនការលក់', 'value' => $totalSales, 'icon' => 'bi-cart', 'bg' => 'bg-info'],
                ['title' => 'ចំនួនការបង្វែត្រឡប់', 'value' => $totalReturns, 'icon' => 'bi-arrow-left-right', 'bg' => 'bg-warning'],
                ['title' => 'ចំនួនអតិថិជន', 'value' => $totalCustomers, 'icon' => 'bi-people', 'bg' => 'bg-primary'],
            ];
        @endphp

        @foreach ($dashboardStats as $stat)
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="card bg-comman w-100">
                <div class="card-body">
                    <div class="db-widgets d-flex justify-content-between align-items-center">
                        <div class="db-info">
                            <h6>{{ $stat['title'] }}</h6>
                            <h3>{{ $stat['value'] }}</h3>
                        </div>
                        <div class="db-icon {{ $stat['bg'] }} text-white p-3">
                            <i class="bi {{ $stat['icon'] }}"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Recent Sales Table -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">ការលក់ថ្មីៗ</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>កាលបរិច្ឆេទ</th>
                        <th>លេខយោង</th>
                        <th>អតិថិជន</th>
                        <th>ចំនួន</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSales as $sale)
                    <tr>
                        <td>{{ $sale->date }}</td>
                        <td>{{ $sale->reference }}</td>
                        <td>{{ $sale->customer->name }}</td>
                        <td>${{ number_format($sale->total_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sales Performance Chart -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">ស្ថិតិលក់</h5>
            <canvas id="salesChart" width="400" height="200"></canvas>
        </div>
    </div>

</div>



{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["ចំណូលសរុប", "ចំនួនការលក់", "ចំនួនការបង្វែត្រឡប់", "ចំនួនអតិថិជន"],
                datasets: [{
                    label: 'ស្ថិតិលក់',
                    data: [{{ $totalRevenue }}, {{ $totalSales }}, {{ $totalReturns }}, {{ $totalCustomers }}],
                    backgroundColor: ['rgba(0, 128, 0, 0.7)', 'rgba(0, 0, 255, 0.7)', 'rgba(255, 165, 0, 0.7)', 'rgba(128, 0, 128, 0.7)'],
                    borderColor: 'rgba(0, 0, 0, 0.7)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection
