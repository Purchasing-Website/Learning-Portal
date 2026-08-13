@extends('layouts.student_app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/course.css') }}">
@endpush

@section('content')
    <!-- Header -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-3">
      <div>
        <h1 class="lp-page-title">All Courses</h1>
        <p class="lp-subtitle">Browse all available courses.</p>
      </div>
      <a class="btn lp-btn lp-btn-outline" href="{{ route('home') }}">
        <i class="bi bi-arrow-left me-1"></i>Back
      </a>
    </div>

    <!-- Summary -->
    <div class="lp-summary p-3 p-lg-4 mb-4">
      <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-2">
        <div class="d-flex flex-wrap gap-2">
          <span class="lp-chip">
            <i class="bi bi-collection"></i>
            Courses: <strong id="chipCount">{{ $courseCount }}</strong>
          </span>
        </div>
        <div class="text-secondary small">
          <i class="bi bi-info-circle me-1"></i>
          Click a course to view its classes.
        </div>
      </div>
    </div>

    <!-- Controls -->
    <div class="lp-controls p-3 mb-4">
      <div class="row g-3 align-items-center">
        <div class="col-12 col-lg-8">
          <div class="lp-search-wrap">
            <i class="bi bi-search"></i>
            <input
              id="q"
              type="search"
              class="form-control lp-input"
              placeholder="Search by course name or course ID..."
              autocomplete="off"
            />
          </div>
        </div>

        <div class="col-12 col-lg-4">
          <select id="sortBy" class="form-select lp-select">
            <option value="name_asc">Sort: Course Name (A → Z)</option>
            <option value="name_desc">Sort: Course Name (Z → A)</option>
            <option value="classes_desc">Sort: Total Classes (High → Low)</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Course Grid -->
    <div class="row g-3" id="grid">
      @foreach ($courses as $course)
        @php
          $courseCode = 'C' . str_pad($course->id, 3, '0', STR_PAD_LEFT);
        @endphp

        <div
          class="col-12 col-md-6 col-xl-4"
          data-course-item
          data-course-title="{{ $course->title }}"
          data-course-code="{{ $courseCode }}"
          data-classes-count="{{ $course->classes_count }}"
        >
          <a
            href="{{ route('getclass', $course->id) }}"
            class="text-decoration-none text-reset d-block"
          >
            <div class="lp-course-card" data-course="{{ $course->id }}">
              <div class="lp-course-row">
                <div class="lp-icon-block">
                  <i class="bi bi-journals"></i>
                </div>

                <div>
                  <p class="lp-course-title mb-0">{{ $course->title }}</p>
                </div>

                <div class="lp-right">
                  <div class="lp-k">Course ID</div>
                  <div class="lp-v">{{ $courseCode }}</div>
                </div>
              </div>

              <div class="lp-course-footer">
                <span class="lp-stat">
                  <i class="bi bi-hourglass-split"></i>
                  {{ $course->classes_count }} total
                </span>
                <span class="text-secondary small"></span>
              </div>
            </div>
          </a>
        </div>
      @endforeach
    </div>

    <!-- Empty Search Result -->
    <div class="lp-empty mt-4 d-none" id="empty">
      <div class="fw-bold fs-5 mb-1">No courses found</div>
      <div>Try searching with another course name or course ID.</div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('q');
    const sortSelect = document.getElementById('sortBy');
    const grid = document.getElementById('grid');
    const emptyMessage = document.getElementById('empty');
    const countLabel = document.getElementById('chipCount');

    if (!searchInput || !sortSelect || !grid || !emptyMessage || !countLabel) {
        return;
    }

    const courseItems = Array.from(
        grid.querySelectorAll('[data-course-item]')
    );

    function normalise(value) {
        return String(value || '').trim().toLocaleLowerCase();
    }

    function updateCourses() {
        const keywords = normalise(searchInput.value)
            .split(/\s+/)
            .filter(Boolean);

        const sortBy = sortSelect.value;
        let visibleCount = 0;

        courseItems.forEach(function (item) {
            const searchableText = normalise(
                item.dataset.courseTitle + ' ' + item.dataset.courseCode
            );

            const matchesSearch = keywords.every(function (keyword) {
                return searchableText.includes(keyword);
            });

            item.classList.toggle('d-none', !matchesSearch);

            if (matchesSearch) {
                visibleCount++;
            }
        });

        courseItems.sort(function (first, second) {
            const firstTitle = normalise(first.dataset.courseTitle);
            const secondTitle = normalise(second.dataset.courseTitle);

            if (sortBy === 'name_desc') {
                return secondTitle.localeCompare(firstTitle);
            }

            if (sortBy === 'classes_desc') {
                const firstCount = Number(first.dataset.classesCount) || 0;
                const secondCount = Number(second.dataset.classesCount) || 0;

                return secondCount - firstCount;
            }

            return firstTitle.localeCompare(secondTitle);
        });

        courseItems.forEach(function (item) {
            grid.appendChild(item);
        });

        countLabel.textContent = visibleCount;
        emptyMessage.classList.toggle('d-none', visibleCount > 0);
    }

    searchInput.addEventListener('input', updateCourses);
    sortSelect.addEventListener('change', updateCourses);

    updateCourses();
});
</script>
@endpush