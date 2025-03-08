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
                            <h3 class="page-title">បញ្ជីប្រភេទការចំណាយ</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                                <li class="breadcrumb-item active">បញ្ជីប្រភេទការចំណាយ</li>
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
                                        <h3 class="page-title">បញ្ជីប្រភេទការចំណាយ</h3>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            @can('បង្កើតប្រភេទការចំណាយ')
                                            <a href="{{ route('expense_categories.create') }}" class="btn btn-primary">បន្ថែម <i
                                                class="fas fa-plus"></i></a>
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
                                                <th>ប្រភេទការចំណាយ</th>
                                                <th>ការពិពណ៌នា</th>
                                                <th class="text-end">សកម្មភាព</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categories as $category)
                                                <tr>
                                                    <td>
                                                        <div class="form-check check-tables">
                                                            <input class="form-check-input" type="checkbox" value="something">
                                                        </div>
                                                    </td>
                                                    <td>{{ $category->name }}</td>
                                                    <td>{{ $category->description }}</td>
                                                    <td class="text-end">
                                                        @can('កែប្រែប្រភេទការចំណាយ')
                                                        <a href="{{ route('expense_categories.edit', $category) }}" type="button"
                                                            class="btn  btn-secondary btn-sm"><i
                                                                class="bi bi-pencil-square"></i></a>
                                                        @endcan

                                                        {{-- <a href="{{ route('brands.show', $brand) }}" type="button"
                                                            class="btn  btn-primary btn-sm"><i
                                                                class="bi bi-eye"></i></a> --}}
                                                        @can('លុបប្រភេទការចំណាយ')
                                                        <form id="deleteForm{{ $category->id }}"
                                                            action="{{ route('expense_categories.destroy', $category->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                onclick="confirmDelete({{ $category->id }})"><i
                                                                    class="bi bi-trash3"></i></button>
                                                        </form>
                                                        @endcan
                                                    </td>
                                                </tr>
                                                @include('admin.categories.modal.edit')
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

    @include('admin.categories.modal.create')
    {{-- delete js --}}
    <script>
        function confirmDelete(postId) {
            Swal.fire({
                title: 'តើអ្នកប្រាកដជាចង់លុបមែនទេ?',
                // text: 'តើអ្នកពិតជាចង់លុបទិន្នន័យអ្នកប្រើប្រាស់មែនទេ?',
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
