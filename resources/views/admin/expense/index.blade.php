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
                                    <h3 class="text-primary font-weight-600 mb-0">បញ្ជីការចំណាយ</h3>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            @can('បង្កើតការចំណាយ')
                                                <a href="{{ route('expenses.create') }}" class="btn btn-primary mb-3"><i
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
                                                <th>ប្រភេទការចំណាយ</th>
                                                <th>ចំនួនទឹកប្រាក់</th>
                                                <th>ពត៌មានលំអិត</th>
                                                <th>សកម្មភាព</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($expenses as $expense)
                                                <tr>
                                                    <td>
                                                        <div class="form-check check-tables">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="something">
                                                        </div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($expense->date)->format('d-m-Y') }}</td>
                                                    <td class="text-primary">{{ $expense->reference }}</td>
                                                    <td>{{ $expense->category->name }}</td>
                                                    <td>{{ number_format($expense->amount, 2) }} $</td>
                                                    <td>{{ $expense->details }}</td>
                                                    <td>
                                                        @can('កែប្រែការចំណាយ')
                                                        <a href="{{ route('expenses.edit', $expense) }}" type="button"
                                                            class="btn  btn-secondary btn-sm"><i
                                                                class="bi bi-pencil-square"></i></a>
                                                        @endcan
                                                            @can('លុបការចំណាយ')
                                                            <form id="deleteForm{{ $expense->id }}"
                                                                action="{{ route('expenses.destroy', $expense->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-danger btn-sm"
                                                                    onclick="confirmDelete({{ $expense->id }})"><i
                                                                        class="bi bi-trash3"></i></button>
                                                            </form>
                                                            @endcan
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
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: "#3d5ee1",
                cancelButtonColor: "#d33",
                confirmButtonText: 'បាទ, ខ្ញុំប្រាកដហើយ!',
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
