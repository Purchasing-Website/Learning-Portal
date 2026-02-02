@extends('layouts.student_app')

@section('content')
    <!-- HERO -->
    <section class="lp-hero p-3 p-lg-4 mb-4">
      <div class="row g-4 align-items-center">
        <div class="col-12 col-lg-7">
          <h1 class="lp-hero-title">Welcome back.</h1>
          <p class="lp-hero-subtitle mt-2">
            Search classes, browse programs, then drill down into courses and classes.
          </p>

          <div class="row g-2 mt-3">
            <div class="col-12 col-md-8">
              <div class="lp-search-wrap">
                <i class="bi bi-search"></i>
                <input id="heroSearch" class="form-control lp-input" placeholder="Search classes (e.g. Feng Shui, Healing)..." />
              </div>
            </div>
            <div class="col-12 col-md-4 d-grid">
              <button class="btn lp-btn lp-btn-primary" id="btnHeroSearch">
                <i class="bi bi-search me-1"></i>Search
              </button>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-5">
          <div class="lp-card">
            <div class="lp-accent"></div>
            <div class="lp-card-body">
              <div class="d-flex justify-content-between gap-3">
                <div>
                  <div class="fw-bold fs-5">Quick Links</div>
                  <div class="text-secondary">Jump into your pages.</div>
                </div>
                <div class="text-end small">
                  <div class="text-secondary">Today</div>
                  <div class="fw-semibold" id="todayDate">—</div>
                </div>
              </div>

              <hr class="my-3">

              <div class="d-grid gap-2">
                <a class="btn lp-btn lp-btn-outline" href="MyLearning.html"><i class="bi bi-journal-check me-1"></i>My Learning</a>
                <a class="btn lp-btn lp-btn-outline" href="SearchResults.html"><i class="bi bi-search me-1"></i>Search Results</a>
                <a class="btn lp-btn lp-btn-outline" href="Profile.html"><i class="bi bi-person-circle me-1"></i>My Profile</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- PROGRAMS (selecting program drives Course/Class lists via JS demo) -->
    <section class="mb-4">
      <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-2 mb-3">
        <div>
          <h2 class="lp-section-title">Programs</h2>
          <p class="lp-section-sub">Pick a program to view its courses and classes.</p>
        </div>
		<a class="btn lp-btn lp-btn-outline" href="SearchResults.html">
          View All
        </a>
      </div>

      <div class="row g-3" id="programGrid"></div>
    </section>

    <!-- COURSES -->
    <section class="mb-4">
      <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-2 mb-3">
        <div>
          <h2 class="lp-section-title">Courses</h2>
          <p class="lp-section-sub">Courses under the selected program.</p>
        </div>
		<a class="btn lp-btn lp-btn-outline" href="SearchResults.html">
          View All
        </a>
      </div>

      <div class="row g-3" id="courseGrid"></div>

      <div class="text-secondary small mt-2" id="courseHint"></div>
    </section>

    <!-- CLASSES -->
    <section class="mb-4">
      <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-2 mb-3">
        <div>
          <h2 class="lp-section-title">Classes</h2>
          <p class="lp-section-sub">Classes under the selected course.</p>
        </div>
		<a class="btn lp-btn lp-btn-outline" href="SearchResults.html">
          View All
        </a>
      </div>

      <div class="row g-3" id="classGrid"></div>

      <div class="text-secondary small mt-2" id="classHint"></div>
    </section>

    <!-- CAROUSEL (replaces announcements) -->
    <section class="mb-4">
      <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-2 mb-3">
        <div>
          <h2 class="lp-section-title">Highlights</h2>
          <p class="lp-section-sub">A quick look at what’s new.</p>
        </div>
      </div>

      <div id="homeCarousel" class="carousel slide lp-carousel" data-bs-ride="carousel">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="0" class="active"></button>
          <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="1"></button>
          <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner">
          <div class="carousel-item active">
            <img alt="Slide 1" src="https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=1600&q=70">
          </div>
          <div class="carousel-item">
            <img alt="Slide 2" src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1600&q=70">
          </div>
          <div class="carousel-item">
            <img alt="Slide 3" src="https://images.unsplash.com/photo-1520975661595-6453be3f7070?auto=format&fit=crop&w=1600&q=70">
          </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </section>

    <!-- POPULAR RECENTLY (kept, renamed; no "View Similar" button) -->
    <section class="mb-4">
      <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-2 mb-3">
        <div>
          <h2 class="lp-section-title">Popular Recently</h2>
          <p class="lp-section-sub">Classes learners engaged with lately.</p>
        </div>
      </div>

      <div class="row g-3" id="popularGrid"></div>
    </section>
@endsection

@push('scripts')
  <script>
	  document.getElementById("btnLogout")?.addEventListener("click", (e) => {
		e.preventDefault();
		// Demo behavior:
		alert("Logged out!");
		// Real behavior later:
		// window.location.href = "Login.html";
	  });
    // ===== Demo data (Program -> Courses -> Classes) =====
    const ACADEMY = [
      {
        program_id: "P001",
        program_name: "Feng Shui",
        program_desc: "Balance space and flow.",
        courses: [
          {
            course_id: "C001",
            course_name: "Feng Shui Foundations",
            total_min: 360,
            classes: [
              { class_id: "CLS-1001", class_name: "风水入门 · Feng Shui Basics", total_min: 320 },
              { class_id: "CLS-1012", class_name: "Feng Shui for Workplace", total_min: 180 }
            ]
          },
          {
            course_id: "C002",
            course_name: "Advanced Home Layout",
            total_min: 420,
            classes: [
              { class_id: "CLS-1022", class_name: "Bagua Deep Dive", total_min: 240 },
              { class_id: "CLS-1023", class_name: "Room-by-Room Practice", total_min: 210 }
            ]
          }
        ]
      },
      {
        program_id: "P002",
        program_name: "Healing",
        program_desc: "Mind and body wellness.",
        courses: [
          {
            course_id: "C010",
            course_name: "Mind & Body Healing",
            total_min: 210,
            classes: [
              { class_id: "CLS-1002", class_name: "身心疗愈 · Mind & Body Healing", total_min: 210 }
            ]
          }
        ]
      },
      {
        program_id: "P003",
        program_name: "Meditation",
        program_desc: "Focus and inner calm.",
        courses: [
          {
            course_id: "C020",
            course_name: "Meditation Essentials",
            total_min: 260,
            classes: [
              { class_id: "CLS-1030", class_name: "Meditation for Clarity", total_min: 260 }
            ]
          }
        ]
      },
      {
        program_id: "P004",
        program_name: "Numerology",
        program_desc: "Patterns through numbers.",
        courses: [
          {
            course_id: "C030",
            course_name: "Yi Numerology Basics",
            total_min: 180,
            classes: [
              { class_id: "CLS-1003", class_name: "易数基础 · Yi Numerology", total_min: 180 }
            ]
          }
        ]
      }
    ];

    const POPULAR_RECENTLY = [
      { class_id:"CLS-1001", class_name:"风水入门 · Feng Shui Basics", program_name:"Feng Shui", total_min: 320 },
      { class_id:"CLS-1002", class_name:"身心疗愈 · Mind & Body Healing", program_name:"Healing", total_min: 210 },
      { class_id:"CLS-1030", class_name:"Meditation for Clarity", program_name:"Meditation", total_min: 260 }
    ];

    const $ = (id) => document.getElementById(id);

    function formatMinutes(min){
      const m = Math.max(0, Math.round(min));
      const h = Math.floor(m / 60);
      const r = m % 60;
      if (h <= 0) return r + "m";
      if (r === 0) return h + "h";
      return h + "h " + r + "m";
    }

    function escapeHtml(str){
      return String(str).replace(/[&<>"']/g, s => ({
        "&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;", "'":"&#039;"
      }[s]));
    }

    let selectedProgramId = ACADEMY[0].program_id;
    let selectedCourseId = ACADEMY[0].courses[0].course_id;

    function renderPrograms(){
      const grid = $("programGrid");
      grid.innerHTML = "";

      ACADEMY.forEach(p => {
        const col = document.createElement("div");
        col.className = "col-12 col-md-6 col-xl-3";

        col.innerHTML = `
          <div class="lp-tile" role="button" tabindex="0" data-program="${escapeHtml(p.program_id)}"
               style="${p.program_id === selectedProgramId ? 'outline:2px solid rgba(79,124,247,.45);' : ''}">
            <h3>${escapeHtml(p.program_name)}</h3>
          </div>
        `;
        grid.appendChild(col);
      });
    }

    function renderCourses(){
      const program = ACADEMY.find(p => p.program_id === selectedProgramId);
      const grid = $("courseGrid");
      grid.innerHTML = "";

      if(!program || !program.courses?.length){
        $("courseHint").textContent = "No courses available for this program.";
        return;
      }

      $("courseHint").textContent = "";
      // Ensure selected course exists
      if(!program.courses.some(c => c.course_id === selectedCourseId)){
        selectedCourseId = program.courses[0].course_id;
      }

      program.courses.forEach(c => {
        const col = document.createElement("div");
        col.className = "col-12 col-md-6 col-xl-4";

        col.innerHTML = `
          <div class="lp-card" role="button" tabindex="0" data-course="${escapeHtml(c.course_id)}"
               style="${c.course_id === selectedCourseId ? 'outline:2px solid rgba(79,124,247,.45);' : ''}">
            <div class="lp-accent"></div>
            <div class="lp-card-body">
              <div class="d-flex justify-content-between gap-3">
                <div>
                  <div class="lp-title">${escapeHtml(c.course_name)}</div>
                  <div class="lp-meta">${escapeHtml(program.program_name)}</div>
                </div>
                <div class="text-end small">
                  <div class="text-secondary">Course ID</div>
                  <div class="fw-semibold">${escapeHtml(c.course_id)}</div>
                </div>
              </div>
            </div>
          </div>
        `;
        grid.appendChild(col);
      });
    }

    function renderClasses(){
      const program = ACADEMY.find(p => p.program_id === selectedProgramId);
      const course = program?.courses?.find(c => c.course_id === selectedCourseId);
      const grid = $("classGrid");
      grid.innerHTML = "";

      if(!course || !course.classes?.length){
        $("classHint").textContent = "No classes available for this course.";
        return;
      }
      $("classHint").textContent = "";

      course.classes.forEach(cls => {
        const col = document.createElement("div");
        col.className = "col-12 col-md-6 col-xl-4";

        col.innerHTML = `
          <div class="lp-card">
            <div class="lp-accent"></div>
            <div class="lp-card-body">
              <div class="d-flex justify-content-between gap-3">
                <div>
                  <div class="lp-title">${escapeHtml(cls.class_name)}</div>
                  <div class="lp-meta">${escapeHtml(course.course_name)}</div>
                </div>
                <div class="text-end small">
                  <div class="text-secondary">Class ID</div>
                  <div class="fw-semibold">${escapeHtml(cls.class_id)}</div>
                </div>
              </div>

              <div class="mt-3 d-flex flex-wrap gap-2">
                <span class="lp-stat"><i class="bi bi-hourglass-split"></i>${formatMinutes(cls.total_min)} total</span>
              </div>
            </div>
          </div>
        `;
        grid.appendChild(col);
      });
    }

    function renderPopular(){
      const grid = $("popularGrid");
      grid.innerHTML = "";

      POPULAR_RECENTLY.forEach(c => {
        const col = document.createElement("div");
        col.className = "col-12 col-md-6 col-xl-4";

        col.innerHTML = `
          <div class="lp-card">
            <div class="lp-accent"></div>
            <div class="lp-card-body">
              <div class="d-flex justify-content-between gap-3">
                <div>
                  <div class="lp-title">${escapeHtml(c.class_name)}</div>
                  <div class="lp-meta">${escapeHtml(c.program_name)}</div>
                </div>
                <div class="text-end small">
                  <div class="text-secondary">Class ID</div>
                  <div class="fw-semibold">${escapeHtml(c.class_id)}</div>
                </div>
              </div>

              <div class="mt-3">
                <span class="lp-stat"><i class="bi bi-hourglass-split"></i>${formatMinutes(c.total_min)} total</span>
              </div>
            </div>
          </div>
        `;
        grid.appendChild(col);
      });
    }

    function goSearch(q){
      const query = (q || "").trim();
      const url = query ? `SearchResults.html?q=${encodeURIComponent(query)}` : "SearchResults.html";
      window.location.href = url;
    }

    document.addEventListener("DOMContentLoaded", () => {
      const d = new Date();
      $("todayDate").textContent = d.toLocaleDateString(undefined, { year:"numeric", month:"short", day:"2-digit" });
      $("yearNow").textContent = d.getFullYear();

      renderPrograms();
      renderCourses();
      renderClasses();
      renderPopular();

      // Hero search
      $("btnHeroSearch").addEventListener("click", () => goSearch($("heroSearch").value));
      $("heroSearch").addEventListener("keydown", (e) => {
        if(e.key === "Enter"){
          e.preventDefault();
          goSearch($("heroSearch").value);
        }
      });

      // Quick buttons
      document.addEventListener("click", (e) => {
        const quick = e.target.closest("[data-quick]");
        if(quick){
          goSearch(quick.getAttribute("data-quick"));
          return;
        }

        const prog = e.target.closest("[data-program]");
        if(prog){
          selectedProgramId = prog.getAttribute("data-program");
          renderPrograms();
          renderCourses();
          renderClasses();
          return;
        }

        const course = e.target.closest("[data-course]");
        if(course){
          selectedCourseId = course.getAttribute("data-course");
          renderCourses();
          renderClasses();
          return;
        }
      });
    });
  </script>
  @endpush
