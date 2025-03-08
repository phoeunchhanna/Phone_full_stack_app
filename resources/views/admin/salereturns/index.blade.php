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
                                <li class="breadcrumb-item active">បញ្ជីបង្វេចូលទំនិញ</li>
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
                                        <h3 class="page-title">បញ្ជីបង្វេចូលទំនិញ</h3>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            <a href="teachers.html" class="btn btn-outline-gray me-2 active"><i
                                                    class="feather-list"></i></a>
                                            <a href="teachers-grid.html" class="btn btn-outline-gray me-2"><i
                                                    class="feather-grid"></i></a>
                                            <a href="#" class="btn btn-outline-primary me-2"><i
                                                    class="fas fa-download"></i> ទាញយកទិន្នន័យ</a>
                                            <a href="{{ route('sale_returns.reference') }}" class="btn btn-primary"><i
                                                    class="fas fa-plus"></i> បន្ថែម</a>
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
                                                <th>លេខយោង</th>
                                                <th>កាលបរិច្ឆេទ</th>
                                                <th>អតិថិជន</th>
                                                <th>ចំនួនទឹកប្រាក់សរុប</th>
                                                <th>ចំនួនទឹកប្រាក់សងវិញ</th>
                                                <th>ទឹកប្រាក់នៅសល់</th>
                                                <th>ស្ថានភាព</th>
                                                <th>ស្ថានភាពទូទាត់</th>
                                                <th class="text-end">សកម្មភាព</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($salereturns as $salereturn)
                                                <tr>
                                                    <td>
                                                        <div class="form-check check-tables">
                                                            <input class="form-check-input" type="checkbox" value="something">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-outline-info">{{ $salereturn->reference }}</span>
                                                    </td>
                                                    <td>{{ $salereturn->date }}</td>
                                                    <td>{{ optional($salereturn->customer)->name ?? 'N/A' }}</td>

                                                    <td class="text-primary">{{ number_format($salereturn->total_amount, 2) }}</td>
                                                    <td class="text-primary">{{ number_format($salereturn->paid_amount, 2) }}</td>
                                                    <td class="text-primary">{{ number_format($salereturn->due_amount, 2) }}</td>
                                                    <td>
                                                        <h6>
                                                            <span class="badge
                                                                {{ $salereturn->status === 'បញ្ចប់' ? 'bg-info' : ($salereturn->status === 'Pending' ? 'bg-warning' : 'bg-danger') }}">
                                                                {{ $salereturn->status }}
                                                            </span>
                                                        </h6>
                                                    </td>
                                                    <td>
                                                        <h6>
                                                            <span class="badge
                                                                {{ $salereturn->payment_status === 'បានទូទាត់រួច' ? 'bg-info' : ($salereturn->payment_status === 'បានទូទាត់ខ្លះ' ? 'bg-warning' : 'bg-danger') }}">
                                                                {{ $salereturn->payment_status }}
                                                            </span>
                                                        </h6>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="" class="btn btn-secondary btn-sm">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        @if ($salereturn->status === 'បញ្ចប់')
                                                            <form action="" method="POST" style="display:inline;" id="deleteForm">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete()">
                                                                    <i class="bi bi-trash3"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
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
    <script>
        function confirmDelete(postId) {
            Swal.fire({
                title: 'តើអ្នកប្រាកដជាចង់លុបមែនទេ?',
                // text: 'តើអ្នកពិតជាចង់លុបទិន្នន័យអ្នកប្រើប្រាស់មែនទេ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: "#3d5ee1",
                cancelButtonColor: "#d33",
                confirmButtonText: 'OK!, ខ្ញុំប្រាកដហើយ!',
                cancelButtonText: 'បោះបង់'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your item has been deleted.",
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
