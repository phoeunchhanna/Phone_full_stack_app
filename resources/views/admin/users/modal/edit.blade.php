<!-- Edit Modal -->
<div class="modal fade" id="ModalEdit{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" method="POST" action="{{ route('user-management.update', '') }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit-user-id" name="id" value="">
                    <div class="form-group">
                        <label for="edit-name">Name<span class="login-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="edit-name" name="name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="edit-email">Email<span class="login-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="edit-email" name="email" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="edit-password">Password (leave blank to keep current password)<span class="login-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="edit-password" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="edit-avatar">Avatar (optional)</label>
                        <input type="text" class="form-control @error('avatar') is-invalid @enderror" id="edit-avatar" name="avatar">
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="edit-status">Status<span class="login-danger">*</span></label>
                        <select class="form-control @error('status') is-invalid @enderror" id="edit-status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
