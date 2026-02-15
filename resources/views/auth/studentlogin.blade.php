<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Learning Portal - Student</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=ADLaM+Display&amp;display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alatsi&amp;display=swap">
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/login_input.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body class="page">
    <div class="d-none d-md-block bg-shape one"></div>
    <div class="d-none d-md-block bg-shape two"></div>
    <main class="position-relative d-flex min-vh-100 justify-content-center align-items-center px-3">
        <div class="card-wrap">
            <div class="card login-card">
                <div class="card-body">
                    <h2 class="fw-bold text-center mb-4">Login</h2>
                    <form id="loginForm" novalidate="" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="position-relative mb-4">
                            <label class="form-label text-muted form-label small">Email</label><span style="margin: 0px 5px;"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 24 24" width="1em" fill="currentColor">
                                    <path d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"></path>
                                </svg></span>
                                <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" placeholder="Type your email" autocomplete="&quot;email&quot;" required="" name="email" value="{{ old('email') }}">
                                {{-- <small class="invalid-feedback">Please enter a valid email.</small> --}}
                                @error('email')
                                    {{-- <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span> --}}
                                    <small class="invalid-feedback">Please enter a valid email.</small>
                                @enderror
                            </div>
                        <div class="position-relative mb-2">
                            <label class="form-label text-muted small">Password</label><span style="margin: 0px 5px;"><svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="1em" viewBox="0 0 24 24" width="1em" fill="currentColor" style="font-size: 17px;">
                                    <g>
                                        <path d="M0,0h24v24H0V0z" fill="none"></path>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M2,17h20v2H2V17z M3.15,12.95L4,11.47l0.85,1.48l1.3-0.75L5.3,10.72H7v-1.5H5.3l0.85-1.47L4.85,7L4,8.47L3.15,7l-1.3,0.75 L2.7,9.22H1v1.5h1.7L1.85,12.2L3.15,12.95z M9.85,12.2l1.3,0.75L12,11.47l0.85,1.48l1.3-0.75l-0.85-1.48H15v-1.5h-1.7l0.85-1.47 L12.85,7L12,8.47L11.15,7l-1.3,0.75l0.85,1.47H9v1.5h1.7L9.85,12.2z M23,9.22h-1.7l0.85-1.47L20.85,7L20,8.47L19.15,7l-1.3,0.75 l0.85,1.47H17v1.5h1.7l-0.85,1.48l1.3,0.75L20,11.47l0.85,1.48l1.3-0.75l-0.85-1.48H23V9.22z"></path>
                                        </g>
                                    </g>
                                </svg></span>
                                <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" id="password" placeholder="Type your password" autocomplete="&quot;current-password&quot;" required="" minlength="6">
                                {{-- <small class="invalid-feedback">Password must be at least 6 characters.</small> --}}
                                @error('password')
                                    {{-- <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span> --}}
                                    <small class="invalid-feedback">Password must be at least 6 characters.</small>
                                @enderror
                            </div>
                        <div class="d-flex justify-content-end mt-2 mb-4"><a class="small-link" href="#">Forgot password?</a></div><button class="btn fw-semibold w-100 btn-gradient text-white" type="submit">Login</button>
                        <div class="text-center mt-4"><small class="text-muted mb-2">Have not account yet?&nbsp;</small><a class="fw-semibold small-link" href="{{ route('register') }}">SIGN UP</a></div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    {{-- <script src="{{ asset('js/login.js') }}"></script> --}}
</body>

</html>