<!-- resources/views/errors/419.blade.php -->
@extends('layouts.app')

@section('title', 'Page Expired')

@section('content')
    <div class="error-page">
        <h1>419 - Page Expired</h1>
        <p>Your session has expired. Please try again.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Go to Home</a>
    </div>
@endsection
