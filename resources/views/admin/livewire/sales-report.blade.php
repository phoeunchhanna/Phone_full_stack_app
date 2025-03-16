<div>
    <div class="row mb-3">
        <div class="col-lg-3 col-md-6">
            <label>ជ្រើសរើស ថ្ងៃខែឆ្នាំ</label>
            <input type="text" class="form-control date_range_picker" wire:model="date_range">
        </div>
        <div class="col-lg-3 col-md-6">
            <label>អតិថិជន</label>
            <select class="form-control form-select" wire:model="customer_id">
                <option value="">អតិថិជនទាំងអស់</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-6">
            <label>វិធីសាស្រ្តទូទាត់</label>
            <select class="form-control form-select" wire:model="payment_method">
                <option value="">វិធីសាស្រ្តទាំងអស់</option>
                <option value="សាច់ប្រាក់">សាច់ប្រាក់</option>
                <option value="អេស៊ីលីដា">អេស៊ីលីដា</option>
                <option value="ABA">ABA</option>
            </select>
        </div>
    </div>

    <!-- ✅ Show Loading Indicator When Data is Loading -->
    <div wire:loading wire:target="date_range, customer_id, payment_method, payment_status" class="text-center">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p>កំពុងដំណើរការ...</p>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>កាលបរិច្ឆេទ</th>
                    <th>លេខយោង</th>
                    <th>ឈ្មោះផលិតផល</th>
                    <th>អតិថិជន</th>
                    <th>តម្លៃ</th>
                    <th>បរិមាណ</th>
                    <th>សរុប</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesDetails as $saleDetail)
                    <tr>
                        <td>{{ $saleDetail->sale->date }}</td>
                        <td>{{ $saleDetail->sale->reference }}</td>
                        <td>{{ $saleDetail->product->name }}</td>
                        <td>{{ $saleDetail->sale->customer->name }}</td>
                        <td>${{ $saleDetail->unit_price }}</td>
                        <td>{{ $saleDetail->quantity }}</td>
                        <td>${{ $saleDetail->total_price }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ✅ Pagination -->
    <div class="d-flex justify-content-center">
        {{ $salesDetails->links() }}
    </div>
</div>
