@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">បញ្ជីប្រភេទផលិតផល</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                                <li class="breadcrumb-item active">បញ្ជីប្រភេទផលិតផល</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="page-header">
                                {{-- <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">បញ្ជីប្រភេទផលិតផល</h3>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            <a href="{{ route('export-products-excel') }}" class="btn btn-outline-primary me-2"><i
                                                    class="fas fa-download"></i> ទាញយកទិន្នន័យ</a>
                                            <button class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#createproducts">បន្ថែម <i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="table-responsive">
                                    <table  id="productTable" class="datatable table-hover table-center mb-0 table table-stripped">
                                        <thead>
                                        </thead>
                                        <tbody>
                                        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>