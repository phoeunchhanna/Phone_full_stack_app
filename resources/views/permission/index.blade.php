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
                                <li class="breadcrumb-item active">បញ្ជីការអនុញ្ញាត</li>
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
                                        <h3 class="text-primary font-weight-600">បញ្ជីការអនុញ្ញាត</h3>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            {{-- <a href="{{ route('permissions.create') }}" class="btn btn-primary">បន្ថែម <i
                                                    class="fas fa-plus"></i></a> --}}
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
                                                <th>ឈ្មោះ</th>
                                                {{-- <th>ឈ្មោះ guard</th> --}}
                                                <th>ថ្ងៃ​ ខែ ឆ្នាំ</th>

                                                {{-- <th class="text-end">សកម្មភាព</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($permissions as $permission)
                                                <tr>
                                                    <td>
                                                        <div class="form-check check-tables">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="something">
                                                        </div>
                                                    </td>
                                                    <td>{{ $permission->name }}</td>

                                                    {{-- <td>{{ $permission->guard_name }}</td> --}}
                                                    <td>{{ \Carbon\Carbon::parse($permission->created_at)->format('d-m-y') }}</td>


                                                    {{-- <td class="text-end">
                                                        <a href="{{route('permissions.edit', $permission->id)}}"
                                                            class="btn btn-secondary btn-sm"><i
                                                                class="bi bi-pencil-square"></i></a>
                                                                <form id="deleteForm{{ $permission->id }}"
                                                                    action="{{ route('permissions.destroy', $permission->id) }}"
                                                                    method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" class="btn btn-danger btn-sm"
                                                                        onclick="confirmDelete({{ $permission->id }})">
                                                                        <i class="bi bi-trash3"></i>
                                                                    </button>
                                                                </form>

                                                    </td> --}}


                                                </tr>
                                                {{-- @include('admin.suppliers.modal.edit') --}}
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{-- {{ $permissions->likes() }} --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.suppliers.modal.create')
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
                        text: "ទិន្នន័យរបស់អ្នកត្រូវបានលុបដោយជោគជ័យ។",
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
