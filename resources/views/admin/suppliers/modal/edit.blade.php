<div class="modal fade" id="ModalEdit{{ $supplier->id }}" tabindex="-1" role="dialog" aria-labelledby="supplierModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supplierModalLabel">ព័ត៌មានអ្នកផ្គត់ផ្គង់</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" novalidate>
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">ឈ្មោះ<span class="login-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $supplier->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="address">អាសយដ្ឋាន<span class="login-danger">*</span></label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ $supplier->address }}" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">លេខទូរស័ព្ទ<span class="login-danger">*</span></label>
                        <input type="text" class="form-control" id="phone" name="phone" value="{{ $supplier->phone }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-primary">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>
