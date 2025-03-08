<div class="modal fade" id="createbrands" tabindex="-1" aria-labelledby="createbrandsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createbrandsLabel">បន្ថែម ម៉ាកយីហោ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('brands.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">ឈ្មោះម៉ាកយីហោ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="បញ្ចូលឈ្មោះរបស់អ្នក" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label for="description">ពណ៌នា</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
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
