@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header">
            <h6>Edit Permission</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Permission Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $permission->name) }}" required>
                </div>

                <button class="btn btn-success">Update</button>
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
