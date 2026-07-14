<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Verification Link Sent - Student</title>
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
                <div class="card-body text-center">
                    <div class="success-icon-wrap" aria-hidden="true">
                        <div class="success-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 2.5a.5.5 0 0 1-.188.39l-9 7.5a.5.5 0 0 1-.624 0l-6-5A.5.5 0 1 1 .812 4.61L6.5 9.35l8.688-7.24A.5.5 0 0 1 16 2.5z"/>
                                <path d="M0 4a2 2 0 0 1 2-2h10.5a.5.5 0 0 1 0 1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5.5a.5.5 0 0 1 1 0V12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4z"/>
                            </svg>
                        </div>
                    </div>

                    @php
                        $mode = $mode ?? 'password_reset';
                        $statusMessage = $status ?? session('status');
                    @endphp

                    <h2 class="fw-bold mb-3">Verification Link Sent</h2>
                    <p class="success-message mb-3">
                        @if ($mode === 'email_verification')
                            {{ $statusMessage ?? 'Please verify your email address to continue.' }}
                        @else
                            {{ $statusMessage ?? 'We have sent a password reset verification link to your email address. Please check your inbox and follow the instructions to reset your password.' }}
                        @endif
                    </p>

                    <div id="sentEmailPreview" class="email-preview mb-4 d-none"></div>

                    @if ($mode === 'email_verification')
                        <form method="POST" action="{{ route('verification.pending.resend') }}" class="mb-2">
                            @csrf
                            <button type="submit" class="btn fw-semibold w-100 btn-gradient text-white">Resend Verification Email</button>
                        </form>
                    @endif

                    <a href="/login" class="btn fw-semibold w-100 btn-gradient text-white">Login</a>
                </div>
            </div>
        </div>
    </main>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script>
        const params = new URLSearchParams(window.location.search);
        const email = @json($email ?? null) || params.get('email');
        const preview = document.getElementById('sentEmailPreview');

        if (email && preview) {
            preview.textContent = email;
            preview.classList.remove('d-none');
        }
    </script>
</body>

</html>
