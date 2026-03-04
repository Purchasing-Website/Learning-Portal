<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Content Displayer</title>

  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="{{ asset('css/ContentDisplayer.css') }}" rel="stylesheet">
</head>

<body>
<div class="lp-page">

  <!-- Topbar -->
  <header class="lp-topbar">
    <div class="container-fluid h-100 px-3">
      <div class="d-flex align-items-center justify-content-between h-100">
        <div class="d-flex align-items-center gap-2">

          <!--<button class="btn btn-link text-white p-0 me-1"-->
          <!--        type="button"-->
          <!--        id="btnSidebarToggle"-->
          <!--        aria-controls="lpSidebar"-->
          <!--        aria-label="Toggle sidebar">-->
          <!--  <i class="bi bi-list fs-2"></i>-->
          <!--</button>-->

          <div class="lp-brand">
            <div>浩霖知识库</div>
          </div>
        </div>

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
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="dropdown-item" type="submit">
                  <i class="bi bi-box-arrow-right me-2"></i>Exit
                </button>
              </form>
            </li>
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
          <div class="small mb-2" style="color: #ffffff" id="lpClassNameSide">Class</div>
          <nav class="lp-nav" id="lpNav"></nav>
        </div>
      </aside>

      <!-- Main -->
      <main class="lp-main">
        <h1 class="lp-title" id="lpLessonTitle">Select a lesson</h1>

        <div class="lp-card">
          <div class="lp-stage">
            <div class="lp-viewbox" id="lpStage">
              <div class="text-center">
                <div class="fw-semibold" style="color:rgba(234,240,255,.75)">Learning Content</div>
                <div class="small" style="color:var(--lp-muted)">Choose a lesson on the left</div>
              </div>
            </div>
          </div>
        </div>

        <div class="lp-nextbar">
          <button class="lp-nextbtn" id="btnNextLesson" type="button">
            Next <i class="bi bi-chevron-double-down"></i>
          </button>
        </div>

        <!-- keep quizRoot if you want later -->
        <div id="quizRoot" class="d-none"></div>
      </main>

    </div>
  </div>

  <footer id="lpFooterText">Hao Lin© Brand 2025</footer>
</div>

<script src="{{ asset('/js/bootstrap.min.js') }}"></script>

<script>
  // Inject DB data into the same variable name your JS expects
  window.CLASS_DATA = @json($classData);
</script>

<script src="{{ asset('js/ContentDisplayer.js') }}"></script>
</body>
</html>
