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
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">ទំព័រដើម</a></li>
                                <li class="breadcrumb-item active">បញ្ជីផលិតផល</li>
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
                                <div class="row align-items-center">
                                    <div class="col mb-2">
                                        <h3 class="text-primary font-weight-600">បញ្ជីផលិតផល</h3>
                                        {{-- <div class="col-auto text-end float-end ms-auto download-grp">
                                            @can('ទាញយកទិន្នន័យផលិតផល')
                                                <a href="{{ route('export-products-excel') }}"
                                                    class="btn btn-outline-primary me-2"><i class="fas fa-download"></i>
                                                    ទាញយកទិន្នន័យ</a>
                                            @endcan

                                            @can('បង្កើតផលិតផល')
                                                <a href="{{ route('products.create') }}" class="btn btn-primary">បន្ថែម <i
                                                        class="fas fa-plus"></i></a>
                                            @endcan

                                        </div> --}}
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            @can('ទាញយកទិន្នន័យផលិតផល')
                                                @if ($products->count() > 0)
                                                    <a href="{{ route('export-products-excel') }}"
                                                       class="btn btn-outline-primary me-2">
                                                       <i class="fas fa-download"></i> ទាញយកទិន្នន័យ
                                                    </a>
                                                @else
                                                    <button class="btn btn-outline-secondary me-2" disabled>
                                                        <i class="fas fa-download"></i> ទាញយកទិន្នន័យ
                                                    </button>
                                                @endif
                                            @endcan
                                        
                                            @can('បង្កើតផលិតផល')
                                                <a href="{{ route('products.create') }}" class="btn btn-primary">
                                                    បន្ថែម <i class="fas fa-plus"></i>
                                                </a>
                                            @endcan
                                        </div>
                                        
                                    </div>
                                </div>
                                <div id="loading-spinner" class="text-center" style="display: none;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="productTable"
                                        class="datatable table-hover table-center mb-0 table table-stripped">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <label class="form-check-label">
                                                        <input id="checkbox1" class="form-check-input" type="checkbox"
                                                            value="something" title="Check this option for something">

                                                    </label>
                                                </th>
                                                <th>រូបភាពផលិតផល</th>
                                                <th>ឈ្មោះផលិតផល</th>
                                                <th>លេខសម្គាល់</th>
                                                <th>តម្លៃទិញចូល</th>
                                                <th>តម្លៃលក់ចេញ</th>
                                                <th>បរិមាណក្នុងស្តុក</th>
                                                <th>ម៉ាកយីហោ</th>
                                                <th>ប្រភេទផលិតផល</th>
                                                <th>លក្ខណៈ</th>
                                                {{-- <th>ស្ថានភាព</th> --}}
                                                <th class="text-end">សកម្មភាព</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($stocks as $stock)
                                                <tr>
                                                    <td>
                                                        <div class="form-check check-tables">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="something">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <img src="{{ asset('storage/' . $stock->product->image) }}"
                                                            style="width: 60px; height:60px;" alt="Image"
                                                            class="img-fluid" />
                                                    </td>
                                                    {{-- <img src="default.png" alt="Default Image"> --}}
                                                    <td>{{ $stock->product->name }}</td>
                                                    <td>{{ $stock->product->code }}</td>
                                                    <td>{{ number_format($stock->product->cost_price, 0, '.', ',') }}$</td>
                                                    <td>{{ number_format($stock->product->selling_price, 0, '.', ',') }}$
                                                    </td>
                                                    <td>{{ $stock->current }}</td>
                                                    <td>{{ $stock->product->category->name ?? 'N/A' }} </td>
                                                    <td>{{ $stock->product->brand->name ?? 'N/A' }}</td>
                                                    <td>{{ $stock->product->condition }}</td>
                                                    {{-- <td>{{ $stock->product->status == 1 ? 'សកម្ម' : 'អសកម្ម' }}</td> --}}
                                                    <td class="text-center">
                                                        <div class=" dropdown-action">
                                                            <a href="#" class="action-icon dropdown-toggle"
                                                                data-bs-toggle="dropdown" aria-expanded="true">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end"
                                                                data-popper-placement="bottom-end">
                                                                <!-- View Sale -->
                                                                <a class="dropdown-item text-primary"
                                                                    href="{{ route('products.show', $stock->product) }}">
                                                                    <i class="bi bi-eye me-2"></i> ពិនិត្យ
                                                                </a>

                                                                <!-- Edit Sale -->
                                                                @can('កែប្រែផលិតផល')
                                                                    <a class="dropdown-item text-warning "
                                                                        href="{{ route('products.edit', $stock->product) }}">
                                                                        <i class="bi bi-pencil-square me-2"></i> កែរប្រែ
                                                                    </a>
                                                                @endcan

                                                                <!-- Delete Sale (only if completed) -->
                                                                @can('លុបផលិតផល')
                                                                    <form
                                                                        action="{{ route('products.destroy', $stock->product->id) }}"
                                                                        method="POST"
                                                                        id="deleteForm{{ $stock->product->id }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button" class="dropdown-item text-danger"
                                                                            onclick="confirmDelete({{ $stock->product->id }})">
                                                                            <i class="bi bi-trash3 me-2"></i> លុប
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                            </div>
                                                        </div>
                                                    </td>

                                                </tr>
                                            @endforeach
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
    <script>
        function confirmDelete(postId) {
            Swal.fire({
                title: 'តើអ្នកប្រាកដជាចង់លុបមែនទេ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: "#3d5ee1",
                cancelButtonColor: "#d33",
                confirmButtonText: 'បាទ, ខ្ញុំប្រាកដហើយ!',
                cancelButtonText: 'បោះបង់'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "បានលុប!",
                        text: "ទិន្នន័យរបស់អ្នកត្រូវបានលុប។",
                        icon: "success",
                        timer: 1400,
                        showConfirmButton: false
                    });
                    setTimeout(function() {
                        document.getElementById('deleteForm' + postId).submit();
                    }, 1400);
                }
            });
        }
    </script>
@endsection
