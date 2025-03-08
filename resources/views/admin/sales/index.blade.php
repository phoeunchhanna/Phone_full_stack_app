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
                                <li class="breadcrumb-item active">បញ្ជីការលក់</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col mb-2">
                                        <h3 class="text-primary font-weight-600">
                                            បញ្ជីការលក់
                                        </h3>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            <a href="{{ route('export.sales') }}" class="btn btn-outline-primary me-2"><i
                                                    class="fas fa-download"></i> ទាញយកទិន្នន័យ</a>
                                            <a href="{{ route('pos.index') }}" class="btn btn-outline-primary me-2"><i
                                                    class="bi bi-grid"></i> ផ្ទាំងPOS</a>
                                            @can('បង្កើតការលក់')
                                            <a href="{{ route('sales.create') }}" class="btn btn-primary"><i
                                                class="fas fa-plus"></i> បន្ថែម</a>
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
                                                <th>កាលបរិច្ឆេទ</th>
                                                <th>លេខយោង</th>
                                                <th>ឈ្មោះអតិថិជន</th>
                                                <th>តម្លៃសរុប</th>
                                                <th>បញ្ចុះតម្លៃ</th>
                                                <th>ចំនួនទឹកប្រាក់បានបង់</th>
                                                <th>ចំនួនទឹកប្រាក់នៅខ្វះ</th>
                                                <th>ស្ថានភាព</th>
                                                <th>ស្ថានភាពការទូទាត់</th>
                                                <th class="text-center">សកម្មភាព</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($sales as $sale)
                                                <tr data-entry-id="{{ $sale->id }}">
                                                    <td>
                                                        <div class="form-check check-tables">
                                                            <input class="form-check-input sale-checkbox" type="checkbox"
                                                                value="{{ $sale->id }}">
                                                        </div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($sale->date)->translatedFormat('d-F-Y') }}</td>
                                                    <td class="text-primary">{{ $sale->reference }}
                                                    </td>
                                                    <td>{{ $sale->customer->name }}</td>
                                                    <td class="text-primary">{{ $sale->total_amount }}</td>
                                                    <td class="text-primary">{{ $sale->discount }}</td>
                                                    <td class="text-primary">{{ $sale->paid_amount }}</td>
                                                    <td class="text-primary">{{ $sale->due_amount }}</td>
                                                    <td>
                                                        <h6>
                                                            <span
                                                                class="badge
                                                        {{ $sale->status === 'បញ្ចប់' ? 'bg-info' : ($sale->status === 'Pending' ? 'bg-warning' : 'bg-danger') }}">
                                                                {{ $sale->status }}
                                                            </span>
                                                        </h6>
                                                    </td>
                                                    <td>
                                                        <h6>
                                                            <span
                                                                class="badge
                                                        {{ $sale->payment_status === 'បានទូទាត់រួច' ? 'bg-info' : ($sale->payment_status === 'បានទូទាត់ខ្លះ' ? 'bg-warning' : 'bg-danger') }}">
                                                                {{ $sale->payment_status }}
                                                            </span>
                                                        </h6>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="dropdown-action">
                                                            <a href="#" class="action-icon dropdown-toggle"
                                                                data-bs-toggle="dropdown" aria-expanded="true">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end"
                                                                data-popper-placement="bottom-end">
                                                                <!-- View Sale -->
                                                                <a class="dropdown-item text-primary"
                                                                    href="{{ route('sales.show', $sale->id) }}">
                                                                    <i class="bi bi-eye me-2"></i> ពិនិត្យ
                                                                </a>

                                                                <!-- Edit Sale -->
                                                                @can('កែរប្រែការលក់')
                                                                <a class="dropdown-item text-warning "
                                                                    href="{{ route('sales.edit', $sale->id) }}">
                                                                    <i class="bi bi-pencil-square me-2"></i> កែរប្រែ
                                                                </a>
                                                                @endcan


                                                                <!-- Delete Sale (only if completed) -->
                                                                @can('លុបការលក់')
                                                                @if ($sale->status == 'បញ្ចប់')
                                                                    <form action="{{ route('sales.destroy', $sale) }}"
                                                                        method="POST" id="deleteForm{{ $sale->id }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button"
                                                                            class="dropdown-item text-danger"
                                                                            onclick="confirmDelete({{ $sale->id }})">
                                                                            <i class="bi bi-trash3 me-2"></i> លុប
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                                @endcan

                                                                @if ($sale->due_amount > 0)
                                                                    <button class="dropdown-item text-success"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#paymentModal-{{ $sale->id }}">
                                                                        <i class="bi bi-plus-circle-dotted me-2"></i>
                                                                        បន្ថែមទូទាត់
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @include('admin.sales.sale_payments.modal')
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
    <script>
        function confirmDelete(postId) {
            Swal.fire({
                title: 'តើអ្នកប្រាកដជាចង់លុបមែនទេ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: "#3d5ee1",
                cancelButtonColor: "#d33",
                confirmButtonText: 'បាទ, ខ្ញុំប្រាកដហើយ!',
                cancelButtonText: 'បោះបង់'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "លុបរួចរាល់!",
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
