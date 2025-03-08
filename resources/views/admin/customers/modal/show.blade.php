@foreach ($brands as $brand)
    <!-- Include the modal code here -->
    <div class="modal fade" id="ModalShow{{ $brand->id }}" tabindex="-1" aria-labelledby="ModalShowModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalShowModalLabel">ព័ត៌មានលម្អិតអំពី: {{ $brand->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" >
                        <tbody>
                            <tr style="border: none">
                                <th scope="row" >ឈ្មោះអតិថិជន:</th>
                                <td >{{ $brand->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">ការពិពណ៌នា:</th>
                                <td>{{ $brand->description ?? 'No description available' }}</td>
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
