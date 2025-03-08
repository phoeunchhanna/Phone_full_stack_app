@extends('layouts.master')

@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}

    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">បង្កើតតួនាទីអ្នកប្រើប្រាស់</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                                <li class="breadcrumb-item"><a href="">តួនាទីអ្នកប្រើប្រាស់</a></li>
                                <li class="breadcrumb-item active">បង្កើតតួនាទីអ្នកប្រើប្រាស់</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">
                            <form action="{{ route('roles.update', $role->id) }}" method="POST" enctype="multipart/form-data" id="formcreate">
                                @csrf
                                <div class="Row">
                                    <div class="form-group d-flex align-items-center justify-content-between">
                                        <h3 class="text-primary font-weight-600 mb-0">កែប្រែតួនាទីអ្នកប្រើប្រាស់</h3>
                                        <span>
                                            <!-- Back Button -->
                                            <a href="{{ route('roles.index')}}" class="btn btn-outline-primary">
                                                <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                                            </a>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">បញ្ចូលឈ្មោះតួនាទី<span class="login-danger">*</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $role->name) }}"
                                            placeholder="បញ្ចូលឈ្មោះតួនាទី" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>





                                <div class="table-responsive">
                                    <table class=" table-hover table-center mb-0 table table-stripped">
                                        <thead>
                                            <tr>
                                                <th>ការអនុញ្ញាត</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="grid grid-cols-4">
                                                        @if ($permissions->isNotEmpty())
                                                            @foreach ($permissions as $permission)
                                                                <div class="mt-3">
                                                                    <input {{ ($hasPermissions->contains($permission->name)) ? 'checked' : ''}} type="checkbox" id="permission-{{ $permission->id}}" class="rounded" name="permission[]" value="{{ $permission->name }}">
                                                                    <label id="permission" for="">{{ $permission->name }}</label>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3 d-flex justify-content-end">
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg" id="saveButton">រក្សាទុក<i class="bi bi-check-lg"></i></button>
                                        <button type="button" class="btn btn-primary btn-lg" id="savingButton" style="display: none;" disabled>
                                            កំពុងរក្សាទុក...
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="absolute">
                                <form method="GET" action="{{ route('permissions.search.edit') }}">
                                    <input class="form-control"
                                           type="text"
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="ការស្វែងរកការអនុញ្ញាត"
                                           onkeydown="if(event.key === 'Enter') { event.preventDefault(); this.form.submit(); }">
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
                /* Grid layout for permissions */
    .grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* 4 equal columns */
        grid-template-rows: repeat(4, auto); /* 4 rows with automatic height */
        gap: 16px; /* Space between grid items */
        margin-top: 10px;
    }
    </style>
@endsection
