@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="modal fade" id="createproducts" tabindex="-1" aria-labelledby="createproducts" aria-hidden="true" data-bs-target="#myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createproductsLabel">បន្ថែមផលិតផល</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">ឈ្មោះផលិតផល:<span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="barcode" class="form-label">បាកូដ:</span></label>
                            <input type="text" name="barcode" id="barcode" class="form-control" min="0">
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="cost_price" class="form-label">ថ្លៃដើម<span class="text-danger">*</span></label>
                                <input type="number" name="cost_price" id="cost_price" class="form-control" required
                                    step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="selling_price" class="form-label">តម្លៃលក់ចេញ<span class="text-danger">*</span></label>
                                <input type="number" name="selling_price" id="selling_price" class="form-control"
                                    required step="0.01">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">ប្រភេទទំនិញ <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-select form-select-lg mb-3 fs-6" required>
                                    <option value="">----ជ្រើសរើសប្រភេទទំនិញ----</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="brand_id" class="form-label">ម៉ាកយីហោ <span class="text-danger">*</span></label>
                                <select name="brand_id" id="brand_id" class="form-select form-select-lg mb-3 fs-6" required>
                                    <option value="">----ជ្រើសរើសម៉ាកយីហោ----</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">បរិមាណក្នុងស្តុក<span class="text-danger">*</span></label>
                                <input type="number" name="quantity" id="quantity" class="form-control" min="0" value="0" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="mb-3">
                                <label for="stock_alert" class="form-label">ការជូនដំណឹងស្តុក<span class="text-danger">*</span></label>
                                <input type="number" name="stock_alert" id="stock_alert" class="form-control" value="0" required>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">ស្ថានភាព <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select form-select-lg mb-3 fs-6" required>
                                    <option value="">ជ្រើសរើសស្ថានភាពទូរស័ព្ទ</option>
                                    <option value="ថ្មី">ថ្មីប្រអប់</option>
                                    <option value="មួយទឹក">មួយទឹក</option>
                                    <option value="សម្រាប់ហ្វ្រីជូន">សម្រាប់ហ្វ្រីជូន</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-3 ">
                                <label for="status" class="form-label">រូបភាពផលិតផល</label>
                                <div class="custom-file-upload">
                                    <label for="productImageCreate" class="custom-label">
                                        <img id="imagePreviewCreate" src="{{ asset('assets/img/defaults/image.png') }}" alt="Image Preview" style="width: 100px; height: 100px; display: block; margin-top: 5px;" />
                                    </label>
                                    <input type="file" class="form-control" id="productImageCreate" name="image" accept="image/*" onchange="previewImageForCreate(event)">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="mb-3">
                                <label for="description" class="form-label">ការពិពណ៌នា</label>
                                <textarea name="description" id="description" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
                    <button type="submit" class="btn btn-primary">បន្ថែមផលិតផល</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImageForCreate(event) {
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function (e) {
            const output = document.getElementById('imagePreviewCreate');
            output.src = e.target.result;
            output.style.display = 'block'; // Show the image preview
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            // Reset to default image if no file is selected
            const output = document.getElementById('imagePreviewCreate');
            output.src = '{{ asset('assets/img/defaults/image.png') }}'; // Ensure the default image path is correct
        }
    }
</script>



