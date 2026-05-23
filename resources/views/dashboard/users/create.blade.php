@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h6>Add User</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>User Type</label>
                        <select name="role" id="user_type" class="form-control" required>
                            <option value="">Select User Type</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="mb-3" id="role_container" style="display: none;">
                        <label>User Role</label>
                        <select name="assigned_role" class="form-control">
                            <option value="">Select Role</option>
                            @foreach ($roles as $role)
                            @if ($role->name !== 'user')
                                <option value="{{ $role->name }}">{{ strtoupper($role->name) }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>


                    <div class="mb-3">
                        <label>Profile Image</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Create User</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userType = document.getElementById('user_type');
        const roleContainer = document.getElementById('role_container');

        function toggleRoleSelect() {
            if (userType.value === 'admin') {
                roleContainer.style.display = 'block';
            } else {
                roleContainer.style.display = 'none';
            }
        }

        toggleRoleSelect();

        userType.addEventListener('change', toggleRoleSelect);
    });
</script>
@endpush
