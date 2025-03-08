 <!-- Modal for Product Details -->
 @foreach ($products as $product)
 <div class="modal fade" id="ModalShow{{ $product->id }}" tabindex="-1" aria-labelledby="ModalShowModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalShowModalLabel">ព័ត៌មានលម្អិតអំពី: {{ $product->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr style="border: none">
                            <th scope="row">រូបភាព:</th>
                            <td><img id="imagePreviewEdit{{ $product->id }}" src="{{ asset($product->image) }}" alt="Image Preview" style="width: 100px; height: 100px; display: block; margin-top: 5px;" /></td>
                        </tr>
                        <tr style="border: none">
                            <th scope="row">ឈ្មោះផលិតផល:</th>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <th scope="row">លេខកូដ:</th>
                            <td>{{ $product->barcode }}</td>
                        </tr>
                        <tr>
                            <th scope="row">តម្លៃទិញ:</th>
                            <td>{{ $product->cost_price }}</td>
                        </tr>
                        <tr>
                            <th scope="row">តម្លៃលក់:</th>
                            <td>{{ $product->selling_price }}</td>
                        </tr>
                        <tr>
                            <th scope="row">ចំនួន:</th>
                            <td>{{ $product->quantity }}</td>
                        </tr>
                        <tr>
                            <th scope="row">ស្ថានភាព:</th>
                            <td>{{ $product->status }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Brand:</th>
                            <td>{{ $product->brand->name ?? 'No brand' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">category:</th>
                            <td>{{ $product->category->name ?? 'No brand' }}</td>
                        </tr>
                        <tr>
                            <th scope="row">ការពិពណ៌នា:</th>
                            <td>{{ $product->description ?? 'No description available' }}</td>
                        </tr>
                        <!-- Add more rows for other fields if needed -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">បោះបង់</button>
            </div>
        </div>
    </div>
 </div>
 @endforeach