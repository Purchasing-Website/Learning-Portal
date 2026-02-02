@extends('layouts.student_app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/my_learning.css') }}">
@endpush

@section('content')
  <!-- HEADER -->
  <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3 mb-3">
    <div>
      <h1 class="lp-page-title">My Learning</h1>
      <p class="lp-subtitle">Track your enrolled classes, progress, and time spent.</p>
    </div>
    <div class="d-flex gap-2">
      <button class="btn lp-btn lp-btn-outline" id="btnRefresh">
        <i class="bi bi-arrow-clockwise me-1"></i>Refresh
      </button>
    </div>
  </div>

  <!-- SUMMARY -->
  <div class="lp-summary p-3 p-lg-4 mb-4">
    <div class="row g-3">
      <div class="col-6 col-lg-3">
        <div class="lp-metric">
          <div class="label">Enrolled</div>
          <div class="value" id="mEnrolled">0</div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="lp-metric">
          <div class="label">In Progress</div>
          <div class="value" id="mInProgress">0</div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="lp-metric">
          <div class="label">Completed</div>
          <div class="value" id="mCompleted">0</div>
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <div class="lp-metric">
          <div class="label">Total Time Spent</div>
          <div class="value" id="mTime">0h</div>
        </div>
      </div>
    </div>
  </div>

  <!-- CONTROLS -->
  <div class="lp-controls p-3 p-lg-3 mb-4">
    <div class="row g-3 align-items-center">
      <div class="col-12 col-lg-6">
        <div class="lp-search-wrap">
          <i class="bi bi-search"></i>
          <input id="searchInput" class="form-control lp-input" placeholder="Search your classes (e.g. Feng Shui, Healing)..." />
        </div>
      </div>

      <div class="col-6 col-lg-3">
        <select id="statusFilter" class="form-select lp-select">
          <option value="all">All Status</option>
          <option value="not_started">Not Started</option>
          <option value="in_progress">In Progress</option>
          <option value="completed">Completed</option>
        </select>
      </div>

      <div class="col-6 col-lg-3">
        <select id="sortBy" class="form-select lp-select">
          <option value="enrolled_desc">Sort: Date Enrolled (Newest)</option>
          <option value="progress_desc">Sort: Progress (High → Low)</option>
          <option value="progress_asc">Sort: Progress (Low → High)</option>
          <option value="time_desc">Sort: Time Spent (High → Low)</option>
          <option value="class_name_asc">Sort: Title (A → Z)</option>
        </select>
      </div>
    </div>
  </div>

  <!-- GRID -->
  <div class="row g-3 g-lg-4" id="classGrid"></div>

  <!-- EMPTY -->
  <div class="lp-empty mt-4 d-none" id="emptyState">
    <div class="fs-5 fw-bold mb-1">No classes found</div>
    <div>Try changing the filter or search keyword.</div>
  </div>
@endsection
  
@push('scripts')
<script>
    // ===== Sample Enrolled Class Data (replace with Laravel later) =====
    const ENROLLED_CLASSES = [
  {
      id: "CLS-1001",
  course_name: "Feng Shui",
      class_name: "风水入门 · Feng Shui Basics",
  date_enrolled: "2026-01-16T09:00:00",
      instructor: "Hao Lin Academy",
      status: "in_progress", // not_started | in_progress | completed
      progress: 42,          // 0 - 100
      duration_total_min: 320,
      time_spent_min: 118,   // duration of taking the class
      last_access: "2026-01-17T21:30:00"
    },
    {
      id: "CLS-1002",
  course_name: "Feng Shui",
      class_name: "身心疗愈 · Mind & Body Healing",
  date_enrolled: "2026-02-16T09:00:00",
      instructor: "Hao Lin Academy",
      status: "not_started",
      progress: 0,
      duration_total_min: 210,
      time_spent_min: 0,
      last_access: "2026-01-10T10:10:00"
    },
    {
      id: "CLS-1003",
  course_name: "Feng Shui",
      class_name: "易数基础 · Yi Numerology 易数基础 · Yi Numerology",
  date_enrolled: "2026-01-26T09:00:00",
      instructor: "Hao Lin Academy",
      status: "completed",
      progress: 100,
      duration_total_min: 180,
      time_spent_min: 196,
      last_access: "2026-01-05T08:00:00"
    },
    {
      id: "CLS-1004",
  course_name: "Feng Shui",
      class_name: "Meditation for Clarity",
  date_enrolled: "2026-03-16T09:00:00",
      instructor: "Hao Lin Academy",
      status: "in_progress",
      progress: 78,
      duration_total_min: 260,
      time_spent_min: 205,
      last_access: "2026-01-18T01:10:00"
    }
  ];

  // ===== Helpers =====
  const $ = (id) => document.getElementById(id);

  function clamp(n, min, max){ return Math.max(min, Math.min(max, n)); }

  function formatMinutes(min){
    const m = Math.max(0, Math.round(min));
    const h = Math.floor(m / 60);
    const r = m % 60;
    if(h <= 0) return r + "m";
    if(r === 0) return h + "h";
    return h + "h " + r + "m";
  }

  function formatDateTime(iso){
    const d = new Date(iso);
    // Simple readable format
    return d.toLocaleString(undefined, { year:"numeric", month:"short", day:"2-digit", hour:"2-digit", minute:"2-digit" });
  }

  function statusLabel(s){
    if(s === "completed") return "Completed";
    if(s === "in_progress") return "In Progress";
    return "Not Started";
  }

  function statusBadgeClass(s){
    if(s === "completed") return "status-complete";
    if(s === "in_progress") return "status-inprogress";
    return "status-notstarted";
  }

  function computeTimeLeft(totalMin, spentMin){
    const left = Math.max(0, totalMin - spentMin);
    return left;
  }

  // ===== Rendering =====
  function render(){
    const q = ($("searchInput").value || "").trim().toLowerCase();
    const status = $("statusFilter").value;
    const sortBy = $("sortBy").value;

    // filter
    let items = ENROLLED_CLASSES.filter(c => {
      const matchesQ =
        c.class_name.toLowerCase().includes(q) ||
        c.instructor.toLowerCase().includes(q) ||
        c.id.toLowerCase().includes(q);

      const matchesStatus = (status === "all") ? true : c.status === status;
      return matchesQ && matchesStatus;
    });

    // sort
    items.sort((a,b) => {
  if (sortBy === "enrolled_desc") return new Date(b.date_enrolled) - new Date(a.date_enrolled);
      //if(sortBy === "last_access_desc") return new Date(b.last_access) - new Date(a.last_access);
      if(sortBy === "progress_desc") return (b.progress - a.progress);
      if(sortBy === "progress_asc") return (a.progress - b.progress);
      if(sortBy === "time_desc") return (b.time_spent_min - a.time_spent_min);
      if(sortBy === "class_name_asc") return a.class_name.localeCompare(b.class_name);
      return 0;
    });

    // grid
    const grid = $("classGrid");
    grid.innerHTML = "";

    if(items.length === 0){
      $("emptyState").classList.remove("d-none");
    } else {
      $("emptyState").classList.add("d-none");
    }

    for(const c of items){
      const progress = clamp(c.progress, 0, 100);
      const spent = Math.max(0, c.time_spent_min);
      const total = Math.max(0, c.duration_total_min);
      const left = computeTimeLeft(total, spent);

      const pctText = progress + "%";
      const spentText = formatMinutes(spent);
      const totalText = formatMinutes(total);
      const leftText = formatMinutes(left);

      const primaryBtnText =
        c.status === "completed" ? "Review" :
        (c.status === "not_started" ? "Start" : "Continue");

      const primaryIcon =
        c.status === "completed" ? "bi-arrow-repeat" :
        (c.status === "not_started" ? "bi-play-circle" : "bi-play-circle");

      const col = document.createElement("div");
      col.className = "col-12 col-md-6 col-xl-4";

      col.innerHTML = `
        <div class="lp-class-card h-100">
    <div style="height:10px;background:linear-gradient(90deg,var(--lp-blue2),var(--lp-purple),var(--lp-pink));"></div>

          <div class="lp-class-body">
            <div class="d-flex align-items-start justify-content-between gap-2">
              <div>
                <h3 class="lp-class-title">${escapeHtml(c.class_name)}</h3>
                <p class="lp-class-meta mb-0">${escapeHtml(c.course_name)}</p>
              </div>
      <span class="lp-badge">${c.id}</span>
            </div>

            <div class="lp-progress-row mt-3">
              <div class="lp-progress" aria-label="Progress">
                <div style="width:${progress}%"></div>
              </div>
              <div class="lp-progress-pct">${pctText}</div>
            </div>

            <div class="lp-stats">
              <div class="lp-stat">
                <i class="bi bi-hourglass-split"></i>
                <span><strong>${spentText}</strong> spent</span>
              </div>
              <div class="lp-stat">
                <i class="bi bi-calendar2-week"></i>
                <span><strong>${leftText}</strong> left</span>
              </div>
              <div class="lp-stat">
                <i class="bi bi-collection-play"></i>
                <span>${totalText} total</span>
              </div>
            </div>

            <div class="mt-3 small text-secondary">
              <span class="lp-badge">${statusLabel(c.status)}</span>
            </div>
          </div>

          <div class="lp-actions mt-auto">
            <button class="btn lp-btn lp-btn-outline" data-action="details" data-id="${escapeAttr(c.id)}">
              <i class="bi bi-info-circle me-1"></i>Details
            </button>
            <button class="btn lp-btn lp-btn-primary" data-action="continue" data-id="${escapeAttr(c.id)}">
              <i class="bi ${primaryIcon} me-1"></i>${primaryBtnText}
            </button>
          </div>
        </div>
      `;

      grid.appendChild(col);
    }

    // summary metrics
    const enrolled = ENROLLED_CLASSES.length;
    const inProg = ENROLLED_CLASSES.filter(x => x.status === "in_progress").length;
    const completed = ENROLLED_CLASSES.filter(x => x.status === "completed").length;
    const totalSpent = ENROLLED_CLASSES.reduce((sum, x) => sum + Math.max(0, x.time_spent_min), 0);

    $("mEnrolled").textContent = enrolled;
    $("mInProgress").textContent = inProg;
    $("mCompleted").textContent = completed;
    $("mTime").textContent = formatMinutes(totalSpent);
  }

  // Escape helpers (avoid HTML injection)
  function escapeHtml(str){
    return String(str).replace(/[&<>"']/g, s => ({
      "&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;", "'":"&#039;"
    }[s]));
  }
  function escapeAttr(str){
    return String(str).replace(/"/g, "&quot;");
  }

  // ===== Events =====
  document.addEventListener("DOMContentLoaded", () => {
    render();

    ["searchInput","statusFilter","sortBy"].forEach(id => {
      $(id).addEventListener("input", render);
      $(id).addEventListener("change", render);
    });

    $("btnRefresh").addEventListener("click", () => {
      // In real app, refetch API then render.
      render();
    });

    // Delegate card button clicks
    document.addEventListener("click", (e) => {
      const btn = e.target.closest("button[data-action]");
      if(!btn) return;

      const action = btn.getAttribute("data-action");
      const id = btn.getAttribute("data-id");
      const cls = ENROLLED_CLASSES.find(x => x.id === id);
      if(!cls) return;

      if(action === "details"){
        alert(
          "Class Details\n\n" +
          "ID: " + cls.id + "\n" +
          "Title: " + cls.class_name + "\n" +
          "Status: " + statusLabel(cls.status) + "\n" +
          "Progress: " + cls.progress + "%\n" +
          "Time spent: " + formatMinutes(cls.time_spent_min) + "\n" +
          "Total: " + formatMinutes(cls.duration_total_min)
        );
      }

      if(action === "continue"){
        alert("Go to learning player for: " + cls.id + "\nNext: " + cls.next_lesson);
        // Example for Laravel:
        // window.location.href = `/student/class/${encodeURIComponent(cls.id)}`;
      }
    });
  });

</script>
@endpush
