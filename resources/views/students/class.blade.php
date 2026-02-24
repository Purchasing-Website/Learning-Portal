@extends('layouts.student_app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/class.css') }}">
@endpush

@section('content')
    <!-- Title -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-3">
      <div>
        <h1 class="lp-page-title">Classes</h1>
        <p class="lp-subtitle">Find classes that match your interests.</p>
      </div>
        <a class="btn lp-btn lp-btn-outline" href="{{ route('home') }}">
          <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>

    <!-- Summary strip -->
    <div class="lp-summary p-3 p-lg-4 mb-4">
      <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-2">
        <div class="d-flex flex-wrap gap-2">
          <span class="lp-chip"><i class="bi bi-layers"></i> Classes: <strong id="chipCount">0</strong></span>
          <span class="lp-chip"><i class="bi bi-funnel"></i> Course: <strong id="chipCourse">All</strong></span>
        </div>
        <div class="text-secondary small">
          <i class="bi bi-info-circle me-1"></i>
          Auto-filter supported: <span class="fw-semibold">AllClasses.html?course=C001</span>
        </div>
      </div>
    </div>

    <!-- Controls -->
    <div class="lp-controls p-3 mb-4">
      <div class="row g-3 align-items-center">
        <div class="col-12 col-lg-6">
          <div class="lp-search-wrap">
            <i class="bi bi-search"></i>
            <input id="searchInput" class="form-control lp-input" placeholder="Search classes (e.g. Feng Shui, Healing)..." />
          </div>
        </div>

        <div class="col-6 col-lg-3">
          <select id="programFilter" class="form-select lp-select">
            <option value="all">All Courses</option>
          </select>
        </div>

        <div class="col-6 col-lg-3">
          <select id="sortBy" class="form-select lp-select">
            <option value="progress_desc">Sort: Progress (High → Low)</option>
            <option value="duration_asc">Sort: Duration (Short → Long)</option>
            <option value="title_asc">Sort: Title (A → Z)</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Results grid -->
    <div class="row g-3 g-lg-4" id="resultsGrid" >
    {{-- <div class="row g-3 g-lg-4" >
      @foreach ($classes as $class)
        <div class="col-12 col-md-6 col-xl-4">
          <div class="lp-course-card" data-course="{{ $class->id }}">
            <div class="lp-course-row">
              <div class="lp-icon-block"><i class="bi bi-journals"></i></div>

              <div>
                <p class="lp-course-title mb-0">{{ $class->title }}</p>
              </div>

              <div class="lp-right">
                <div class="lp-k">Course ID</div>
                <div class="lp-v">{{ $class->id }}</div>
              </div>
            </div>

             Keep total hours (allowed). If you don't want it, remove this footer block. 
            <div class="lp-course-footer">
              <span class="lp-stat"><i class="bi bi-hourglass-split"></i>${formatMinutes(c.total_min)} total</span>
              <span class="text-secondary small"> </span>
            </div>
          </div>
        </div>
      @endforeach --}}
    </div>

    <!-- Empty -->
    <div class="lp-empty mt-4 d-none" id="emptyState">
      <div class="fs-5 fw-bold mb-1">No results found</div>
      <div>Try another keyword or choose “All Programs”.</div>
    </div>
@endsection
  
@push('scripts')
  <script>
    // ===== Sample Search Results (replace with API later) =====
    // enrolled=true means user already enrolled -> show progress/time spent
    // const SEARCH_RESULTS = [
    //   {
    //     class_id: "CLS-1001",
    //     class_name: "风水入门 · Feng Shui Basics",
    //     program_name: "Feng Shui",
    //     enrolled: true,
    //     progress: 42,
    //     duration_total_min: 320,
    //     time_spent_min: 118,
    //     popularity: 96
    //   },
    //   {
    //     class_id: "CLS-1012",
    //     class_name: "Feng Shui for Workplace",
    //     program_name: "Feng Shui",
    //     enrolled: false,
    //     progress: 0,
    //     duration_total_min: 180,
    //     time_spent_min: 0,
    //     popularity: 88
    //   },
    //   {
    //     class_id: "CLS-1002",
    //     class_name: "身心疗愈 · Mind & Body Healing",
    //     program_name: "Healing",
    //     enrolled: true,
    //     progress: 0,
    //     duration_total_min: 210,
    //     time_spent_min: 0,
    //     popularity: 84
    //   },
    //   {
    //     class_id: "CLS-1030",
    //     class_name: "Meditation for Clarity",
    //     program_name: "Meditation",
    //     enrolled: true,
    //     progress: 78,
    //     duration_total_min: 260,
    //     time_spent_min: 205,
    //     popularity: 92
    //   }
    // ];

    const SEARCH_RESULTS = @json($classes);
    console.log( @json($classes));

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

    // simple keyword highlighting
    function highlight(text, q){
      const safe = escapeHtml(text);
      const query = (q || "").trim();
      if(!query) return safe;
      const parts = query.split(/\s+/).filter(Boolean);
      let out = safe;
      for(const p of parts){
        const re = new RegExp(`(${p.replace(/[.*+?^${}()|[\]\\]/g, "\\$&")})`, "ig");
        out = out.replace(re, "<mark>$1</mark>");
      }
      return out;
    }

    function buildProgramOptions(){
      const programs = Array.from(new Set(SEARCH_RESULTS.map(x => x.program_name))).sort();
      const sel = $("programFilter");
      programs.forEach(p => {
        const opt = document.createElement("option");
        opt.value = p;
        opt.textContent = p;
        sel.appendChild(opt);
      });
    }

    function render(){
      const q = ($("searchInput").value || "").trim();
      const program = $("programFilter").value;
      const sortBy = $("sortBy").value;

      // update chip query
      //$("chipQuery").textContent = q || "—";

      // filter
      let items = SEARCH_RESULTS.filter(it => {
        const matchesProgram = (program === "all") ? true : it.program_name === program;
        const hay = (it.class_name + " " + it.program_name + " " + it.class_id).toLowerCase();
        const matchesQ = !q ? true : q.toLowerCase().split(/\s+/).filter(Boolean).every(w => hay.includes(w));
        return matchesProgram && matchesQ;
      });

      // sort
      items.sort((a,b) => {
        if(sortBy === "progress_desc") return (b.progress - a.progress);
        if(sortBy === "duration_asc") return (a.duration_total_min - b.duration_total_min);
        if(sortBy === "title_asc") return a.class_name.localeCompare(b.class_name);
        return 0;
      });

      $("chipCount").textContent = items.length;

      const grid = $("resultsGrid");
      grid.innerHTML = "";

      if(items.length === 0){
        $("emptyState").classList.remove("d-none");
        return;
      }
      $("emptyState").classList.add("d-none");

      for(const c of items){
        const progress = Math.max(0, Math.min(100, c.progress || 0));
        const spent = c.time_spent_min || 0;
        const total = c.duration_total_min || 0;
        const left = Math.max(0, total - spent);

        const col = document.createElement("div");
        col.className = "col-12 col-md-6 col-xl-4";

        const titleHtml = highlight(c.class_name, q);
        const programHtml = highlight(c.program_name, q);

        col.innerHTML = `
          <div class="lp-class-card">
            <div class="lp-accent"></div>

            <div class="lp-body">
              <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                  <h3 class="lp-class-title mb-1">${titleHtml}</h3>
                  <p class="lp-class-meta mb-0">${programHtml}</p>
                </div>
                <div class="text-end">
                  <div class="small text-secondary">Class ID</div>
                  <div class="small fw-semibold">${escapeHtml(c.class_id)}</div>
                </div>
              </div>

              <div class="lp-badges">
                ${c.enrolled ? `<span class="lp-badge enrolled"><i class="bi bi-check-circle me-1"></i>Enrolled</span>` : `<span class="lp-badge new"><i class="bi bi-compass me-1"></i>Discover</span>`}
                <span class="lp-badge"><i class="bi bi-hourglass-split me-1"></i>${formatMinutes(total)} total</span>
              </div>

              ${c.enrolled ? `
                <div class="lp-progress-row">
                  <div class="lp-progress"><div style="width:${progress}%"></div></div>
                  <div class="lp-progress-pct">${progress}%</div>
                </div>

                <div class="lp-stats">
                  <div class="lp-stat"><i class="bi bi-stopwatch"></i><span><strong>${formatMinutes(spent)}</strong> spent</span></div>
                  <div class="lp-stat"><i class="bi bi-calendar2-week"></i><span><strong>${formatMinutes(left)}</strong> left</span></div>
                </div>
              ` : `
                <div class="mt-3 text-secondary small">
                  <i class="bi bi-stars me-1"></i>
                  Suggested for you based on your search and popularity.
                </div>
              `}
            </div>

            <div class="lp-actions">
              <button class="btn lp-btn lp-btn-outline" data-action="details" data-id="${escapeHtml(c.class_id)}" data-classid="${escapeHtml(c.classId)}">
                <i class="bi bi-info-circle me-1"></i>Details
              </button>

              ${c.enrolled ? `
                <button class="btn lp-btn lp-btn-primary" data-action="open" data-id="${escapeHtml(c.class_id)}" data-classid="${escapeHtml(c.classId)}">
                  <i class="bi bi-play-circle me-1"></i>Open Class
                </button>
              ` : `
                <button class="btn lp-btn lp-btn-primary" data-action="enroll" data-id="${escapeHtml(c.class_id)}" data-classid="${escapeHtml(c.classId)}">
                  <i class="bi bi-plus-circle me-1"></i>Enroll
                </button>
              `}
            </div>
          </div>
        `;

        grid.appendChild(col);
      }
    }

    document.addEventListener("DOMContentLoaded", () => {
      buildProgramOptions();

      // Read query from URL: SearchResults.html?q=feng+shui
      const params = new URLSearchParams(window.location.search);
      const q = params.get("q") || "";
      $("searchInput").value = q;

      render();

      ["searchInput", "programFilter", "sortBy"].forEach(id => {
        $(id).addEventListener("input", render);
        $(id).addEventListener("change", render);
      });

      document.addEventListener("click", (e) => {
        const btn = e.target.closest("button[data-action]");
        if(!btn) return;

        const id = btn.getAttribute("data-id");
        const classid = btn.getAttribute("data-classid");
        const action = btn.getAttribute("data-action");

        if(action === "details") {
          console.log("Open details for " + id);
          
          const detailUrlTemplate = '{{ route("classDetail", "__ID__") }}';
          window.location.href = detailUrlTemplate.replace('__ID__', encodeURIComponent(classid));
          
        } 
        if(action === "open") {
          //alert("Open class player for " + id);
          
        }
        if(action === "enroll") {
          @auth
          (async () => {
            if (!classid) return;

            btn.disabled = true;

            try {
              const res = await fetch(`/student/class/${classid}/enroll`, {
                method: "POST",
                headers: {
                  "Content-Type": "application/json",
                  "Accept": "application/json",
                  "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify({ class_id: classid }),
              });

              const data = await res.json();

              if (!res.ok || !data.success) {
                throw new Error(data.message || "Enrollment failed.");
              }

              const item = SEARCH_RESULTS.find(x => String(x.classID) === String(classid));
              if (item) {
                item.enrolled = true;
                item.progress = item.progress || 0;
              }

              alert(data.message || "Register successful");
              render();
            } catch (err) {
              alert(err.message || "Enrollment failed.");
              btn.disabled = false;
            }
          })();
          @else
          window.location.href = "{{ url('/login') }}";
          @endauth
        }
      });
    });
  </script>
@endpush
