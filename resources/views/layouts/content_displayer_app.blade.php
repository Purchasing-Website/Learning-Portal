<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Content Displayer</title>

  <!-- Bootstrap 5 -->
  <link href="resources/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="resources/css/bootstrap-icons.min.css" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <div id="lp-page">
        <!-- Topbar -->
        <header class="lp-topbar">
            <div class="container-fluid h-100 px-3">
            <div class="d-flex align-items-center justify-content-between h-100">
                <div class="d-flex align-items-center gap-2">
                <!-- Mobile toggle -->
                <!-- Sidebar toggle (works for mobile + desktop) -->
                    <button class="btn btn-link text-white p-0 me-1"
                            type="button"
                            id="btnSidebarToggle"
                            aria-controls="lpSidebar"
                            aria-label="Toggle sidebar">
                    <i class="bi bi-list fs-2"></i>
                    </button>


                <div class="lp-brand">
                    <div class="lp-logo"><i class="bi bi-emoji-smile"></i></div>
                    <div>HAO LIN</div>
                </div>
                </div>

                <!-- Profile dropdown -->
                <div class="dropdown">
                <button class="btn btn-link text-white text-decoration-none d-flex align-items-center gap-2"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <span id="lpUserName" class="fw-semibold">User</span>
                    <i class="bi bi-person-circle fs-4"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li><h6 class="dropdown-header text-white-50">Account</h6></li>
                    <li><button class="dropdown-item" type="button" id="btnExit">
                    <i class="bi bi-box-arrow-right me-2"></i>Exit
                    </button></li>
                </ul>
                </div>

            </div>
            </div>
        </header>


        <div class="lp-content">
            <div class="lp-shell">

                <!-- Sidebar -->
                <aside class="offcanvas offcanvas-start text-white" tabindex="-1" id="lpSidebar">
                    <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="lpClassNameMobile">Class</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
                    </div>

                    <div class="offcanvas-body p-3">
                    <!-- dynamic class name -->
                    <div class="small mb-2" style="color:#ffffff" id="lpClassNameSide">Class</div>
                    <nav class="lp-nav" id="lpNav"></nav>
                    </div>
                </aside>

                <!-- Main -->
                <main class="lp-main">
                    @yield('content')
                </main>

            </div>
        </div>
        <!-- FOOTER -->
        <footer id="lpFooterText">Hao Lin© Brand 202</footer>
    </div>
    
    <script src="{{ asset('js/bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('js/ContentDisplayer.js') }}" defer></script>
    <script src="{{ asset('js/quiz.js') }}" defer></script>
    <!-- Inject scripts pushed from child views -->
    @stack('scripts')
</body>    
</html>
