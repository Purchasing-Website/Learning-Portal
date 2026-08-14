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
          <span class="lp-chip">
            <i class="bi bi-layers"></i>
            Classes: <strong id="chipCount">0</strong>
          </span>

          <span class="lp-chip">
            <i class="bi bi-funnel"></i>
            Course: <strong id="chipCourse">All</strong>
          </span>
        </div>
      </div>
    </div>

    <!-- Controls -->
    <div class="lp-controls p-3 mb-4">
      <div class="row g-3 align-items-center">
        <div class="col-12 col-lg-6">
          <div class="lp-search-wrap">
            <i class="bi bi-search"></i>
            <input
              id="searchInput"
              type="search"
              class="form-control lp-input"
              placeholder="Search classes by name or class ID..."
              autocomplete="off"
            />
          </div>
        </div>

        <!-- Course filter retained -->
        <div class="col-6 col-lg-3">
        <select id="courseFilter" class="form-select lp-select">
          <option value="all">All Courses</option>

          @foreach (($courseOptions ?? collect()) as $courseOption)
            <option value="{{ $courseOption->id }}">
              {{ $courseOption->title }}
            </option>
          @endforeach
        </select>
        </div>

        <div class="col-6 col-lg-3">
          <select id="sortBy" class="form-select lp-select">
            <option value="progress_desc">
              Sort: Progress (High → Low)
            </option>
            <option value="duration_asc">
              Sort: Duration (Short → Long)
            </option>
            <option value="title_asc">
              Sort: Title (A → Z)
            </option>
          </select>
        </div>
      </div>
    </div>

    <!-- Results grid -->
    <div class="row g-3 g-lg-4" id="resultsGrid"></div>

    <!-- Empty state -->
    <div class="lp-empty mt-4 d-none" id="emptyState">
      <div class="fs-5 fw-bold mb-1">No results found</div>
      <div>Try another keyword or choose “All Courses”.</div>
    </div>
@endsection

@push('scripts')
<script>
  const SEARCH_RESULTS = {{ \Illuminate\Support\Js::from($classes) }};
  const ENROLLMENT_STATE_KEY = "student_class_enrollments";

  const $ = (id) => document.getElementById(id);

  /*
   * These helper functions support either array format:
   *
   * class_name / class_id
   * or title / id.
   * Course data is read separately from the courses array.
   */
  function getClassName(item) {
    return String(
      item?.class_name ??
      item?.title ??
      ""
    );
  }

  function getDisplayClassId(item) {
    return String(
      item?.class_id ??
      item?.classId ??
      item?.id ??
      ""
    );
  }

  function getCourseName(item) {
    if (Array.isArray(item?.courses) && item.courses.length > 0) {
      return item.courses
        .map(course => String(course?.title ?? ""))
        .filter(Boolean)
        .join(", ");
    }

    return String(
      item?.course_name ??
      item?.course?.title ??
      ""
    );
  }

  function getClassKey(item) {
    return String(
      item?.classId ??
      item?.classID ??
      item?.id ??
      item?.class_id ??
      ""
    );
  }

  function normalise(value) {
    return String(value || "")
      .trim()
      .toLocaleLowerCase();
  }

  function formatMinutes(min) {
    const m = Math.max(0, Math.round(min || 0));
    const h = Math.floor(m / 60);
    const r = m % 60;

    if (h <= 0) {
      return r + "m";
    }

    if (r === 0) {
      return h + "h";
    }

    return h + "h " + r + "m";
  }

  function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, character => ({
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#039;"
    }[character]));
  }

  function highlight(text, query) {
    const safeText = escapeHtml(text);
    const cleanedQuery = String(query || "").trim();

    if (!cleanedQuery) {
      return safeText;
    }

    const keywords = cleanedQuery
      .split(/\s+/)
      .filter(Boolean);

    let result = safeText;

    keywords.forEach(keyword => {
      const escapedKeyword = keyword.replace(
        /[.*+?^${}()|[\]\\]/g,
        "\\$&"
      );

      const expression = new RegExp(
        `(${escapedKeyword})`,
        "ig"
      );

      result = result.replace(
        expression,
        "<mark>$1</mark>"
      );
    });

    return result;
  }

  function loadEnrollmentState() {
    try {
      return JSON.parse(
        sessionStorage.getItem(
          ENROLLMENT_STATE_KEY
        ) || "{}"
      );
    } catch (_) {
      return {};
    }
  }

  function saveEnrollmentState(state) {
    try {
      sessionStorage.setItem(
        ENROLLMENT_STATE_KEY,
        JSON.stringify(state)
      );
    } catch (_) {
      // Ignore unavailable session storage.
    }
  }

  function applyEnrollmentState() {
    const state = loadEnrollmentState();

    SEARCH_RESULTS.forEach(item => {
      const classKey = getClassKey(item);

      if (!classKey || !state[classKey]) {
        return;
      }

      item.enrolled = true;
      item.progress = item.progress || 0;
    });
  }

  function getCourseIds(item) {
    if (!Array.isArray(item?.courses)) {
      return [];
    }

    return item.courses.map(course =>
      String(course.id)
    );
  }

  function render() {
    const query = (
      $("searchInput").value || ""
    ).trim();

    const selectedCourse =
      $("courseFilter").value;

    const sortBy =
      $("sortBy").value;

    const keywords = normalise(query)
      .split(/\s+/)
      .filter(Boolean);

    /*
     * Keyword search:
     * Only class name and class ID are searchable.
     *
     * Course selection is handled separately.
     */
    let items = SEARCH_RESULTS.filter(item => {
      const matchesCourse =
        selectedCourse === "all" ||
        getCourseIds(item).includes(
          String(selectedCourse)
        );

      const searchableText = normalise(
        getClassName(item) +
        " " +
        getDisplayClassId(item)
      );

      const matchesSearch = keywords.every(
        keyword => searchableText.includes(keyword)
      );

      return matchesCourse && matchesSearch;
    });

    items.sort((first, second) => {
      if (sortBy === "progress_desc") {
        return (
          Number(second.progress || 0) -
          Number(first.progress || 0)
        );
      }

      if (sortBy === "duration_asc") {
        return (
          Number(first.duration_total_min || 0) -
          Number(second.duration_total_min || 0)
        );
      }

      if (sortBy === "title_asc") {
        return getClassName(first).localeCompare(
          getClassName(second)
        );
      }

      return 0;
    });

    $("chipCount").textContent = items.length;

    const selectedCourseOption =
      $("courseFilter").selectedOptions[0];

    $("chipCourse").textContent =
      selectedCourseOption?.textContent.trim() || "All";

    const grid = $("resultsGrid");

    grid.innerHTML = "";

    if (items.length === 0) {
      $("emptyState").classList.remove("d-none");
      return;
    }

    $("emptyState").classList.add("d-none");

    items.forEach(classItem => {
      const progress = Math.max(
        0,
        Math.min(
          100,
          Number(classItem.progress || 0)
        )
      );

      const spent = Number(
        classItem.time_spent_min || 0
      );

      const total = Number(
        classItem.duration_total_min || 0
      );

      const left = Math.max(
        0,
        total - spent
      );

      const className =
        getClassName(classItem);

      const displayClassId =
        getDisplayClassId(classItem);

      const courseName =
        getCourseName(classItem);

      const classKey =
        getClassKey(classItem);

      const titleHtml =
        highlight(className, query);

      const courseHtml =
        escapeHtml(courseName);

      const column =
        document.createElement("div");

      column.className =
        "col-12 col-md-6 col-xl-4";

      column.innerHTML = `
        <div class="lp-class-card">
          <div class="lp-accent"></div>

          <div class="lp-body">
            <div class="d-flex align-items-start justify-content-between gap-2">
              <div>
                <h3 class="lp-class-title mb-1">
                  ${titleHtml}
                </h3>

                <p class="lp-class-meta mb-0">
                  ${courseHtml}
                </p>
              </div>

              <div class="text-end">
                <div class="small text-secondary">
                  Class ID
                </div>

                <div class="small fw-semibold">
                  ${escapeHtml(displayClassId)}
                </div>
              </div>
            </div>

            <div class="lp-badges">
              ${
                classItem.enrolled
                  ? progress >= 100
                    ? `
                    <span class="lp-badge enrolled">
                      <i class="bi bi-award me-1"></i>
                      Completed
                    </span>
                  `
                    : `
                    <span class="lp-badge enrolled">
                      <i class="bi bi-check-circle me-1"></i>
                      Enrolled
                    </span>
                  `
                  : `
                    <span class="lp-badge new">
                      <i class="bi bi-compass me-1"></i>
                      Discover
                    </span>
                  `
              }

              <span class="lp-badge">
                <i class="bi bi-hourglass-split me-1"></i>
                ${formatMinutes(total)} total
              </span>
            </div>

            ${
              classItem.enrolled
                ? `
                  <div class="lp-progress-row">
                    <div class="lp-progress">
                      <div style="width:${progress}%"></div>
                    </div>

                    <div class="lp-progress-pct">
                      ${progress}%
                    </div>
                  </div>

                  <div class="lp-stats">
                    <div class="lp-stat">
                      <i class="bi bi-stopwatch"></i>
                      <span>
                        <strong>${formatMinutes(spent)}</strong>
                        spent
                      </span>
                    </div>

                    <div class="lp-stat">
                      <i class="bi bi-calendar2-week"></i>
                      <span>
                        <strong>${formatMinutes(left)}</strong>
                        left
                      </span>
                    </div>
                  </div>
                `
                : `
                  <div class="mt-3 text-secondary small">
                    <i class="bi bi-stars me-1"></i>
                    Suggested for you based on your search
                    and popularity.
                  </div>
                `
            }
          </div>

          <div class="lp-actions">
            <button
              type="button"
              class="btn lp-btn lp-btn-outline"
              data-action="details"
              data-id="${escapeHtml(displayClassId)}"
              data-classid="${escapeHtml(classKey)}"
            >
              <i class="bi bi-info-circle me-1"></i>
              Details
            </button>

            ${
              classItem.enrolled
                ? `
                  <button
                    type="button"
                    class="btn lp-btn lp-btn-primary"
                    data-action="open"
                    data-id="${escapeHtml(displayClassId)}"
                    data-classid="${escapeHtml(classKey)}"
                  >
                    <i class="bi bi-play-circle me-1"></i>
                    Open Class
                  </button>
                `
                : `
                  <button
                    type="button"
                    class="btn lp-btn lp-btn-primary"
                    data-action="enroll"
                    data-id="${escapeHtml(displayClassId)}"
                    data-classid="${escapeHtml(classKey)}"
                  >
                    <i class="bi bi-plus-circle me-1"></i>
                    Enroll
                  </button>
                `
            }
          </div>
        </div>
      `;

      grid.appendChild(column);
    });
  }

  document.addEventListener(
    "DOMContentLoaded",
    () => {
      const navigationEntry =
        performance.getEntriesByType("navigation")[0];

      if (
        navigationEntry &&
        navigationEntry.type === "reload"
      ) {
        sessionStorage.removeItem(
          ENROLLMENT_STATE_KEY
        );
      }

      applyEnrollmentState();

      const params = new URLSearchParams(
        window.location.search
      );

      $("searchInput").value =
        params.get("q") || "";

      const courseFromUrl =
        params.get("course");

      if (
        courseFromUrl &&
        Array.from($("courseFilter").options).some(
          option => option.value === courseFromUrl
        )
      ) {
        $("courseFilter").value = courseFromUrl;
      }

      render();

      [
        "searchInput",
        "courseFilter",
        "sortBy"
      ].forEach(id => {
        $(id).addEventListener("input", render);
        $(id).addEventListener("change", render);
      });

      window.addEventListener(
        "pageshow",
        () => {
          applyEnrollmentState();
          render();
        }
      );

      document.addEventListener(
        "click",
        event => {
          const button = event.target.closest(
            "button[data-action]"
          );

          if (!button) {
            return;
          }

          const displayId =
            button.getAttribute("data-id");

          const classId =
            button.getAttribute("data-classid");

          const action =
            button.getAttribute("data-action");

          if (action === "details") {
            console.log(
              "Open details for " + displayId
            );

            const detailUrlTemplate =
              @json(route('classDetail', '__ID__'));

            window.location.href =
              detailUrlTemplate.replace(
                "__ID__",
                encodeURIComponent(classId)
              );

            return;
          }

          if (action === "open") {
            const contentUrlTemplate =
              @json(route('student.Content', '__ID__'));

            window.location.href =
              contentUrlTemplate.replace(
                "__ID__",
                encodeURIComponent(classId)
              );

            return;
          }

          if (action === "enroll") {
            @auth
            (async () => {
              if (!classId) {
                return;
              }

              button.disabled = true;

              try {
                const response = await fetch(
                  `/student/class/${classId}/enroll`,
                  {
                    method: "POST",
                    headers: {
                      "Content-Type": "application/json",
                      "Accept": "application/json",
                      "X-CSRF-TOKEN":
                        @json(csrf_token())
                    },
                    body: JSON.stringify({
                      class_id: classId
                    })
                  }
                );

                const data =
                  await response.json();

                if (
                  !response.ok ||
                  !data.success
                ) {
                  throw new Error(
                    data.message ||
                    "Enrollment failed."
                  );
                }

                const item =
                  SEARCH_RESULTS.find(
                    result =>
                      getClassKey(result) ===
                      String(classId)
                  );

                if (item) {
                  item.enrolled = true;
                  item.progress =
                    item.progress || 0;

                  const state =
                    loadEnrollmentState();

                  state[
                    getClassKey(item) ||
                    String(classId)
                  ] = true;

                  saveEnrollmentState(state);
                }

                const card = button.closest(
                  ".lp-class-card"
                );

                if (card) {
                  const statusBadge =
                    card.querySelector(
                      ".lp-badges .lp-badge"
                    );

                  if (statusBadge) {
                    const isCompleted =
                      Number(item?.progress || 0) >= 100;

                    statusBadge.className =
                      isCompleted
                        ? "lp-badge"
                        : "lp-badge enrolled";

                    statusBadge.innerHTML =
                      isCompleted
                        ? '<i class="bi bi-award me-1"></i>Completed'
                        : '<i class="bi bi-check-circle me-1"></i>Enrolled';
                  }
                }

                button.disabled = false;

                button.setAttribute(
                  "data-action",
                  "open"
                );

                button.innerHTML =
                  '<i class="bi bi-play-circle me-1"></i>Open Class';

                alert(
                  data.message ||
                  "Register successful"
                );
              } catch (error) {
                alert(
                  error.message ||
                  "Enrollment failed."
                );

                button.disabled = false;
              }
            })();
            @else
            window.location.href =
              @json(url('/login'));
            @endauth
          }
        }
      );
    }
  );
</script>
@endpush
