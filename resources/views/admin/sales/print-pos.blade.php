<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ URL::to('fonts/battambang.css') }}">
    <style>
        body {
            font-family: Battambang, sans-serif;
            font-size: 10px;
        }

        .receipt {
            width: 80mm;
            margin: 0 auto;
            padding: 5mm;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .receipt-header,
        .receipt-footer {
            text-align: center;
        }

        .receipt-header h2,
        .receipt-header h4,
        .receipt-header h5 {
            margin: 0;
            line-height: 1.2;
        }

        .receipt-body {
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .separator {
            margin: 5px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h2>ហាងលក់ទូរស័ព្ទដៃ ឡេង ស៊ីណេត</h2>
            <h4>វិក្កយប័ត្រ / Commercial Invoice</h4>
            <h5>អាសយដ្ឋាន: ភូមិគោករកា ឃុំតាបែន​ ស្រុកស្វាយចេក ខេត្តបន្ទាយមានជ័យ</h5>
        </div>
        <div class="receipt-body">
            <table>
                <tbody>
                    <tr>
                        <td>លេខរៀងវិក្កយបត្រ: #{{ $sales->reference }}</td>
                        <td class="text-right">អ្នកគិតលុយ: {{ $sales->user->name }}</td>
                    </tr>
                    <tr>
                        <td>កាលបរិច្ឆេទ: {{ date('d-m-Y') }}</td>
                        <td class="text-right">អតិថិជន: {{ $sales->customer->name }}</td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <table>
                <thead>
                    <tr>
                        <th>ល.រ</th>
                        <th>បរិយាយមុខទំនិញ</th>
                        <th>ថ្លៃឯកត្តា</th>
                        <th>បរិមាណ</th>
                        <th>ថ្លៃទំនិញ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales->saleDetails as $index => $saleDetail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $saleDetail->product->name }}</td>
                            <td class="text-right">{{ number_format($saleDetail->unit_price, 2) }} $</td>
                            <td class="text-right">{{ $saleDetail->quantity }}</td>
                            <td class="text-right">{{ number_format($saleDetail->quantity * $saleDetail->unit_price, 2) }} $</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <hr>
            <table>
                <tbody>
                    <tr>
                        <td>តម្លៃសរុប:</td>
                        <td class="text-right">{{ number_format($sales->total_amount, 2) }} $</td>
                    </tr>
                    <tr>
                        <td>បញ្ចុះតម្លៃ:</td>
                        <td class="text-right">{{ number_format($sales->discount, 2) }} $</td>
                    </tr>
                    <tr>
                        <td>ទឹកប្រាក់សរុប:</td>
                        <td class="text-right">{{ number_format($sales->total_amount - $sales->discount, 2) }} $</td>
                    </tr>
                    <tr>
                        <td>ទឹកប្រាក់បានបង់:</td>
                        <td class="text-right">{{ number_format($sales->paid_amount, 2) }} $</td>
                    </tr>
                    <tr>
                        <td>ទឹកប្រាក់នៅខ្វះ:</td>
                        <td class="text-right">{{ number_format($sales->due_amount, 2) }} $</td>
                    </tr>
                </tbody>
            </table>
            <div class="separator">....................................................................................</div>
        </div>

        <div class="receipt-footer">
            <p>សូមអរគុណសម្រាប់ការទិញទំនិញ!</p>
            <p>Thank you for shopping with us!</p>
        </div>
    </div>
    <script>
        window.onload = function() {
            window.print();
        };

        window.onafterprint = function() {
            window.location.href = "{{ url()->previous() }}";
        };

        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
            body.scrollHeight, body.offsetHeight,
            html.clientHeight, html.scrollHeight, html.offsetHeight
        );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight=" + ((height + 50) * 0.264583);
    </script>
</body>
</html>
