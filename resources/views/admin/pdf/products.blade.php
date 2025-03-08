<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products PDF Export</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ URL::to('fonts/battambang.css') }}">

    <style>
        /* Custom styles for PDF export */
        body {
            font-family: 'battambang', sans-serif; /* Replace with desired Khmer font */
        }
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Product List</h2>
    <div class="container">
        <table  id="productTable" class="datatable table-hover table-center mb-0 table table-stripped">
            <thead>
                <tr>
                    <th>
                        <div class="form-check check-tables">
                            <input class="form-check-input" type="checkbox" value="something">
                        </div>
                    </th>
                    <th>រូបភាពផលិតផល</th>
                    <th>ឈ្មោះផលិតផល</th>
                    <th>លេខសម្គាល់</th>
                    {{-- <th>ការពណ៌នា</th> --}}
                    <th>តម្លៃទិញចូល</th>
                    <th>តម្លៃលក់ចេញ</th>
                    <th>ឯកតា</th>
                    <th>ម៉ាកយីហោ</th>
                    <th>ប្រភេទផលិតផល</th>
                    {{-- <th>ជូនដំណឹងពីបរិមាណ</th> --}}
                    <th>ស្ថានភាព</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>
                            <div class="form-check check-tables">
                                <input class="form-check-input" type="checkbox"
                                    value="something">
                            </div>
                        </td>

                        <td>
                            <img src="{{ asset($product->image) }}"
                                style="width: 60px; height:60px;" alt="Img" />
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->barcode }}</td>
                        <td>{{ number_format($product->cost_price, 0, '.', ',') }}$</td>
                        <td>{{ number_format($product->selling_price, 0, '.', ',') }}$</td>
                        <td>{{ $product->quantity }} | គ្រឿង</td>
                        <td>{{ $product->category->name ?? 'N/A' }} </td>
                        <td>{{ $product->brand->name ?? 'N/A' }}</td>
                        <td>{{ $product->status }}</td>
                        
                    </tr>
                    @include('admin.products.modal.edit')
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
