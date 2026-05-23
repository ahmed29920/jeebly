@extends('dashboard.layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- Profile Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent">
                <h3 class="mb-0 text-info fw-bold">My Profile</h3>
                <small class="text-muted">Manage your account information and password</small>
            </div>
            <div class="card-body">

                {{-- User Info --}}
                <div class="row mb-4 align-items-center">
                    <!-- User Avatar -->
                    <div class="col-auto text-center mb-3 mb-md-0">
                        <div class="position-relative d-inline-block">
                            @if ($user->image)
                                <img src="{{ asset('storage/' . $user->image) }}"
                                    alt="{{ $user->name }}" class="rounded-circle shadow-sm border border-3 border-info"
                                    style="width: 140px; height: 140px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center shadow-sm"
                                    style="width: 140px; height: 140px; font-size: 3rem;">
                                    {{ Str::substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="col-auto">
                        <h4 class="fw-bold text-dark mb-1">{{ $user->name }}</h4>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-user-tag me-2 text-info"></i> {{ ucfirst($user->role) }}
                        </p>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <i class="fas fa-envelope me-2 text-secondary"></i> {{ $user->email }}
                            </div>
                            <div class="col-12 mb-2">
                                <i class="fas fa-phone me-2 text-secondary"></i> {{ $user->phone ?? 'Not provided' }}
                            </div>
                            <div class="col-12">
                                <i class="fas fa-calendar me-2 text-secondary"></i> Joined
                                {{ $user->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                </div>


                {{-- Update Profile --}}
                <hr>
                <h5 class="text-info fw-bold mb-3">Update Profile Information</h5>
                <form action="{{ route('admin.profile.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $user->phone) }}" required>
                            @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"
                            required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Profile Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Max size: 2MB (JPEG, PNG, JPG, GIF)</small>
                        @error('image')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-info w-100">Update Profile</button>
                </form>

                {{-- Change Password --}}
                <hr class="my-4">
                <h5 class="text-info fw-bold mb-3">Change Password</h5>
                <form action="{{ route('admin.profile.password.update') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                        @error('current_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" required>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Change Password</button>
                </form>
            </div>

            <div class="card-footer text-center bg-transparent">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-link text-info fw-bold">← Back to Dashboard</a>
            </div>
        </div>
    </div>
@endsection
