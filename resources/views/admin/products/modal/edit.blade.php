<!-- Edit Modal -->
<div class="modal fade" id="ModalEdit{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">កែប្រែផលិតផល</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="barcode" class="form-label">បាកូដ</label>
                                <input type="text" name="barcode" id="barcode" class="form-control" value="{{ $product->barcode }}" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">ឈ្មោះផលិតផល</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ $product->name }}" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">    
                            <div class="mb-3">
                                <label for="cost_price" class="form-label">ថ្លៃដើម</label>
                                <input type="number" name="cost_price" id="cost_price" class="form-control" value="{{ $product->cost_price }}" required step="0.01">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="selling_price" class="form-label">តម្លៃលក់</label>
                                <input type="number" name="selling_price" id="selling_price" class="form-control" value="{{ $product->selling_price }}" required step="0.01">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select name="category_id" id="category_id" class="form-select" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="brand_id" class="form-label">Brand</label>
                                <select name="brand_id" id="brand_id" class="form-select" required>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>  
                        <div class="col-12 col-sm-4">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">បរិមាណក្នុងស្តុក</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $product->quantity }}" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="mb-3">
                                <label for="stock_alert" class="form-label">ការជូនដំណឹងស្តុក</label>
                                <input type="number" name="stock_alert" id="stock_alert" class="form-control" value="{{ $product->stock_alert }}" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">ស្ថានភាព <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select form-select-lg mb-3 fs-6" required>
                                    <option value="ថ្មីប្រអប់" {{ $product->status == 'ថ្មីប្រអប់' ? 'selected' : '' }}>ថ្មីប្រអប់</option>
                                    <option value="មួយទឹក" {{ $product->status == 'មួយទឹក' ? 'selected' : '' }}>មួយទឹក</option>
                                    <option value="សម្រាប់ហ្វ្រីជូន" {{ $product->status == 'សម្រាប់ហ្វ្រីជូន' ? 'selected' : '' }}>សម្រាប់ហ្វ្រីជូន</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="status" class="form-label">រូបភាពផលិតផល</label>
                            <div class="mb-3">
                                
                                <div class="custom-file-upload">
                                    <label for="productImageEdit{{ $product->id }}" class="custom-label">
                                        <img id="imagePreviewEdit{{ $product->id }}" src="{{ asset($product->image) }}" alt="Image Preview" style="width: 100px; height: 100px; display: block; margin-top: 5px;" />
                                    </label>
                                    <input type="file" class="form-control" id="productImageEdit{{ $product->id }}" name="image" accept="image/*" onchange="previewImageForEdit(event, {{ $product->id }})">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">ការពិពណ៌នា</label>
                                <textarea name="description" id="description" class="form-control">{{ $product->description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បិទ</button>
                    <button type="submit" class="btn btn-primary">កែរប្រែ</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function previewImageForEdit(event, productId) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('imagePreviewEdit' + productId);
            output.src = reader.result;
            output.style.display = 'block';
        };
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
