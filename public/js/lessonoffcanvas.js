const SAMPLE_DATA = {
      courses: [
        { id: "CRS-1001", name: "Feng Shui Basics", category: "Lifestyle", level: "Beginner" },
        { id: "CRS-1002", name: "Advanced Color Matching", category: "Design", level: "Intermediate" },
        { id: "CRS-1003", name: "Personal Finance 101", category: "Finance", level: "Beginner" },
        { id: "CRS-1004", name: "Branding Starter Kit", category: "Business", level: "Beginner" }
      ],
      classes: [
        { id: "CLS-2001", courseId: "CRS-1001", name: "Class A", schedule: "Sat 10:00", seats: 30 },
        { id: "CLS-2002", courseId: "CRS-1001", name: "Class B", schedule: "Sun 20:00", seats: 40 },
        { id: "CLS-2003", courseId: "CRS-1002", name: "Design Batch 01", schedule: "Wed 21:00", seats: 25 },
        { id: "CLS-2004", courseId: "CRS-1002", name: "Design Batch 02", schedule: "Fri 21:00", seats: 25 },
        { id: "CLS-2005", courseId: "CRS-1003", name: "Finance Evening", schedule: "Tue 20:30", seats: 35 },
        { id: "CLS-2006", courseId: "CRS-1004", name: "Branding Weekend", schedule: "Sun 11:00", seats: 20 }
      ]
    };

    /* =========================================================
       Searchable dropdown component (no external libraries)
       - Click input opens list
       - Search inside panel filters
       - Select item writes visible text + hidden id
    ========================================================== */
    function createSearchDropdown(opts) {
      const {
        root,
        input,
        hidden,
        panelSearch,
        listEl,
        getItems,
        itemToLabel,
        itemToMeta,
        onSelect,
      } = opts;

      function open() { root.classList.add('open'); }
      function close(){ root.classList.remove('open'); }

      function render(filterText = "") {
        const ft = filterText.trim().toLowerCase();
        const items = getItems().filter(it => {
          const label = itemToLabel(it).toLowerCase();
          const meta = itemToMeta(it).toLowerCase();
          return label.includes(ft) || meta.includes(ft);
        });

        listEl.innerHTML = items.length
          ? items.map(it => `
              <div class="dd-item" data-id="${it.id}">
                <div style="flex:1;">
                  <div class="fw-semibold">${escapeHtml(itemToLabel(it))}</div>
                  <small>${escapeHtml(itemToMeta(it))}</small>
                </div>
                <span class="chip">${escapeHtml(it.id)}</span>
              </div>
            `).join("")
          : `<div class="p-3 text-center small" style="color: rgba(229,231,235,.75);">No results</div>`;
      }

      // open on click/focus
      input.addEventListener('focus', () => {
        if (input.disabled) return;
        render(panelSearch.value || "");
        open();
      });
      input.addEventListener('click', () => {
        if (input.disabled) return;
        render(panelSearch.value || "");
        open();
      });

      // filter inside panel
      panelSearch.addEventListener('input', () => render(panelSearch.value));

      // select item
      listEl.addEventListener('click', (e) => {
        const itemEl = e.target.closest('.dd-item');
        if (!itemEl) return;
        const id = itemEl.getAttribute('data-id');
        const item = getItems().find(x => x.id === id);
        if (!item) return;

        hidden.value = item.id;
        input.value = itemToLabel(item);
        input.classList.remove('is-invalid');
        close();
        onSelect?.(item);
      });

      // close on outside click
      document.addEventListener('click', (e) => {
        if (!root.contains(e.target)) close();
      });

      // public API
      return {
        open,
        close,
        render,
        clear: () => {
          hidden.value = "";
          input.value = "";
          panelSearch.value = "";
          render("");
        }
      };
    }

    function escapeHtml(str) {
      return String(str).replace(/[&<>"']/g, m => ({
        "&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"
      }[m]));
    }

    /* =========================================================
       Wire up dropdowns + form behavior
    ========================================================== */
    /* =========================================================
   Wire up dropdowns + form behavior (Add + Edit)
   - suffix ""  => Add offcanvas elements
   - suffix "-1"=> Edit offcanvas elements
========================================================== */

function byId(id) { return document.getElementById(id); }

function initLessonOffcanvas(suffix, cfg) {
  // cfg contains IDs for offcanvas/form/result + any special behavior
  const offcanvasEl = byId(cfg.offcanvasId);
  const formEl      = byId(cfg.formId);
  const resultEl    = byId(cfg.resultId);

  // If this offcanvas is not on the current page, safely skip (prevents "backdrop" errors)
  if (!offcanvasEl || !formEl) {
    console.warn(`[lesson_offcanvas] Skip init (missing offcanvas/form):`, cfg.offcanvasId, cfg.formId);
    return;
  }

  // Core inputs
  const courseDDRoot = byId(`courseDD${suffix}`);
  const classDDRoot  = byId(`classDD${suffix}`);

  const courseInput  = byId(`courseInput${suffix}`);
  const courseId     = byId(`courseId${suffix}`);
  const courseSearch = byId(`courseSearch${suffix}`);
  const courseList   = byId(`courseList${suffix}`);

  const classInput   = byId(`classInput${suffix}`);
  const classId      = byId(`classId${suffix}`);
  const classSearch  = byId(`classSearch${suffix}`);
  const classList    = byId(`classList${suffix}`);

  const contentType  = byId(`contentType${suffix}`);
  const sourceUrl    = byId(`sourceUrl${suffix}`);
  const uploadFile   = byId(cfg.uploadFileId); // Add: uploadFile, Edit: editFile

  // Optional fields (if your edit form has these IDs, it will include them in payload)
  const lessonNameEl = byId(`lessonName${suffix}`);
  const durHoursEl   = byId(`durHours${suffix}`);
  const durMinsEl    = byId(`durMins${suffix}`);
  const descEl       = byId(`description${suffix}`);

  // Guard required elements for dropdown logic
  const requiredForDropdown =
    courseDDRoot && classDDRoot &&
    courseInput && courseId && courseSearch && courseList &&
    classInput && classId && classSearch && classList;

  if (!requiredForDropdown) {
    console.warn(`[lesson_offcanvas] Dropdown nodes missing for suffix "${suffix}". Check IDs in Edit offcanvas.`);
    // Still continue for contentType/file behavior if those exist.
  }

  // Initial states
  if (sourceUrl) {
    sourceUrl.disabled = true;
    sourceUrl.value = "";
    sourceUrl.placeholder = "Source URL disabled for this content type";
  }

  let selectedCourseId = "";

  // ===== Searchable Dropdowns =====
  let courseDD = null;
  let classDD = null;

  if (requiredForDropdown) {
    courseDD = createSearchDropdown({
      root: courseDDRoot,
      input: courseInput,
      hidden: courseId,
      panelSearch: courseSearch,
      listEl: courseList,
      getItems: () => SAMPLE_DATA.courses,
      itemToLabel: (c) => c.name,
      itemToMeta: (c) => `${c.category} • ${c.level}`,
      onSelect: (course) => {
        selectedCourseId = course.id;

        // enable class dropdown and clear previous selection
        classInput.disabled = false;
        classId.value = "";
        classInput.value = "";
        classSearch.value = "";

        classDD && classDD.render("");
      }
    });

    classDD = createSearchDropdown({
      root: classDDRoot,
      input: classInput,
      hidden: classId,
      panelSearch: classSearch,
      listEl: classList,
      getItems: () => SAMPLE_DATA.classes.filter(x => x.courseId === selectedCourseId),
      itemToLabel: (c) => c.name,
      itemToMeta: (c) => `${c.schedule} • Seats ${c.seats}`,
    });

    // If you want Edit to start with class disabled until course chosen:
    if (cfg.disableClassInitially) {
      classInput.disabled = true;
    }
  }

  // ===== File validation (image/* or pdf) =====
  if (uploadFile) {
    uploadFile.addEventListener("change", () => {
      const f = uploadFile.files[0];
      if (!f) return;
      const ok = f.type.startsWith("image/") || f.type === "application/pdf";
      if (!ok) {
        alert("Only Image or PDF files are allowed.");
        uploadFile.value = "";
      }
    });
  }

  // ===== Content type behavior (your requirement) =====
  // Video => enable sourceUrl, disable upload
  // Others => disable sourceUrl, enable upload
  if (contentType && sourceUrl && uploadFile) {
    contentType.addEventListener("change", () => {
      const type = contentType.value;

      if (type === "video") {
        sourceUrl.disabled = false;
        uploadFile.disabled = true;
        sourceUrl.placeholder = "Paste Bunny Stream video ID";
      } else {
        sourceUrl.disabled = true;
        uploadFile.disabled = false;
        sourceUrl.value = "";
        sourceUrl.placeholder = "Source URL disabled for this content type";
      }
    });
  }

  // ===== Submit demo (prints payload) =====
  formEl.addEventListener("submit", (e) => {
    e.preventDefault();

    // Minimal validation for searchable selects
    let ok = true;

    if (courseId && courseInput && !courseId.value) {
      courseInput.classList.add("is-invalid");
      ok = false;
    }
    if (classId && classInput && !classId.value) {
      classInput.classList.add("is-invalid");
      ok = false;
    }

    // If Video, require URL
    if (contentType && sourceUrl) {
      const t = contentType.value;
      const hasUrl = sourceUrl.value.trim().length > 0;
      if (t === "video" && !hasUrl) {
        alert("For Video content type, Source URL is required.");
        ok = false;
      }
    }

    if (!ok) return;

    const payload = {
      mode: cfg.mode, // "add" or "edit"
      contentType: contentType ? contentType.value : null,
      sourceUrl: sourceUrl ? sourceUrl.value.trim() : null,
      uploadedFileName: (uploadFile && uploadFile.files && uploadFile.files.length > 0)
        ? uploadFile.files[0].name
        : null,
      course: (courseId && courseInput) ? { id: courseId.value, name: courseInput.value } : null,
      class: (classId && classInput) ? { id: classId.value, name: classInput.value } : null,
      lessonName: lessonNameEl ? lessonNameEl.value.trim() : null,
      duration: {
        hours: durHoursEl ? Number(durHoursEl.value || 0) : null,
        mins: durMinsEl ? Number(durMinsEl.value || 0) : null,
      },
      description: descEl ? descEl.value.trim() : null,
    };

    if (resultEl) {
      resultEl.style.display = "block";
      resultEl.innerHTML = `<div class="p-3 rounded-3" style="background: rgba(0,0,0,.25); border: 1px solid rgba(255,255,255,.12);">
        <div class="fw-semibold mb-2">Payload (testing)</div>
        <pre class="m-0" style="white-space:pre-wrap; color: rgba(229,231,235,.9);">${escapeHtml(JSON.stringify(payload, null, 2))}</pre>
      </div>`;
    }
  });

  // ===== Offcanvas open behavior =====
  offcanvasEl.addEventListener("show.bs.offcanvas", () => {
    // For Add: reset everything
    if (cfg.resetOnOpen) {
      formEl.reset();
      if (resultEl) resultEl.style.display = "none";

      selectedCourseId = "";
      if (classInput) classInput.disabled = true;

      if (courseId) courseId.value = "";
      if (classId) classId.value = "";

      if (courseInput) courseInput.classList.remove("is-invalid");
      if (classInput) classInput.classList.remove("is-invalid");

      courseDD && courseDD.clear();
      classDD && classDD.clear();

      // Reset sourceUrl/upload rules
      if (sourceUrl && uploadFile) {
        sourceUrl.disabled = true;
        sourceUrl.value = "";
        uploadFile.disabled = false;
      }
    }

    // For Edit: DO NOT reset by default (you will populate existing data)
    // You can add “prefill” logic later when you click an Edit button.
  });
}

// Run after DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  // Add Lesson
  initLessonOffcanvas("", {
    mode: "add",
    offcanvasId: "AddLesson",
    formId: "lessonForm",
    resultId: "result",
    uploadFileId: "uploadFile",
    resetOnOpen: true,
    disableClassInitially: true,
  });

  // Edit Lesson (your IDs from screenshots)
  initLessonOffcanvas("-1", {
    mode: "edit",
    offcanvasId: "EditLesson",
    formId: "lessonForm-1",
    resultId: "editresult",
    uploadFileId: "editFile",
    resetOnOpen: false,        // important: don't wipe existing values
    disableClassInitially: false,
  });
});

