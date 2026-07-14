<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Forgot Password - Student</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=ADLaM+Display&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alatsi&amp;display=swap">
    <link rel="stylesheet" href="{{ asset('css/forgot_pwd.css') }}">
</head>

<body class="page">
    <div class="d-none d-md-block bg-shape one"></div>
    <div class="d-none d-md-block bg-shape two"></div>

    <main class="position-relative d-flex min-vh-100 justify-content-center align-items-center px-3">
        <div class="card-wrap">
            <div class="card login-card">
                <div class="card-body">
                    <h2 class="fw-bold text-center mb-3">Forgot Password</h2>

                    <form id="forgotPasswordForm" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="position-relative mb-4">
                            <label class="form-label text-muted small" for="email">Email</label>
                            <span class="email-icon" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 24 24" width="1em" fill="currentColor">
                                    <path d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"></path>
                                </svg>
                            </span>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Confirm your email" >

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            <small class="invalid-feedback">Please enter a valid email.</small>

                            <!-- Show this label when backend returns email-not-found error. -->
                            <small id="emailNotExist" class="email-not-exist d-none">Email does not exist.</small>
                        </div>

                        <button class="btn fw-semibold w-100 btn-gradient text-white" type="submit">Send Reset Password Link</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="assets/js/forgot_pwd.js"></script>
</body>

</html>
