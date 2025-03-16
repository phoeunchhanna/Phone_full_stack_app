@extends('layouts.error')

@section('content')
<div class="main-wrapper">
    <div class="error-box">
        <h1>404</h1>
        <h3 class="h2 mb-3"><i class="fas fa-exclamation-triangle"></i> សូមអភ័យទោស! ទំព័រដែលអ្នកកំពុងរកមិនមានទេ</h3>
        <p class="h4 font-weight-normal">សូមព្យាយាមម្តងទៀត ឬត្រឡប់ទៅទំព័រដើម</p>
        <a href="{{route('home')}}" class="btn btn-primary">ត្រលប់ទៅគេហទំព័រដើម</a>
    </div>
</div>
@endsection
