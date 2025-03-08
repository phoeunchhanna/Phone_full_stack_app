@extends('layouts.master')
@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-sub-header">
                <h3 class="page-title">ព័តមានលំអិត</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('brands.index') }}">ម៉ាកយីហោ</a></li>
                    <li class="breadcrumb-item active">ព័តមានលំអិត</li>
                </ul>
            </div>
            <div class="card">
                <div class="card-body">
                    <h3 class="text-primary font-weight-600 mb-0">ព័ត៌មានលម្អិតអំពី: ម៉ាកយីហោ</h3>
                    <div>
                        <strong>ឈ្មោះម៉ាកយីហោ: </strong> {{ $brand->name }} <br>
                        <strong>ការពិពណ៌នា: </strong> {{ $brand->description ?? 'គ្មាន' }} <br>
                    </div>
                    <a href="{{ route('brands.index') }}" class="btn btn-secondary">ត្រឡប់ក្រោយ</a>
                </div>
            </div>
        </div>
    </div>
@endsection
