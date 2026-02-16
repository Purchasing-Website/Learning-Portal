@extends('layouts.student_app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/class_details.css') }}">
@endpush

@section('content')

    <!-- Top header -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-3">
      <div>
        {{-- <h1 class="lp-title" id="className">Class</h1> --}}
        <h1 class="lp-title">{{ $classDetail->class_name }}</h1>
        <p class="lp-sub" id="classMeta">{{ $classDetail->tier_name }} • {{ $classDetail->course_name }}</p>
      </div>
      <a class="btn lp-btn lp-btn-outline" href="javascript:history.back()">
        <i class="bi bi-arrow-left me-1"></i>Back
      </a>
    </div>

    <!-- Hero summary -->
    <div class="lp-hero p-3 p-lg-4 mb-4">
      <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
        <div class="d-flex flex-wrap gap-2">
          {{-- <span class="lp-pill"><i class="bi bi-list-check"></i> Lessons: <strong id="totalLessons">0</strong></span>
          <span class="lp-pill"><i class="bi bi-check2-circle"></i> Completed: <strong id="doneLessons">0</strong></span>
          <span class="lp-pill"><i class="bi bi-hourglass-split"></i> Total Time: <strong id="totalTime">0m</strong></span> --}}
          <span class="lp-pill"><i class="bi bi-list-check"></i> Lessons: <strong >{{ $classDetail->total_lessons }}</strong></span>
          <span class="lp-pill"><i class="bi bi-check2-circle"></i> Completed: <strong >0</strong></span>
          <span class="lp-pill"><i class="bi bi-hourglass-split"></i> Total Time: <strong >{{ $classDetail->total_lesson_duration }}m</strong></span>
        </div>

        <div class="d-flex gap-2 flex-wrap">
          <button class="btn lp-btn lp-btn-primary" id="btnResume">
            <i class="bi bi-play-circle me-1"></i><span id="resumeText">Start</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Lessons -->
    <div class="lp-panel">
      <div class="lp-panel-h">
        <h2 class="lp-panel-title mb-0">Lessons</h2>
        <div class="text-secondary small" id="hintText"><i class="bi bi-info-circle me-1"></i>Select a lesson to open.</div>
      </div>

      {{-- <div id="lessonList"></div> --}}

      <div>
        @forelse ($lessons as $lesson)
          <div class="lp-lesson">
            <div class="lp-lesson-idx">{{ $lesson->sequence }}</div>
            <div class="flex-grow-1">
              <p class="lp-lesson-name mb-0">{{ $lesson->title }}</p>
              <div class="lp-lesson-meta">
                <span class="lp-badge"><i class="bi bi-clock"></i>{{ $lesson->duration }}m </span>
                @if ($lesson->status == 'not_started')
                  <span class="lp-badge todo"><i class="bi bi-circle"></i>Not Started</span>
                @elseif ($lesson->status == 'in_progress')
                  <span class="lp-badge progress"><i class="bi bi-hourglass"></i>In Progress</span>
                @elseif ($lesson->status == 'completed')
                  <span class="lp-badge done"><i class="bi bi-check-circle"></i>Completed</span>
                @endif
                
              </div>
            </div>  
            <div class="lp-lesson-actions">
              <a class="btn lp-btn lp-btn-outline" href="{{ route('student.lesson.start', $lesson->id) }}">
                <i class="bi bi-play-circle me-1"></i>Open
              </a>
            </div>
          </div>
        @empty
          <div class="p-3">
            <div class="lp-empty">
              <div class="fw-bold fs-5 mb-1">No lessons found</div>
              <div>This class does not have lessons yet.</div>
            </div>
          </div>
        @endforelse
      </div>

      {{-- <div class="p-3 d-none" id="emptyWrap">
        <div class="lp-empty">
          <div class="fw-bold fs-5 mb-1">No lessons found</div>
          <div>This class does not have lessons yet.</div>
        </div>
      </div>
    </div> --}}

  </main>
  
@endsection

@push('scripts')
  <script>
   
    const DATA = '';
    console.log(@json($lessons));
    const $ = (id) => document.getElementById(id);

    function escapeHtml(str){
      return String(str).replace(/[&<>"']/g, s => ({
        "&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;", "'":"&#039;"
      }[s]));
    }

    function fmtMin(min){
      const m = Math.max(0, Math.round(min || 0));
      const h = Math.floor(m/60);
      const r = m % 60;
      if(h <= 0) return `${r}m`;
      if(r === 0) return `${h}h`;
      return `${h}h ${r}m`;
    }

    function statusBadge(status){
      if(status === "completed"){
        return `<span class="lp-badge done"><i class="bi bi-check-circle"></i>Completed</span>`;
      }
      if(status === "in_progress"){
        return `<span class="lp-badge progress"><i class="bi bi-hourglass"></i>In Progress</span>`;
      }
      return `<span class="lp-badge todo"><i class="bi bi-circle"></i>Not Started</span>`;
    }

    function getClassIdFromUrl(){
      const params = new URLSearchParams(window.location.search);
      return params.get("class") || "CLS-1001";
    }

    function computeResumeTarget(cls){
      // resume = first in_progress, else first not_started, else first lesson
      const lessons = cls.lessons || [];
      return lessons.find(l => l.status === "in_progress")
          || lessons.find(l => l.status === "not_started")
          || lessons[0] || null;
    }

    function render(){
      const cls = DATA;

      if(!cls){
        $("className").textContent = "Class not found";
        $("classMeta").textContent = "";
        $("emptyWrap").classList.remove("d-none");
        return;
      }

      $("className").textContent = cls.class_name;
      $("classMeta").textContent = `${cls.program_name} • ${cls.course_name}`;

      const lessons = cls.lessons || [];
      const totalLessons = lessons.length;
      const doneLessons = lessons.filter(l => l.status === "completed").length;
      const totalTime = lessons.reduce((sum, l) => sum + (l.duration_min || 0), 0);

      $("totalLessons").textContent = totalLessons;
      $("doneLessons").textContent = doneLessons;
      $("totalTime").textContent = fmtMin(totalTime);

      // Resume/Start button label
      const resumeLesson = computeResumeTarget(cls);
      const isAllDone = totalLessons > 0 && doneLessons === totalLessons;
      $("resumeText").textContent = isAllDone ? "Review" : (doneLessons > 0 ? "Resume" : "Start");

      $("btnResume").onclick = () => {
        if(!resumeLesson){
          alert("No lesson available.");
          return;
        }
        // Wire to lesson viewer later:
        // window.location.href = `LessonViewer.html?class=${encodeURIComponent(cls.class_id)}&lesson=${encodeURIComponent(resumeLesson.lesson_id)}`;
        alert(`Open lesson: ${resumeLesson.lesson_id} (${resumeLesson.title})`);
      };

      // Lesson list
      const list = $("lessonList");
      list.innerHTML = "";

      if(totalLessons === 0){
        $("emptyWrap").classList.remove("d-none");
        return;
      }
      $("emptyWrap").classList.add("d-none");

      lessons.forEach((l, idx) => {
        const row = document.createElement("div");
        row.className = "lp-lesson";
        row.innerHTML = `
          <div class="lp-lesson-idx">${idx + 1}</div>

          <div class="flex-grow-1">
            <p class="lp-lesson-name mb-0">${escapeHtml(l.title)}</p>
            <div class="lp-lesson-meta">
              <span class="lp-badge"><i class="bi bi-clock"></i>${fmtMin(l.duration_min)} </span>
              ${statusBadge(l.status)}
            </div>
          </div>

          <div class="lp-lesson-actions">
            <button class="btn lp-btn lp-btn-outline" data-lesson="${escapeHtml(l.lesson_id)}">
              <i class="bi bi-play-circle me-1"></i>Open
            </button>
          </div>
        `;
        list.appendChild(row);
      });
    }

    document.addEventListener("DOMContentLoaded", () => {
      render();

      // Open lesson button handler
      document.addEventListener("click", (e) => {
        const btn = e.target.closest("button[data-lesson]");
        if(!btn) return;
        const lessonId = btn.getAttribute("data-lesson");

        // Wire to lesson viewer later:
        // window.location.href = `LessonViewer.html?class=${encodeURIComponent(getClassIdFromUrl())}&lesson=${encodeURIComponent(lessonId)}`;

        alert("Open lesson: " + lessonId);
      });

      // Logout demo
      document.getElementById("btnLogout")?.addEventListener("click", (e) => {
        e.preventDefault();
        alert("Logged out!");
      });
    });
  </script>
@endpush
