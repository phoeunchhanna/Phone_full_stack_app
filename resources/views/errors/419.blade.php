@extends('layouts.error')

@section('content')
<img src="{{ asset('images/500.png') }}" alt="500 Internal Error">
<h1 class="text-danger mt-3">500 - មានបញ្ហាក្នុងប្រព័ន្ធ</h1>
<p class="text-muted">សូមព្យាយាមម្តងទៀត ឬទាក់ទងអ្នកគ្រប់គ្រង។</p>
<a href="{{ route('home') }}" class="btn btn-primary">ទៅទំព័រដើម</a>
@endsection
