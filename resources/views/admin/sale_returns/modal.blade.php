<div class="modal fade" id="returnItemModal" tabindex="-1" aria-labelledby="returnItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnItemModalLabel">បង្វិលទំនិញ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('sale_returns.getSaleDetails') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="sale_reference">លេខយោង ការលក់ <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="sale_reference" id="sale_reference" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បិទ</button>
                        <button type="submit" class="btn btn-primary">បន្ត</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>