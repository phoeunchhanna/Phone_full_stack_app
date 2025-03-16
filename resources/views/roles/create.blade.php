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
                            <form action="{{ route('roles.store') }}" method="POST" enctype="multipart/form-data"
                                id="formcreate">
                                @csrf
                                <div class="Row">
                                    <div class="form-group d-flex align-items-center justify-content-between">
                                        <h3 class="text-primary font-weight-600 mb-0">បង្កើតតួនាទីអ្នកប្រើប្រាស់</h3>
                                        <span>
                                            <!-- Back Button -->
                                            <a href="{{ route('roles.index') }}" class="btn btn-outline-primary">
                                                <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
                                            </a>
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">បញ្ចូលឈ្មោះតួនាទី<span class="login-danger">*</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="បញ្ចូលឈ្មោះតួនាទី" required>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table-hover table-center mb-0 table table-stripped">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="checkAll"> <label
                                                        for="checkAll">ជ្រើសទាំងអស់</label>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="grid grid-cols-4">
                                                        @if ($permissions->isNotEmpty())
                                                            @foreach ($permissions as $permission)
                                                                <div class="mt-3">
                                                                    <input type="checkbox" class="permission-checkbox"
                                                                        id="permission-{{ $permission->id }}"
                                                                        name="permission[]" value="{{ $permission->name }}">
                                                                    <label
                                                                        for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <p>គ្មានទិន្នន័យ.</p>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3 d-flex justify-content-end">
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg" id="saveButton">រក្សាទុក<i
                                                class="bi bi-check-lg"></i></button>
                                        <button class="btn btn-primary btn-lg" type="button" disabled=""
                                            id="savingButton" style="display: none;">
                                            <span class="spinner-border spinner-border-sm me-1" role="status"
                                                aria-hidden="true"></span>
                                            កំពុងរក្សាទុក...
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="absolute">
                                <form method="GET" action="{{ route('permissions.search') }}">
                                    <input class="form-control" type="text" name="search"
                                        value="{{ request('search') }}" placeholder="ការស្វែងរកការអនុញ្ញាត"
                                        onkeydown="if(event.key === 'Enter' || event.keyCode === 13){ this.form.submit(); }">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('checkAll').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.permission-checkbox');
            checkboxes.forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
        });
    </script>

    <style>
        /* Grid layout for permissions */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            /* Auto adjusts columns based on screen size */
            gap: 16px;
            /* Space between the grid items */
            margin-top: 10px;
        }

        .absolute {
            position: absolute;
            top: 170px;
            right: 2.5vh;
        }
    </style>
@endsection
