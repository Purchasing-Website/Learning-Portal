@extends('layouts.student_app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/search.css') }}">
@endpush

@section('content')
  <div id="enrollMessage" class="alert d-none mb-3" role="alert"></div>

  <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-3">
    <div>
      <h1 class="lp-page-title">Search Results</h1>
      <p class="lp-subtitle">Find classes that match your interests.</p>
    </div>
    <div class="d-flex gap-2">
      <a class="btn lp-btn lp-btn-outline" href="{{ route('home') }}">
        <i class="bi bi-arrow-left me-1"></i>Back
      </a>
    </div>
  </div>

  <div class="lp-summary p-3 p-lg-4 mb-4">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-2">
      <div class="d-flex flex-wrap gap-2">
        <span class="lp-chip"><i class="bi bi-search"></i> Query: <strong>{{ $query !== '' ? $query : '—' }}</strong></span>
        <span class="lp-chip"><i class="bi bi-collection"></i> Found: <strong>{{ (int) ($totalResults ?? $classes->count()) }}</strong></span>
      </div>
      <div class="text-secondary small">
        <i class="bi bi-info-circle me-1"></i>
        Results include enrolled and discoverable classes.
      </div>
    </div>
  </div>

  <div class="lp-controls p-3 mb-4">
    <form id="searchForm" method="GET" action="{{ route('search') }}" class="row g-3 align-items-center">
      <div class="col-12 col-lg-5">
        <div class="lp-search-wrap">
          <i class="bi bi-search"></i>
          <input
            name="q"
            class="form-control lp-input"
            value="{{ $query }}"
            placeholder="Search classes (e.g. Feng Shui, Healing)..."
          />
        </div>
      </div>
      <div class="col-12 col-lg-3">
        <select id="courseSelect" name="course" class="form-select lp-select">
          <option value="0">All Courses</option>
          @foreach(($courses ?? collect()) as $course)
            <option value="{{ $course->id }}" {{ (int) ($selectedCourse ?? 0) === (int) $course->id ? 'selected' : '' }}>
              {{ $course->title }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-12 col-lg-2">
        <select id="sortSelect" name="sort" class="form-select lp-select">
          <option value="latest" {{ ($sort ?? 'latest') === 'latest' ? 'selected' : '' }}>Sort: Latest</option>
          <option value="duration_asc" {{ ($sort ?? '') === 'duration_asc' ? 'selected' : '' }}>Sort: Duration (Short to Long)</option>
          <option value="duration_desc" {{ ($sort ?? '') === 'duration_desc' ? 'selected' : '' }}>Sort: Duration (Long to Short)</option>
          <option value="title_asc" {{ ($sort ?? '') === 'title_asc' ? 'selected' : '' }}>Sort: Title (A to Z)</option>
          <option value="title_desc" {{ ($sort ?? '') === 'title_desc' ? 'selected' : '' }}>Sort: Title (Z to A)</option>
        </select>
      </div>
      <div class="col-12 col-lg-2">
        <button type="submit" class="btn lp-btn lp-btn-primary w-100">Search</button>
      </div>
    </form>
  </div>

  @if($classes->isEmpty())
    <div class="lp-empty mt-4">
      <div class="fs-5 fw-bold mb-1">No results found</div>
      <div>Try another keyword.</div>
    </div>
  @else
    <div class="row g-3 g-lg-4">
      @foreach($classes as $c)
        @php
          $progress = max(0, min(100, (int) ($c['progress'] ?? 0)));
          $enrolled = (bool) ($c['enrolled'] ?? false);
          $totalMin = (int) ($c['duration_total_min'] ?? 0);
          $spentMin = (int) ($c['time_spent_min'] ?? 0);
          $leftMin = max(0, $totalMin - $spentMin);
          $formatMinutes = function (int $minutes): string {
              $hours = intdiv($minutes, 60);
              $mins = $minutes % 60;
              if ($hours > 0 && $mins > 0) {
                  return $hours . 'h ' . $mins . 'm';
              }
              if ($hours > 0) {
                  return $hours . 'h';
              }
              return $mins . 'm';
          };
        @endphp
        <div class="col-12 col-md-6 col-xl-4">
          <div class="lp-class-card">
            <div class="lp-accent"></div>
            <div class="lp-body">
              <div class="d-flex align-items-start justify-content-between gap-2">
                <div>
                  <h3 class="lp-class-title mb-1">{{ $c['class_name'] }}</h3>
                  <p class="lp-class-meta mb-0">{{ $c['program_name'] }}</p>
                </div>
                <div class="text-end">
                  <div class="small text-secondary">Class ID</div>
                  <div class="small fw-semibold">{{ $c['class_id'] }}</div>
                </div>
              </div>

              <div class="lp-badges">
                @if($enrolled)
                  <span class="lp-badge enrolled"><i class="bi bi-check-circle me-1"></i>Enrolled</span>
                @else
                  <span class="lp-badge new"><i class="bi bi-compass me-1"></i>Discover</span>
                @endif
                <span class="lp-badge"><i class="bi bi-hourglass-split me-1"></i>{{ $formatMinutes($totalMin) }} total</span>
              </div>

              @if($enrolled)
                <div class="lp-progress-row">
                  <div class="lp-progress"><div style="width:{{ $progress }}%"></div></div>
                  <div class="lp-progress-pct">{{ $progress }}%</div>
                </div>

                <div class="lp-stats">
                  <div class="lp-stat">
                    <i class="bi bi-stopwatch"></i>
                    <span><strong>{{ $formatMinutes($spentMin) }}</strong> spent</span>
                  </div>
                  <div class="lp-stat">
                    <i class="bi bi-calendar2-week"></i>
                    <span><strong>{{ $formatMinutes($leftMin) }}</strong> left</span>
                  </div>
                </div>
              @endif
            </div>

            <div class="lp-actions">
              <a class="btn lp-btn lp-btn-outline" href="{{ route('classDetail', $c['classId']) }}">
                <i class="bi bi-info-circle me-1"></i>Details
              </a>
              @if($enrolled)
                <a class="btn lp-btn lp-btn-primary" href="{{ route('student.Content', $c['classId']) }}">
                  <i class="bi bi-play-circle me-1"></i>Open Class
                </a>
              @else
                @auth
                  <form method="POST" action="{{ route('student.class.enroll', $c['classId']) }}" class="enroll-form">
                    @csrf
                    <button type="submit" class="btn lp-btn lp-btn-primary">
                      <i class="bi bi-plus-circle me-1"></i>Enroll
                    </button>
                  </form>
                @else
                  <a class="btn lp-btn lp-btn-primary" href="/login">
                    <i class="bi bi-plus-circle me-1"></i>Enroll
                  </a>
                @endauth
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>

    @if(!empty($hasMore))
      <div class="d-flex justify-content-center mt-4">
        <a
          class="btn lp-btn lp-btn-outline"
          href="{{ route('search', array_merge(request()->query(), ['page' => ((int) ($page ?? 1)) + 1])) }}"
        >
          <i class="bi bi-plus-circle me-1"></i>View More
        </a>
      </div>
    @endif
  @endif
@endsection

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const searchForm = document.getElementById("searchForm");
    const courseSelect = document.getElementById("courseSelect");
    const sortSelect = document.getElementById("sortSelect");
    const alertBox = document.getElementById("enrollMessage");
    const forms = document.querySelectorAll(".enroll-form");

    if (courseSelect && searchForm) {
      courseSelect.addEventListener("change", () => {
        searchForm.submit();
      });
    }

    if (sortSelect && searchForm) {
      sortSelect.addEventListener("change", () => {
        searchForm.submit();
      });
    }

    const showMessage = (type, text) => {
      if (!alertBox) return;
      alertBox.classList.remove("d-none", "alert-success", "alert-danger");
      alertBox.classList.add(type === "success" ? "alert-success" : "alert-danger");
      alertBox.textContent = text;
      window.scrollTo({ top: 0, behavior: "smooth" });
    };

    forms.forEach((form) => {
      form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const button = form.querySelector("button[type='submit']");
        if (button) button.disabled = true;

        try {
          const response = await fetch(form.action, {
            method: "POST",
            headers: {
              "Accept": "application/json",
              "X-Requested-With": "XMLHttpRequest",
              "X-CSRF-TOKEN": form.querySelector("input[name='_token']").value
            }
          });

          const data = await response.json();
          showMessage(data.success ? "success" : "error", data.message || "Enrollment failed.");

          if (data.success) {
            window.location.reload();
          }
        } catch (error) {
          showMessage("error", "Unable to enroll right now. Please try again.");
        } finally {
          if (button) button.disabled = false;
        }
      });
    });
  });
</script>
@endpush
