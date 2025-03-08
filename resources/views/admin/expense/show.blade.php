@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">ព័ត៌មានចំណាយ</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('expenses.index') }}">ចំណាយ</a></li>
                                <li class="breadcrumb-item active">ព័ត៌មានចំណាយ</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card comman-shadow">
                        <div class="card-body">
                            <h4 class="card-title">ព័ត៌មានចំណាយ</h4>
                            <div class="mt-4">
                                <strong>ប្រភេទ: </strong> {{ $expense->category->name }} <br>
                                <strong>ថ្ងៃខែ: </strong> {{ \Carbon\Carbon::parse($expense->date)->format('d-m-Y') }} <br>
                                <strong>កំណត់ត្រា: </strong> {{ $expense->reference }} <br>
                                <strong>ចំនួន: </strong> {{ number_format($expense->amount, 2) }} <br>
                                <strong>ពត៌មានលំអិត: </strong> {{ $expense->details }} <br>
                                <strong>ប្រើប្រាស់នៅថ្ងៃ: </strong> {{ $expense->created_at->format('d-m-Y H:i:s') }} <br>
                                <strong>អផ្ទាំងលើក​កំណត់ត្រា: </strong> {{ $expense->updated_at->format('d-m-Y H:i:s') }} <br>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('expenses.index') }}" class="btn btn-primary">ត្រលប់ក្រោយ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
