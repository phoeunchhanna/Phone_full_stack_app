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
                                <li class="breadcrumb-item active">ការទូទាត់ការលក់</li>
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
                                    <div class="col">
                                        <h3 class="text-primary font-weight-600">បញ្ជីការទូទាត់ការលក់</h3>
                                    </div>
                                </div>
                                <table class="datatable table-hover table-center mb-0 table table-stripped">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="form-check check-tables">
                                                    <input class="form-check-input" type="checkbox" value="something">
                                                </div>
                                            </th>
                                            <th>យោងការលក់</th>
                                            <th>ចំនួនប្រាក់</th>
                                            <th>កាលបរិច្ឆេទ</th>
                                            <th>វិធីសាស្ត្រទូទាត់</th>
                                            <th>សកម្មភាព</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments as $payment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-primary">{{ $payment->sale->reference ?? 'N/A' }}</td>
                                                <td>${{ $payment->amount }}</td>
                                                <td>{{ \Carbon\Carbon::parse($payment->date)->translatedFormat('d-F-Y') }}</td>
                                                <td>{{ $payment->payment_method }}</td>
                                                <td>
                                                    @can('កែរប្រែការទូទាត់ការលក់')
                                                    <a href="{{ route('sale_payments.edit', ['sale_id' => $payment->sale_id, 'sale_payment' => $payment->id]) }}"
                                                        class="btn btn-secondary btn-sm">
                                                        <i class="bi bi-pencil-square"></i>
                                                     </a>
                                                    @endcan

                                                     @can('លុបការទូទាត់ការលក់')
                                                     <form id="deleteForm{{ $payment->id }}"
                                                        action="{{ route('sale_payments.destroy', $payment->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="confirmDelete({{ $payment->id }})"><i
                                                                class="bi bi-trash3"></i></button>
                                                    </form>
                                                     @endcan

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{-- {{ $payments->links() }} --}}
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
