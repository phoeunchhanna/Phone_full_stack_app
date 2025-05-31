<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ URL::to('fonts/battambang.css') }}">
    <style>
        @page {
            size: 80mm auto; /* Set width to 80mm for thermal printer */
            margin: 0; /* Remove default margin */
        }

        body {
            font-family: Battambang, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            background-color: white;
        }

        .receipt {
            width: 80mm; /* Standard thermal printer width */
            padding: 5mm;
            margin: 0 auto;
            box-sizing: border-box;
        }

        .receipt-header,
        .receipt-footer {
            text-align: center;
            margin-bottom: 5px;
        }

        .receipt-header h2,
        .receipt-header h4,
        .receipt-header h5 {
            margin: 0;
            line-height: 1.2;
        }

        .receipt-body {
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        th, td {
            padding: 3px;
            text-align: left;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .separator {
            margin: 3px 0;
            text-align: center;
            font-size: 12px;
        }

        /* Avoid page break inside item lists */
        .page-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h2>ហាងលក់ទូរស័ព្ទដៃ ឡេង ស៊ីណេត</h2>
            <h4>វិក្កយប័ត្រ / Commercial Invoice</h4>
            <h5>អាសយដ្ឋាន: ភូមិគោករកា ឃុំតាបែន ស្រុកស្វាយចេក ខេត្តបន្ទាយមានជ័យ</h5>
        </div>

        <div class="receipt-body">
            <table>
                <tbody>
                    <tr>
                        <td>លេខវិក្កយបត្រ: #{{ $sales->reference }}</td>
                        <td class="text-right">អ្នកគិតលុយ: {{ $sales->user->name }}</td>
                    </tr>
                    <tr>
                        <td>កាលបរិច្ឆេទ: {{ date('d-m-Y') }}</td>
                        <td class="text-right">អតិថិជន: {{ $sales->customer->name }}</td>
                    </tr>
                </tbody>
            </table>
            <hr>

            <table class="page-break">
                <thead>
                    <tr>
                        <th>ល.រ</th>
                        <th>បរិយាយ</th>
                        <th class="text-right">ថ្លៃឯកត្តា</th>
                        <th class="text-right">បរិមាណ</th>
                        <th class="text-right">សរុប</th>
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
            <div class="separator">------------------------------------------------</div>
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
