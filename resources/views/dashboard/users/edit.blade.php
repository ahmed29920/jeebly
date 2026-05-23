@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h6>Edit User</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" value="{{ $user->phone }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>User Type</label>
                        <select name="user_type" id="user_type" class="form-control" required>
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div class="mb-3" id="role_container" style="display: none;">
                        <label>User Role</label>
                        <select name="role" class="form-control">
                            <option value="">Select Role</option>
                            @foreach ($roles as $role)
                                @if ($role->name !== 'user')
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ strtoupper($role->name) }}
                                    </option>
                                @endif
                            @endforeach

                        </select>
                    </div>


                    <div class="mb-3">
                        <label>Profile Image</label>
                        <input type="file" name="image" class="form-control">
                        @if ($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}" alt="Profile" class="img-thumbnail mt-2"
                                width="80">
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Update User</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
