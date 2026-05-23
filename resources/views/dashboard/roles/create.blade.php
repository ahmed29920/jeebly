@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header">
            <h6>Create Role</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Permissions</label>
                    <div class="row">
                        @foreach($permissions as $permission)
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}">
                                <label class="form-check-label">{{ $permission->name }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <button class="btn btn-success">Create</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
