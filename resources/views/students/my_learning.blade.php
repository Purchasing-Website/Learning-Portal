@extends('layouts.student_app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/my_learning.css') }}">
@endpush

@section('content')
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

  <div class="row g-3 g-lg-4" id="classGrid">
    @foreach(($enrolledClasses ?? []) as $class)
      @php
        $status = $class['status'] ?? 'not_started';
        $progress = max(0, min(100, (int) ($class['progress'] ?? 0)));
        $spentMin = max(0, (int) ($class['time_spent_min'] ?? 0));
        $totalMin = max(0, (int) ($class['duration_total_min'] ?? 0));
        $leftMin = max(0, $totalMin - $spentMin);
        $statusLabel = $status === 'completed' ? 'Completed' : ($status === 'in_progress' ? 'In Progress' : 'Not Started');
        $buttonText = $status === 'completed' ? 'Review' : ($status === 'not_started' ? 'Start' : 'Continue');
        $buttonIcon = $status === 'completed' ? 'bi-arrow-repeat' : 'bi-play-circle';
      @endphp
      <div
        class="col-12 col-md-6 col-xl-4"
        data-class-item
        data-id="{{ $class['id'] ?? '' }}"
        data-status="{{ $status }}"
        data-progress="{{ $progress }}"
        data-enrolled="{{ $class['date_enrolled'] ?? '' }}"
        data-time="{{ $spentMin }}"
        data-title="{{ strtolower($class['class_name'] ?? '') }}"
        data-search="{{ strtolower(($class['class_name'] ?? '') . ' ' . ($class['course_name'] ?? '') . ' ' . ($class['instructor'] ?? '') . ' ' . ($class['id'] ?? '')) }}"
      >
        <div class="lp-class-card h-100">
          <div style="height:10px;background:linear-gradient(90deg,var(--lp-blue2),var(--lp-purple),var(--lp-pink));"></div>

          <div class="lp-class-body">
            <div class="d-flex align-items-start justify-content-between gap-2">
              <div>
                <h3 class="lp-class-title">{{ $class['class_name'] ?? '' }}</h3>
                <p class="lp-class-meta mb-0">{{ $class['course_name'] ?? '' }}</p>
              </div>
              <span class="lp-badge">{{ $class['id'] ?? '' }}</span>
            </div>

            <div class="lp-progress-row mt-3">
              <div class="lp-progress" aria-label="Progress">
                <div class="js-progress-fill" data-progress="{{ $progress }}"></div>
              </div>
              <div class="lp-progress-pct">{{ $progress }}%</div>
            </div>

            <div class="lp-stats">
              <div class="lp-stat">
                <i class="bi bi-hourglass-split"></i>
                <span><strong class="js-minutes" data-minutes="{{ $spentMin }}">{{ $spentMin }}</strong> spent</span>
              </div>
              <div class="lp-stat">
                <i class="bi bi-calendar2-week"></i>
                <span><strong class="js-minutes" data-minutes="{{ $leftMin }}">{{ $leftMin }}</strong> left</span>
              </div>
              <div class="lp-stat">
                <i class="bi bi-collection-play"></i>
                <span><span class="js-minutes" data-minutes="{{ $totalMin }}">{{ $totalMin }}</span> total</span>
              </div>
            </div>

            <div class="mt-3 small text-secondary">
              <span class="lp-badge">{{ $statusLabel }}</span>
            </div>
          </div>

          <div class="lp-actions mt-auto">
            
              <a href="{{ route('classDetail',$class['classId']) }}" class="btn lp-btn lp-btn-outline" data-action="details">
                <i class="bi bi-info-circle me-1"></i>Details
              </a>
              <a href="{{ route('student.Content',$class['classId']) }}" class="btn lp-btn lp-btn-primary" data-action="continue">
                <i class="bi {{ $buttonIcon }} me-1"></i>{{ $buttonText }}
              </a>
            
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="lp-empty mt-4 {{ empty($enrolledClasses) ? '' : 'd-none' }}" id="emptyState">
    <div class="fs-5 fw-bold mb-1">No classes found</div>
    <div>Try changing the filter or search keyword.</div>
  </div>
@endsection

@push('scripts')
<script>
  const $ = (id) => document.getElementById(id);

  function clamp(n, min, max) {
    return Math.max(min, Math.min(max, n));
  }

  function formatMinutes(min) {
    const m = Math.max(0, Math.round(Number(min) || 0));
    const h = Math.floor(m / 60);
    const r = m % 60;
    if (h <= 0) return r + 'm';
    if (r === 0) return h + 'h';
    return h + 'h ' + r + 'm';
  }

  function applyProgressBars() {
    document.querySelectorAll('.js-progress-fill').forEach((el) => {
      const progress = clamp(Number(el.dataset.progress || 0), 0, 100);
      el.style.width = progress + '%';
    });
  }

  function applyMinuteLabels() {
    document.querySelectorAll('.js-minutes').forEach((el) => {
      el.textContent = formatMinutes(el.dataset.minutes);
    });
  }

  function applyFiltersAndSort() {
    const q = (($('searchInput')?.value || '').trim().toLowerCase());
    const status = $('statusFilter')?.value || 'all';
    const sortBy = $('sortBy')?.value || 'enrolled_desc';
    const grid = $('classGrid');
    const cards = Array.from(document.querySelectorAll('[data-class-item]'));

    cards.sort((a, b) => {
      if (sortBy === 'enrolled_desc') return new Date(b.dataset.enrolled || 0) - new Date(a.dataset.enrolled || 0);
      if (sortBy === 'progress_desc') return Number(b.dataset.progress || 0) - Number(a.dataset.progress || 0);
      if (sortBy === 'progress_asc') return Number(a.dataset.progress || 0) - Number(b.dataset.progress || 0);
      if (sortBy === 'time_desc') return Number(b.dataset.time || 0) - Number(a.dataset.time || 0);
      if (sortBy === 'class_name_asc') return String(a.dataset.title || '').localeCompare(String(b.dataset.title || ''));
      return 0;
    });

    let visibleCount = 0;
    let inProgressCount = 0;
    let completedCount = 0;
    let totalSpent = 0;

    cards.forEach((card) => {
      const matchesSearch = (card.dataset.search || '').includes(q);
      const matchesStatus = status === 'all' || (card.dataset.status || '') === status;
      const visible = matchesSearch && matchesStatus;

      card.classList.toggle('d-none', !visible);
      grid.appendChild(card);

      if (visible) {
        visibleCount += 1;
        totalSpent += Number(card.dataset.time || 0);

        if ((card.dataset.status || '') === 'in_progress') inProgressCount += 1;
        if ((card.dataset.status || '') === 'completed') completedCount += 1;
      }
    });

    $('emptyState')?.classList.toggle('d-none', visibleCount > 0);
    if ($('mEnrolled')) $('mEnrolled').textContent = String(visibleCount);
    if ($('mInProgress')) $('mInProgress').textContent = String(inProgressCount);
    if ($('mCompleted')) $('mCompleted').textContent = String(completedCount);
    if ($('mTime')) $('mTime').textContent = formatMinutes(totalSpent);
  }

  document.addEventListener('DOMContentLoaded', () => {
    applyProgressBars();
    applyMinuteLabels();
    applyFiltersAndSort();

    ['searchInput', 'statusFilter', 'sortBy'].forEach((id) => {
      const el = $(id);
      if (!el) return;
      el.addEventListener('input', applyFiltersAndSort);
      el.addEventListener('change', applyFiltersAndSort);
    });

    $('btnRefresh')?.addEventListener('click', applyFiltersAndSort);

  });
</script>
@endpush
