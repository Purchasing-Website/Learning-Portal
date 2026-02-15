<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Home</title>

  <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons.min.css') }}">

    <!-- Custom styles -->
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg lp-navbar">
            <div class="container">
                <a class="navbar-brand text-dark fw-bold" href="{{ route('home') }}">HAOLIN</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#lpNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="lpNav">
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link lp-nav-link" href="Homepage.html"><i class="bi bi-house-door me-1"></i>Home</a></li>
                    <li class="nav-item"><a class="nav-link lp-nav-link" href="MyLearning.html"><i class="bi bi-journal-check me-1"></i>My Learning</a></li>
                    <li class="nav-item"><a class="nav-link lp-nav-link" href="Search.html"><i class="bi bi-search me-1"></i>Search</a></li>

                    <li class="nav-item dropdown">
                        <a class="nav-link lp-nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>Account
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="Profile.html"><i class="bi bi-person-lines-fill me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ route('logout') }}"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container py-4 py-lg-5">
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="lp-footer pt-3 pb-2">
            <div class="d-flex flex-column flex-md-row justify-content-center gap-2">
                <div class="small">© <span id="yearNow"></span> HAOLIN Learning Portal</div>
            </div>
        </footer>
    </div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <!-- Inject scripts pushed from child views -->
    @stack('scripts')
</body>    
</html>
