<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Learning Portal - Student</title>
	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/login_input.css') }}">
	<link rel="stylesheet" href="{{ asset('css/login.css') }}">
	<link rel="stylesheet" href="{{ asset('css/reset_pwd.css') }}">

</head>

<body class="page">
    <div class="d-none d-md-block bg-shape one"></div>
    <div class="d-none d-md-block bg-shape two"></div>

    <main class="position-relative d-flex min-vh-100 justify-content-center align-items-center px-3">
        <div class="card-wrap">
            <div class="card login-card">
                <div class="card-body">
                    <h2 class="fw-bold text-center mb-4">Reset Password</h2>

                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="loginForm" method="POST" action="{{ route('student.changePassword') }}" novalidate>
                        @csrf
                        <div class="position-relative mb-5">
							<div class="d-flex align-items-center mb-0 gap-2">
								<label for="currentPassword" class="form-label text-muted small mb-0">Current Password</label>
								<span class="password-label-icon d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="1em" viewBox="0 0 24 24" width="1em" fill="currentColor" style="font-size: 17px;">
                                        <g>
                                            <path d="M0,0h24v24H0V0z" fill="none"></path>
                                        </g>
                                        <g>
                                            <g>
                                                <path d="M2,17h20v2H2V17z M3.15,12.95L4,11.47l0.85,1.48l1.3-0.75L5.3,10.72H7v-1.5H5.3l0.85-1.47L4.85,7L4,8.47L3.15,7l-1.3,0.75L2.7,9.22H1v1.5h1.7L1.85,12.2L3.15,12.95z M9.85,12.2l1.3,0.75L12,11.47l0.85,1.48l1.3-0.75l-0.85-1.48H15v-1.5h-1.7l0.85-1.47L12.85,7L12,8.47L11.15,7l-1.3,0.75l0.85,1.47H9v1.5h1.7L9.85,12.2z M23,9.22h-1.7l0.85-1.47L20.85,7L20,8.47L19.15,7l-1.3,0.75l0.85,1.47H17v1.5h1.7l-0.85,1.48l1.3,0.75L20,11.47l0.85,1.48l1.3-0.75l-0.85-1.48H23V9.22z"></path>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
							</div>
							<input class="form-control" type="password" id="currentPassword" name="current_password" placeholder="Type your password" autocomplete="current-password" required>
							<small class="invalid-feedback">Current password is required.</small>
                        </div>

                        <div class="position-relative mb-2">
                            <div class="d-flex align-items-center mb-0 gap-2">
                                <label for="newPassword" class="form-label text-muted small mb-0">New Password</label>

                                <span class="password-label-icon d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="1em" viewBox="0 0 24 24" width="1em" fill="currentColor" style="font-size: 17px;">
                                        <g>
                                            <path d="M0,0h24v24H0V0z" fill="none"></path>
                                        </g>
                                        <g>
                                            <g>
                                                <path d="M2,17h20v2H2V17z M3.15,12.95L4,11.47l0.85,1.48l1.3-0.75L5.3,10.72H7v-1.5H5.3l0.85-1.47L4.85,7L4,8.47L3.15,7l-1.3,0.75L2.7,9.22H1v1.5h1.7L1.85,12.2L3.15,12.95z M9.85,12.2l1.3,0.75L12,11.47l0.85,1.48l1.3-0.75l-0.85-1.48H15v-1.5h-1.7l0.85-1.47L12.85,7L12,8.47L11.15,7l-1.3,0.75l0.85,1.47H9v1.5h1.7L9.85,12.2z M23,9.22h-1.7l0.85-1.47L20.85,7L20,8.47L19.15,7l-1.3,0.75l0.85,1.47H17v1.5h1.7l-0.85,1.48l1.3,0.75L20,11.47l0.85,1.48l1.3-0.75l-0.85-1.48H23V9.22z"></path>
                                            </g>
                                        </g>
                                    </svg>
                                </span>

                                <button
                                    type="button"
                                    class="btn btn-sm p-0 border-0 bg-transparent text-primary d-flex align-items-center justify-content-center password-info-btn"
                                    data-bs-toggle="popover"
                                    data-bs-trigger="hover focus"
                                    data-bs-placement="right"
                                    data-bs-html="true"
                                    data-bs-content="
                                        <ul class='mb-0 ps-3'>
                                            <li>At least 8 characters</li>
                                            <li>1 uppercase letter</li>
                                            <li>1 lowercase letter</li>
                                            <li>1 number</li>
                                            <li>1 special symbol</li>
                                        </ul>
                                    "
                                    aria-label="Password rules"
                                    title="Password rules">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm0-12.5a1 1 0 1 1 0 2a1 1 0 0 1 0-2zm1 9h-2V7h2v5.5z"/>
                                    </svg>
                                </button>
                            </div>

                            <input
                                class="form-control"
                                type="password"
                                id="newPassword"
                                name="new_password"
                                placeholder="Create a password"
                                autocomplete="new-password"
                                required>

                            <div class="password-warning" id="newPasswordWarning">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zM8 4.25a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0V5A.75.75 0 0 1 8 4.25zm0 7a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                                </svg>
                                <span>Password must meet the requirements below.</span>
                            </div>

                            <ul class="password-rule-list" id="newPasswordRuleList"></ul>
                        </div>

                        <div class="position-relative mt-4 mb-2">
                            <div class="d-flex align-items-center mb-0 gap-2">
                                <label for="confirmPassword" class="form-label text-muted small mb-0">Confirm Password</label>

                                <span class="password-label-icon d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="1em" viewBox="0 0 24 24" width="1em" fill="currentColor" style="font-size: 17px;">
                                        <g>
                                            <path d="M0,0h24v24H0V0z" fill="none"></path>
                                        </g>
                                        <g>
                                            <g>
                                                <path d="M2,17h20v2H2V17z M3.15,12.95L4,11.47l0.85,1.48l1.3-0.75L5.3,10.72H7v-1.5H5.3l0.85-1.47L4.85,7L4,8.47L3.15,7l-1.3,0.75L2.7,9.22H1v1.5h1.7L1.85,12.2L3.15,12.95z M9.85,12.2l1.3,0.75L12,11.47l0.85,1.48l1.3-0.75l-0.85-1.48H15v-1.5h-1.7l0.85-1.47L12.85,7L12,8.47L11.15,7l-1.3,0.75l0.85,1.47H9v1.5h1.7L9.85,12.2z M23,9.22h-1.7l0.85-1.47L20.85,7L20,8.47L19.15,7l-1.3,0.75l0.85,1.47H17v1.5h1.7l-0.85,1.48l1.3,0.75L20,11.47l0.85,1.48l1.3-0.75l-0.85-1.48H23V9.22z"></path>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                            </div>

                            <input
                                class="form-control"
                                type="password"
                                id="confirmPassword"
                                name="new_password_confirmation"
                                placeholder="Re-type your password"
                                autocomplete="new-password"
                                required>

                            <div class="password-warning" id="confirmPasswordWarning">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zM8 4.25a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0V5A.75.75 0 0 1 8 4.25zm0 7a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                                </svg>
                                <span>Passwords not matching.</span>
                            </div>
                        </div>

                        <button class="btn fw-semibold w-100 btn-gradient text-white mt-3" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @if(session('logout_after_delay'))
                setTimeout(function () {
                    window.location.href = "{{ route('student.changePassword.logout') }}";
                }, 3000);
            @endif

            const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
            [...popoverTriggerList].forEach(el => new bootstrap.Popover(el));

            const form = document.getElementById("loginForm");
            const currentPassword = document.getElementById("currentPassword");
            const newPassword = document.getElementById("newPassword");
            const confirmPassword = document.getElementById("confirmPassword");

            const newPasswordWarning = document.getElementById("newPasswordWarning");
            const confirmPasswordWarning = document.getElementById("confirmPasswordWarning");
            const newPasswordRuleList = document.getElementById("newPasswordRuleList");

            const passwordRules = [
                {
                    test: value => value.length >= 8,
                    message: "Password must be at least 8 characters."
                },
                {
                    test: value => /[A-Z]/.test(value),
                    message: "Password must include 1 uppercase letter."
                },
                {
                    test: value => /[a-z]/.test(value),
                    message: "Password must include 1 lowercase letter."
                },
                {
                    test: value => /\d/.test(value),
                    message: "Password must include 1 number."
                },
                {
                    test: value => /[^A-Za-z0-9]/.test(value),
                    message: "Password must include 1 special symbol."
                }
            ];

            function resetCurrentPasswordState() {
                currentPassword.classList.remove("is-invalid");
            }

            function resetNewPasswordState() {
                newPassword.classList.remove("field-warning");
                newPasswordWarning.classList.remove("show");
                newPasswordRuleList.classList.remove("show");
                newPasswordRuleList.innerHTML = "";
            }

            function resetConfirmPasswordState() {
                confirmPassword.classList.remove("field-warning");
                confirmPasswordWarning.classList.remove("show");
            }

            function validateCurrentPassword() {
                const isValid = currentPassword.value.trim() !== "";

                if (!isValid) {
                    currentPassword.classList.add("is-invalid");
                }

                return isValid;
            }

            function validateNewPassword() {
                const value = newPassword.value;
                const failedRules = passwordRules.filter(rule => !rule.test(value));

                resetNewPasswordState();

                if (failedRules.length > 0) {
                    newPassword.classList.add("field-warning");
                    newPasswordWarning.classList.add("show");
                    newPasswordRuleList.classList.add("show");

                    failedRules.forEach(rule => {
                        const li = document.createElement("li");
                        li.innerHTML = `
                            <span class="password-rule-bullet" aria-hidden="true"></span>
                            <span>${rule.message}</span>
                        `;
                        newPasswordRuleList.appendChild(li);
                    });

                    return false;
                }

                return true;
            }

            function validateConfirmPassword() {
                const isValid = confirmPassword.value !== "" && confirmPassword.value === newPassword.value;

                resetConfirmPasswordState();

                if (!isValid) {
                    confirmPassword.classList.add("field-warning");
                    confirmPasswordWarning.classList.add("show");
                    return false;
                }

                return true;
            }

            form.addEventListener("submit", function (e) {
                resetCurrentPasswordState();
                resetNewPasswordState();
                resetConfirmPasswordState();

                const isCurrentPasswordValid = validateCurrentPassword();
                const isNewPasswordValid = validateNewPassword();
                const isConfirmPasswordValid = validateConfirmPassword();

                if (!isCurrentPasswordValid || !isNewPasswordValid || !isConfirmPasswordValid) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        });
    </script>
</body>

</html>
