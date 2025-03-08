<div class="modal fade" id="paymentModal-{{ $saleReturn->id }}" tabindex="-1" aria-labelledby="paymentModalLabel-{{ $saleReturn->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel-{{ $saleReturn->id }}">ព័ត៌មានលម្អិតអំពីការទូទាត់ការត្រឡប់ទំនិញ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('sale_return_payments.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="reference">យោងការលក់</label>
                        <input type="text" class="form-control" name="reference" required readonly value="INV/{{ $saleReturn->reference }}">
                    </div>
                    <div class="form-group">
                        <label for="due_amount">ចំនួនទឹកប្រាក់នៅខ្វះ</label>
                        <input type="number" name="due_amount" id="due_amount-{{ $saleReturn->id }}" class="form-control" step="0.01" value="{{ $saleReturn->due_amount }}" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="amount">ចំនួនប្រាក់ <span class="text-danger">*</span></label>
                        <input type="number" name="amount" id="amount-{{ $saleReturn->id }}" class="form-control" step="0.01" value="{{ old('amount') }}" required placeholder="$" max="{{ $saleReturn->due_amount }}">
                    </div>
                    <div class="form-group">
                        <label for="date">ថ្ងៃខែឆ្នាំ(ខែ/ថ្ងៃទី/ឆ្នាំ)</label>
                        <input type="date" name="date" id="date-{{ $saleReturn->id }}" value="{{ now()->format('Y-m-d') }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="payment_method">វិធីសាស្ត្រទូទាត់</label>
                        <select name="payment_method" class="form-control form-select" required>
                            <option value="សាច់ប្រាក់">សាច់ប្រាក់</option>
                            <option value="អេស៊ីលីដា">អេស៊ីលីដា</option>
                            <option value="ABA">ABA</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="note">កត់សម្គាល់</label>
                        <textarea name="note" id="note-{{ $saleReturn->id }}" class="form-control">{{ old('note') }}</textarea>
                    </div>
                    <input type="hidden" name="sale_id" value="{{ $saleReturn->id }}">
                    <button type="submit" class="btn btn-success">រក្សាទុក</button>
                </form>
            </div>
        </div>
    </div>
</div>
