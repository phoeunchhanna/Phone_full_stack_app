<div class="modal fade" id="CreateUsers" tabindex="-1" aria-labelledby="CreateUsers" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="CreateUsersLabel">Create User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createUserForm" method="POST" action="{{ route('user-management.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="create-name">Name<span class="login-danger">*</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="create-name" name="name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="create-email">Email<span class="login-danger">*</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="create-email" name="email" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="create-password">Password<span class="login-danger">*</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="create-password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="create-avatar">Avatar (optional)</label>
                        <input type="text" class="form-control @error('avatar') is-invalid @enderror" id="create-avatar" name="avatar">
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="create-status">Status<span class="login-danger">*</label>
                        <select class="form-control @error('status') is-invalid @enderror" id="create-status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
