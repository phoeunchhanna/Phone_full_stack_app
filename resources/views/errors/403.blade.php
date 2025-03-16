@extends('layouts.error')

@section('content')
<img src="{{ asset('images/403.png') }}" alt="403 Forbidden">
<h1 class="text-danger mt-3">403 - មិនមានសិទ្ធិចូលប្រើ</h1>
<p class="text-muted">សូមទាក់ទងអ្នកគ្រប់គ្រងប្រព័ន្ធ។</p>
<a href="{{ route('home') }}" class="btn btn-primary">ទៅទំព័រដើម</a>
@endsection
