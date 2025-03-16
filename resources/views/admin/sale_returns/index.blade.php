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
                                <li class="breadcrumb-item active">បញ្ជីការបង្វិលចូលទំនិញ</li> {{-- Changed --}}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @if (session('success'))
                <script>
                    setTimeout(function() {
                        fetchSaleReturn({{ session('sale_return_id') }});
                    }, 500);
                </script>
            @endif
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col mb-2">
                                        <h3 class="text-primary font-weight-600">
                                            បញ្ជីការបង្វិលចូលទំនិញ {{-- Changed --}}
                                        </h3>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            @can('ទាញយកទិន្នន័យការបង្វិលលក់')
                                                {{-- Changed --}}
                                                {{-- <a href="{{ route('export.sales.return') }}" class="btn btn-outline-primary me-2"><i
                                                class="fas fa-download"></i> ទាញយកទិន្នន័យ</a>  --}}
                                            @endcan

                                            @can('បង្កើតការបង្វិលលក់')
                                                {{-- Changed --}}
                                                <a href="{{ route('sale-returns.create') }}" class="btn btn-primary"><i
                                                        class="fas fa-plus"></i> បន្ថែម {{-- Changed --}}</a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="datatable table-hover table-center mb-0 table table-stripped">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="form-check check-tables">
                                                        <input class="form-check-input" type="checkbox" value="something">
                                                    </div>
                                                </th>
                                                <th class="text-center">កាលបរិច្ឆេទ</th>
                                                <th class="text-start">លេខយោង</th>
                                                <th class="text-start">ឈ្មោះអតិថិជន</th> {{-- Changed --}}
                                                <th class="text-end">ចំនួនទឹកប្រាក់សរុប</th>
                                                <th class="text-end">ចំនួនទឹកប្រាក់បានបង់</th>
                                                <th class="text-end">ចំនួនទឹកប្រាក់ដែលនៅសល់</th>
                                                <th class="text-center">ស្ថានភាព</th>
                                                <th>ស្ថានភាពបង់ប្រាក់</th>
                                                <th class="text-end">សកម្មភាព</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($salesReturns as $saleReturn)
                                                {{-- Changed --}}
                                                <tr>
                                                    <td>
                                                        <div class="form-check check-tables">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="something">
                                                        </div>
                                                    </td>

                                                    <td>{{ \Carbon\Carbon::parse($saleReturn->date)->translatedFormat('d-F-Y') }}
                                                    </td> {{-- Changed --}}
                                                    <td class="text-primary">{{ $saleReturn->reference }}</td>
                                                    {{-- Changed --}}
                                                    <td>{{ $saleReturn->customer->name }}</td> {{-- Changed --}}
                                                    <td class="text-end">{{ $saleReturn->total_amount }} $</td>
                                                    <td class="text-end">{{ $saleReturn->paid_amount }} $</td>
                                                    <td class="text-end">{{ $saleReturn->due_amount }} $</td>
                                                    <td>
                                                        <h6>
                                                            <span
                                                                class="badge
                                                            {{ $saleReturn->status === 'បញ្ចប់' ? 'bg-info' : ($saleReturn->status === 'កំពុងរង់ចាំ' ? 'bg-warning' : 'bg-danger') }}">
                                                                {{ $saleReturn->status }}
                                                            </span>
                                                        </h6>
                                                    </td>
                                                    <td>
                                                        <h6>
                                                            <span
                                                                class="badge
                                                            {{ $saleReturn->payment_status === 'បានទូទាត់រួច' ? 'bg-info' : ($saleReturn->payment_status === 'បានទូទាត់ខ្លះ' ? 'bg-warning' : 'bg-danger') }}">
                                                                {{ $saleReturn->payment_status }}
                                                            </span>
                                                            <h6>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class=" dropdown-action">
                                                            <a href="#" class="action-icon dropdown-toggle"
                                                                data-bs-toggle="dropdown" aria-expanded="true">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end"
                                                                data-popper-placement="bottom-end">
                                                                <!-- View Sale Return -->
                                                                <a class="dropdown-item text-primary"
                                                                    href="{{ route('sale-returns.show', $saleReturn->id) }}">
                                                                    {{-- Changed --}}
                                                                    <i class="bi bi-eye me-2"></i> ពិនិត្យ
                                                                    {{-- Changed --}}
                                                                </a>

                                                                <!-- Edit Sale Return -->
                                                                @can('កែប្រែការបង្វិលលក់')
                                                                    {{-- Changed --}}
                                                                    <a class="dropdown-item text-warning "
                                                                        href="{{ route('sale-returns.edit', $saleReturn->id) }}">
                                                                        {{-- Changed --}}
                                                                        <i class="bi bi-pencil-square me-2"></i> កែរប្រែ
                                                                        {{-- Changed --}}
                                                                    </a>
                                                                @endcan

                                                                <!-- Delete Sale Return (only if completed) -->
                                                                @can('លុបការបង្វិលលក់')
                                                                    {{-- Changed --}}
                                                                    @if ($saleReturn->status == 'បញ្ចប់')
                                                                        <form
                                                                            action="{{ route('sale-returns.destroy', $saleReturn) }}"
                                                                            {{-- Changed --}} method="POST"
                                                                            id="deleteForm{{ $saleReturn->id }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="button"
                                                                                class="dropdown-item text-danger"
                                                                                onclick="confirmDelete({{ $saleReturn->id }})">
                                                                                <i class="bi bi-trash3 me-2"></i> លុប
                                                                                {{-- Changed --}}
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                @endcan

                                                                @if ($saleReturn->due_amount > 0)
                                                                    <button class="dropdown-item text-success"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#paymentModal-{{ $saleReturn->id }}">
                                                                        <i class="bi bi-plus-circle-dotted me-2"></i>
                                                                        បន្ថែមទូទាត់ {{-- Changed --}}
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @include('admin.sale_returns.sales_return_payments.modal')
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
    <!-- Sale Return Invoice Modal -->
    <div class="modal fade" id="saleReturnInvoiceModal" tabindex="-1" aria-labelledby="saleReturnInvoiceLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">🧾 វិក័យប័ត្រត្រឡប់ទំនិញ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Loading Spinner -->
                    <div id="loading-spinner" class="text-center" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">កំពុងផ្ទុក...</span>
                        </div>
                        <p>កំពុងផ្ទុកវិក័យប័ត្រ...</p>
                    </div>

                    <!-- Invoice Content -->
                    <div id="invoice-content">
                        <p class="text-muted">សូមជ្រើសរើសវិក័យប័ត្រ...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បិទ</button>
                    <button type="button" class="btn btn-primary" onclick="printInvoice()">បោះពុម្ព</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Fetching Sale Return Invoice -->
    <script>
        function fetchSaleReturn(id) {
            if (!id || id === 'undefined') {
                console.error("Invalid Sale Return ID");
                return;
            }

            // Show Loading Spinner & Clear Invoice Content
            document.getElementById('loading-spinner').style.display = 'block';
            document.getElementById('invoice-content').innerHTML = '';

            // Show Modal First
            var modal = new bootstrap.Modal(document.getElementById('saleReturnInvoiceModal'));
            modal.show();

            // Fetch Invoice Data
            fetch(`/sale-returns/invoice/${id}`)
                .then(response => response.text())
                .then(data => {
                    // Hide Loading Spinner & Show Invoice
                    document.getElementById('loading-spinner').style.display = 'none';
                    document.getElementById('invoice-content').innerHTML = data;
                })
                .catch(error => {
                    document.getElementById('loading-spinner').style.display = 'none';
                    document.getElementById('invoice-content').innerHTML =
                        "<p class='text-danger'>មានបញ្ហាក្នុងការផ្ទុកវិក័យប័ត្រ!</p>";
                    console.error("Error fetching invoice:", error);
                });
        }

        function printInvoice() {
            var printContents = document.getElementById('invoice-content').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>

    <script>
        function confirmDelete(postId) {
            Swal.fire({
                title: 'តើអ្នកប្រាកដទេ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: "#3d5ee1",
                cancelButtonColor: "#d33",
                confirmButtonText: 'OK!, ខ្ញុំប្រាកដហើយ!',
                cancelButtonText: 'បោះបង់'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "បានលុប!",
                        text: "ទិន្នន័យរបស់អ្នកត្រូវបានលុប។",
                        icon: "success",
                        timer: 1400,
                        showConfirmButton: false
                    });
                    setTimeout(function() {
                        document.getElementById('deleteForm' + postId).submit();
                    }, 1400);
                }
            });
        }
    </script>
@endsection
