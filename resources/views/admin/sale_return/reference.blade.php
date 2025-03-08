@extends('layouts.master')
@section('content')
{{-- message --}}
{!! Toastr::message() !!}
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">ការបង្វែចូលទំនិញ</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">ទំព័រដើម</a></li>
                        <li class="breadcrumb-item active">ការបង្វែចូលទំនិញ</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card card-table">
           <div class="card-body">
               <div class="page-header">
                   <div class="row align-items-center">
                       <div class="col">
                           <h3 class="page-title">ការបង្វែចូលទំនិញ</h3>
                       </div>
                       <div class="col-auto text-end float-end ms-auto download-grp">
                       </div>
                   </div>
               </div>
               <div class="row justify-content-center">
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <form action="{{ route('sale_returns.getSaleDetails') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="sale_reference">លេខយោង ការលក់<span class="login-danger">*</label>
                            <input class="form-control" type="text" name="sale_reference" id="sale_reference" required>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">បន្ត</button>
                    </form>
                </div>
            </div>

           </div>
       </div>
    </div>
</div>
@endsection
<script>
    
</script>