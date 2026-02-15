<!DOCTYPE html>
<html data-bs-theme="light" lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>Learning Portal - Sign Up</title>

  <!-- Same assets as login.html -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=ADLaM+Display&amp;display=swap">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Alatsi&amp;display=swap">
  <link rel="stylesheet" href="{{ asset('css/homepage.css') }}" >
  <link rel="stylesheet" href="{{ asset('css/login_input.css') }}">
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">

  <style>
    /* ===== Responsive wrapper fixes (mobile) ===== */
.card-wrap {
      width: 100%;
      max-width: 560px; /* desktop cap */
      margin: 0 auto;
    }
    .login-card {
      width: 100%;
      border-radius: 18px;
    }
    .login-card .card-body {
      padding: 28px 26px;
    }
    @media (max-width: 575.98px) {
      .login-card .card-body { padding: 22px 18px; }
      h2 { font-size: 1.55rem; }
      .row.g-3 { --bs-gutter-x: 10px; --bs-gutter-y: 10px; }
    }
/* ===== Password rule indicator ===== */
    .pw-meter {
      font-size: .85rem;
      line-height: 1.25;
      margin-top: 8px;
      user-select: none;
    }
    .pw-meter .rule {
      display: flex;
      align-items: center;
      gap: 8px;
      opacity: .85;
      margin: 2px 0;
    }
    .pw-meter .rule .dot {
      width: 18px;
      height: 18px;
      border-radius: 999px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: .8rem;
      border: 1px solid rgba(0,0,0,.18);
      opacity: .85;
    }
    .pw-meter .rule.ok {
      opacity: 1;
      font-weight: 600;
    }
    .pw-meter .rule.ok .dot {
      border-color: rgba(25,135,84,.55);
      color: #198754; /* bootstrap success */
    }

    /* ===== Confirm password message ===== */
    .match-hint {
      display: none;
      font-size: .82rem;
      margin-top: 6px;
    }
    .match-hint.show { display: block; }
    .match-hint.bad { color: #dc3545; }   /* bootstrap danger */
    .match-hint.good { color: #198754; }  /* bootstrap success */
  </style>
</head>

<body class="page">
  <div class="d-none d-md-block bg-shape one"></div>
  <div class="d-none d-md-block bg-shape two"></div>

  <main class="position-relative d-flex min-vh-100 justify-content-center align-items-center px-3">
    <div class="card-wrap">
      <div class="card login-card">
        <div class="card-body">
          <h2 class="fw-bold text-center mb-4">Sign Up</h2>

          <form id="signupForm" novalidate method="POST" action="{{ route('register') }}">
            @csrf
            <!-- First/Last name -->
              <div class="position-relative mb-2">
                <div class="position-relative mb-3">
                  <label class="form-label text-muted small">Full Name <span class="text-danger">*</span></label>
                  <span style="margin: 0px 5px;">
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 24 24" width="1em" fill="currentColor">
                      <path d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                    </svg>
                  </span>
                  <input class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" type="text" id="name" name="name" placeholder="Type your Full name" autocomplete="given-name" required>
                  {{-- <small class="invalid-feedback">Full name is required.</small> --}}
                  @error('name')
                      {{-- <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span> --}}
                      <small class="invalid-feedback">Full name is required.</small>
                  @enderror
                </div>
              </div>

            

            <!-- Phone (required) + Optional fields -->
            <div class="row g-3 mb-1">
				<div class="col-12 col-md-6">
                <!-- Email -->
				<div class="position-relative mb-3">
				  <label class="form-label text-muted small">Email Address <span class="text-danger">*</span></label>
				  <span style="margin: 0px 5px;">
					<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 24 24" width="1em" fill="currentColor">
					  <path d="M0 0h24v24H0z" fill="none"></path>
					  <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"></path>
					</svg>
				  </span>
				  <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Type your email" autocomplete="email" required>
				  {{-- <small class="invalid-feedback">Please enter a valid email.</small> --}}
          @error('email')
              {{-- <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span> --}}
              <small class="invalid-feedback">Please enter a valid email.</small>
          @enderror
				</div>
              </div>
			  
              <div class="col-12 col-md-6">
                <div class="position-relative mb-3">
                  <label class="form-label text-muted small">Phone Number <span class="text-danger">*</span></label>
                  <span style="margin: 0px 5px;">
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 24 24" width="1em" fill="currentColor">
                      <path d="M0 0h24v24H0z" fill="none"></path>
                      <path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24c1.12.37 2.33.57 3.58.57a1 1 0 011 1V20a1 1 0 01-1 1C10.07 21 3 13.93 3 5a1 1 0 011-1h3.5a1 1 0 011 1c0 1.25.2 2.46.57 3.58a1 1 0 01-.24 1.01l-2.2 2.2z"></path>
                    </svg>
                  </span>
                  <input class="form-control @error('phone') is-invalid @enderror" type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="e.g. +60 12-345 6789" autocomplete="tel" required>
                  {{-- <small class="invalid-feedback">Please enter a valid phone number.</small> --}}
                  @error('phone')
                      {{-- <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span> --}}
                      <small class="invalid-feedback">Please enter a valid phone number.</small>
                  @enderror
                </div>
              </div>
            </div>

            <!-- Password -->
            <div class="position-relative mb-2">
              <label class="form-label text-muted small">Password <span class="text-danger">*</span></label>
              <span style="margin: 0px 5px;">
                <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="1em" viewBox="0 0 24 24" width="1em" fill="currentColor" style="font-size: 17px;">
                  <g><path d="M0,0h24v24H0V0z" fill="none"></path></g>
                  <g><g>
                    <path d="M2,17h20v2H2V17z M3.15,12.95L4,11.47l0.85,1.48l1.3-0.75L5.3,10.72H7v-1.5H5.3l0.85-1.47L4.85,7L4,8.47L3.15,7l-1.3,0.75 L2.7,9.22H1v1.5h1.7L1.85,12.2L3.15,12.95z M9.85,12.2l1.3,0.75L12,11.47l0.85,1.48l1.3-0.75l-0.85-1.48H15v-1.5h-1.7l0.85-1.47 L12.85,7L12,8.47L11.15,7l-1.3,0.75l0.85,1.47H9v1.5h1.7L9.85,12.2z M23,9.22h-1.7l0.85-1.47L20.85,7L20,8.47L19.15,7l-1.3,0.75 l0.85,1.47H17v1.5h1.7l-0.85,1.48l1.3,0.75L20,11.47l0.85,1.48l1.3-0.75l-0.85-1.48H23V9.22z"></path>
                  </g></g>
                </svg>
              </span>
              <input class="form-control @error('password') is-invalid @enderror" type="password" id="password" name="password"
                     placeholder="Create a password" autocomplete="new-password" required minlength="8">
              {{-- <small class="invalid-feedback" id="passwordInvalid">Password must meet the requirements below.</small> --}}
              @error('password')
                  {{-- <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span> --}}
                  <small class="invalid-feedback" id="passwordInvalid">Password must meet the requirements below.</small>
              @enderror

              <div class="pw-meter" id="pwMeter" aria-live="polite">
                <div class="rule" data-rule="len"><span class="dot" aria-hidden="true">•</span> At least 8 characters</div>
                <div class="rule" data-rule="upper"><span class="dot" aria-hidden="true">•</span> 1 uppercase letter</div>
                <div class="rule" data-rule="lower"><span class="dot" aria-hidden="true">•</span> 1 lowercase letter</div>
                <div class="rule" data-rule="num"><span class="dot" aria-hidden="true">•</span> 1 number</div>
                <div class="rule" data-rule="special"><span class="dot" aria-hidden="true">•</span> 1 special character</div>
              </div>
            </div>

            <!-- Confirm Password -->
            <div class="position-relative mb-3">
              <label class="form-label text-muted small">Confirm Password <span class="text-danger">*</span></label>
              <span style="margin: 0px 5px;">
                <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="1em" viewBox="0 0 24 24" width="1em" fill="currentColor" style="font-size: 17px;">
                  <g><path d="M0,0h24v24H0V0z" fill="none"></path></g>
                  <g><g>
                    <path d="M2,17h20v2H2V17z M3.15,12.95L4,11.47l0.85,1.48l1.3-0.75L5.3,10.72H7v-1.5H5.3l0.85-1.47L4.85,7L4,8.47L3.15,7l-1.3,0.75 L2.7,9.22H1v1.5h1.7L1.85,12.2L3.15,12.95z M9.85,12.2l1.3,0.75L12,11.47l0.85,1.48l1.3-0.75l-0.85-1.48H15v-1.5h-1.7l0.85-1.47 L12.85,7L12,8.47L11.15,7l-1.3,0.75l0.85,1.47H9v1.5h1.7L9.85,12.2z M23,9.22h-1.7l0.85-1.47L20.85,7L20,8.47L19.15,7l-1.3,0.75 l0.85,1.47H17v1.5h1.7l-0.85,1.48l1.3,0.75L20,11.47l0.85,1.48l1.3-0.75l-0.85-1.48H23V9.22z"></path>
                  </g></g>
                </svg>
              </span>
              <input class="form-control" type="password" id="confirmPassword" name="password_confirmation"
                     placeholder="Re-type your password" autocomplete="new-password" required>
              {{-- <small class="invalid-feedback" id="confirmInvalid">Confirm password is required.</small> --}}

              <!-- live mismatch hint -->
              <span id="matchHint" class="match-hint bad">Passwords do not match.</span>
            </div>

            <button class="btn fw-semibold w-100 btn-gradient text-white" type="submit">Create Account</button>

            <div class="text-center mt-4">
              <small class="text-muted mb-2">Already have an account? </small>
              <a class="fw-semibold small-link" href="{{ route('login') }}">LOGIN</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </main>

  <script src="{{ asset('js/bootstrap.min.js') }}"></script>

  <script>
    (function () {
      const form = document.getElementById('signupForm');

      const email = document.getElementById('email');
      const phone = document.getElementById('phone');
      const password = document.getElementById('password');
      const confirmPassword = document.getElementById('confirmPassword');

      const pwMeter = document.getElementById('pwMeter');
      const matchHint = document.getElementById('matchHint');

      // Email: simple + reliable
      const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

      // Phone: allows digits, spaces, +, -, (), with 7-20 digits total
      const phoneDigitsRe = /\d/g;
      const phoneCharsRe = /^[0-9+\-()\s]{7,25}$/;

      function setRuleOk(ruleName, ok) {
        const el = pwMeter.querySelector(`[data-rule="${ruleName}"]`);
        if (!el) return;
        el.classList.toggle('ok', !!ok);

        const dot = el.querySelector('.dot');
        if (dot) dot.textContent = ok ? '✓' : '•';
      }

      function checkPasswordRules(pw) {
        const rules = {
          len: pw.length >= 8,
          upper: /[A-Z]/.test(pw),
          lower: /[a-z]/.test(pw),
          num: /\d/.test(pw),
          special: /[^A-Za-z0-9]/.test(pw),
        };

        Object.entries(rules).forEach(([k, v]) => setRuleOk(k, v));
        return Object.values(rules).every(Boolean);
      }

      function validateEmail() {
        const ok = emailRe.test(email.value.trim());
        email.setCustomValidity(ok ? '' : 'invalid');
        return ok;
      }

      function validatePhone() {
        const val = phone.value.trim();
        const digitsCount = (val.match(phoneDigitsRe) || []).length;
        const ok = phoneCharsRe.test(val) && digitsCount >= 7 && digitsCount <= 20;
        phone.setCustomValidity(ok ? '' : 'invalid');
        return ok;
      }

      function validatePassword() {
        const ok = checkPasswordRules(password.value);
        password.setCustomValidity(ok ? '' : 'invalid');
        return ok;
      }

      function updateMatchHint() {
        const p = password.value;
        const c = confirmPassword.value;

        // only show hint after user starts typing confirm password
        if (!c) {
          matchHint.classList.remove('show', 'good', 'bad');
          matchHint.classList.add('bad');
          matchHint.textContent = 'Passwords do not match.';
          return;
        }

        if (c === p) {
          matchHint.classList.add('show', 'good');
          matchHint.classList.remove('bad');
          matchHint.textContent = 'Passwords match.';
        } else {
          matchHint.classList.add('show', 'bad');
          matchHint.classList.remove('good');
          matchHint.textContent = 'Passwords do not match.';
        }
      }

      function validateConfirmPassword() {
        const ok = confirmPassword.value === password.value && confirmPassword.value.length > 0;
        confirmPassword.setCustomValidity(ok ? '' : 'invalid');
        updateMatchHint();
        return ok;
      }

      // Live validation
      email.addEventListener('input', validateEmail);
      phone.addEventListener('input', validatePhone);

      password.addEventListener('input', () => {
        validatePassword();
        // re-check confirm if user edits password
        if (confirmPassword.value) validateConfirmPassword();
        else updateMatchHint();
      });

      confirmPassword.addEventListener('input', validateConfirmPassword);

      // Submit
      // form.addEventListener('submit', async (e) => {
      //   validateEmail();
      //   validatePhone();
      //   validatePassword();
      //   validateConfirmPassword();

      //   if (!form.checkValidity()) {
      //     e.preventDefault();
      //     e.stopPropagation();
      //   } else {
      //     // Demo only: remove this block when wired to backend
      //     //e.preventDefault();
      //     //alert('Signup form is valid. Wire this to your backend submit.');
      //     const formData = new FormData();
      //     formData.append('_token', document.head.querySelector('meta[name="csrf-token"]').content);
      //     formData.append('name', document.getElementById("name").value);
      //     formData.append('email', document.getElementById("email").value);
      //     formData.append('phone', document.getElementById("phone").value);
      //     formData.append('password', document.getElementById("password").value);

      //     const res = await fetch("{{ route('register') }}", {
      //         method: 'POST',
      //         body: formData
      //     });

      //     const data = await res.json();

      //     if (data.success) {
      //         console.log('Login');
      //     } else {
      //         alert("Something went wrong!");
      //     }
      //   }

      //   form.classList.add('was-validated');
      // });
    })();
  </script>
</body>
</html>
