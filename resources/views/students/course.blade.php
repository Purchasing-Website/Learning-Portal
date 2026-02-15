@extends('layouts.student_app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/course.css') }}">
@endpush

@section('content')
    <!-- Header -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-3">
      <div>
        <h1 class="lp-page-title">All Courses</h1>
        <p class="lp-subtitle">Browse courses across all programs.</p>
      </div>
      <a class="btn lp-btn lp-btn-outline" href="{{ route('home') }}">
        <i class="bi bi-arrow-left me-1"></i>Back
      </a>
    </div>

    <!-- Summary -->
    <div class="lp-summary p-3 p-lg-4 mb-4">
      <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-2">
        <div class="d-flex flex-wrap gap-2">
          <span class="lp-chip"><i class="bi bi-collection"></i> Courses: <strong id="chipCount">0</strong></span>
          <span class="lp-chip"><i class="bi bi-funnel"></i> Program: <strong id="chipProgram">All</strong></span>
        </div>
        <div class="text-secondary small">
          <i class="bi bi-info-circle me-1"></i>
          Click a course to view its classes (you can wire to Course page later).
        </div>
      </div>
    </div>

    <!-- Controls -->
    <div class="lp-controls p-3 mb-4">
      <div class="row g-3 align-items-center">
        <div class="col-12 col-lg-6">
          <div class="lp-search-wrap">
            <i class="bi bi-search"></i>
            <input id="q" class="form-control lp-input" placeholder="Search courses (e.g. Foundations)..." />
          </div>
        </div>

        <div class="col-6 col-lg-3">
          <select id="programFilter" class="form-select lp-select">
            <option value="all">All Programs</option>
          </select>
        </div>

        <div class="col-6 col-lg-3">
          <select id="sortBy" class="form-select lp-select">
            <option value="name_asc">Sort: Course Name (A → Z)</option>
            <option value="name_desc">Sort: Course Name (Z → A)</option>
            <option value="program_asc">Sort: Program (A → Z)</option>
            <option value="hours_desc">Sort: Total Hours (High → Low)</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Grid -->
    {{-- <div class="row g-3" id="grid"> --}}
    <div class="row g-3">
      @foreach ($courses as $course)
        <div class="col-12 col-md-6 col-xl-4">
          <a href="{{ route('getclass', $course->id) }}" class="text-decoration-none text-reset d-block">
          <div class="lp-course-card" data-course="{{ $course->id }}">
            <div class="lp-course-row">
              <div class="lp-icon-block"><i class="bi bi-journals"></i></div>

              <div>
                <p class="lp-course-title mb-0">{{ $course->title }}</p>
              </div>

              <div class="lp-right">
                <div class="lp-k">Course ID</div>
                <div class="lp-v">{{ 'C' . str_pad($course->id, 3, '0', STR_PAD_LEFT) }}</div>
              </div>
            </div>

            <!-- Keep total hours (allowed). If you don't want it, remove this footer block. -->
            <div class="lp-course-footer">
              <span class="lp-stat"><i class="bi bi-hourglass-split"></i>{{ $course->classes_count }} total</span>
              <span class="text-secondary small"> </span>
            </div>
          </div>
          </a>
        </div>
      @endforeach
    </div>

    <div class="lp-empty mt-4 d-none" id="empty">
      <div class="fw-bold fs-5 mb-1">No courses found</div>
      <div>Try another keyword or choose “All Programs”.</div>
    </div>
@endsection
  
@push('scripts')
<script>
    // ===== Demo academy data (same structure as your Home) =====
    // const ACADEMY = [
    //   {
    //     program_id: "P001",
    //     program_name: "Feng Shui",
    //     courses: [
    //       { course_id: "C001", course_name: "Feng Shui Foundations", total_min: 360 },
    //       { course_id: "C002", course_name: "Advanced Home Layout", total_min: 420 }
    //     ]
    //   },
    //   {
    //     program_id: "P002",
    //     program_name: "Healing",
    //     courses: [
    //       { course_id: "C010", course_name: "Mind & Body Healing", total_min: 210 }
    //     ]
    //   },
    //   {
    //     program_id: "P003",
    //     program_name: "Meditation",
    //     courses: [
    //       { course_id: "C020", course_name: "Meditation Essentials", total_min: 260 }
    //     ]
    //   },
    //   {
    //     program_id: "P004",
    //     program_name: "Numerology",
    //     courses: [
    //       { course_id: "C030", course_name: "Yi Numerology Basics", total_min: 180 }
    //     ]
    //   }
    // ];

    const $ = (id) => document.getElementById(id);

    function escapeHtml(str){
      return String(str).replace(/[&<>"']/g, s => ({
        "&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;", "'":"&#039;"
      }[s]));
    }

    function formatMinutes(min){
      const m = Math.max(0, Math.round(min || 0));
      const h = Math.floor(m / 60);
      const r = m % 60;
      if (h <= 0) return r + "m";
      if (r === 0) return h + "h";
      return h + "h " + r + "m";
    }

    function getAllCourses(){
      const list = [];
      ACADEMY.forEach(p => {
        (p.courses || []).forEach(c => {
          list.push({
            ...c,
            program_id: p.program_id,
            program_name: p.program_name
          });
        });
      });
      return list;
    }

    function buildProgramOptions(){
      const sel = $("programFilter");
      const programs = ACADEMY.map(p => p.program_name).sort();
      programs.forEach(name => {
        const opt = document.createElement("option");
        opt.value = name;
        opt.textContent = name;
        sel.appendChild(opt);
      });
    }

    function render(){
      const query = ($("q").value || "").trim().toLowerCase();
      const program = $("programFilter").value;
      const sortBy = $("sortBy").value;

      let items = getAllCourses().filter(c => {
        const matchesProgram = (program === "all") ? true : c.program_name === program;
        if(!matchesProgram) return false;

        if(!query) return true;
        const hay = (c.course_name + " " + c.course_id + " " + c.program_name).toLowerCase();
        return query.split(/\s+/).filter(Boolean).every(w => hay.includes(w));
      });

      items.sort((a,b) => {
        if(sortBy === "name_asc") return a.course_name.localeCompare(b.course_name);
        if(sortBy === "name_desc") return b.course_name.localeCompare(a.course_name);
        if(sortBy === "program_asc") return a.program_name.localeCompare(b.program_name);
        if(sortBy === "hours_desc") return (b.total_min||0) - (a.total_min||0);
        return 0;
      });

      $("chipCount").textContent = items.length;
      $("chipProgram").textContent = (program === "all") ? "All" : program;

      const grid = $("grid");
      grid.innerHTML = "";

      if(items.length === 0){
        $("empty").classList.remove("d-none");
        return;
      }
      $("empty").classList.add("d-none");

      items.forEach(c => {
        const col = document.createElement("div");
        col.className = "col-12 col-md-6 col-xl-4";

        col.innerHTML = `
          <div class="lp-course-card" data-course="${escapeHtml(c.course_id)}">
            <div class="lp-course-row">
              <div class="lp-icon-block"><i class="bi bi-journals"></i></div>

              <div>
                <p class="lp-course-title mb-0">${escapeHtml(c.course_name)}</p>
                <p class="lp-course-meta mb-0">${escapeHtml(c.program_name)}</p>
              </div>

              <div class="lp-right">
                <div class="lp-k">Course ID</div>
                <div class="lp-v">${escapeHtml(c.course_id)}</div>
              </div>
            </div>

            <!-- Keep total hours (allowed). If you don't want it, remove this footer block. -->
            <div class="lp-course-footer">
              <span class="lp-stat"><i class="bi bi-hourglass-split"></i>${formatMinutes(c.total_min)} total</span>
              <span class="text-secondary small"> </span>
            </div>
          </div>
        `;

        grid.appendChild(col);
      });
    }

    document.addEventListener("DOMContentLoaded", () => {
      buildProgramOptions();
      render();

      $("q").addEventListener("input", render);
      $("programFilter").addEventListener("change", render);
      $("sortBy").addEventListener("change", render);

      // Click action - wire to Course page later
      document.addEventListener("click", (e) => {
        const card = e.target.closest("[data-course]");
        if(!card) return;
        const cid = card.getAttribute("data-course");

        // Option A: Go back Home and auto-select course (if you support it)
        // window.location.href = `Home.html?course=${encodeURIComponent(cid)}`;

        // Option B: Future course detail page
        // window.location.href = `Course.html?course=${encodeURIComponent(cid)}`;

        alert("Clicked course: " + cid);
      });
    });
  </script>
@endpush
