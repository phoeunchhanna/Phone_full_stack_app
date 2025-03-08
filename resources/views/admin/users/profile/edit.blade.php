{{-- @extends('layouts.master')
@section('content')
    <div class="container">
        <h1>Edit Profile</h1>
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <<input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="mb-3">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password (Leave blank if you don't want to change)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>
            <div class="form-group">
                <label for="avatar">Profile Picture</label>
                <input type="file" name="avatar" id="avatar" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
@endsection --}}
