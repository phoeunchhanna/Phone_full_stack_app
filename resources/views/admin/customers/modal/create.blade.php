<div class="modal fade" id="createcustomers" tabindex="-1" aria-labelledby="createcustomers" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createcustomersLabel">បន្ថែមអតិថិជន</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">ឈ្មោះអតិថិជន<span class="login-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">លេខទូរស័ព្ទ<span class="login-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" value="" required>
                    </div>
                    <div class="form-group">
                        <label for="address">អាសយដ្ឋាន<span class="login-danger">*</span></label>
                        <textarea name="address" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="cancelButton">បោះបង់</button>
                    <button type="submit" class="btn btn-primary">បន្ថែមអតិថិជន</button>
                </div>
            </form>
        </div>
    </div>
</div>
