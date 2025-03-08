<div class="modal fade" id="createsuppliers" tabindex="-1" aria-labelledby="createsuppliers" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createsuppliersLabel">បង្កើតអ្នកផ្គត់ផ្គង់</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">ឈ្មោះអ្នកផ្គត់ផ្គង់<span class="login-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="address">អាសយដ្ឋាន<span class="login-danger">*</span></label>
                        <input type="text" name="address" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">លេខទូរស័ព្ទ<span class="login-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-primary">រក្សារទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>
