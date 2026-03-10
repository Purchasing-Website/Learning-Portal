@extends('layouts.student_app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/class.css') }}">
@endpush

@section('content')
@php
  $allClasses = collect($classes ?? []);
  $q = trim((string) request('q', ''));
  $program = (string) request('program', 'all');
  $sortBy = (string) request('sort', 'progress_desc');

  $programs = $allClasses
    ->pluck('program_name')
    ->filter()
    ->unique()
    ->sort()
    ->values();

  $filtered = $allClasses->filter(function ($it) use ($q, $program) {
    $matchesProgram = $program === 'all' || data_get($it, 'program_name') === $program;
    if (!$matchesProgram) {
      return false;
    }

    if ($q === '') {
      return true;
    }

    $hay = mb_strtolower(
      (string) data_get($it, 'class_name', '') . ' ' .
      (string) data_get($it, 'program_name', '') . ' ' .
      (string) data_get($it, 'class_id', '')
    );

    foreach (preg_split('/\s+/', mb_strtolower($q), -1, PREG_SPLIT_NO_EMPTY) as $word) {
      if (!str_contains($hay, $word)) {
        return false;
      }
    }

    return true;
  });

  $sorted = match ($sortBy) {
    'duration_asc' => $filtered->sortBy('duration_total_min')->values(),
    'title_asc' => $filtered->sortBy('class_name')->values(),
    default => $filtered->sortByDesc('progress')->values(),
  };
@endphp

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-3">
  <div>
    <h1 class="lp-page-title">Classes v2</h1>
    <p class="lp-subtitle">Server-rendered class catalog.</p>
  </div>
  <a class="btn lp-btn lp-btn-outline" href="{{ route('home') }}">
    <i class="bi bi-arrow-left me-1"></i>Back
  </a>
</div>

<div class="lp-summary p-3 p-lg-4 mb-4">
  <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-2">
    <div class="d-flex flex-wrap gap-2">
      <span class="lp-chip"><i class="bi bi-layers"></i> Classes: <strong>{{ $sorted->count() }}</strong></span>
      <span class="lp-chip"><i class="bi bi-funnel"></i> Course: <strong>{{ $program === 'all' ? 'All' : $program }}</strong></span>
    </div>
    <div class="text-secondary small">
      <i class="bi bi-info-circle me-1"></i>
      Server-side filtered and sorted.
    </div>
  </div>
</div>

<div id="enrollMessage" class="d-none mb-3 alert" role="alert"></div>

<form class="lp-controls p-3 mb-4" method="GET" action="">
  <div class="row g-3 align-items-center">
    <div class="col-12 col-lg-6">
      <div class="lp-search-wrap">
        <i class="bi bi-search"></i>
        <input name="q" class="form-control lp-input" value="{{ $q }}" placeholder="Search classes..." />
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <select name="program" class="form-select lp-select">
        <option value="all" @selected($program === 'all')>All Courses</option>
        @foreach ($programs as $programName)
          <option value="{{ $programName }}" @selected($program === $programName)>{{ $programName }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-6 col-lg-3">
      <select name="sort" class="form-select lp-select">
        <option value="progress_desc" @selected($sortBy === 'progress_desc')>Sort: Progress (High -> Low)</option>
        <option value="duration_asc" @selected($sortBy === 'duration_asc')>Sort: Duration (Short -> Long)</option>
        <option value="title_asc" @selected($sortBy === 'title_asc')>Sort: Title (A -> Z)</option>
      </select>
    </div>
  </div>
</form>

@if($sorted->isEmpty())
  <div class="lp-empty mt-4">
    <div class="fs-5 fw-bold mb-1">No results found</div>
    <div>Try another keyword or choose "All Courses".</div>
  </div>
@else
  <div class="row g-3 g-lg-4">
    @foreach ($sorted as $c)
      @php
        $isEnrolled = (bool) data_get($c, 'enrolled', false);
        $progress = (int) max(0, min(100, (float) data_get($c, 'progress', 0)));
        $spent = (int) max(0, (float) data_get($c, 'time_spent_min', 0));
        $total = (int) max(0, (float) data_get($c, 'duration_total_min', 0));
        $left = max(0, $total - $spent);
        $classId = data_get($c, 'classId');
        $classCode = data_get($c, 'class_id');
      @endphp

      <div class="col-12 col-md-6 col-xl-4">
        <div class="lp-class-card">
          <div class="lp-accent"></div>

          <div class="lp-body">
            <div class="d-flex align-items-start justify-content-between gap-2">
              <div>
                <h3 class="lp-class-title mb-1">{{ data_get($c, 'class_name') }}</h3>
                <p class="lp-class-meta mb-0">{{ data_get($c, 'program_name') }}</p>
              </div>
              <div class="text-end">
                <div class="small text-secondary">Class ID</div>
                <div class="small fw-semibold">{{ $classCode }}</div>
              </div>
            </div>

            <div class="lp-badges">
              @if($isEnrolled)
                <span class="lp-badge enrolled"><i class="bi bi-check-circle me-1"></i>Enrolled</span>
              @else
                <span class="lp-badge new"><i class="bi bi-compass me-1"></i>Discover</span>
              @endif
              <span class="lp-badge"><i class="bi bi-hourglass-split me-1"></i>{{ floor($total / 60) > 0 ? floor($total / 60) . 'h ' . ($total % 60) . 'm' : ($total % 60) . 'm' }} total</span>
            </div>

            @if($isEnrolled)
              <div class="lp-progress-row">
                <div class="lp-progress"><div style="width:{{ $progress }}%"></div></div>
                <div class="lp-progress-pct">{{ $progress }}%</div>
              </div>

              <div class="lp-stats">
                <div class="lp-stat"><i class="bi bi-stopwatch"></i><span><strong>{{ floor($spent / 60) > 0 ? floor($spent / 60) . 'h ' . ($spent % 60) . 'm' : ($spent % 60) . 'm' }}</strong> spent</span></div>
                <div class="lp-stat"><i class="bi bi-calendar2-week"></i><span><strong>{{ floor($left / 60) > 0 ? floor($left / 60) . 'h ' . ($left % 60) . 'm' : ($left % 60) . 'm' }}</strong> left</span></div>
              </div>
            @else
              <div class="mt-3 text-secondary small">
                <i class="bi bi-stars me-1"></i>
                Suggested for you based on your search and popularity.
              </div>
            @endif
          </div>

          <div class="lp-actions">
            <a class="btn lp-btn lp-btn-outline" href="{{ route('classDetail', $classId) }}">
              <i class="bi bi-info-circle me-1"></i>Details
            </a>

            @if($isEnrolled)
              <a class="btn lp-btn lp-btn-primary" href="{{ route('student.Content', $classId) }}">
                <i class="bi bi-play-circle me-1"></i>Open Class
              </a>
            @else
              @auth
                <button
                  class="btn lp-btn lp-btn-primary"
                  type="button"
                  data-enroll-btn
                  data-class-id="{{ $classId }}"
                >
                  <i class="bi bi-plus-circle me-1"></i>Enroll
                </button>
              @else
                <a class="btn lp-btn lp-btn-primary" href="{{ url('/login') }}">
                  <i class="bi bi-plus-circle me-1"></i>Enroll
                </a>
              @endauth
            @endif
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endif
@endsection

@push('scripts')
<script>
  const ENROLL_MESSAGE_KEY = "class_v2_enroll_message";

  function showEnrollMessage(message, type) {
    const box = document.getElementById("enrollMessage");
    if (!box) return;
    box.classList.remove("d-none", "alert-success", "alert-danger");
    box.classList.add(type === "success" ? "alert-success" : "alert-danger");
    box.textContent = message;
  }

  document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form.lp-controls");
    if (form) {
      const autoSubmitEls = form.querySelectorAll('select[name="program"], select[name="sort"]');
      autoSubmitEls.forEach((el) => {
        el.addEventListener("change", () => form.submit());
      });
    }

    try {
      const saved = sessionStorage.getItem(ENROLL_MESSAGE_KEY);
      if (saved) {
        const parsed = JSON.parse(saved);
        showEnrollMessage(parsed.message || "Enrollment updated.", parsed.type || "success");
        sessionStorage.removeItem(ENROLL_MESSAGE_KEY);
      }
    } catch (_) {}
  });

  document.addEventListener("click", (e) => {
    const btn = e.target.closest("[data-enroll-btn]");
    if (!btn) return;

    const classId = btn.getAttribute("data-class-id");
    if (!classId) return;

    btn.disabled = true;

    fetch(`/student/class/${classId}/enroll`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      },
      body: JSON.stringify({ class_id: classId })
    })
      .then(async (res) => {
        const data = await res.json();
        if (!res.ok || !data.success) {
          throw new Error(data.message || "Enrollment failed.");
        }
        sessionStorage.setItem(ENROLL_MESSAGE_KEY, JSON.stringify({
          type: "success",
          message: data.message || "Student enrolled successfully."
        }));
        window.location.reload();
      })
      .catch((err) => {
        showEnrollMessage(err.message || "Enrollment failed.", "error");
        btn.disabled = false;
      });
  });
</script>
@endpush
