@extends('layouts.app')

@section('title', 'Admin Panel | Login')

@section('content')
    <div class="card">
        <div class="p-4 p-sm-5">
            <!-- Logo -->
            <div class="d-flex justify-content-center align-items-center mb-2">
                <div class="">
                    <div class="w-100 position-relative">
                        <h2>{{ config('app.name') }}</h2>
                    </div>
                </div>
            </div>
            <!-- / Logo -->

            <h5 class="text-center text-muted font-weight-normal mb-4">Login to Your Account</h5>

            <!-- Form -->
            <form action="{{ route('login') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="form-label">Email</label>
                    <input type="text" name="email" class="form-control" required tabindex="1">
                    <span class="help-block">
                        {{ $errors->first('email') }}
                    </span>
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="form-label d-flex justify-content-between align-items-end">
                        <div>Password</div>
                        <a href="{{ route('password.request') }}" class="d-block small">Forgot password?</a>
                    </label>
                    <input type="password" class="form-control" name="password" required tabindex="2">
                    <span class="help-block">
                        {{ $errors->first('email') }}
                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center m-0">
                    <label class="custom-control custom-checkbox m-0">
                        <input type="checkbox" class="custom-control-input" name="remember">
                        <span class="custom-control-label">Remember me</span>
                    </label>
                    <button type="submit" class="btn btn-primary">Sign In</button>
                </div>
            </form>
            <!-- / Form -->

        </div>
        <div class="card-footer py-3 px-4 px-sm-5">
            <div class="text-center text-muted">
                Don't have an account yet? <a href="{{ route('register') }}">Sign Up</a>
            </div>
        </div>
    </div>
@endsection
