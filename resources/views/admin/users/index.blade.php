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
                                <li class="breadcrumb-item active">បញ្ជីអ្នកប្រើប្រាស់</li>
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
                                        <h3 class="page-title">បញ្ជីអ្នកប្រើប្រាស់</h3>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            @can('បង្កើតអ្នកប្រើប្រាស់')
                                            <a href="{{ route('users.create') }}" class="btn btn-primary">បន្ថែម <i
                                                    class="fas fa-plus"></i></a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="datatable table-hover table-center mb-0 table table-stripped">
                                        <thead>
                                            <tr>
                                                <th>រូបភាព</th>
                                                <th>ឈ្មោះអ្នកប្រើប្រាស់</th>
                                                <th>អ៊ីម៉ែល</th>
                                                {{-- <th>ប្រភេទអ្នកប្រើប្រាស់</th> --}}
                                                <th>តួនាទីអ្នកប្រើប្រាស់</th>
                                                <th class="text-end">សកម្មភាព</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>
                                                        <h2 class="table-avatar">
                                                            <a href="{{ route('users.show', $user) }}" class="avatar avatar-sm me-2">
                                                                <img class="avatar-img rounded-circle"
                                                                    src="{{ asset('storage/' . $user->avatar) }}"
                                                                    alt="User Image">
                                                            </a>
                                                        </h2>
                                                    </td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                             <td>
                                                        @foreach($user->roles as $role)
                                                            <span class="btn btn-sm btn-primary ">{{ $role->name }}</span>
                                                        @endforeach
                                                    </td>
                                                    {{-- <td>{{ $user->user_type }}</td> --}}
                                                    <td class="text-end">
                                                        @can('ព័ត៌មានអ្នកប្រើប្រាស់')


                                                        <a href="{{ route('users.show', $user) }}" type="button"
                                                            class="btn btn-primary btn-sm"><i class="bi bi-eye"></i></a>
                                                            @endcan
                                                            @can('កែប្រែអ្នកប្រើប្រាស់')
                                                        <a href="{{ route('users.edit', $user->id) }}" type="button"
                                                            class="btn  btn-secondary btn-sm"><i
                                                                class="bi bi-pencil-square"></i></a>
                                                            @endcan
                                                        @can('លុបអ្នកប្រើប្រាស់')
                                                        @if (auth()->user()->id !== $user->id && $user->roles->first()->name !== 'admin')
                                                        <form action="{{ route('users.destroy', $user) }}"
                                                            method="POST" style="display:inline;"
                                                            id="deleteForm{{ $user->id }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                onclick="confirmDelete({{ $user->id }})">
                                                                <i class="bi bi-trash3"></i>
                                                            </button>
                                                        </form>
                                                    @endif
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
        function confirmDelete(userId) {
            Swal.fire({
                title: 'តើអ្នកប្រាកដជាចង់លុបមែនទេ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'បាទ, ខ្ញុំប្រាកដហើយ!',
                cancelButtonText: 'បោះបង់'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "បានលុប!",
                        text: "ឯកសាររបស់អ្នកត្រូវបានលុប :)",
                        icon: "success",
                        timer: 1400,
                        showConfirmButton: false
                    });
                    setTimeout(function() {
                        // Submit the delete form after the success message
                        document.getElementById('deleteForm' + userId).submit();
                    }, 1400);
                }
            });
        }
    </script>
@endsection
