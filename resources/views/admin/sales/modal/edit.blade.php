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
                <div class="modal-content">
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
                                    <label for="cost_price" class="form-label">តម្លៃដើម</label>
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
                                    <label for="quantity" class="form-label">បរិមាណក្នុងស្តុក</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $product->quantity }}" required>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="stock_alert" class="form-label">ការជូនដំណឹងស្តុក</label>
                                    <input type="number" name="stock_alert" id="stock_alert" class="form-control" value="{{ $product->stock_alert }}" required>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label>បញ្ចូលឯកសារ/រូបភាព</label>
                                    <input type="file" name="image" class="form-control" />
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">ស្ថានភាព</label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>សកម្ម</option>
                                        <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>អសកម្ម</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">ប្រភេទ</label>
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
                                    <label for="brand_id" class="form-label">ម៉ាក</label>
                                    <select name="brand_id" id="brand_id" class="form-select" required>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>  
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">ពិពណ៌នា</label>
                                    <textarea name="description" id="description" class="form-control">{{ $product->description }}</textarea>
                                </div>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បិទ</button>
                        <button type="submit" class="btn btn-primary">ធ្វើបច្ចុប្បន្នភាព</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
