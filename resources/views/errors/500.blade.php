@extends('layouts.error')

@section('content')
<div class="main-wrapper">
    <div class="error-box">
        <h1>500</h1>
        <h3 class="h2 mb-3"><i class="fas fa-exclamation-triangle"></i> មានបញ្ហាក្នុងប្រព័ន្ធ</h3>
        <p class="h4 font-weight-normal">សូមព្យាយាមម្តងទៀត ឬទាក់ទងអ្នកគ្រប់គ្រង។</p>
        <a href="{{route('home')}}" class="btn btn-primary">ត្រលប់ទៅគេហទំព័រដើម</a>
    </div>
</div>
@endsection
