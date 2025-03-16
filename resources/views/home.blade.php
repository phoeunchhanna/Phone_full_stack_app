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

            @if (auth()->user() && auth()->user()->roles->contains('name', 'admin'))
                @php
                    $dashboardStats = [
                        [
                            'title' => 'ចំណូលសរុប',
                            'value' => number_format($totalRevenue, 2),
                            'icon' => 'bi-cash-stack',
                            'bg' => 'bg-success',
                        ],
                        [
                            'title' => 'ចំណាយសរុប',
                            'value' => number_format($totalExpenses, 2),
                            'icon' => 'bi-credit-card',
                            'bg' => 'bg-danger',
                        ],
                        [
                            'title' => 'ចំណេញ / ខាត',
                            'value' => number_format($totalProfit, 2),
                            'icon' => 'bi-graph-up',
                            'bg' => $totalProfit >= 0 ? 'bg-primary' : 'bg-warning',
                        ], // Fixed
                        ['title' => 'ចំនួនការលក់', 'value' => $totalSales, 'icon' => 'bi-cart', 'bg' => 'bg-info'],
                        [
                            'title' => 'ចំនួនការបង្វែត្រឡប់',
                            'value' => $totalReturns,
                            'icon' => 'bi-arrow-left-right',
                            'bg' => 'bg-secondary',
                        ],
                        [
                            'title' => 'ចំនួនការទិញ',
                            'value' => $totalPurchases,
                            'icon' => 'bi-truck',
                            'bg' => 'bg-primary',
                        ],
                        [
                            'title' => 'ចំនួនអតិថិជន',
                            'value' => $totalCustomers,
                            'icon' => 'bi-people',
                            'bg' => 'bg-success',
                        ],
                        [
                            'title' => 'ចំនួនអ្នកផ្គត់ផ្គង់',
                            'value' => $totalSuppliers,
                            'icon' => 'bi-shop',
                            'bg' => 'bg-danger',
                        ],
                    ];
                @endphp


                <div class="row">
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
            @elseif(auth()->user() && auth()->user()->roles->pluck('permissions')->flatten()->doesntContain('name', ''))
                <p>តួនាទីរបស់អ្នកមិនមានការអនុញ្ញាតទេ!</p>
            @else
                <div class="row">
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-comman w-100">
                            <div class="card-body">
                                <div class="db-widgets d-flex justify-content-between align-items-center">
                                    <div class="db-info">
                                        <h6>ចំនួនផលិតផល</h6>
                                        <h3>...................</h3>
                                    </div>
                                    <div class="db-icon bg-info text-white p-3">
                                        <i class="bi bi-box-seam"></i> <!-- Product Icon -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-comman w-100">
                            <div class="card-body">
                                <div class="db-widgets d-flex justify-content-between align-items-center">
                                    <div class="db-info">
                                        <h6>ចំណូល</h6>
                                        <h3>...................</h3>
                                    </div>
                                    <div class="db-icon bg-success text-white p-3">
                                        <i class="bi bi-graph-up-arrow"></i> <!-- Income Icon -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-comman w-100">
                            <div class="card-body">
                                <div class="db-widgets d-flex justify-content-between align-items-center">
                                    <div class="db-info">
                                        <h6>ចំណាយ</h6>
                                        <h3>...................</h3>
                                    </div>
                                    <div class="db-icon bg-danger text-white p-3">
                                        <i class="bi bi-graph-down-arrow"></i> <!-- Expenses Icon -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-comman w-100">
                            <div class="card-body">
                                <div class="db-widgets d-flex justify-content-between align-items-center">
                                    <div class="db-info">
                                        <h6>ចំណេញ</h6>
                                        <h3>...................</h3>
                                    </div>
                                    <div class="db-icon bg-warning text-white p-3">
                                        <i class="bi bi-cash-stack"></i> <!-- Profit Icon -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-xl-6 d-flex">
                    
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">ក្រាហ្វិកហិរញ្ញវត្ថុ</h4>
                            <canvas id="financialChart" style="height: 400px; width: 100%;"></canvas> <!-- Smaller size -->
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill student-space comman-shadow">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title">ការជូនដំណឹងអំពីបរិមាណក្នុងស្តុក</h5>
                            @php
                                $low_quantity_products = \App\Models\Product::with('stock') // Eager load stock
                                    ->whereHas('stock', function ($query) {
                                        $query->whereColumn('current', '<=', 'stock_alert');
                                    })
                                    ->get();
                            @endphp
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table star-student table-hover table-center table-borderless table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ល.រ</th>
                                            <th>ឈ្មោះផលិតផល</th>
                                            <th class="text-center">កូដ</th>
                                            <th class="text-center">បរិមាណក្នុងស្តុក</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($low_quantity_products as $key => $product)
                                            <tr>
                                                <td class="text-nowrap">
                                                    {{ $key + 1 }}
                                                </td>
                                                <td class="text-nowrap">
                                                    <a class="text-primary"
                                                        href="{{ route('products.show', $product->id) }}">
                                                        {{ $product->name }}
                                                    </a>
                                                </td>
                                                <td class="text-center">{{ $product->code }} </td>
                                                <td class="text-center text-danger">{{ $product->stock->current }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-center text-danger" colspan="4">
                                                    គ្មានផលិតផលដែលមានបរិមាណតិច។</td>
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
    </div>
    <script>
        var ctx = document.getElementById('financialChart').getContext('2d');
        var financialChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['ចំណូល', 'ចំណាយ', 'ចំណេញ/ខាត'],
                datasets: [{
                    label: 'សរុប ($)',
                    data: [{{ $totalRevenue }}, {{ $totalExpenses }}, {{ $totalProfit }}],
                    backgroundColor: [
                        'rgba(0, 123, 255, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(255, 193, 7, 0.6)'
                    ],

                    borderColor: [
                        'rgba(0, 123, 255, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(40, 167, 69, 1)'
                    ],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                            lineWidth: 1,
                        },
                        ticks: {
                            font: {
                                size: 12,
                                family: 'Battambong, Arial, sans-serif',
                                weight: 'bold', // Bold font weight for Y-axis
                            },
                            color: 'rgba(0, 0, 0, 0.8)', // Dark color for tick labels
                            stepSize: 1000, // Custom step size for Y-axis
                        }
                    },
                    x: {
                        grid: {
                            display: false, // No grid lines for X-axis
                        },
                        ticks: {
                            font: {
                                size: 12, // Smaller font size for X-axis
                                family: 'Helvetica, Arial, sans-serif', // Font family for X-axis
                                weight: 'bold', // Bold font weight for X-axis
                            },
                            color: 'rgba(0, 0, 0, 0.8)', // Dark color for X-axis labels
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)', // Dark background for tooltips
                        titleColor: '#fff', // White color for tooltip title
                        bodyColor: '#fff', // White color for tooltip body
                        bodyFont: {
                            size: 12, // Smaller font size for tooltip body
                        },
                        displayColors: false, // Disable color boxes in tooltips
                    },
                    legend: {
                        display: true, // Show the legend
                        labels: {
                            font: {
                                size: 12, // Smaller font size for legend labels
                                family: 'Helvetica, Arial, sans-serif', // Font family for legend
                            },
                            color: 'rgba(0, 0, 0, 0.7)', // Color for legend text
                        }
                    }
                }
            }
        });
    </script>
@endsection
