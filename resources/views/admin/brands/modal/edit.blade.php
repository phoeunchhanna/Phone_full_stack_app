<div class="modal fade" id="ModalEdit{{ $brand->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">កែប្រែ ម៉ាកយីហោ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('brands.update', $brand->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">ឈ្មោះ ម៉ាកយីហោ<span class="login-danger">*</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ $brand->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description">ពិពណ៌នា</label>
                        <textarea class="form-control" id="description" name="description" required>{{ $brand->description }}</textarea>
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
