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
                                <li class="breadcrumb-item active">បញ្ជីការបញ្ជាទិញ</li>
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
                                            បញ្ជីការបញ្ជាទិញ
                                        </h3>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            @can('ទាញយកទិន្នន័យការបញ្ជាទិញ')
                                            <a href="{{ route('export.purchases') }}" class="btn btn-outline-primary me-2"><i
                                                class="fas fa-download"></i> ទាញយកទិន្នន័យ</a>
                                            @endcan

                                            @can('បង្កើតការបញ្ជាទិញ')
                                            <a href="{{ route('purchases.create') }}" class="btn btn-primary"><i
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
                                                <th class="text-center">កាលបរិច្ឆេទ</th>
                                                <th class="text-start">លេខយោង</th>
                                                <th class="text-start">ឈ្មោះអ្នកផ្គត់ផ្គង់</th>
                                                <th class="text-end">ចំនួនទឹកប្រាក់សរុប</th>
                                                <th class="text-end">ចំនួនទឹកប្រាក់បានបង់</th>
                                                <th class="text-end">ចំនួនទឹកប្រាក់ដែលនៅសល់</th>
                                                <th class="text-center">ស្ថានភាព</th>
                                                <th>ស្ថានភាពបង់ប្រាក់</th>
                                                <th class="text-end">សកម្មភាព</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($purchases as $purchase)
                                                <tr>
                                                    <td>
                                                        <div class="form-check check-tables">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="something">
                                                        </div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($purchase->date)->translatedFormat('d-F-Y') }}</td>
                                                    <td class="text-primary">{{ $purchase->reference }}
                                                    </td>
                                                    <td>{{ $purchase->supplier->name }}</td>
                                                    <td class="text-end">{{ $purchase->total_amount }} $</td>
                                                    <td class="text-end">{{ $purchase->paid_amount }} $</td>
                                                    <td class="text-end">{{ $purchase->due_amount }} $</td>
                                                    <td>
                                                        <h6>
                                                            <span
                                                                class="badge
                                                            {{ $purchase->status === 'បញ្ចប់' ? 'bg-info' : ($purchase->status === 'កំពុងរង់ចាំ' ? 'bg-warning' : 'bg-danger') }}">
                                                                {{ $purchase->status }}
                                                            </span>
                                                        </h6>
                                                    </td>
                                                    <td>
                                                        <h6>
                                                            <span
                                                                class="badge
                                                            {{ $purchase->payment_status === 'បានទូទាត់រួច' ? 'bg-info' : ($purchase->payment_status === 'បានទូទាត់ខ្លះ' ? 'bg-warning' : 'bg-danger') }}">
                                                                {{ $purchase->payment_status }}
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
                                                                <!-- View Sale -->
                                                                <a class="dropdown-item text-primary"
                                                                    href="{{ route('purchases.show', $purchase->id) }}">
                                                                    <i class="bi bi-eye me-2"></i> ពិនិត្យ
                                                                </a>

                                                                <!-- Edit Sale -->
                                                                @can('កែប្រែការបញ្ជាទិញ')
                                                                <a class="dropdown-item text-warning "
                                                                    href="{{ route('purchases.edit', $purchase->id) }}">
                                                                    <i class="bi bi-pencil-square me-2"></i> កែរប្រែ
                                                                </a>
                                                                @endcan

                                                                <!-- Delete Sale (only if completed) -->
                                                                @can('លុបការបញ្ជាទិញ')
                                                                @if ($purchase->status == 'បញ្ចប់')
                                                                    <form
                                                                        action="{{ route('purchases.destroy', $purchase) }}"
                                                                        method="POST" id="deleteForm{{ $purchase->id }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button"
                                                                            class="dropdown-item text-danger"
                                                                            onclick="confirmDelete({{ $purchase->id }})">
                                                                            <i class="bi bi-trash3 me-2"></i> លុប
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                                @endcan


                                                                @if ($purchase->due_amount > 0)
                                                                    <button class="dropdown-item text-success"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#paymentModal-{{ $purchase->id }}">
                                                                        <i class="bi bi-plus-circle-dotted me-2"></i>
                                                                        បន្ថែមទូទាត់
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @include('admin.purchases.purchase_payments.modal')
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
