@extends('dashboard.layouts.auth')
@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
            <div class="card card-plain">
                <div class="card-header pb-0 text-start">
                    <h4 class="font-weight-bolder">{{ __('Welcome Back Admin') }}</h4>
                    <p class="mb-0">{{ __('Enter your email and password to sign in') }}</p>
                </div>
                <div class="card-body">
                    <form role="form" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="email" class="form-control form-control-lg" name="email"
                                value="{{ old('email') }}" placeholder="Email" aria-label="Email">
                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control form-control-lg" placeholder="Password"
                                name="password" type="password" aria-label="Password">
                            @error('password')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Sign in</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div
            class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                style="background-image: url('{{ asset('dashboard/img/logo.jpg') }}');background-size: contain;
                background-repeat: no-repeat; background-position: center;">
            </div>
        </div>
    </div>
@endsection
