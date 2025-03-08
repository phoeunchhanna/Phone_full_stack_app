<!-- resources/views/errors/500.blade.php -->
@extends('error.app')


@section('title', 'Server Error')

@section('content')
    <div class="error-page">
        <h1>500 - Internal Server Error</h1>
        <p>Something went wrong on our end. Please try again later.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Go to Home</a>
    </div>
@endsection
