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
                        <label for="name">ឈ្មោះអតិថិជន<span class="login-danger">*</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="" placeholder="បញ្ចូលឈ្មោះអតិថិជន" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    {{-- <div class="form-group">
                        <label for="phone">លេខទូរស័ព្ទ<span class="login-danger">*</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                            name="phone" value="" placeholder="បញ្ចូលលេខទូរស័ព្ទ" required>
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div> --}}
                    <div class="form-group">
                        <label for="phone">លេខទូរស័ព្ទ<span class="login-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               placeholder="បញ្ចូលលេខទូរស័ព្ទ" 
                               required 
                               pattern="^[0-9]{9,10}$" 
                               inputmode="numeric"
                               maxlength="10"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="address">អាសយដ្ឋាន<span class="login-danger">*</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address"
                            name="address" value="" placeholder="បញ្ចូលអាសយដ្ឋាន" required>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                        id="cancelButton">បោះបង់</button>
                    <button type="submit" class="btn btn-primary">បន្ថែមអតិថិជន</button>
                </div>
            </form>
        </div>
    </div>
</div>
