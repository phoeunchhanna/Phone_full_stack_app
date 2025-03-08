@extends('error.app')

@section('title', 'Page Not Found')

@section('content')
    <div class="error-page">
        <h1>404 - Page Not Found</h1>
        <p>We couldn't find the page you were looking for. It might have been moved or deleted.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Go to Home</a>
    </div>
@endsection
