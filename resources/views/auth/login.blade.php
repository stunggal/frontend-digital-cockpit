@extends('layouts.master-without-nav')
@section('title')
    Login
@endsection
@section('content')
    {{--
        Login view
        - Displays a login form that posts to `auth.loginPost` route.
        - Uses `@csrf` for protection against CSRF attacks.
        - Shows validation errors using the `@error` directive next to inputs.
        - NOTE: The template contains demo default values for email/password
          (`admin@dcfh.com` / `12345678`) for local development; remove them
          before deploying to production.
    --}}
    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="index" class="d-inline-block auth-logo">
                                    <img src="{{ URL::asset('build/images/logo-light.png') }}" alt=""
                                        height="20">
                                </a>
                            </div>
                            <p class="mt-3 fs-15 fw-medium">Digital Cockpit for Hospital</p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Welcome Back !</h5>
                                    <p class="text-muted">Sign in to continue to DCH.</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form action="{{ route('auth.loginPost') }}" method="POST">
                                        {{-- CSRF token required for POST forms in Laravel --}}
                                        @csrf
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username <span
                                                    class="text-danger">*</span></label>
                                            {{--
                                                Username/email input:
                                                - `old('email')` repopulates the input after validation failure
                                                - `is-invalid` class is toggled when there's a validation error
                                            --}}
                                            <input type="text" class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', 'admin@dcfh.com') }}" id="username" name="email"
                                                placeholder="Enter username">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <div class="float-end">
                                                <a href="{{ route('auth.passwordUpdate') }}" class="text-muted">Forgot
                                                    password?</a>
                                            </div>
                                            <label class="form-label" for="password-input">Password <span
                                                    class="text-danger">*</span></label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                {{--
                                                                                                        Password input:
                                                                                                        - For demo this view pre-fills `12345678`. Remove the
                                                                                                            `value` attribute to avoid leaking credentials.
                                                                                                        - The toggle button with id `password-addon` shows/hides
                                                                                                            the password using front-end JS (`password-addon.init.js`).
                                                                                                --}}
                                                <input type="password"
                                                    class="form-control pe-5 password-input @error('password') is-invalid @enderror"
                                                    name="password" placeholder="Enter password" id="password-input"
                                                    value="12345678">
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Submit button: posts the form to the auth API via controller --}}
                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">Sign In</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        {{-- <div class="mt-4 text-center">
                            <p class="mb-0">Don't have an account ? <a href="register"
                                    class="fw-semibold text-primary text-decoration-underline"> Signup </a> </p>
                        </div> --}}

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> Stunggal. Crafted with <i class="mdi mdi-heart text-danger"></i> by
                                Stunggal
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/password-addon.init.js') }}"></script>
@endsection
