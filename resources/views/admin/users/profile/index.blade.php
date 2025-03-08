{{-- @extends('layouts.app')

@section('content')
    <div class="container">
        <h1>My Profile</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header">Profile Details</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <img src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="Profile Picture" class="img-thumbnail">
                    </div>
                    <div class="col-md-9">
                        <p><strong>Name:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Role:</strong> {{ $user->role }}</p>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}
