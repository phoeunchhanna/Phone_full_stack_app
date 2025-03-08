<!-- resources/views/errors/403.blade.php -->
@extends('layouts.app')

@section('title', 'Forbidden')

@section('content')
    <div class="error-page">
        <h1>403 - Forbidden</h1>
        <p>You do not have permission to access this page.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Go to Home</a>
    </div>
@endsection
