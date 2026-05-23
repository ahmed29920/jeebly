@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6>{{ __('Users') }}</h6>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">Add User</a>
        </div>

        <div class="card-body">
            <table id="users-table" class="table">
                <thead>
                    <tr>
                        <th class="text-center"><input type="checkbox" id="select-all"></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Points</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" name="ids[]" value="{{ $user->id }}">
                        </td>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->getRoleNames()->first() }}</td>
                        <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>{{ $user->points }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#users-table').DataTable();
    $('#select-all').on('click', function() {
        $('input[name="ids[]"]').prop('checked', this.checked);
    });
});
</script>
@endpush
