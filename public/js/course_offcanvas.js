const SAMPLE_DATA = {
    programs: [
    { id: "PRG-01", name: "Lifestyle Program" },
    { id: "PRG-02", name: "Business Program" }
  ],
};

function byId(id) { return document.getElementById(id); }

function escapeHtml(str) {
  return String(str ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

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
      const meta  = itemToMeta(it).toLowerCase();
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
    input.value  = itemToLabel(item);
    input.classList.remove('is-invalid');
    close();
    onSelect?.(item);
  });

  // close on outside click
  document.addEventListener('click', (e) => {
    if (!root.contains(e.target)) close();
  });

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

function initClassOffcanvas(suffix, cfg) {
// cfg contains IDs for offcanvas/form/result + any special behavior
  const offcanvasEl = byId(cfg.offcanvasId);
  const formEl      = byId(cfg.formId);
  const resultEl    = byId(cfg.resultId);
    
// If this offcanvas is not on the current page, safely skip (prevents "backdrop" errors)
  if (!offcanvasEl || !formEl) {
    console.warn(`course_offcanvas] Skip init (missing offcanvas/form):`, cfg.offcanvasId, cfg.formId);
    return;
  }
    
// Core inputs
  const programDDRoot = byId(`programDD${suffix}`);
  const programId     = byId(`programId${suffix}`);
  const programSearch = byId(`programSearch${suffix}`);
  const programList   = byId(`programList${suffix}`);
const programInput = byId(`programInput${suffix}`);

    
    // Guard required elements for dropdown logic
  const requiredForDropdown =
    programDDRoot && programInput && programId && programSearch && programList;

  if (!requiredForDropdown) {
    console.warn(`[lesson_offcanvas] Dropdown nodes missing for suffix "${suffix}". Check IDs in Edit offcanvas.`);
    // Still continue for contentType/file behavior if those exist.
  }
    
    if (requiredForDropdown) {
    programDD = createSearchDropdown({
      root: programDDRoot,
      input: programInput,
      hidden: programId,
      panelSearch: programSearch,
      listEl: programList,
      
       getItems: () => SAMPLE_DATA.programs,
  itemToLabel: (c) => c.name,
  itemToMeta:  (c) => c.programId || "—",
    });
}

// ===== Offcanvas open behavior =====
  offcanvasEl.addEventListener("show.bs.offcanvas", () => {
    // For Add: reset everything
    if (cfg.resetOnOpen) {
      formEl.reset();
      if (resultEl) resultEl.style.display = "none";

      if (programId) programId.value = "";

      if (programInput) programInput.classList.remove("is-invalid");
      //if (classInput) classInput.classList.remove("is-invalid");

      programDD && programDD.clear();
      //classDD && classDD.clear();
    }

    // For Edit: DO NOT reset by default (you will populate existing data)
    // You can add “prefill” logic later when you click an Edit button.
  });
}
// Run after DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  // Add Lesson
  initClassOffcanvas("", {
    mode: "add",
    offcanvasId: "AddCourse",
    formId: "courseForm",
    resultId: "result",
    resetOnOpen: true,
  });

  // Edit Lesson (your IDs from screenshots)
  initClassOffcanvas("-1", {
    mode: "edit",
    offcanvasId: "EditCourse",
    formId: "courseForm-1",
    resultId: "editresult",
    resetOnOpen: false,        // important: don't wipe existing values
  });
});